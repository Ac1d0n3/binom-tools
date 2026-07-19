<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class KpiDefinitionController extends Controller
{
    public function show(): View
    {
        return view('tools.kpi-definition.show');
    }
}
