<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Runtime storage driver
    |--------------------------------------------------------------------------
    |
    | file (default): JSON under storage/app — no database required.
    | mysql: Eloquent tables bn_* — requires DB_* and php artisan migrate.
    |
    | Markup stories, repo sprint templates, and content/catalogs JSON always stay as files
    | (source of truth). Optional bn-tools:catalog-sync can mirror catalogs into MySQL as cache.
    |
    */
    'driver' => strtolower((string) env('BINOM_TOOLS_STORAGE_DRIVER', 'file')),
];
