<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FabricPiiGovernancePatternGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.fabric-pii-governance-pattern-generator.show');
    }
}
