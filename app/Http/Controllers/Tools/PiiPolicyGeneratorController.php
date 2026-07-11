<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PiiPolicyGeneratorController extends Controller
{
    public function show(): View
    {
        return view('tools.pii-policy-generator.show');
    }
}
