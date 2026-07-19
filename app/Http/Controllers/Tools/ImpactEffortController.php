<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ImpactEffortController extends Controller
{
    public function show(): View
    {
        return view('tools.impact-effort.show');
    }
}
