<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\Playbook;
use App\Playbooks\PlaybookRepository;
use Carbon\Carbon;
use Tests\TestCase;

class PlaybookIndexSortTest extends TestCase
{
    public function test_index_sort_timestamp_adds_series_part_offset(): void
    {
        $base = Carbon::parse('2026-07-13 12:00:00');
        $playbook = new Playbook(
            slug: 'missing-pieces-data-quality',
            heroUrl: null,
            order: -1,
            modifiedAt: Carbon::parse('2099-01-01'),
            variants: [],
            publishedAt: $base,
            seriesId: 'missing-pieces',
            seriesPart: 1,
        );

        $laterPart = new Playbook(
            slug: 'missing-pieces-data-lifecycle-retirement',
            heroUrl: null,
            order: -1,
            modifiedAt: Carbon::parse('2099-01-01'),
            variants: [],
            publishedAt: $base,
            seriesId: 'missing-pieces',
            seriesPart: 6,
        );

        $this->assertSame($base->getTimestamp() + 1, $playbook->indexSortTimestamp());
        $this->assertSame($base->getTimestamp() + 6, $laterPart->indexSortTimestamp());
        $this->assertGreaterThan($playbook->indexSortTimestamp(), $laterPart->indexSortTimestamp());
    }

    public function test_sort_date_ignores_file_mtime_when_published_at_missing(): void
    {
        $playbook = new Playbook(
            slug: 'no-published-at',
            heroUrl: null,
            order: -1,
            modifiedAt: Carbon::parse('2099-06-01 12:00:00'),
            variants: [],
            publishedAt: null,
        );

        $this->assertSame(0, $playbook->sortDate()->getTimestamp());
    }

    public function test_index_orders_series_parts_when_file_timestamps_match(): void
    {
        $repository = app(PlaybookRepository::class);
        $missing = collect($repository->allForIndex())
            ->filter(fn (array $item): bool => ($item['seriesId'] ?? null) === 'missing-pieces')
            ->values()
            ->all();

        $this->assertNotEmpty($missing);

        $parts = array_map(
            static fn (array $item): int => (int) ($item['seriesPart'] ?? 0),
            $missing,
        );

        $sorted = $parts;
        rsort($sorted);

        $this->assertSame($sorted, $parts);
    }

    public function test_every_content_locale_file_declares_published_at(): void
    {
        $contentPath = config('playbooks.content_path');
        $this->assertIsString($contentPath);
        $this->assertDirectoryExists($contentPath);

        $files = glob(rtrim($contentPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'*.{de,en}.md', GLOB_BRACE);
        $this->assertNotFalse($files);
        $this->assertNotEmpty($files);

        $missing = [];

        foreach ($files as $path) {
            $raw = file_get_contents($path) ?: '';
            if (! str_starts_with($raw, "---\n") && ! str_starts_with($raw, "---\r\n")) {
                $missing[] = basename($path).' (no frontmatter)';
                continue;
            }

            $end = strpos($raw, "\n---", 3);
            if ($end === false) {
                $missing[] = basename($path).' (unclosed frontmatter)';
                continue;
            }

            $frontmatter = substr($raw, 3, $end - 3);
            if (! preg_match('/^publishedAt\s*:/m', $frontmatter)) {
                $missing[] = basename($path);
            }
        }

        $this->assertSame(
            [],
            $missing,
            'Every story locale file must declare frontmatter publishedAt so overview order stays deploy-stable.',
        );
    }

    public function test_roles_hub_parts_share_stable_published_day(): void
    {
        $repository = app(PlaybookRepository::class);
        $roles = collect($repository->allForIndex())
            ->filter(fn (array $item): bool => ($item['seriesId'] ?? null) === 'roles-hub')
            ->sortBy(fn (array $item): int => (int) ($item['seriesPart'] ?? 0))
            ->values()
            ->all();

        $this->assertCount(5, $roles);

        foreach ($roles as $item) {
            $this->assertNotNull($item['publishedAt'] ?? null, 'roles-hub part missing publishedAt: '.$item['slug']);
            $this->assertSame(
                '2026-07-19',
                $item['publishedAt']->format('Y-m-d'),
                'roles-hub publishedAt day drifted for '.$item['slug'],
            );
        }
    }
}
