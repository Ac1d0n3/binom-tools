<?php

namespace App\Accounts\Contracts;

use App\Accounts\AccountUser;

interface StoryAclRepositoryInterface
{
    /**
     * @return array{visibility: string, userIds: list<string>, teamIds: list<string>}
     */
    public function forSlug(string $slug): array;

    public function canAccess(?AccountUser $user, string $slug): bool;

    /**
     * @param  array{visibility: string, userIds?: list<string>, teamIds?: list<string>}  $acl
     */
    public function set(string $slug, array $acl): void;

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array;
}
