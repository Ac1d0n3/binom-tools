<?php

namespace App\Playbooks\Database;

use App\Models\BnTools\BnPlaybookStat;
use App\Playbooks\Contracts\PlaybookStatsStoreInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class DatabasePlaybookStatsStore implements PlaybookStatsStoreInterface
{
    private readonly ?string $seedDirectory;

    public function __construct(?string $seedDirectory = null)
    {
        $this->seedDirectory = $seedDirectory ?? base_path('modules/playbooks/script/stats-seed');
    }

    public function get(string $slug): array
    {
        $slug = $this->assertSlug($slug);
        $row = BnPlaybookStat::query()->find($slug);
        if ($row !== null) {
            return ['views' => (int) $row->views, 'likes' => (int) $row->likes];
        }

        $seeded = $this->readSeed($slug);
        if ($seeded !== null) {
            $this->set($slug, $seeded['views'], $seeded['likes']);

            return $seeded;
        }

        return ['views' => 0, 'likes' => 0];
    }

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

    public function set(string $slug, int $views, int $likes): array
    {
        $slug = $this->assertSlug($slug);
        $payload = ['views' => max(0, $views), 'likes' => max(0, $likes)];
        BnPlaybookStat::query()->updateOrCreate(
            ['slug' => $slug],
            $payload,
        );

        return $payload;
    }

    public function incrementView(string $slug): array
    {
        return $this->mutate($slug, static function (array $stats): array {
            $stats['views']++;

            return $stats;
        });
    }

    public function like(string $slug): array
    {
        $stats = $this->mutate($slug, static function (array $stats): array {
            $stats['likes']++;

            return $stats;
        });

        return [...$stats, 'liked' => true];
    }

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

        return DB::transaction(function () use ($slug, $callback): array {
            $row = BnPlaybookStat::query()->where('slug', $slug)->lockForUpdate()->first();
            if ($row === null) {
                $seeded = $this->readSeed($slug) ?? ['views' => 0, 'likes' => 0];
                $stats = $callback($seeded);
                BnPlaybookStat::query()->create([
                    'slug' => $slug,
                    'views' => $stats['views'],
                    'likes' => $stats['likes'],
                ]);

                return $stats;
            }

            $stats = $callback([
                'views' => (int) $row->views,
                'likes' => (int) $row->likes,
            ]);
            $row->views = $stats['views'];
            $row->likes = $stats['likes'];
            $row->save();

            return $stats;
        });
    }

    /**
     * @return array{views: int, likes: int}|null
     */
    private function readSeed(string $slug): ?array
    {
        if ($this->seedDirectory === null || $this->seedDirectory === '') {
            return null;
        }
        $path = $this->seedDirectory.DIRECTORY_SEPARATOR.$slug.'.json';
        if (! is_file($path)) {
            return null;
        }
        $raw = file_get_contents($path);
        if ($raw === false || trim($raw) === '') {
            return null;
        }
        try {
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }
        if (! is_array($data)) {
            return null;
        }

        return [
            'views' => max(0, (int) ($data['views'] ?? 0)),
            'likes' => max(0, (int) ($data['likes'] ?? 0)),
        ];
    }

    private function assertSlug(string $slug): string
    {
        if (! preg_match('/^[a-z0-9-]+$/', $slug)) {
            throw new InvalidArgumentException('Invalid playbook slug.');
        }

        return $slug;
    }
}
