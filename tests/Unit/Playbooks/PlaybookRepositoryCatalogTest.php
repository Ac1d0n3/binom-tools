<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookRepository;
use Tests\TestCase;

class PlaybookRepositoryCatalogTest extends TestCase
{
    public function test_catalog_index_includes_only_first_part_per_series(): void
    {
        $repository = app(PlaybookRepository::class);
        $catalog = $repository->allForIndexCatalog();

        $missingParts = collect($catalog)
            ->filter(fn (array $item): bool => ($item['seriesId'] ?? null) === 'missing-pieces')
            ->pluck('seriesPart')
            ->all();

        $this->assertSame([1], $missingParts);

        $governanceParts = collect($catalog)
            ->filter(fn (array $item): bool => ($item['seriesId'] ?? null) === 'governance-pillars')
            ->pluck('seriesPart')
            ->all();

        $this->assertSame([1], $governanceParts);
    }

    public function test_catalog_index_sorts_by_sort_date_descending(): void
    {
        $repository = app(PlaybookRepository::class);
        $catalog = $repository->allForIndexCatalog();

        if (count($catalog) < 2) {
            $this->markTestSkipped('Not enough catalog entries to test sort order.');
        }

        $first = $catalog[0]['sortDate']->getTimestamp();
        $second = $catalog[1]['sortDate']->getTimestamp();
        $this->assertGreaterThanOrEqual($second, $first);
    }

    public function test_all_series_sorts_by_modified_at_descending(): void
    {
        $repository = app(PlaybookRepository::class);
        $seriesList = $repository->allSeries();

        if (count($seriesList) < 2) {
            $this->markTestSkipped('Not enough series to test sort order.');
        }

        $this->assertGreaterThanOrEqual($seriesList[1]->modifiedAt, $seriesList[0]->modifiedAt);
        $this->assertSame('missing-pieces', $seriesList[0]->id);
    }

    public function test_index_orders_series_parts_by_part_number_when_sort_dates_match(): void
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
