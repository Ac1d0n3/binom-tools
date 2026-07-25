<?php

namespace App\Accounts\Database;

use App\Accounts\AccountUser;
use App\Accounts\Contracts\StoryAclRepositoryInterface;
use App\Models\BnTools\BnStoryAcl;

final class DatabaseStoryAclRepository implements StoryAclRepositoryInterface
{
    public function forSlug(string $slug): array
    {
        $row = BnStoryAcl::query()->find($slug);
        if ($row === null) {
            return ['visibility' => 'public', 'userIds' => [], 'teamIds' => []];
        }

        $visibility = (string) ($row->visibility ?? 'public');
        if (! in_array($visibility, ['public', 'restricted'], true)) {
            $visibility = 'public';
        }

        return [
            'visibility' => $visibility,
            'userIds' => array_values(array_map('strval', is_array($row->user_ids) ? $row->user_ids : [])),
            'teamIds' => array_values(array_map('strval', is_array($row->team_ids) ? $row->team_ids : [])),
        ];
    }

    public function canAccess(?AccountUser $user, string $slug): bool
    {
        $acl = $this->forSlug($slug);
        if ($acl['visibility'] === 'public') {
            return true;
        }
        if ($user === null) {
            return false;
        }
        if (in_array($user->id, $acl['userIds'], true)) {
            return true;
        }
        foreach ($user->teamIds as $teamId) {
            if (in_array($teamId, $acl['teamIds'], true)) {
                return true;
            }
        }

        return false;
    }

    public function set(string $slug, array $acl): void
    {
        BnStoryAcl::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'visibility' => ($acl['visibility'] ?? '') === 'restricted' ? 'restricted' : 'public',
                'user_ids' => array_values(array_map('strval', $acl['userIds'] ?? [])),
                'team_ids' => array_values(array_map('strval', $acl['teamIds'] ?? [])),
            ],
        );
    }

    public function all(): array
    {
        $out = [];
        foreach (BnStoryAcl::query()->get() as $row) {
            $out[$row->slug] = [
                'visibility' => $row->visibility,
                'userIds' => is_array($row->user_ids) ? $row->user_ids : [],
                'teamIds' => is_array($row->team_ids) ? $row->team_ids : [],
            ];
        }

        return $out;
    }
}
