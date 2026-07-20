<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DatabricksDqPatternGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.databricks-dq-pattern-generator.show');
    }
}
