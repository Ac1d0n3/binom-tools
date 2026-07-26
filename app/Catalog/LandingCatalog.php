<?php

namespace App\Catalog;

use App\Governance\GovernanceRadarFeedItemStore;
use App\Playbooks\PlaybookRepository;
use App\Support\ToolsNav;
use Carbon\Carbon;

final class LandingCatalog
{
    /** Max tool cards on the home page (6th card is “View all tools”). */
    public const TOOLS_PREVIEW_LIMIT = 5;

    /** Max story cards on the home page (next card is “View all stories”). */
    public const STORIES_PREVIEW_LIMIT = 3;

    public function __construct(
        private readonly PlaybookRepository $playbooks,
        private readonly GovernanceRadarFeedItemStore $radarFeedItems,
    ) {}

    /**
     * AI tools always featured on the home page (independent of “latest”).
     *
     * @return list<array<string, mixed>>
     */
    public function featuredAiTools(): array
    {
        return ToolsNav::aiTools(ToolsNav::withRegisteredRoutes(config('tools.nav', [])));
    }

    /**
     * BI formula generators featured on the home page (Set Analysis, Tableau, DAX).
     *
     * @return list<array<string, mixed>>
     */
    public function featuredBiFormulaTools(): array
    {
        return ToolsNav::biFormulaTools(ToolsNav::withRegisteredRoutes(config('tools.nav', [])));
    }

    /**
     * Recent governance / setup tools for the Binom-Tools preview strip.
     *
     * @return list<array<string, mixed>>
     */
    public function latestTools(): array
    {
        return collect(ToolsNav::governancePreviewTools(ToolsNav::withRegisteredRoutes(config('tools.nav', []))))
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
     * Landing preview: standalone stories plus series teasers (not part-1 story cards).
     *
     * @return list<array{type: 'story', item: array<string, mixed>}|array{type: 'series', series: \App\Playbooks\PlaybookSeriesOverview}>
     */
    public function latestLandingCards(): array
    {
        return $this->playbooks->latestCatalogCards(self::STORIES_PREVIEW_LIMIT);
    }

    /**
     * Landing preview: standalone stories plus the first part of each series only.
     *
     * @return list<array<string, mixed>>
     */
    public function latestStories(): array
    {
        return collect($this->allForIndexCatalog())
            ->take(self::STORIES_PREVIEW_LIMIT)
            ->values()
            ->all();
    }

    /**
     * Full catalog index entries (standalone + first series parts), newest first.
     *
     * @return list<array<string, mixed>>
     */
    public function allForIndexCatalog(): array
    {
        return $this->playbooks->allForIndexCatalog();
    }

    /** Total stories in the full overview (all series parts included). */
    public function storyCount(): int
    {
        return count($this->playbooks->allForIndex());
    }

    /**
     * KPI counts for landing hub cards.
     *
     * @return array{
     *   stories: int,
     *   resources: int,
     *   suppliers: int,
     *   compliance: int,
     *   sprintPlanner: int,
     *   radar: int,
     *   tools: int
     * }
     */
    public function hubCounts(): array
    {
        return [
            'stories' => $this->storyCount(),
            'resources' => count(config('vendor-resources.products', [])),
            'suppliers' => count(config('suppliers.products', [])),
            'compliance' => count(config('compliance.items', [])),
            'sprintPlanner' => $this->sprintTemplateCount(),
            'radar' => count(config('governance-radar.sources', [])),
            'tools' => $this->toolCount(),
        ];
    }

    /**
     * Top stories by likes, then views (catalog entries: series first parts only).
     *
     * @param  list<array<string, mixed>>  $itemsWithStats
     * @return list<array<string, mixed>>
     */
    public function topStories(array $itemsWithStats, int $limit = 3): array
    {
        $ranked = $itemsWithStats;

        usort($ranked, static function (array $a, array $b): int {
            $likesA = max(0, (int) ($a['stats']['likes'] ?? 0));
            $likesB = max(0, (int) ($b['stats']['likes'] ?? 0));
            if ($likesA !== $likesB) {
                return $likesB <=> $likesA;
            }

            $viewsA = max(0, (int) ($a['stats']['views'] ?? 0));
            $viewsB = max(0, (int) ($b['stats']['views'] ?? 0));
            if ($viewsA !== $viewsB) {
                return $viewsB <=> $viewsA;
            }

            $dateA = $a['indexSortTimestamp'] ?? ($a['sortDate']?->getTimestamp() ?? 0);
            $dateB = $b['indexSortTimestamp'] ?? ($b['sortDate']?->getTimestamp() ?? 0);

            return (int) $dateB <=> (int) $dateA;
        });

        return array_values(array_slice($ranked, 0, max(0, $limit)));
    }

    private function sprintTemplateCount(): int
    {
        $path = config('sprint-planner.content_path');

        if (! is_string($path) || $path === '' || ! is_dir($path)) {
            return 0;
        }

        $files = glob(rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'*.en.md');

        return is_array($files) ? count($files) : 0;
    }

    /**
     * Bilingual date badge for the Radar hub card (last list update).
     *
     * @return array{de: string, en: string}|null
     */
    public function radarUpdatedBadge(): ?array
    {
        $raw = $this->radarFeedItems->latestListUpdatedAt();
        if ($raw === null || $raw === '') {
            return null;
        }

        try {
            $date = Carbon::parse($raw);
        } catch (\Throwable) {
            return null;
        }

        return [
            'en' => $date->format('j M Y H:i'),
            'de' => $date->format('d.m.Y H:i'),
        ];
    }

    /**
     * Pick a random landing quote filler from the configured pool.
     *
     * @return array{quote: array{de: string, en: string}, attribution: array{de: string, en: string}}|null
     */
    public function landingQuote(): ?array
    {
        $quotes = config('tools.landing_quotes', []);

        if (! is_array($quotes) || $quotes === []) {
            return null;
        }

        /** @var list<array{quote?: array{de?: string, en?: string}, attribution?: array{de?: string, en?: string}}> $quotes */
        $quotes = array_values(array_filter($quotes, 'is_array'));

        if ($quotes === []) {
            return null;
        }

        $quote = $quotes[array_rand($quotes)];

        if (! is_array($quote)) {
            return null;
        }

        $quoteText = $quote['quote'] ?? null;
        $attribution = $quote['attribution'] ?? null;

        if (! is_array($quoteText) || ! is_array($attribution)) {
            return null;
        }

        $quoteEn = trim((string) ($quoteText['en'] ?? ''));
        $quoteDe = trim((string) ($quoteText['de'] ?? $quoteEn));
        $attrEn = trim((string) ($attribution['en'] ?? ''));
        $attrDe = trim((string) ($attribution['de'] ?? $attrEn));

        if ($quoteEn === '' && $quoteDe === '') {
            return null;
        }

        return [
            'quote' => [
                'en' => $quoteEn !== '' ? $quoteEn : $quoteDe,
                'de' => $quoteDe !== '' ? $quoteDe : $quoteEn,
            ],
            'attribution' => [
                'en' => $attrEn,
                'de' => $attrDe !== '' ? $attrDe : $attrEn,
            ],
        ];
    }
}
