<?php

namespace App\Http\Controllers\Tools;

use App\Catalog\LandingCatalog;
use App\Http\Controllers\Controller;
use App\Playbooks\PlaybookStatsStore;
use Illuminate\View\View;

class ToolsLandingController extends Controller
{
    public function __construct(
        private readonly LandingCatalog $catalog,
        private readonly PlaybookStatsStore $stats,
    ) {}

    public function index(): View
    {
        return view('tools.landing', [
            'ecosystemItems' => config('tools.ecosystem', []),
            'links' => config('tools.links', []),
            'heroPills' => config('tools.hero_pills', []),
            'featuredAiTools' => $this->catalog->featuredAiTools(),
            'latestTools' => $this->catalog->latestTools(),
            'toolCount' => $this->catalog->toolCount(),
            'latestStories' => $this->stats->attachToItems($this->catalog->latestStories()),
            'storyCount' => $this->catalog->storyCount(),
        ]);
    }
}
