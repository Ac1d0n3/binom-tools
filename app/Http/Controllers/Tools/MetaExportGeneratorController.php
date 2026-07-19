<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MetaExportGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.meta-export-generator.show');
    }
}
