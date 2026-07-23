<?php

namespace App\Accounts;

use InvalidArgumentException;

final class PromptStudioLibraryStore
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    public function libraryDirectory(): string
    {
        return $this->config->promptStudioLibraryDirectory();
    }

    /**
     * @return array{templates: list<array<string, mixed>>, chains: list<array<string, mixed>>, customRoles: list<array<string, mixed>>, ownerUserId?: string, updatedAt?: string}
     */
    public function loadFor(AccountUser $user): array
    {
        $this->store->ensureDirectory($this->libraryDirectory());
        $path = $this->pathFor($user->id);
        if (! is_file($path)) {
            return [
                'templates' => [],
                'chains' => [],
                'customRoles' => [],
            ];
        }
        $data = $this->store->read($path, []);
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

    /**
     * @param  array<string, mixed>  $library
     * @return array{templates: list<array<string, mixed>>, chains: list<array<string, mixed>>, customRoles: list<array<string, mixed>>, ownerUserId: string, updatedAt: string}
     */
    public function saveFor(AccountUser $user, array $library): array
    {
        $this->store->ensureDirectory($this->libraryDirectory());
        $payload = [
            'ownerUserId' => $user->id,
            'updatedAt' => gmdate('c'),
            'templates' => array_values(array_filter($library['templates'] ?? [], 'is_array')),
            'chains' => array_values(array_filter($library['chains'] ?? [], 'is_array')),
            'customRoles' => array_values(array_filter($library['customRoles'] ?? [], 'is_array')),
        ];
        $this->store->write($this->pathFor($user->id), $payload);

        return $payload;
    }

    private function pathFor(string $userId): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_\-]/', '', $userId) ?: 'invalid';
        if ($safe === 'invalid') {
            throw new InvalidArgumentException('Invalid user id.');
        }

        return $this->libraryDirectory().DIRECTORY_SEPARATOR.$safe.'.json';
    }
}
