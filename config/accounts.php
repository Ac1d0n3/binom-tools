<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Optional file-based accounts (no database)
    |--------------------------------------------------------------------------
    |
    | When disabled, Sprint Planner and story read-state stay browser-local.
    | When enabled, users/teams/plans/read-state live under accounts.path as JSON.
    | Passwords are stored only as password_hash() digests — never plaintext.
    |
    | Prefer SESSION_DRIVER=file so login works without a DB session table.
    |
    */
    'enabled' => filter_var(env('BINOM_TOOLS_ACCOUNTS_ENABLED', false), FILTER_VALIDATE_BOOLEAN),

    'path' => env('BINOM_TOOLS_ACCOUNTS_PATH', storage_path('app/bn-tools')),
];
