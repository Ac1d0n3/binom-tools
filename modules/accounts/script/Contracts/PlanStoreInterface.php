<?php

namespace App\Accounts\Contracts;

use App\Accounts\AccountUser;

interface PlanStoreInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function listVisibleTo(AccountUser $user): array;

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $planId): ?array;

    /**
     * @param  array<string, mixed>  $plan
     * @param  array{action?: string, summary?: string}|array<string, mixed>  $historyMeta
     * @return array<string, mixed>
     */
    public function save(array $plan, AccountUser $actor, array $historyMeta = []): array;

    public function delete(string $planId, AccountUser $actor): void;

    /**
     * @return list<array<string, mixed>>
     */
    public function listHistory(string $planId, AccountUser $actor): array;

    /**
     * @return array<string, mixed>|null
     */
    public function findRevision(string $planId, string $revisionId, AccountUser $actor): ?array;

    /**
     * @return array<string, mixed>
     */
    public function restoreRevision(string $planId, string $revisionId, AccountUser $actor): array;

    /**
     * @param  array<string, mixed>  $plan
     */
    public function canAccess(AccountUser $user, array $plan): bool;
}
