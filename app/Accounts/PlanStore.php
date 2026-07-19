<?php

namespace App\Accounts;

use InvalidArgumentException;

final class PlanStore
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
        private readonly TeamRepository $teams,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function listVisibleTo(AccountUser $user): array
    {
        $this->store->ensureDirectory($this->config->plansDirectory());
        $files = glob($this->config->plansDirectory().DIRECTORY_SEPARATOR.'*.json') ?: [];
        $out = [];
        foreach ($files as $file) {
            $plan = $this->store->read($file, []);
            if ($plan === [] || ! isset($plan['id'])) {
                continue;
            }
            if ($this->canAccess($user, $plan)) {
                $out[] = $plan;
            }
        }

        usort($out, static fn (array $a, array $b): int => strcmp((string) ($b['updatedAt'] ?? ''), (string) ($a['updatedAt'] ?? '')));

        return $out;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $planId): ?array
    {
        $path = $this->pathFor($planId);
        if (! is_file($path)) {
            return null;
        }
        $plan = $this->store->read($path, []);

        return isset($plan['id']) ? $plan : null;
    }

    /**
     * @param  array<string, mixed>  $plan
     * @param  array{action?: string, summary?: string}|array<string, mixed>  $historyMeta
     * @return array<string, mixed>
     */
    public function save(array $plan, AccountUser $actor, array $historyMeta = []): array
    {
        $id = (string) ($plan['id'] ?? '');
        if ($id === '' || ! preg_match('/^plan_[a-zA-Z0-9_]+$/', $id)) {
            throw new InvalidArgumentException('Invalid plan id.');
        }

        $existing = $this->find($id);
        if ($existing !== null && ! $this->canAccess($actor, $existing)) {
            throw new InvalidArgumentException('Not allowed to update this plan.');
        }

        if ($existing === null) {
            $plan['ownerUserId'] = $actor->id;
            $plan['createdAt'] = $plan['createdAt'] ?? now()->toIso8601String();
        } else {
            $plan['ownerUserId'] = $existing['ownerUserId'] ?? $actor->id;
            if (($existing['ownerUserId'] ?? null) !== $actor->id && ! $this->canAccess($actor, $existing)) {
                throw new InvalidArgumentException('Only owner or viewers can update.');
            }
            // Never let a partial client payload wipe structural identity / ACL / progress.
            $plan = $this->mergePreservedFields($existing, $plan, $historyMeta);
            $this->recordRevision($existing, $actor, $historyMeta);
        }

        $plan['id'] = $id;
        $plan['viewerUserIds'] = array_values(array_map('strval', $plan['viewerUserIds'] ?? $existing['viewerUserIds'] ?? []));
        $plan['viewerTeamIds'] = array_values(array_map('strval', $plan['viewerTeamIds'] ?? $existing['viewerTeamIds'] ?? []));
        $plan['linkedStorySlugs'] = array_values(array_map('strval', $plan['linkedStorySlugs'] ?? $existing['linkedStorySlugs'] ?? []));
        $plan = $this->normalizePlanTeams($plan);
        $plan['updatedAt'] = now()->toIso8601String();
        $plan['updatedBy'] = $actor->id;

        // Never accept plaintext plan password; only hash fields from client soft-lock.
        unset($plan['password'], $plan['plainPassword'], $plan['password_plaintext']);

        $this->store->ensureDirectory($this->config->plansDirectory());
        $this->store->write($this->pathFor($id), $plan);

        return $plan;
    }

    public function delete(string $planId, AccountUser $actor): void
    {
        $plan = $this->find($planId);
        if ($plan === null) {
            return;
        }
        if (($plan['ownerUserId'] ?? null) !== $actor->id) {
            throw new InvalidArgumentException('Only the owner can delete this plan.');
        }
        @unlink($this->pathFor($planId));
        $this->removeHistoryDirectory($planId);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listHistory(string $planId, AccountUser $actor): array
    {
        $plan = $this->find($planId);
        if ($plan === null) {
            throw new InvalidArgumentException('Plan not found.');
        }
        if (! $this->canAccess($actor, $plan)) {
            throw new InvalidArgumentException('Not allowed to view history.');
        }

        return $this->readHistoryIndex($planId);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findRevision(string $planId, string $revisionId, AccountUser $actor): ?array
    {
        $plan = $this->find($planId);
        if ($plan === null || ! $this->canAccess($actor, $plan)) {
            throw new InvalidArgumentException('Not allowed to view history.');
        }

        $path = $this->revisionPath($planId, $revisionId);
        if (! is_file($path)) {
            return null;
        }
        $revision = $this->store->read($path, []);

        return isset($revision['id']) ? $revision : null;
    }

    /**
     * Restore plan to the snapshot stored in a revision (state before that change).
     *
     * @return array<string, mixed>
     */
    public function restoreRevision(string $planId, string $revisionId, AccountUser $actor): array
    {
        $revision = $this->findRevision($planId, $revisionId, $actor);
        if ($revision === null) {
            throw new InvalidArgumentException('Revision not found.');
        }
        $snapshot = $revision['snapshot'] ?? null;
        if (! is_array($snapshot) || ! isset($snapshot['id'])) {
            throw new InvalidArgumentException('Revision snapshot missing.');
        }

        $snapshot['id'] = $planId;

        return $this->save($snapshot, $actor, [
            'action' => 'restore',
            'summary' => 'Restored from '.$revisionId,
        ]);
    }

    private const HISTORY_RETENTION = 50;

    /**
     * @param  array<string, mixed>  $existing
     * @param  array{action?: string, summary?: string}|array<string, mixed>  $historyMeta
     */
    private function recordRevision(array $existing, AccountUser $actor, array $historyMeta): void
    {
        $planId = (string) ($existing['id'] ?? '');
        if ($planId === '') {
            return;
        }

        $revisionId = 'rev_'.bin2hex(random_bytes(8));
        $entry = [
            'id' => $revisionId,
            'planId' => $planId,
            'createdAt' => now()->toIso8601String(),
            'actorUserId' => $actor->id,
            'actorLabel' => $actor->displayName !== '' ? $actor->displayName : $actor->email,
            'action' => (string) ($historyMeta['action'] ?? 'update'),
            'summary' => (string) ($historyMeta['summary'] ?? 'Plan updated'),
            'snapshot' => $existing,
        ];

        $dir = $this->config->planHistoryDirectory($planId);
        $this->store->ensureDirectory($dir);
        $this->store->write($this->revisionPath($planId, $revisionId), $entry);

        $index = $this->readHistoryIndex($planId);
        array_unshift($index, [
            'id' => $revisionId,
            'planId' => $planId,
            'createdAt' => $entry['createdAt'],
            'actorUserId' => $entry['actorUserId'],
            'actorLabel' => $entry['actorLabel'],
            'action' => $entry['action'],
            'summary' => $entry['summary'],
        ]);

        while (count($index) > self::HISTORY_RETENTION) {
            $dropped = array_pop($index);
            if (is_array($dropped) && isset($dropped['id'])) {
                @unlink($this->revisionPath($planId, (string) $dropped['id']));
            }
        }

        $this->store->write($this->historyIndexPath($planId), ['revisions' => array_values($index)]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function readHistoryIndex(string $planId): array
    {
        $path = $this->historyIndexPath($planId);
        if (! is_file($path)) {
            return [];
        }
        $data = $this->store->read($path, []);
        $revisions = $data['revisions'] ?? [];

        return is_array($revisions) ? array_values($revisions) : [];
    }

    private function historyIndexPath(string $planId): string
    {
        return $this->config->planHistoryDirectory($planId).DIRECTORY_SEPARATOR.'index.json';
    }

    private function revisionPath(string $planId, string $revisionId): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $revisionId) ?: 'invalid';

        return $this->config->planHistoryDirectory($planId).DIRECTORY_SEPARATOR.$safe.'.json';
    }

    private function removeHistoryDirectory(string $planId): void
    {
        $dir = $this->config->planHistoryDirectory($planId);
        if (! is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            @unlink($dir.DIRECTORY_SEPARATOR.$item);
        }
        @rmdir($dir);
        $parent = dirname($dir);
        if (is_dir($parent)) {
            $remaining = array_diff(scandir($parent) ?: [], ['.', '..']);
            if ($remaining === []) {
                @rmdir($parent);
            }
        }
    }

    /**
     * @param  array<string, mixed>  $plan
     */
    public function canAccess(AccountUser $user, array $plan): bool
    {
        if (($plan['ownerUserId'] ?? null) === $user->id) {
            return true;
        }

        $participantIds = array_map('strval', $plan['participantIds'] ?? []);
        if (in_array($user->id, $participantIds, true)) {
            return true;
        }

        return $this->isViewer($user, $plan) || $this->isPlanTeamMember($user, $plan);
    }

    /**
     * @param  array<string, mixed>  $plan
     */
    private function isViewer(AccountUser $user, array $plan): bool
    {
        $viewerUsers = array_map('strval', $plan['viewerUserIds'] ?? []);
        if (in_array($user->id, $viewerUsers, true)) {
            return true;
        }

        return $this->userBelongsToAnyTeam($user, array_map('strval', $plan['viewerTeamIds'] ?? []));
    }

    /**
     * Members of the plan's working team(s) can open/edit the plan (not only explicit share list).
     *
     * @param  array<string, mixed>  $plan
     */
    private function isPlanTeamMember(AccountUser $user, array $plan): bool
    {
        $plan = $this->normalizePlanTeams($plan);

        return $this->userBelongsToAnyTeam($user, array_map('strval', $plan['teamIds'] ?? []));
    }

    /**
     * @param  list<string>  $teamIds
     */
    private function userBelongsToAnyTeam(AccountUser $user, array $teamIds): bool
    {
        if ($teamIds === []) {
            return false;
        }

        foreach ($user->teamIds as $teamId) {
            if (in_array($teamId, $teamIds, true)) {
                return true;
            }
        }

        foreach ($this->teams->all(true) as $team) {
            if (in_array($team->id, $teamIds, true) && in_array($user->id, $team->memberIds, true)) {
                return true;
            }
        }

        return false;
    }

    private function pathFor(string $planId): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $planId) ?: 'invalid';

        return $this->config->plansDirectory().DIRECTORY_SEPARATOR.$safe.'.json';
    }

    /**
     * Keep plan identity / ACL / progress when a partial client payload left them empty.
     *
     * @param  array<string, mixed>  $existing
     * @param  array<string, mixed>  $incoming
     * @param  array{action?: string, summary?: string}|array<string, mixed>  $historyMeta
     * @return array<string, mixed>
     */
    private function mergePreservedFields(array $existing, array $incoming, array $historyMeta = []): array
    {
        foreach (['templateSlug', 'startedAt', 'createdAt', 'ownerUserId'] as $key) {
            $next = $incoming[$key] ?? null;
            $prev = $existing[$key] ?? null;
            if (($next === null || $next === '') && $prev !== null && $prev !== '') {
                $incoming[$key] = $prev;
            }
        }

        foreach (['viewerUserIds', 'viewerTeamIds', 'participantIds', 'linkedStorySlugs'] as $key) {
            $next = $incoming[$key] ?? null;
            $prev = $existing[$key] ?? null;
            if ($this->isEmptyStructure($next) && ! $this->isEmptyStructure($prev)) {
                $incoming[$key] = $prev;
            }
        }

        // Restores must be allowed to clear progress bags from an older snapshot.
        $isRestore = ($historyMeta['action'] ?? '') === 'restore';
        if (! $isRestore) {
            foreach ([
                'completedTasks',
                'completedDeliverables',
                'fieldValues',
                'sprintNotes',
                'customTasks',
                'customDeliverables',
                'customSprints',
                'sprintOverrides',
                'itemOverrides',
                'removedItemKeys',
            ] as $key) {
                $next = $incoming[$key] ?? null;
                $prev = $existing[$key] ?? null;
                if ($this->isEmptyStructure($next) && ! $this->isEmptyStructure($prev)) {
                    $incoming[$key] = $prev;
                }
            }
        }

        $prevTranslations = $existing['translations'] ?? null;
        $nextTranslations = $incoming['translations'] ?? null;
        if ($this->isEmptyStructure($nextTranslations) && ! $this->isEmptyStructure($prevTranslations)) {
            $incoming['translations'] = $prevTranslations;
        }

        $prevSnapshot = $existing['templateSnapshot'] ?? null;
        $nextSnapshot = $incoming['templateSnapshot'] ?? null;
        if ($this->isEmptyStructure($nextSnapshot) && ! $this->isEmptyStructure($prevSnapshot)) {
            $incoming['templateSnapshot'] = $prevSnapshot;
        }

        $incoming = $this->normalizePlanTeams($incoming);
        $existingTeams = $this->normalizePlanTeams($existing);
        $nextTeams = $incoming['teamIds'] ?? [];
        if (
            (! is_array($nextTeams) || $nextTeams === [])
            && is_array($existingTeams['teamIds'] ?? null)
            && $existingTeams['teamIds'] !== []
        ) {
            $incoming['teamIds'] = $existingTeams['teamIds'];
            $incoming['teamId'] = null;
        }

        return $incoming;
    }

    /**
     * Migrate legacy teamId into teamIds.
     *
     * @param  array<string, mixed>  $plan
     * @return array<string, mixed>
     */
    private function normalizePlanTeams(array $plan): array
    {
        $teamIds = [];
        if (isset($plan['teamIds']) && is_array($plan['teamIds'])) {
            foreach ($plan['teamIds'] as $teamId) {
                $value = trim((string) $teamId);
                if ($value !== '') {
                    $teamIds[] = $value;
                }
            }
        }
        if ($teamIds === [] && isset($plan['teamId']) && $plan['teamId'] !== null && $plan['teamId'] !== '') {
            $teamIds[] = (string) $plan['teamId'];
        }
        $plan['teamIds'] = array_values(array_unique($teamIds));
        $plan['teamId'] = null;

        return $plan;
    }

    private function isEmptyStructure(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }
        if (! is_array($value)) {
            return false;
        }

        return $value === [];
    }
}
