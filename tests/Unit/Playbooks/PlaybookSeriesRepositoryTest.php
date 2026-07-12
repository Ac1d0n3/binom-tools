<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookRepository;
use Tests\TestCase;

class PlaybookSeriesRepositoryTest extends TestCase
{
    public function test_all_series_groups_playbooks_and_sums_reading_time(): void
    {
        $repository = app(PlaybookRepository::class);
        $seriesList = $repository->allSeries();

        $this->assertNotEmpty($seriesList);

        $governance = collect($seriesList)->firstWhere('id', 'governance-pillars');

        $this->assertNotNull($governance);
        $this->assertSame('The 8 Pillars of Data Governance', $governance->titleEn);
        $this->assertSame('Die 8 Säulen der Data Governance', $governance->titleDe);
        $this->assertSame(3, $governance->partCount());
        $this->assertNotNull($governance->heroUrl);
        $this->assertStringContainsString('eight-pillar-hero', $governance->heroUrl);
        $this->assertGreaterThan(0, $governance->totalReadingTimeEn);
        $this->assertGreaterThan(0, $governance->totalReadingTimeDe);
        $this->assertSame('eight-pillars', $governance->parts[0]->slug);
        $this->assertSame(1, $governance->parts[0]->part);
        $this->assertSame('metadata-catalog-lineage', $governance->parts[2]->slug);
        $this->assertSame(3, $governance->parts[2]->part);
    }

    public function test_find_attaches_series_navigation_for_member(): void
    {
        $repository = app(PlaybookRepository::class);
        $playbook = $repository->find('metadata-catalog-lineage');

        $this->assertNotNull($playbook);
        $this->assertSame('governance-pillars', $playbook->seriesId);
        $this->assertSame(3, $playbook->seriesPart);
        $this->assertNotNull($playbook->series);
        $this->assertSame(3, $playbook->series->currentPart);
        $this->assertSame(3, $playbook->series->totalParts());

        $currentParts = array_filter(
            $playbook->series->parts,
            fn ($part) => $part->isCurrent,
        );

        $this->assertCount(1, $currentParts);
    }

    public function test_story_index_orders_series_parts_when_order_matches(): void
    {
        $repository = app(PlaybookRepository::class);
        $slugs = array_column($repository->allForIndex(), 'slug');

        $eightPos = array_search('eight-pillars', $slugs, true);
        $ownershipPos = array_search('data-ownership-stewardship', $slugs, true);
        $metadataPos = array_search('metadata-catalog-lineage', $slugs, true);

        $this->assertNotFalse($eightPos);
        $this->assertNotFalse($ownershipPos);
        $this->assertNotFalse($metadataPos);
        $this->assertLessThan($ownershipPos, $eightPos);
        $this->assertLessThan($metadataPos, $ownershipPos);
    }
}
