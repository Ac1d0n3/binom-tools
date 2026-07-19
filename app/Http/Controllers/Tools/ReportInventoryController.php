<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReportInventoryController extends Controller
{
    public function show(): View
    {
        return view('tools.report-inventory.show');
    }
}
