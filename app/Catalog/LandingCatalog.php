<?php

namespace App\Catalog;

use App\Playbooks\PlaybookRepository;
use App\Support\ToolsNav;

final class LandingCatalog
{
    /** Max tool cards on the home page (6th card is “View all tools”). */
    public const TOOLS_PREVIEW_LIMIT = 5;

    /** Max story cards on the home page (next card is “View all stories”). */
    public const STORIES_PREVIEW_LIMIT = 5;

    public function __construct(
        private readonly PlaybookRepository $playbooks,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function latestTools(): array
    {
        return collect(ToolsNav::withRegisteredRoutes(config('tools.nav', [])))
            ->reverse()
            ->take(self::TOOLS_PREVIEW_LIMIT)
            ->values()
            ->all();
    }

    public function toolCount(): int
    {
        return count(ToolsNav::withRegisteredRoutes(config('tools.nav', [])));
    }

    /**
     * Landing preview: standalone stories plus the first part of each series only.
     *
     * @return list<array<string, mixed>>
     */
    public function latestStories(): array
    {
        return collect($this->playbooks->allForIndexCatalog())
            ->take(self::STORIES_PREVIEW_LIMIT)
            ->values()
            ->all();
    }

    /** Total stories in the full overview (all series parts included). */
    public function storyCount(): int
    {
        return count($this->playbooks->allForIndex());
    }
}
