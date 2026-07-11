<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DbtDqHistoryGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.dbt-dq-history-generator.show');
    }
}
