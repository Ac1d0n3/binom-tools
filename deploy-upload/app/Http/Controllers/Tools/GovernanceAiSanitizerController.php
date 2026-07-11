<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GovernanceAiSanitizerController extends Controller
{
    public function show(): View
    {
        return view('tools.governance-ai-sanitizer.show');
    }
}
