<?php

namespace App\Accounts\Contracts;

use App\Accounts\AccountUser;

interface UserTemplateStoreInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function listFor(AccountUser $user): array;

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $templateId): ?array;

    /**
     * @param  array<string, mixed>  $template
     * @return array<string, mixed>
     */
    public function save(array $template, AccountUser $actor): array;

    public function delete(string $templateId, AccountUser $actor): void;
}
