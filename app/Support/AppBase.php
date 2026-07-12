<?php

namespace App\Support;

final class AppBase
{
    public static function path(): string
    {
        if (! app()->runningInConsole() && app()->bound('request')) {
            $basePath = request()->getBasePath();

            if ($basePath !== '') {
                return $basePath;
            }
        }

        $path = parse_url((string) config('app.url'), PHP_URL_PATH) ?? '';

        return rtrim($path, '/') ?: '';
    }
}
