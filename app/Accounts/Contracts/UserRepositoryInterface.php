<?php

namespace App\Accounts\Contracts;

use App\Accounts\AccountUser;

interface UserRepositoryInterface
{
    /**
     * @return list<AccountUser>
     */
    public function all(): array;

    public function findById(string $id): ?AccountUser;

    public function findByEmail(string $email): ?AccountUser;

    /**
     * @param  array{
     *   id?: string,
     *   email: string,
     *   displayName?: string,
     *   passwordHash: string,
     *   teamIds?: list<string>,
     *   canManageUsers?: bool,
     *   canManageTeams?: bool,
     *   active?: bool,
     *   pendingApproval?: bool,
     *   shortName?: string,
     *   colorToken?: string,
     *   avatarIcon?: string,
     *   mustChangePassword?: bool
     * }  $input
     */
    public function upsert(array $input): AccountUser;

    public function setPasswordHash(string $emailOrId, string $passwordHash): AccountUser;

    public function delete(string $id): void;
}
