<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GovernanceStartingPointDecisionController extends Controller
{
    public function show(): View
    {
        return view('tools::governance-starting-point-decision.show');
    }
}
