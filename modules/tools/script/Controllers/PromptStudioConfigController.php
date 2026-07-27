<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PromptStudioConfigController extends Controller
{
    public function show(string $file): BinaryFileResponse|Response
    {
        $root = realpath(public_path('prompt-studio/config'));
        if ($root === false) {
            abort(404);
        }

        $candidate = realpath($root.DIRECTORY_SEPARATOR.$file);
        if ($candidate === false || ! str_starts_with($candidate, $root.DIRECTORY_SEPARATOR)) {
            abort(404);
        }

        if (! is_file($candidate)) {
            abort(404);
        }

        return response()->file($candidate, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
