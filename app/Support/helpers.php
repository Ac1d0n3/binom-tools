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
        $base = app_base_path();
        $prefix = $base !== '' ? rtrim($base, '/') : '';

        return $prefix.'/prompt-studio/config';
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
