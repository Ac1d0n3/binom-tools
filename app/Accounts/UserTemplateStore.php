<?php

namespace App\Accounts;

use InvalidArgumentException;

final class UserTemplateStore
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function listFor(AccountUser $user): array
    {
        $this->store->ensureDirectory($this->config->userTemplatesDirectory());
        $files = glob($this->config->userTemplatesDirectory().DIRECTORY_SEPARATOR.'*.json') ?: [];
        $out = [];
        foreach ($files as $file) {
            $template = $this->store->read($file, []);
            if ($template === [] || ! isset($template['id'])) {
                continue;
            }
            if (($template['ownerUserId'] ?? null) === $user->id || ($user->canManageUsers ?? false)) {
                $out[] = $template;
            }
        }

        usort($out, static fn (array $a, array $b): int => strcmp((string) ($b['updatedAt'] ?? ''), (string) ($a['updatedAt'] ?? '')));

        return $out;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $templateId): ?array
    {
        $path = $this->pathFor($templateId);
        if (! is_file($path)) {
            return null;
        }
        $template = $this->store->read($path, []);

        return isset($template['id']) ? $template : null;
    }

    /**
     * @param  array<string, mixed>  $template
     * @return array<string, mixed>
     */
    public function save(array $template, AccountUser $actor): array
    {
        $id = (string) ($template['id'] ?? '');
        if ($id === '' || ! preg_match('/^utpl_[a-zA-Z0-9_]+$/', $id)) {
            throw new InvalidArgumentException('Invalid user template id.');
        }

        $existing = $this->find($id);
        if ($existing !== null && ($existing['ownerUserId'] ?? null) !== $actor->id && ! $actor->canManageUsers) {
            throw new InvalidArgumentException('Not allowed to update this template.');
        }

        $slug = (string) ($template['slug'] ?? '');
        if ($slug === '') {
            $slug = 'custom:'.$id;
        }

        $template['id'] = $id;
        $template['slug'] = $slug;
        $template['version'] = (int) ($template['version'] ?? 1);
        $template['duration'] = max(1, (int) ($template['duration'] ?? 1));
        $template['unit'] = in_array(($template['unit'] ?? 'week'), ['week', 'day'], true)
            ? $template['unit']
            : 'week';
        $template['ownerUserId'] = $existing['ownerUserId'] ?? $actor->id;
        $template['createdAt'] = $existing['createdAt'] ?? now()->toIso8601String();
        $template['updatedAt'] = now()->toIso8601String();
        $template['locales'] = is_array($template['locales'] ?? null) ? $template['locales'] : [
            'de' => ['title' => $slug, 'description' => ''],
            'en' => ['title' => $slug, 'description' => ''],
        ];
        $template['sprints'] = is_array($template['sprints'] ?? null) ? array_values($template['sprints']) : [];
        $template['userTemplate'] = true;

        $this->store->ensureDirectory($this->config->userTemplatesDirectory());
        $this->store->write($this->pathFor($id), $template);

        return $template;
    }

    public function delete(string $templateId, AccountUser $actor): void
    {
        $template = $this->find($templateId);
        if ($template === null) {
            return;
        }
        if (($template['ownerUserId'] ?? null) !== $actor->id && ! $actor->canManageUsers) {
            throw new InvalidArgumentException('Only the owner can delete this template.');
        }
        @unlink($this->pathFor($templateId));
    }

    private function pathFor(string $templateId): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $templateId) ?: 'invalid';

        return $this->config->userTemplatesDirectory().DIRECTORY_SEPARATOR.$safe.'.json';
    }
}
