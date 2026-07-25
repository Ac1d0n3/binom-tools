<?php

namespace App\Accounts\Contracts;

use App\Accounts\AccountTeam;

interface TeamRepositoryInterface
{
    /**
     * @return list<AccountTeam>
     */
    public function all(bool $includeArchived = false): array;

    public function findById(string $id): ?AccountTeam;

    /**
     * @param  array<string, mixed>  $input
     */
    public function upsert(array $input): AccountTeam;

    public function delete(string $id): void;
}
