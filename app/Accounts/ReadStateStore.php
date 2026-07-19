<?php

namespace App\Accounts;

final class ReadStateStore
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    /**
     * @return array<string, int>
     */
    public function forUser(string $userId): array
    {
        $path = $this->pathFor($userId);
        $raw = $this->store->read($path, ['schemaVersion' => 1, 'read' => []]);
        $read = $raw['read'] ?? [];
        if (! is_array($read)) {
            return [];
        }

        $out = [];
        foreach ($read as $slug => $at) {
            if (is_string($slug) && $slug !== '' && is_numeric($at)) {
                $out[$slug] = (int) $at;
            }
        }

        return $out;
    }

    public function markRead(string $userId, string $slug): void
    {
        $read = $this->forUser($userId);
        if (! isset($read[$slug])) {
            $read[$slug] = time();
        }
        $this->persist($userId, $read);
    }

    public function clear(string $userId): void
    {
        $this->persist($userId, []);
    }

    public function isRead(string $userId, string $slug): bool
    {
        return array_key_exists($slug, $this->forUser($userId));
    }

    /**
     * @param  array<string, int>  $read
     */
    private function persist(string $userId, array $read): void
    {
        $this->store->ensureDirectory($this->config->readStateDirectory());
        $this->store->write($this->pathFor($userId), [
            'schemaVersion' => 1,
            'read' => $read,
        ]);
    }

    private function pathFor(string $userId): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $userId) ?: 'unknown';

        return $this->config->readStateDirectory().DIRECTORY_SEPARATOR.$safe.'.json';
    }
}
