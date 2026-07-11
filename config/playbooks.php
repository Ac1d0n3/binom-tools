<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Playbook content directory
    |--------------------------------------------------------------------------
    |
    | Path to Markdown story files (.de.md / .en.md). Point this at a private
    | checkout or symlink when platform code and governance content live in
    | separate repositories.
    |
    */
    'content_path' => env('BINOM_TOOLS_CONTENT_PATH', base_path('content')),
];
