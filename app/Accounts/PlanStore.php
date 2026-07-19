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
     * @return array<string, mixed>
     */
    public function save(array $plan, AccountUser $actor): array
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
            if (($existing['ownerUserId'] ?? null) !== $actor->id && ! $this->isViewer($actor, $existing)) {
                throw new InvalidArgumentException('Only owner or viewers can update.');
            }
            // Never let a partial client payload wipe structural identity.
            $plan = $this->mergePreservedFields($existing, $plan);
        }

        $plan['id'] = $id;
        $plan['viewerUserIds'] = array_values(array_map('strval', $plan['viewerUserIds'] ?? $existing['viewerUserIds'] ?? []));
        $plan['viewerTeamIds'] = array_values(array_map('strval', $plan['viewerTeamIds'] ?? $existing['viewerTeamIds'] ?? []));
        $plan['linkedStorySlugs'] = array_values(array_map('strval', $plan['linkedStorySlugs'] ?? $existing['linkedStorySlugs'] ?? []));
        $plan['updatedAt'] = now()->toIso8601String();

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
    }

    /**
     * @param  array<string, mixed>  $plan
     */
    public function canAccess(AccountUser $user, array $plan): bool
    {
        if (($plan['ownerUserId'] ?? null) === $user->id) {
            return true;
        }

        return $this->isViewer($user, $plan);
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

        $viewerTeams = array_map('strval', $plan['viewerTeamIds'] ?? []);
        foreach ($user->teamIds as $teamId) {
            if (in_array($teamId, $viewerTeams, true)) {
                return true;
            }
        }

        // Also allow membership via team file
        foreach ($this->teams->all(true) as $team) {
            if (in_array($team->id, $viewerTeams, true) && in_array($user->id, $team->memberIds, true)) {
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
     * Keep plan identity when a partial client payload left them empty.
     * Progress/override bags are intentionally not merged (empty can be legitimate).
     *
     * @param  array<string, mixed>  $existing
     * @param  array<string, mixed>  $incoming
     * @return array<string, mixed>
     */
    private function mergePreservedFields(array $existing, array $incoming): array
    {
        foreach (['templateSlug', 'startedAt', 'createdAt'] as $key) {
            $next = $incoming[$key] ?? null;
            $prev = $existing[$key] ?? null;
            if (($next === null || $next === '') && $prev !== null && $prev !== '') {
                $incoming[$key] = $prev;
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

        return $incoming;
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
