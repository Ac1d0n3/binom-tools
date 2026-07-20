<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FabricDqPatternGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.fabric-dq-pattern-generator.show');
    }
}
