<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DbtDqMacroGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.dbt-dq-macro-generator.show');
    }
}
