<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function show(): View
    {
        return view('about.show', [
            'repositoryUrl' => config('tools.links.repository'),
        ]);
    }
}
