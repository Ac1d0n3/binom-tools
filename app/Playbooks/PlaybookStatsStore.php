<?php

namespace App\Playbooks;

use InvalidArgumentException;
use RuntimeException;

/**
 * File-based playbook view/like counters (no database).
 * Storage: storage/app/playbook-stats/{slug}.json
 */
final class PlaybookStatsStore
{
    public function __construct(
        private readonly string $directory,
    ) {}

    public static function default(): self
    {
        return new self(storage_path('app/playbook-stats'));
    }

    /**
     * @return array{views: int, likes: int}
     */
    public function get(string $slug): array
    {
        $slug = $this->assertSlug($slug);
        $path = $this->path($slug);

        if (! is_file($path)) {
            return ['views' => 0, 'likes' => 0];
        }

        return $this->readFile($path);
    }

    /**
     * @param  list<string>  $slugs
     * @return array<string, array{views: int, likes: int}>
     */
    public function getMany(array $slugs): array
    {
        $out = [];

        foreach ($slugs as $slug) {
            if (! is_string($slug) || $slug === '' || isset($out[$slug])) {
                continue;
            }

            $out[$slug] = $this->get($slug);
        }

        return $out;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    public function attachToItems(array $items): array
    {
        $slugs = [];

        foreach ($items as $item) {
            if (isset($item['slug']) && is_string($item['slug']) && $item['slug'] !== '') {
                $slugs[] = $item['slug'];
            }
        }

        $stats = $this->getMany($slugs);

        return array_map(static function (array $item) use ($stats): array {
            $slug = is_string($item['slug'] ?? null) ? $item['slug'] : '';
            $item['stats'] = $stats[$slug] ?? ['views' => 0, 'likes' => 0];

            return $item;
        }, $items);
    }

    /**
     * Overwrite counters (used for demo seeding).
     *
     * @return array{views: int, likes: int}
     */
    public function set(string $slug, int $views, int $likes): array
    {
        return $this->mutate($slug, static function () use ($views, $likes): array {
            return [
                'views' => max(0, $views),
                'likes' => max(0, $likes),
            ];
        });
    }

    /**
     * @return array{views: int, likes: int}
     */
    public function incrementView(string $slug): array
    {
        return $this->mutate($slug, static function (array $stats): array {
            $stats['views']++;

            return $stats;
        });
    }

    /**
     * @return array{views: int, likes: int, liked: bool}
     */
    public function like(string $slug): array
    {
        $stats = $this->mutate($slug, static function (array $stats): array {
            $stats['likes']++;

            return $stats;
        });

        return [...$stats, 'liked' => true];
    }

    /**
     * @return array{views: int, likes: int, liked: bool}
     */
    public function unlike(string $slug): array
    {
        $stats = $this->mutate($slug, static function (array $stats): array {
            $stats['likes'] = max(0, $stats['likes'] - 1);

            return $stats;
        });

        return [...$stats, 'liked' => false];
    }

    /**
     * @param  callable(array{views: int, likes: int}): array{views: int, likes: int}  $callback
     * @return array{views: int, likes: int}
     */
    private function mutate(string $slug, callable $callback): array
    {
        $slug = $this->assertSlug($slug);
        $this->ensureDirectory();
        $path = $this->path($slug);

        $handle = fopen($path, 'c+');
        if ($handle === false) {
            throw new RuntimeException('Unable to open playbook stats file: '.$path);
        }

        try {
            if (! flock($handle, LOCK_EX)) {
                throw new RuntimeException('Unable to lock playbook stats file: '.$path);
            }

            $raw = stream_get_contents($handle);
            $stats = $this->decode($raw === false || $raw === '' ? null : $raw);
            $stats = $callback($stats);

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($stats, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)."\n");
            fflush($handle);

            return $stats;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    /**
     * @return array{views: int, likes: int}
     */
    private function readFile(string $path): array
    {
        $raw = file_get_contents($path);

        return $this->decode($raw === false ? null : $raw);
    }

    /**
     * @return array{views: int, likes: int}
     */
    private function decode(?string $raw): array
    {
        if ($raw === null || trim($raw) === '') {
            return ['views' => 0, 'likes' => 0];
        }

        try {
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return ['views' => 0, 'likes' => 0];
        }

        if (! is_array($data)) {
            return ['views' => 0, 'likes' => 0];
        }

        return [
            'views' => max(0, (int) ($data['views'] ?? 0)),
            'likes' => max(0, (int) ($data['likes'] ?? 0)),
        ];
    }

    private function path(string $slug): string
    {
        return $this->directory.DIRECTORY_SEPARATOR.$slug.'.json';
    }

    private function ensureDirectory(): void
    {
        if (is_dir($this->directory)) {
            return;
        }

        if (! mkdir($this->directory, 0775, true) && ! is_dir($this->directory)) {
            throw new RuntimeException('Unable to create playbook stats directory: '.$this->directory);
        }
    }

    private function assertSlug(string $slug): string
    {
        if (! preg_match('/^[a-z0-9-]+$/', $slug)) {
            throw new InvalidArgumentException('Invalid playbook slug.');
        }

        return $slug;
    }
}
