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
            modifiedAt: $base,
            variants: [],
            publishedAt: null,
            seriesId: 'missing-pieces',
            seriesPart: 1,
        );

        $laterPart = new Playbook(
            slug: 'missing-pieces-data-lifecycle-retirement',
            heroUrl: null,
            order: -1,
            modifiedAt: $base,
            variants: [],
            publishedAt: null,
            seriesId: 'missing-pieces',
            seriesPart: 6,
        );

        $this->assertSame($base->getTimestamp() + 1, $playbook->indexSortTimestamp());
        $this->assertSame($base->getTimestamp() + 6, $laterPart->indexSortTimestamp());
        $this->assertGreaterThan($playbook->indexSortTimestamp(), $laterPart->indexSortTimestamp());
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
}
