<?php

namespace App\Catalog;

use App\Playbooks\PlaybookRepository;
use App\Support\ToolsNav;

final class LandingCatalog
{
    public const PREVIEW_LIMIT = 9;

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
            ->take(self::PREVIEW_LIMIT)
            ->values()
            ->all();
    }

    public function toolCount(): int
    {
        return count(ToolsNav::withRegisteredRoutes(config('tools.nav', [])));
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function latestStories(): array
    {
        return collect($this->playbooks->allForIndex())
            ->sortByDesc(fn (array $item): int => $item['modifiedAt']->getTimestamp())
            ->take(self::PREVIEW_LIMIT)
            ->values()
            ->all();
    }

    public function storyCount(): int
    {
        return count($this->playbooks->allForIndex());
    }
}
