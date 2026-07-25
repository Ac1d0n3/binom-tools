<?php

namespace App\Accounts\Database;

use App\Accounts\AccountUser;
use App\Accounts\Contracts\UserTemplateStoreInterface;
use App\Models\BnTools\BnUserTemplate;
use InvalidArgumentException;

final class DatabaseUserTemplateStore implements UserTemplateStoreInterface
{
    public function listFor(AccountUser $user): array
    {
        $query = BnUserTemplate::query()->orderByDesc('updated_at');
        if (! $user->canManageUsers) {
            $query->where('owner_user_id', $user->id);
        }

        $out = [];
        foreach ($query->get() as $row) {
            $template = is_array($row->payload) ? $row->payload : [];
            if ($template === [] || ! isset($template['id'])) {
                continue;
            }
            $out[] = $template;
        }

        return $out;
    }

    public function find(string $templateId): ?array
    {
        $row = BnUserTemplate::query()->find($templateId);
        if ($row === null) {
            return null;
        }
        $template = is_array($row->payload) ? $row->payload : [];

        return isset($template['id']) ? $template : null;
    }

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

        BnUserTemplate::query()->updateOrCreate(
            ['id' => $id],
            [
                'owner_user_id' => (string) $template['ownerUserId'],
                'payload' => $template,
            ],
        );

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
        BnUserTemplate::query()->where('id', $templateId)->delete();
    }
}
