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
        $catalogStories = $this->stats->attachToItems($this->catalog->latestStories());
        $allCatalogForRanking = $this->stats->attachToItems($this->catalog->allForIndexCatalog());

        return view('tools.landing', [
            'ecosystemItems' => config('tools.ecosystem', []),
            'links' => config('tools.links', []),
            'metaKeywords' => config('tools.meta_keywords', []),
            'featuredAiTools' => $this->catalog->featuredAiTools(),
            'latestTools' => $this->catalog->latestTools(),
            'toolCount' => $this->catalog->toolCount(),
            'latestStories' => $catalogStories,
            'topStories' => $this->catalog->topStories($allCatalogForRanking, 3),
            'storyCount' => $this->catalog->storyCount(),
            'hubCounts' => $this->catalog->hubCounts(),
            'landingQuote' => $this->catalog->landingQuote(),
            'radarUpdatedBadge' => $this->catalog->radarUpdatedBadge(),
        ]);
    }
}
