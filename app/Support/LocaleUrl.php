<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

final class LocaleUrl
{
    private const LOCALIZED_PREFIX = 'localized.';

    /**
     * @param  array<string, mixed>|string|int|null  $parameters
     */
    public static function route(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        $locale = Locale::normalize($locale ?? Locale::current());

        if ($locale === Locale::DEFAULT) {
            return route($name, $parameters);
        }

        $localizedName = self::LOCALIZED_PREFIX.$name;

        if (! Route::has($localizedName)) {
            return route($name, $parameters);
        }

        $params = is_array($parameters) ? $parameters : ['slug' => $parameters];

        return route($localizedName, ['locale' => $locale, ...$params]);
    }
}
