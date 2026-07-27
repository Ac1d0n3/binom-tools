<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DisclaimerController extends Controller
{
    public function show(): View
    {
        return view('legal::disclaimer');
    }
}
