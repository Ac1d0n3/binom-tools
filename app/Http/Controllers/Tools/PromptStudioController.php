<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PromptStudioController extends Controller
{
    public function show(): View
    {
        return view('tools.prompt-studio.show');
    }
}
