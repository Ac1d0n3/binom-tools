<?php

namespace App\Support;

final class AppBase
{
    public static function path(): string
    {
        if (! app()->runningInConsole() && app()->bound('request')) {
            return request()->getBasePath();
        }

        $path = parse_url((string) config('app.url'), PHP_URL_PATH) ?? '';

        return rtrim($path, '/') ?: '';
    }
}
