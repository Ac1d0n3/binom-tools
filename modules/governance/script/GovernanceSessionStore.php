<?php

namespace App\Governance;

use App\Accounts\AccountUser;
use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Models\BnTools\BnGovernanceSession;
use App\Support\StorageDriver;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

final class GovernanceSessionStore
{
    public const STATUSES = [
        'draft',
        'in_review',
        'decision_ready',
        'approved',
        'change_requested',
        'archived',
    ];

    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $files,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function listFor(AccountUser $user, bool $includeArchived = false): array
    {
        $sessions = StorageDriver::isMysql() && Schema::hasTable('bn_governance_sessions')
            ? $this->listFromDatabase($user, $includeArchived)
            : $this->listFromFiles($user, $includeArchived);

        usort($sessions, static fn (array $a, array $b): int => strcmp((string) ($b['updatedAt'] ?? ''), (string) ($a['updatedAt'] ?? '')));

        return array_values($sessions);
    }

    public function findFor(AccountUser $user, string $id): ?array
    {
        if (! $this->validId($id)) {
            return null;
        }

        if (StorageDriver::isMysql() && Schema::hasTable('bn_governance_sessions')) {
            $row = BnGovernanceSession::query()
                ->where('id', $id)
                ->where('owner_user_id', $user->id)
                ->first();

            return $row ? $this->fromRow($row) : null;
        }

        $session = $this->files->read($this->pathFor($id), []);
        if (($session['ownerUserId'] ?? null) !== $user->id) {
            return null;
        }

        return $this->normalize($session, $user, false);
    }

    public function save(AccountUser $user, array $input): array
    {
        $existing = null;
        $id = (string) ($input['id'] ?? '');
        if ($id !== '') {
            if (! $this->validId($id)) {
                throw new InvalidArgumentException('Invalid governance session id.');
            }
            $existing = $this->findFor($user, $id);
            if ($existing === null) {
                throw new InvalidArgumentException('Governance session not found.');
            }
        } else {
            $id = $this->createId();
        }

        $session = $this->normalize([
            ...($existing ?? []),
            ...$input,
            'id' => $id,
            'ownerUserId' => $user->id,
            'createdAt' => $existing['createdAt'] ?? now()->toIso8601String(),
            'updatedAt' => now()->toIso8601String(),
        ], $user, true);

        if (StorageDriver::isMysql() && Schema::hasTable('bn_governance_sessions')) {
            $this->persistDatabase($session);
        } else {
            $this->files->write($this->pathFor($id), $session);
        }

        return $session;
    }

    public function duplicate(AccountUser $user, string $id): array
    {
        $session = $this->findFor($user, $id);
        if ($session === null) {
            throw new InvalidArgumentException('Governance session not found.');
        }

        unset($session['id'], $session['createdAt'], $session['updatedAt'], $session['archivedAt']);
        $session['title'] = trim((string) ($session['title'] ?? 'Governance Discovery')).' Copy';
        $session['status'] = 'draft';

        return $this->save($user, $session);
    }

    public function archive(AccountUser $user, string $id): array
    {
        $session = $this->findFor($user, $id);
        if ($session === null) {
            throw new InvalidArgumentException('Governance session not found.');
        }

        $session['status'] = 'archived';
        $session['archivedAt'] = now()->toIso8601String();

        return $this->save($user, $session);
    }

    /**
     * @return array<string, mixed>
     */
    private function normalize(array $input, AccountUser $user, bool $forSave): array
    {
        $payload = is_array($input['payload'] ?? null) ? $input['payload'] : [];
        $advisor = is_array($payload['advisor'] ?? null) ? $payload['advisor'] : [];
        $dataQuality = is_array($payload['dataQuality'] ?? null) ? $payload['dataQuality'] : [];
        if (($advisor['goal'] ?? null) === 'dq' && $dataQuality === []) {
            $dataQuality = [
                'mode' => $advisor['dqMode'] ?? 'health_check',
                'layer' => $advisor['dqLayer'] ?? 'source',
                'issueTypes' => is_array($advisor['dqIssues'] ?? null) ? $advisor['dqIssues'] : [],
                'affectedSources' => [],
                'affectedKpis' => [],
                'affectedReports' => [],
                'proposedRules' => [],
                'validationFindings' => [],
                'decisionStatus' => 'draft',
            ];
            $payload['dataQuality'] = $dataQuality;
        }
        $scenario = $this->pick($input['scenario'] ?? ($advisor['scenario'] ?? 'new'), ['new', 'extend', 'help'], 'new');
        $status = $this->pick($input['status'] ?? 'draft', self::STATUSES, 'draft');
        $title = trim((string) ($input['title'] ?? ''));
        if ($title === '') {
            $title = $this->defaultTitle($scenario);
        }

        $validation = $this->validatePayload($payload, $scenario);

        return [
            'id' => (string) ($input['id'] ?? ($forSave ? $this->createId() : '')),
            'ownerUserId' => $user->id,
            'title' => mb_substr($title, 0, 190),
            'companyName' => $this->optionalString($input['companyName'] ?? null, 190),
            'projectName' => $this->optionalString($input['projectName'] ?? null, 190),
            'scenario' => $scenario,
            'status' => $status,
            'currentStep' => $this->optionalString($input['currentStep'] ?? 'advisor', 64) ?: 'advisor',
            'payload' => $payload,
            'validationSummary' => $validation,
            'reportSnapshot' => is_array($input['reportSnapshot'] ?? null) ? $input['reportSnapshot'] : $this->buildReportSnapshot($payload, $validation),
            'archivedAt' => $input['archivedAt'] ?? null,
            'createdAt' => (string) ($input['createdAt'] ?? now()->toIso8601String()),
            'updatedAt' => (string) ($input['updatedAt'] ?? now()->toIso8601String()),
        ];
    }

    private function validatePayload(array $payload, string $scenario): array
    {
        $advisor = is_array($payload['advisor'] ?? null) ? $payload['advisor'] : [];
        $warnings = [];
        $required = [
            'scenario' => $scenario,
            'goal' => $advisor['goal'] ?? null,
            'domain' => $advisor['domain'] ?? null,
            'platform' => $advisor['platform'] ?? null,
        ];

        if (($required['goal'] ?? '') === '') {
            $warnings[] = 'Decision topic is missing.';
        }
        if (($required['domain'] ?? 'unknown') === 'unknown') {
            $warnings[] = 'Source type is still open.';
        }
        if (($required['platform'] ?? 'unknown') === 'unknown' && in_array($scenario, ['new', 'extend'], true)) {
            $warnings[] = 'Target stack is still open.';
        }
        if (($required['goal'] ?? '') === 'kpi' && empty($payload['kpis'])) {
            $warnings[] = 'KPI details are not collected yet.';
        }
        if (($required['goal'] ?? '') === 'pii' && empty($payload['pii'])) {
            $warnings[] = 'PII/DSDR details are not collected yet.';
        }
        if (($required['goal'] ?? '') === 'dq') {
            $dataQuality = is_array($payload['dataQuality'] ?? null) ? $payload['dataQuality'] : [];
            if (($dataQuality['mode'] ?? '') === '') {
                $warnings[] = 'Data Quality mode is missing.';
            }
            if (($dataQuality['layer'] ?? '') === '') {
                $warnings[] = 'Data Quality layer is missing.';
            }
            if (empty($dataQuality['issueTypes'])) {
                $warnings[] = 'At least one Data Quality issue class is required.';
            }
            if (empty($dataQuality['affectedSources']) && empty($dataQuality['affectedReports']) && empty($dataQuality['affectedKpis'])) {
                $warnings[] = 'Affected source, KPI or report is not linked yet.';
            }
            if (empty($dataQuality['proposedRules'])) {
                $warnings[] = 'Data Quality rules are not drafted yet.';
            }
        }

        $score = max(0, 100 - (count($warnings) * 18));

        return [
            'score' => $score,
            'state' => $score >= 82 ? 'decision_ready' : ($score >= 55 ? 'review_needed' : 'incomplete'),
            'warnings' => $warnings,
        ];
    }

    private function buildReportSnapshot(array $payload, array $validation): array
    {
        return [
            'generatedAt' => now()->toIso8601String(),
            'advisor' => is_array($payload['advisor'] ?? null) ? $payload['advisor'] : [],
            'guidance' => is_array($payload['guidance'] ?? null) ? $payload['guidance'] : [],
            'dataQuality' => is_array($payload['dataQuality'] ?? null) ? $payload['dataQuality'] : [],
            'recommendations' => is_array($payload['recommendations'] ?? null) ? $payload['recommendations'] : [],
            'validation' => $validation,
        ];
    }

    private function defaultTitle(string $scenario): string
    {
        return match ($scenario) {
            'extend' => 'Governance Discovery - bestehende Umgebung',
            'help' => 'Governance Discovery - Orientierung',
            default => 'Governance Discovery - Neuaufbau',
        };
    }

    private function optionalString(mixed $value, int $max): ?string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? null : mb_substr($value, 0, $max);
    }

    /**
     * @param  list<string>  $allowed
     */
    private function pick(mixed $value, array $allowed, string $fallback): string
    {
        $value = (string) $value;

        return in_array($value, $allowed, true) ? $value : $fallback;
    }

    private function validId(string $id): bool
    {
        return preg_match('/^gov_[a-zA-Z0-9_]+$/', $id) === 1;
    }

    private function createId(): string
    {
        return 'gov_'.date('Ymd_His').'_'.bin2hex(random_bytes(3));
    }

    private function pathFor(string $id): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $id) ?: 'invalid';

        return $this->config->governanceSessionsDirectory().DIRECTORY_SEPARATOR.$safe.'.json';
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listFromFiles(AccountUser $user, bool $includeArchived): array
    {
        $this->files->ensureDirectory($this->config->governanceSessionsDirectory());
        $sessions = [];
        foreach (glob($this->config->governanceSessionsDirectory().DIRECTORY_SEPARATOR.'gov_*.json') ?: [] as $file) {
            $session = $this->files->read($file, []);
            if (($session['ownerUserId'] ?? null) !== $user->id) {
                continue;
            }
            $normalized = $this->normalize($session, $user, false);
            if (! $includeArchived && ($normalized['status'] ?? '') === 'archived') {
                continue;
            }
            $sessions[] = $normalized;
        }

        return $sessions;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listFromDatabase(AccountUser $user, bool $includeArchived): array
    {
        $query = BnGovernanceSession::query()->where('owner_user_id', $user->id);
        if (! $includeArchived) {
            $query->where('status', '!=', 'archived');
        }

        return $query->orderByDesc('updated_at')->get()->map(fn (BnGovernanceSession $row): array => $this->fromRow($row))->all();
    }

    private function fromRow(BnGovernanceSession $row): array
    {
        return [
            'id' => $row->id,
            'ownerUserId' => $row->owner_user_id,
            'title' => $row->title,
            'companyName' => $row->company_name,
            'projectName' => $row->project_name,
            'scenario' => $row->scenario,
            'status' => $row->status,
            'currentStep' => $row->current_step,
            'payload' => is_array($row->payload) ? $row->payload : [],
            'validationSummary' => is_array($row->validation_summary) ? $row->validation_summary : [],
            'reportSnapshot' => is_array($row->report_snapshot) ? $row->report_snapshot : [],
            'archivedAt' => $row->archived_at?->toIso8601String(),
            'createdAt' => $row->created_at?->toIso8601String() ?? '',
            'updatedAt' => $row->updated_at?->toIso8601String() ?? '',
        ];
    }

    private function persistDatabase(array $session): void
    {
        BnGovernanceSession::query()->updateOrCreate(
            ['id' => $session['id']],
            [
                'owner_user_id' => $session['ownerUserId'],
                'title' => $session['title'],
                'company_name' => $session['companyName'],
                'project_name' => $session['projectName'],
                'scenario' => $session['scenario'],
                'status' => $session['status'],
                'current_step' => $session['currentStep'],
                'payload' => $session['payload'],
                'validation_summary' => $session['validationSummary'],
                'report_snapshot' => $session['reportSnapshot'],
                'archived_at' => $session['archivedAt'],
            ],
        );
    }
}
