<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ToolsHomeController extends Controller
{
    public function index(): View
    {
        return view('tools.home', [
            'navItems' => config('tools.nav', []),
            'ecosystemItems' => config('tools.ecosystem', []),
            'links' => config('tools.links', []),
            'heroPills' => config('tools.hero_pills', []),
        ]);
    }
}
