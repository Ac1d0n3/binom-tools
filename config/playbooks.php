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

    /*
    |--------------------------------------------------------------------------
    | Series pager behaviour on playbook detail pages
    |--------------------------------------------------------------------------
    |
    | Controls prev/next navigation when a story belongs to a series:
    | - both: global order pager plus series navigation block
    | - series: prev/next only within the same series
    | - global: prev/next by sidebar sort order (ignores series for pager)
    |
    */
    'series_pager' => env('PLAYBOOKS_SERIES_PAGER', 'both'),

    /*
    |--------------------------------------------------------------------------
    | Story overview page header ( /playbooks )
    |--------------------------------------------------------------------------
    |
    | show_title / show_lead — display the h1 and intro paragraph on the index.
    | title_* / lead_* — optional overrides (otherwise locale.js strings apply).
    |
    */
    'overview' => [
        'show_title' => env('PLAYBOOKS_OVERVIEW_SHOW_TITLE', false),
        'show_lead' => env('PLAYBOOKS_OVERVIEW_SHOW_LEAD', false),
        'title_de' => env('PLAYBOOKS_OVERVIEW_TITLE_DE'),
        'title_en' => env('PLAYBOOKS_OVERVIEW_TITLE_EN'),
        'lead_de' => env('PLAYBOOKS_OVERVIEW_LEAD_DE'),
        'lead_en' => env('PLAYBOOKS_OVERVIEW_LEAD_EN'),
    ],
];
