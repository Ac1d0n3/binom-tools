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

        for ($index = 0; $index < count($seriesList) - 1; $index++) {
            $this->assertGreaterThanOrEqual(
                $seriesList[$index + 1]->modifiedAt,
                $seriesList[$index]->modifiedAt,
                sprintf(
                    'Series "%s" should sort before "%s" by modifiedAt descending.',
                    $seriesList[$index]->id,
                    $seriesList[$index + 1]->id,
                ),
            );
        }
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

    public function test_end_to_end_series_aggregates_products(): void
    {
        $repository = app(PlaybookRepository::class);
        $series = collect($repository->allSeries())
            ->first(fn ($overview): bool => $overview->id === 'end-to-end-data-governance');

        $this->assertNotNull($series);
        $this->assertSame(['snowflake', 'dbt', 'qlik'], $series->products);

        $story = collect($repository->allForIndex())
            ->first(fn (array $item): bool => ($item['slug'] ?? null) === 'end-to-end-governance-architecture');

        $this->assertNotNull($story);
        $this->assertSame(['snowflake', 'dbt', 'qlik'], $story['products']);
    }
}
