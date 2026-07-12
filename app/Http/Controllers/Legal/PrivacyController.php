<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PrivacyController extends Controller
{
    public function show(): View
    {
        $locale = current_locale();
        $content = config("legal.privacy.{$locale}", config('legal.privacy.en', []));

        return view('legal.privacy', [
            'content' => is_array($content) ? $content : [],
        ]);
    }
}
