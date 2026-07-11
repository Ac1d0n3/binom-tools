<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DbtGovernanceMacroGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.dbt-governance-macro-generator.show');
    }
}
