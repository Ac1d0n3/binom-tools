<?php

namespace App\Profile\Contracts;

use App\Accounts\AccountUser;

interface WorkspaceStoreInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function listFor(AccountUser $user, bool $includeArchived = false): array;

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $workspaceId, AccountUser $user): ?array;

    /**
     * @param  array<string, mixed>  $workspace
     * @return array<string, mixed>
     */
    public function save(array $workspace, AccountUser $actor): array;

    public function archive(string $workspaceId, AccountUser $actor): void;

    /**
     * @return array<string, mixed>
     */
    public function duplicate(string $workspaceId, AccountUser $actor, ?string $name = null): array;

    public function activeId(AccountUser $user): ?string;

    public function setActive(AccountUser $user, ?string $workspaceId): void;

    /**
     * @param  array<string, mixed>  $selection
     * @return array<string, mixed>
     */
    public function upsertSavedStack(string $workspaceId, AccountUser $actor, string $name, array $selection, ?string $stackId = null): array;

    public function removeSavedStack(string $workspaceId, AccountUser $actor, string $stackId): void;

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function upsertToolArtifact(
        string $workspaceId,
        AccountUser $actor,
        string $name,
        string $toolId,
        array $payload,
        string $kind = 'dq-config',
        ?string $region = null,
        ?string $artifactId = null,
    ): array;

    public function removeToolArtifact(string $workspaceId, AccountUser $actor, string $artifactId): void;
}
