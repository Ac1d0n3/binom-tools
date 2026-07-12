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

if (! function_exists('current_locale')) {
    function current_locale(): string
    {
        return Locale::current();
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
