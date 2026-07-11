<?php

namespace Tests\Unit\Catalog;

use App\Catalog\LandingCatalog;
use App\Playbooks\PlaybookRepository;
use Tests\TestCase;

class LandingCatalogTest extends TestCase
{
    public function test_latest_tools_returns_at_most_five_in_reverse_config_order(): void
    {
        $catalog = app(LandingCatalog::class);
        $latest = $catalog->latestTools();

        $this->assertLessThanOrEqual(LandingCatalog::TOOLS_PREVIEW_LIMIT, count($latest));
        $this->assertSame('governance-ai-sanitizer', $latest[0]['id'] ?? null);
    }

    public function test_latest_stories_sorts_by_modified_at_descending(): void
    {
        $catalog = app(LandingCatalog::class);
        $latest = $catalog->latestStories();

        $this->assertLessThanOrEqual(LandingCatalog::PREVIEW_LIMIT, count($latest));

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
}
