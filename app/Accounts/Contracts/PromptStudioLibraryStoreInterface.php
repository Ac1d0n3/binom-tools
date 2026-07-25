<?php

namespace App\Accounts\Contracts;

use App\Accounts\AccountUser;

interface PromptStudioLibraryStoreInterface
{
    public function libraryDirectory(): string;

    /**
     * @return array{templates: list<array<string, mixed>>, chains: list<array<string, mixed>>, customRoles: list<array<string, mixed>>, ownerUserId?: string, updatedAt?: string}
     */
    public function loadFor(AccountUser $user): array;

    /**
     * @param  array<string, mixed>  $library
     * @return array{templates: list<array<string, mixed>>, chains: list<array<string, mixed>>, customRoles: list<array<string, mixed>>, ownerUserId: string, updatedAt: string}
     */
    public function saveFor(AccountUser $user, array $library): array;
}
