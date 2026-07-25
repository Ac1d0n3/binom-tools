<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Optional accounts
    |--------------------------------------------------------------------------
    |
    | When disabled, Sprint Planner and story read-state stay browser-local.
    | When enabled, users/teams/plans/read-state use BINOM_TOOLS_STORAGE_DRIVER
    | (file JSON under accounts.path, or mysql bn_* tables).
    | Passwords are stored only as password_hash() digests — never plaintext.
    |
    | Prefer SESSION_DRIVER=file when using the file storage driver.
    |
    */
    'enabled' => filter_var(env('BINOM_TOOLS_ACCOUNTS_ENABLED', false), FILTER_VALIDATE_BOOLEAN),

    'path' => env('BINOM_TOOLS_ACCOUNTS_PATH', storage_path('app/bn-tools')),

    /*
    |--------------------------------------------------------------------------
    | Self-registration (optional)
    |--------------------------------------------------------------------------
    |
    | When true (and accounts.enabled), visitors can register at /register.
    | New accounts are inactive with pendingApproval until an admin approves.
    | Works with both file and mysql storage drivers.
    |
    */
    'registration_enabled' => filter_var(
        env('BINOM_TOOLS_REGISTRATION_ENABLED', false),
        FILTER_VALIDATE_BOOLEAN,
    ),

    /*
    |--------------------------------------------------------------------------
    | Profile self-service avatar
    |--------------------------------------------------------------------------
    |
    | When true, signed-in users can edit shortName, colorToken, and avatarIcon
    | on /account. Admins can always edit these via user management.
    |
    */
    'profile_avatar_enabled' => filter_var(
        env('BINOM_TOOLS_ACCOUNTS_PROFILE_AVATAR_ENABLED', true),
        FILTER_VALIDATE_BOOLEAN,
    ),
];
