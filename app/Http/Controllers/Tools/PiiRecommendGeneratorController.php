<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PiiRecommendGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.pii-recommend-generator.show');
    }
}
