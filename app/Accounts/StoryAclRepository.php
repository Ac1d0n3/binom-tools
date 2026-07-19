<?php

namespace App\Accounts;

final class StoryAclRepository
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    /**
     * @return array{visibility: string, userIds: list<string>, teamIds: list<string>}
     */
    public function forSlug(string $slug): array
    {
        $all = $this->all();
        $entry = $all[$slug] ?? null;
        if (! is_array($entry)) {
            return ['visibility' => 'public', 'userIds' => [], 'teamIds' => []];
        }

        $visibility = (string) ($entry['visibility'] ?? 'public');
        if (! in_array($visibility, ['public', 'restricted'], true)) {
            $visibility = 'public';
        }

        return [
            'visibility' => $visibility,
            'userIds' => array_values(array_map('strval', $entry['userIds'] ?? [])),
            'teamIds' => array_values(array_map('strval', $entry['teamIds'] ?? [])),
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

    /**
     * @param  array{visibility: string, userIds?: list<string>, teamIds?: list<string>}  $acl
     */
    public function set(string $slug, array $acl): void
    {
        $all = $this->all();
        $all[$slug] = [
            'visibility' => $acl['visibility'] === 'restricted' ? 'restricted' : 'public',
            'userIds' => array_values(array_map('strval', $acl['userIds'] ?? [])),
            'teamIds' => array_values(array_map('strval', $acl['teamIds'] ?? [])),
        ];
        $this->store->write($this->config->storyAclPath(), [
            'schemaVersion' => 1,
            'stories' => $all,
        ]);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        $raw = $this->store->read($this->config->storyAclPath(), [
            'schemaVersion' => 1,
            'stories' => [],
        ]);
        $stories = $raw['stories'] ?? [];

        return is_array($stories) ? $stories : [];
    }
}
