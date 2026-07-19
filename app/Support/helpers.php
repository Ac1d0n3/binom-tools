<?php

use App\Support\AppBase;
use App\Support\Locale;
use App\Support\LocaleUrl;

if (! function_exists('app_base_path')) {
    function app_base_path(): string
    {
        return AppBase::path();
    }
}

if (! function_exists('prompt_studio_config_path')) {
    /**
     * Same-origin path to Prompt Studio JSON config (works with subfolder deploys).
     */
    function prompt_studio_config_path(): string
    {
        $manifest = route('prompt-studio.config', ['file' => 'manifest.json'], false);

        return dirname($manifest);
    }
}

if (! function_exists('current_locale')) {
    function current_locale(): string
    {
        return Locale::current();
    }
}

if (! function_exists('playbook_category_key')) {
    /**
     * Stable filter key for a playbook category (English label preferred).
     */
    function playbook_category_key(?string $categoryEn, ?string $categoryDe): ?string
    {
        $label = filled($categoryEn) ? $categoryEn : $categoryDe;

        if (! is_string($label) || trim($label) === '') {
            return null;
        }

        $key = \Illuminate\Support\Str::slug($label);

        return $key !== '' ? $key : null;
    }
}

if (! function_exists('locale_route')) {
    /**
     * @param  array<string, mixed>|string|int|null  $parameters
     */
    function locale_route(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        return LocaleUrl::route($name, $parameters, $locale);
    }
}

if (! function_exists('tools_version')) {
    function tools_version(): ?string
    {
        $version = config('tools.version');

        return is_string($version) && trim($version) !== '' ? trim($version) : null;
    }
}

if (! function_exists('tools_is_beta')) {
    function tools_is_beta(): bool
    {
        return (bool) config('tools.beta', false);
    }
}

if (! function_exists('accounts_enabled')) {
    function accounts_enabled(): bool
    {
        return (bool) config('accounts.enabled', false);
    }
}

if (! function_exists('tools_release_label')) {
    function tools_release_label(): ?string
    {
        $version = tools_version();

        if ($version === null) {
            return null;
        }

        return str_starts_with($version, 'v') ? $version : 'v'.$version;
    }
}
