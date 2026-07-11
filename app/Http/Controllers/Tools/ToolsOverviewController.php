<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ToolsOverviewController extends Controller
{
    public function index(): View
    {
        return view('tools.overview', [
            'navItems' => config('tools.nav', []),
            'workflows' => config('tools.workflows', []),
        ]);
    }
}
