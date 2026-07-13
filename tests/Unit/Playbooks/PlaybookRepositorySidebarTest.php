<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookRepository;
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
}
