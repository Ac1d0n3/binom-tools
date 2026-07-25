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
    | Markup stories and repo sprint templates always stay as files.
    |
    */
    'driver' => strtolower((string) env('BINOM_TOOLS_STORAGE_DRIVER', 'file')),
];
