<?php

namespace App\Accounts\Database;

use App\Accounts\AccountUser;
use App\Accounts\AccountsConfig;
use App\Accounts\Contracts\PromptStudioLibraryStoreInterface;
use App\Models\BnTools\BnPromptStudioLibrary;
use InvalidArgumentException;

final class DatabasePromptStudioLibraryStore implements PromptStudioLibraryStoreInterface
{
    public function __construct(
        private readonly AccountsConfig $config,
    ) {}

    public function libraryDirectory(): string
    {
        return $this->config->promptStudioLibraryDirectory();
    }

    public function loadFor(AccountUser $user): array
    {
        $row = BnPromptStudioLibrary::query()->find($user->id);
        if ($row === null) {
            return [
                'templates' => [],
                'chains' => [],
                'customRoles' => [],
            ];
        }
        $data = is_array($row->payload) ? $row->payload : [];
        if (($data['ownerUserId'] ?? null) !== $user->id && ! ($user->canManageUsers ?? false)) {
            return [
                'templates' => [],
                'chains' => [],
                'customRoles' => [],
            ];
        }

        return [
            'templates' => array_values(array_filter($data['templates'] ?? [], 'is_array')),
            'chains' => array_values(array_filter($data['chains'] ?? [], 'is_array')),
            'customRoles' => array_values(array_filter($data['customRoles'] ?? [], 'is_array')),
            'ownerUserId' => $user->id,
            'updatedAt' => (string) ($data['updatedAt'] ?? ''),
        ];
    }

    public function saveFor(AccountUser $user, array $library): array
    {
        if ($user->id === '') {
            throw new InvalidArgumentException('Invalid user id.');
        }

        $payload = [
            'ownerUserId' => $user->id,
            'updatedAt' => gmdate('c'),
            'templates' => array_values(array_filter($library['templates'] ?? [], 'is_array')),
            'chains' => array_values(array_filter($library['chains'] ?? [], 'is_array')),
            'customRoles' => array_values(array_filter($library['customRoles'] ?? [], 'is_array')),
        ];

        BnPromptStudioLibrary::query()->updateOrCreate(
            ['owner_user_id' => $user->id],
            ['payload' => $payload],
        );

        return $payload;
    }
}
