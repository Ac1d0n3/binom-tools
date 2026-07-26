<?php

namespace App\Console\Commands;

use App\Accounts\AccountsConfig;
use App\Accounts\Contracts\PlanAttachmentStoreInterface;
use App\Accounts\Contracts\PlanStoreInterface;
use App\Accounts\Contracts\PromptStudioLibraryStoreInterface;
use App\Accounts\Contracts\ReadStateStoreInterface;
use App\Accounts\Contracts\StoryAclRepositoryInterface;
use App\Accounts\Contracts\TeamRepositoryInterface;
use App\Accounts\Contracts\UserRepositoryInterface;
use App\Accounts\Contracts\UserTemplateStoreInterface;
use App\Accounts\JsonFileStore;
use App\Governance\GovernanceSessionStore;
use App\Models\BnTools\BnPlanHistory;
use App\Playbooks\Contracts\PlaybookStatsStoreInterface;
use App\Support\StorageDriver;
use Illuminate\Console\Command;

/**
 * Import JSON runtime data into the active mysql storage driver.
 */
class ImportBnToolsStorageCommand extends Command
{
    protected $signature = 'bn-tools:storage-import
        {--force : Run even if storage driver is not mysql (writes via bound stores)}';

    protected $description = 'Import file-based bn-tools / playbook-stats JSON into the database storage driver';

    public function handle(
        AccountsConfig $config,
        JsonFileStore $files,
        UserRepositoryInterface $users,
        TeamRepositoryInterface $teams,
        StoryAclRepositoryInterface $storyAcl,
        PlanStoreInterface $plans,
        UserTemplateStoreInterface $userTemplates,
        ReadStateStoreInterface $readState,
        PromptStudioLibraryStoreInterface $promptLibrary,
        PlanAttachmentStoreInterface $attachments,
        PlaybookStatsStoreInterface $stats,
        GovernanceSessionStore $governanceSessions,
    ): int {
        if (! StorageDriver::isMysql() && ! $this->option('force')) {
            $this->error('BINOM_TOOLS_STORAGE_DRIVER must be mysql (or pass --force).');

            return self::FAILURE;
        }

        StorageDriver::assertMysqlReady();

        $imported = 0;

        $usersRaw = $files->read($config->usersPath(), ['users' => []]);
        foreach ($usersRaw['users'] ?? [] as $row) {
            if (! is_array($row) || ! isset($row['email'], $row['passwordHash'])) {
                continue;
            }
            $users->upsert($row);
            $imported++;
        }
        $this->info('Users imported.');

        $teamsRaw = $files->read($config->teamsPath(), ['teams' => []]);
        foreach ($teamsRaw['teams'] ?? [] as $row) {
            if (! is_array($row)) {
                continue;
            }
            $teams->upsert($row);
            $imported++;
        }
        $this->info('Teams imported.');

        $aclRaw = $files->read($config->storyAclPath(), ['stories' => []]);
        foreach ($aclRaw['stories'] ?? [] as $slug => $acl) {
            if (! is_string($slug) || ! is_array($acl)) {
                continue;
            }
            $storyAcl->set($slug, $acl);
            $imported++;
        }
        $this->info('Story ACL imported.');

        $planDir = $config->plansDirectory();
        if (is_dir($planDir)) {
            foreach (glob($planDir.DIRECTORY_SEPARATOR.'*.json') ?: [] as $file) {
                $plan = $files->read($file, []);
                if ($plan === [] || ! isset($plan['id'], $plan['ownerUserId'])) {
                    continue;
                }
                $owner = $users->findById((string) $plan['ownerUserId']);
                if ($owner === null) {
                    $this->warn('Skip plan '.$plan['id'].' — owner missing.');

                    continue;
                }
                $plans->save($plan, $owner, ['action' => 'import', 'summary' => 'Imported from JSON']);
                $this->importPlanHistory($config, $files, (string) $plan['id']);
                $this->importPlanAttachments($config, $files, $attachments, (string) $plan['id']);
                $imported++;
            }
        }
        $this->info('Plans imported.');

        $tplDir = $config->userTemplatesDirectory();
        if (is_dir($tplDir)) {
            foreach (glob($tplDir.DIRECTORY_SEPARATOR.'*.json') ?: [] as $file) {
                $template = $files->read($file, []);
                if ($template === [] || ! isset($template['id'], $template['ownerUserId'])) {
                    continue;
                }
                $owner = $users->findById((string) $template['ownerUserId']);
                if ($owner === null) {
                    continue;
                }
                $userTemplates->save($template, $owner);
                $imported++;
            }
        }
        $this->info('User templates imported.');

        $readDir = $config->readStateDirectory();
        if (is_dir($readDir)) {
            foreach (glob($readDir.DIRECTORY_SEPARATOR.'*.json') ?: [] as $file) {
                $userId = pathinfo($file, PATHINFO_FILENAME);
                $raw = $files->read($file, ['read' => []]);
                foreach ($raw['read'] ?? [] as $slug => $at) {
                    if (is_string($slug) && $slug !== '') {
                        $readState->markRead($userId, $slug);
                        $imported++;
                    }
                }
            }
        }
        $this->info('Read state imported.');

        $libDir = $config->promptStudioLibraryDirectory();
        if (is_dir($libDir)) {
            foreach (glob($libDir.DIRECTORY_SEPARATOR.'*.json') ?: [] as $file) {
                $userId = pathinfo($file, PATHINFO_FILENAME);
                $owner = $users->findById($userId);
                if ($owner === null) {
                    continue;
                }
                $payload = $files->read($file, []);
                $promptLibrary->saveFor($owner, $payload);
                $imported++;
            }
        }
        $this->info('Prompt Studio libraries imported.');

        $governanceDir = $config->governanceSessionsDirectory();
        if (is_dir($governanceDir)) {
            foreach (glob($governanceDir.DIRECTORY_SEPARATOR.'gov_*.json') ?: [] as $file) {
                $session = $files->read($file, []);
                if ($session === [] || ! isset($session['id'], $session['ownerUserId'])) {
                    continue;
                }
                $owner = $users->findById((string) $session['ownerUserId']);
                if ($owner === null) {
                    $this->warn('Skip governance session '.$session['id'].' — owner missing.');

                    continue;
                }
                $governanceSessions->save($owner, $session);
                $imported++;
            }
        }
        $this->info('Governance sessions imported.');

        $statsDir = storage_path('app/playbook-stats');
        if (is_dir($statsDir)) {
            foreach (glob($statsDir.DIRECTORY_SEPARATOR.'*.json') ?: [] as $file) {
                $slug = pathinfo($file, PATHINFO_FILENAME);
                $raw = $files->read($file, []);
                $stats->set($slug, (int) ($raw['views'] ?? 0), (int) ($raw['likes'] ?? 0));
                $imported++;
            }
        }
        $this->info('Playbook stats imported.');

        $this->info("Done. Upserted ~{$imported} records.");

        return self::SUCCESS;
    }

    private function importPlanHistory(AccountsConfig $config, JsonFileStore $files, string $planId): void
    {
        $dir = $config->planHistoryDirectory($planId);
        if (! is_dir($dir)) {
            return;
        }
        foreach (glob($dir.DIRECTORY_SEPARATOR.'rev_*.json') ?: [] as $file) {
            $revision = $files->read($file, []);
            if ($revision === [] || ! isset($revision['id'])) {
                continue;
            }
            BnPlanHistory::query()->updateOrCreate(
                ['id' => (string) $revision['id']],
                [
                    'plan_id' => $planId,
                    'actor_user_id' => (string) ($revision['actorUserId'] ?? ''),
                    'actor_label' => (string) ($revision['actorLabel'] ?? ''),
                    'action' => (string) ($revision['action'] ?? 'update'),
                    'summary' => (string) ($revision['summary'] ?? ''),
                    'snapshot' => is_array($revision['snapshot'] ?? null) ? $revision['snapshot'] : [],
                    'created_at' => $revision['createdAt'] ?? now(),
                ],
            );
        }
    }

    private function importPlanAttachments(
        AccountsConfig $config,
        JsonFileStore $files,
        PlanAttachmentStoreInterface $attachments,
        string $planId,
    ): void {
        $indexPath = $config->planAttachmentsDirectory($planId).DIRECTORY_SEPARATOR.'index.json';
        if (! is_file($indexPath)) {
            return;
        }
        $index = $files->read($indexPath, []);
        if (! is_array($index)) {
            return;
        }
        foreach ($index as $id => $meta) {
            if (! is_string($id) || ! is_array($meta)) {
                continue;
            }
            $attachments->put($planId, $id, $meta);
        }
    }
}
