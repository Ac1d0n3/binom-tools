<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookRepository;
use App\Playbooks\PlaybookSeriesOverview;
use Tests\TestCase;

class PlaybookRepositorySidebarTest extends TestCase
{
    public function test_latest_for_index_returns_at_most_ten_stories(): void
    {
        $repository = app(PlaybookRepository::class);
        $latest = $repository->latestForIndex();

        $this->assertLessThanOrEqual(PlaybookRepository::SIDEBAR_INDEX_LIMIT, count($latest));
        $this->assertGreaterThan(0, count($latest));
    }

    public function test_latest_for_index_includes_current_story_when_not_in_top_ten(): void
    {
        $repository = app(PlaybookRepository::class);
        $all = $repository->allForIndex();

        if (count($all) <= PlaybookRepository::SIDEBAR_INDEX_LIMIT) {
            $this->markTestSkipped('Not enough stories to test ensureSlug behavior.');
        }

        $oldest = $all[count($all) - 1];
        $latest = $repository->latestForIndex(PlaybookRepository::SIDEBAR_INDEX_LIMIT, $oldest['slug']);

        $this->assertLessThanOrEqual(PlaybookRepository::SIDEBAR_INDEX_LIMIT, count($latest));
        $this->assertTrue(
            collect($latest)->contains(static fn (array $item): bool => $item['slug'] === $oldest['slug']),
        );
    }

    public function test_latest_catalog_cards_collapse_series_to_one_entry(): void
    {
        $repository = app(PlaybookRepository::class);
        $cards = $repository->latestCatalogCards();

        $this->assertLessThanOrEqual(PlaybookRepository::SIDEBAR_INDEX_LIMIT, count($cards));

        $seriesIds = [];
        foreach ($cards as $card) {
            $this->assertArrayHasKey('type', $card);
            if ($card['type'] === 'series') {
                $this->assertInstanceOf(PlaybookSeriesOverview::class, $card['series']);
                $seriesIds[] = $card['series']->id;
            } else {
                $this->assertSame('story', $card['type']);
                $this->assertTrue(! is_string($card['item']['seriesId'] ?? null) || $card['item']['seriesId'] === '');
            }
        }

        $this->assertSame($seriesIds, array_values(array_unique($seriesIds)));
        $this->assertContains('metadata-deep-dive', $seriesIds);
    }

    public function test_latest_catalog_cards_ensures_series_for_current_part_slug(): void
    {
        $repository = app(PlaybookRepository::class);
        $cards = $repository->latestCatalogCards(
            PlaybookRepository::SIDEBAR_INDEX_LIMIT,
            'eight-pillars',
        );

        $this->assertTrue(
            collect($cards)->contains(
                static fn (array $card): bool => ($card['type'] ?? '') === 'series'
                    && ($card['series']->id ?? null) === 'governance-pillars',
            ),
        );
    }
}
