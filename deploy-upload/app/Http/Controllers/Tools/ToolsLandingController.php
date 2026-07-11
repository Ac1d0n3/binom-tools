<?php

namespace App\Http\Controllers\Tools;

use App\Catalog\LandingCatalog;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ToolsLandingController extends Controller
{
    public function __construct(
        private readonly LandingCatalog $catalog,
    ) {}

    public function index(): View
    {
        return view('tools.landing', [
            'ecosystemItems' => config('tools.ecosystem', []),
            'links' => config('tools.links', []),
            'heroPills' => config('tools.hero_pills', []),
            'latestTools' => $this->catalog->latestTools(),
            'toolCount' => $this->catalog->toolCount(),
            'latestStories' => $this->catalog->latestStories(),
            'storyCount' => $this->catalog->storyCount(),
        ]);
    }
}
