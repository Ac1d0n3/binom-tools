<?php

namespace App\Profile;

use App\Accounts\AccountUser;
use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Profile\Contracts\WorkspaceStoreInterface;
use InvalidArgumentException;

final class WorkspaceStore implements WorkspaceStoreInterface
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    public function listFor(AccountUser $user, bool $includeArchived = false): array
    {
        $this->store->ensureDirectory($this->directory());
        $files = glob($this->directory().DIRECTORY_SEPARATOR.'*.json') ?: [];
        $out = [];
        foreach ($files as $file) {
            $row = $this->store->read($file, []);
            if (($row['ownerUserId'] ?? '') !== $user->id) {
                continue;
            }
            if (! $includeArchived && ! empty($row['archived'])) {
                continue;
            }
            if (isset($row['id'])) {
                $out[] = $row;
            }
        }

        usort($out, static fn (array $a, array $b): int => strcmp((string) ($b['updatedAt'] ?? ''), (string) ($a['updatedAt'] ?? '')));

        return $out;
    }

    public function find(string $workspaceId, AccountUser $user): ?array
    {
        $path = $this->pathFor($workspaceId);
        if (! is_file($path)) {
            return null;
        }
        $row = $this->store->read($path, []);
        if (($row['ownerUserId'] ?? '') !== $user->id) {
            return null;
        }

        return isset($row['id']) ? $row : null;
    }

    public function save(array $workspace, AccountUser $actor): array
    {
        $id = (string) ($workspace['id'] ?? '');
        if ($id === '') {
            $id = 'ws_'.bin2hex(random_bytes(8));
        }
        if (! preg_match('/^ws_[a-zA-Z0-9_]+$/', $id)) {
            throw new InvalidArgumentException('Invalid workspace id.');
        }

        $existing = $this->find($id, $actor);
        $name = trim((string) ($workspace['name'] ?? $existing['name'] ?? ''));
        if ($name === '') {
            throw new InvalidArgumentException('Workspace name is required.');
        }

        $now = now()->toIso8601String();
        $customStack = $workspace['customStack'] ?? $existing['customStack'] ?? null;
        if (! is_array($customStack)) {
            $customStack = null;
        }
        $savedStacks = $workspace['savedStacks'] ?? $existing['savedStacks'] ?? [];
        if (! is_array($savedStacks)) {
            $savedStacks = [];
        }
        $savedStacks = array_values(array_filter(array_map(
            static function (mixed $item): ?array {
                if (! is_array($item)) {
                    return null;
                }
                $id = trim((string) ($item['id'] ?? ''));
                $name = trim((string) ($item['name'] ?? ''));
                if ($id === '' || $name === '' || ! is_array($item['selection'] ?? null)) {
                    return null;
                }

                return [
                    'id' => $id,
                    'name' => mb_substr($name, 0, 120),
                    'selection' => $item['selection'],
                    'updatedAt' => (string) ($item['updatedAt'] ?? now()->toIso8601String()),
                ];
            },
            $savedStacks
        )));

        $row = [
            'id' => $id,
            'ownerUserId' => $actor->id,
            'name' => $name,
            'stack' => (string) ($workspace['stack'] ?? $existing['stack'] ?? 'unknown'),
            'customStack' => $customStack,
            'savedStacks' => array_slice($savedStacks, 0, 40),
            'label' => trim((string) ($workspace['label'] ?? $existing['label'] ?? '')),
            'notes' => trim((string) ($workspace['notes'] ?? $existing['notes'] ?? '')),
            'archived' => (bool) ($workspace['archived'] ?? $existing['archived'] ?? false),
            'createdAt' => $existing['createdAt'] ?? $now,
            'updatedAt' => $now,
        ];

        $this->store->ensureDirectory($this->directory());
        $this->store->write($this->pathFor($id), $row);

        return $row;
    }

    public function archive(string $workspaceId, AccountUser $actor): void
    {
        $row = $this->find($workspaceId, $actor);
        if ($row === null) {
            return;
        }
        $row['archived'] = true;
        $this->save($row, $actor);
        if ($this->activeId($actor) === $workspaceId) {
            $this->setActive($actor, null);
        }
    }

    /**
     * @param  array<string, mixed>  $selection
     * @return array<string, mixed>
     */
    public function upsertSavedStack(string $workspaceId, AccountUser $actor, string $name, array $selection, ?string $stackId = null): array
    {
        $workspace = $this->find($workspaceId, $actor);
        if ($workspace === null) {
            throw new InvalidArgumentException('Workspace not found.');
        }

        $trimmed = trim($name);
        if ($trimmed === '') {
            throw new InvalidArgumentException('Stack name is required.');
        }

        $items = is_array($workspace['savedStacks'] ?? null) ? $workspace['savedStacks'] : [];
        $now = now()->toIso8601String();
        $matchIndex = null;
        foreach ($items as $index => $item) {
            if (! is_array($item)) {
                continue;
            }
            if ($stackId !== null && ($item['id'] ?? '') === $stackId) {
                $matchIndex = $index;
                break;
            }
            if (strcasecmp((string) ($item['name'] ?? ''), $trimmed) === 0) {
                $matchIndex = $index;
                break;
            }
        }

        $entry = [
            'id' => $stackId ?: ('stack_'.bin2hex(random_bytes(6))),
            'name' => mb_substr($trimmed, 0, 120),
            'selection' => $selection,
            'updatedAt' => $now,
        ];
        if ($matchIndex !== null) {
            $entry['id'] = (string) ($items[$matchIndex]['id'] ?? $entry['id']);
            $items[$matchIndex] = $entry;
        } else {
            array_unshift($items, $entry);
        }

        $workspace['savedStacks'] = $items;
        $this->save($workspace, $actor);

        return $entry;
    }

    public function removeSavedStack(string $workspaceId, AccountUser $actor, string $stackId): void
    {
        $workspace = $this->find($workspaceId, $actor);
        if ($workspace === null) {
            throw new InvalidArgumentException('Workspace not found.');
        }
        $items = is_array($workspace['savedStacks'] ?? null) ? $workspace['savedStacks'] : [];
        $workspace['savedStacks'] = array_values(array_filter(
            $items,
            static fn (mixed $item): bool => is_array($item) && ($item['id'] ?? '') !== $stackId
        ));
        $this->save($workspace, $actor);
    }

    public function duplicate(string $workspaceId, AccountUser $actor, ?string $name = null): array
    {
        $source = $this->find($workspaceId, $actor);
        if ($source === null) {
            throw new InvalidArgumentException('Workspace not found.');
        }

        return $this->save([
            'name' => $name ?: ($source['name'].' (copy)'),
            'stack' => $source['stack'] ?? 'unknown',
            'customStack' => is_array($source['customStack'] ?? null) ? $source['customStack'] : null,
            'savedStacks' => is_array($source['savedStacks'] ?? null) ? $source['savedStacks'] : [],
            'label' => $source['label'] ?? '',
            'notes' => $source['notes'] ?? '',
            'archived' => false,
        ], $actor);
    }

    public function activeId(AccountUser $user): ?string
    {
        $path = $this->activePath($user->id);
        if (! is_file($path)) {
            return null;
        }
        $data = $this->store->read($path, []);
        $id = isset($data['workspaceId']) ? (string) $data['workspaceId'] : '';

        return $id !== '' ? $id : null;
    }

    public function setActive(AccountUser $user, ?string $workspaceId): void
    {
        $this->store->ensureDirectory($this->directory());
        if ($workspaceId !== null && $this->find($workspaceId, $user) === null) {
            throw new InvalidArgumentException('Workspace not found.');
        }
        $this->store->write($this->activePath($user->id), [
            'workspaceId' => $workspaceId,
            'updatedAt' => now()->toIso8601String(),
        ]);
    }

    private function directory(): string
    {
        return $this->config->basePath().DIRECTORY_SEPARATOR.'workspaces';
    }

    private function pathFor(string $id): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $id) ?: 'invalid';

        return $this->directory().DIRECTORY_SEPARATOR.$safe.'.json';
    }

    private function activePath(string $userId): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $userId) ?: 'invalid';

        return $this->directory().DIRECTORY_SEPARATOR.'active_'.$safe.'.json';
    }
}
