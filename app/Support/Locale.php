<?php

namespace App\Support;

final class Locale
{
    public const DEFAULT = 'en';

    /** @var list<string> */
    public const SUPPORTED = ['de', 'en'];

    public static function normalize(?string $locale): string
    {
        return in_array($locale, self::SUPPORTED, true) ? $locale : self::DEFAULT;
    }

    public static function current(): string
    {
        if (app()->bound('currentLocale')) {
            return self::normalize(app('currentLocale'));
        }

        return self::DEFAULT;
    }

    public static function setCurrent(?string $locale): string
    {
        $normalized = self::normalize($locale);
        app()->instance('currentLocale', $normalized);
        app()->setLocale($normalized);

        return $normalized;
    }

    public static function routeBaseName(?string $name): ?string
    {
        if ($name === null) {
            return null;
        }

        return str_starts_with($name, 'localized.')
            ? substr($name, strlen('localized.'))
            : $name;
    }

    public static function routeIs(string $baseName): bool
    {
        return self::routeBaseName(request()->route()?->getName()) === $baseName;
    }
}
