<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StakeholderMatrixController extends Controller
{
    public function show(): View
    {
        return view('tools.stakeholder-matrix.show');
    }
}
