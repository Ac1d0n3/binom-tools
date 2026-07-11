<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Support\ToolsNav;
use Illuminate\View\View;

class ToolsOverviewController extends Controller
{
    public function index(): View
    {
        return view('tools.overview', [
            'navItems' => ToolsNav::withRegisteredRoutes(config('tools.nav', [])),
            'workflows' => ToolsNav::workflowsWithRegisteredRoutes(config('tools.workflows', [])),
        ]);
    }
}
