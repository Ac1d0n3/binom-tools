<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DatabricksPiiGovernancePatternGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.databricks-pii-governance-pattern-generator.show');
    }
}
