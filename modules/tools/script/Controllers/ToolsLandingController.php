<?php

namespace App\Http\Controllers\Tools;

use App\Catalog\LandingCatalog;
use App\Http\Controllers\Controller;
use App\Playbooks\Contracts\PlaybookStatsStoreInterface;
use Illuminate\View\View;

class ToolsLandingController extends Controller
{
    public function __construct(
        private readonly LandingCatalog $catalog,
        private readonly PlaybookStatsStoreInterface $stats,
    ) {}

    public function index(): View
    {
        $landingCards = [];
        foreach ($this->catalog->latestLandingCards() as $card) {
            if (($card['type'] ?? '') === 'story' && isset($card['item']) && is_array($card['item'])) {
                $withStats = $this->stats->attachToItems([$card['item']]);
                $card['item'] = $withStats[0] ?? $card['item'];
            }
            $landingCards[] = $card;
        }

        $allCatalogForRanking = $this->stats->attachToItems($this->catalog->allForIndexCatalog());

        return view('tools::landing', [
            'ecosystemItems' => config('tools.ecosystem', []),
            'links' => config('tools.links', []),
            'metaKeywords' => config('tools.meta_keywords', []),
            'featuredAiTools' => $this->catalog->featuredAiTools(),
            'featuredBiFormulaTools' => $this->catalog->featuredBiFormulaTools(),
            'latestTools' => $this->catalog->latestTools(),
            'toolCount' => $this->catalog->toolCount(),
            'latestLandingCards' => $landingCards,
            'topStories' => $this->catalog->topStories($allCatalogForRanking, 3),
            'storyCount' => $this->catalog->storyCount(),
            'hubCounts' => $this->catalog->hubCounts(),
            'hubStats' => $this->catalog->hubStats(),
            'landingQuote' => $this->catalog->landingQuote(),
            'radarUpdatedBadge' => $this->catalog->radarUpdatedBadge(),
        ]);
    }
}
