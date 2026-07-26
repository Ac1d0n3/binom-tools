<?php

namespace Tests\Unit\Catalog;

use App\Catalog\LandingCatalog;
use App\Playbooks\PlaybookRepository;
use Tests\TestCase;

class LandingCatalogTest extends TestCase
{
    public function test_latest_tools_returns_non_ai_preview_cards(): void
    {
        $catalog = app(LandingCatalog::class);
        $latest = $catalog->latestTools();
        $ai = $catalog->featuredAiTools();

        $this->assertLessThanOrEqual(LandingCatalog::TOOLS_PREVIEW_LIMIT, count($latest));
        $this->assertNotEmpty($ai);
        $this->assertContains('prompt-studio', array_column($ai, 'id'));
        $this->assertContains('governance-ai-sanitizer', array_column($ai, 'id'));

        foreach ($latest as $item) {
            $this->assertFalse(
                \App\Support\ToolsNav::isAiTool($item),
                'latestTools must not include AI tools (they have a dedicated section)',
            );
        }
    }

    public function test_latest_stories_preview_uses_catalog_entries_only(): void
    {
        $repository = app(PlaybookRepository::class);
        $catalog = app(LandingCatalog::class);
        $latest = $catalog->latestStories();

        $this->assertLessThanOrEqual(LandingCatalog::STORIES_PREVIEW_LIMIT, count($latest));

        $missingParts = collect($latest)
            ->filter(fn (array $item): bool => ($item['seriesId'] ?? null) === 'missing-pieces')
            ->pluck('seriesPart')
            ->all();

        $this->assertLessThanOrEqual(1, count($missingParts));
        if ($missingParts !== []) {
            $this->assertSame(1, $missingParts[0]);
        }
    }

    public function test_latest_stories_sorts_by_modified_at_descending(): void
    {
        $catalog = app(LandingCatalog::class);
        $latest = $catalog->latestStories();

        $this->assertLessThanOrEqual(LandingCatalog::STORIES_PREVIEW_LIMIT, count($latest));

        if (count($latest) > 1) {
            $first = $latest[0]['modifiedAt']->getTimestamp();
            $second = $latest[1]['modifiedAt']->getTimestamp();
            $this->assertGreaterThanOrEqual($second, $first);
        }
    }

    public function test_counts_match_full_catalog_sizes(): void
    {
        $catalog = app(LandingCatalog::class);

        $this->assertSame(count(config('tools.nav', [])), $catalog->toolCount());
        $this->assertSame(count(app(PlaybookRepository::class)->allForIndex()), $catalog->storyCount());
    }

    public function test_landing_quote_returns_configured_bilingual_entry(): void
    {
        $catalog = app(LandingCatalog::class);
        $quote = $catalog->landingQuote();

        $this->assertIsArray($quote);
        $this->assertArrayHasKey('quote', $quote);
        $this->assertArrayHasKey('attribution', $quote);
        $this->assertNotSame('', $quote['quote']['en']);
        $this->assertNotSame('', $quote['quote']['de']);
    }

    public function test_hub_counts_include_products_and_catalog_sizes(): void
    {
        $catalog = app(LandingCatalog::class);
        $counts = $catalog->hubCounts();

        $this->assertSame($catalog->storyCount(), $counts['stories']);
        $this->assertSame(count(config('governance-radar.sources', [])), $counts['radar']);
        $this->assertSame($catalog->toolCount(), $counts['tools']);
        $this->assertSame(count(config('suppliers.products', [])), $counts['suppliers']);
        $this->assertSame(count(config('vendor-resources.products', [])), $counts['resources']);
        $this->assertSame(count(config('compliance.items', [])), $counts['compliance']);
        $this->assertGreaterThan(0, $counts['sprintPlanner']);
    }

    public function test_top_stories_ranks_by_likes_then_views(): void
    {
        $catalog = app(LandingCatalog::class);
        $ranked = $catalog->topStories([
            [
                'slug' => 'a',
                'stats' => ['likes' => 1, 'views' => 100],
                'indexSortTimestamp' => 1,
            ],
            [
                'slug' => 'b',
                'stats' => ['likes' => 5, 'views' => 10],
                'indexSortTimestamp' => 2,
            ],
            [
                'slug' => 'c',
                'stats' => ['likes' => 5, 'views' => 50],
                'indexSortTimestamp' => 3,
            ],
        ], 2);

        $this->assertSame(['c', 'b'], array_column($ranked, 'slug'));
    }
}
