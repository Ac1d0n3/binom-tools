<?php

namespace App\Support;

final class AppBase
{
    public static function path(): string
    {
        if (app()->bound('request')) {
            $basePath = request()->getBasePath();

            if ($basePath !== '') {
                return $basePath;
            }

            $configured = rtrim((string) (parse_url((string) config('app.url'), PHP_URL_PATH) ?? ''), '/');

            if ($configured !== '') {
                $uri = request()->getRequestUri();
                $pathOnly = str_contains($uri, '?') ? strstr($uri, '?', true) : $uri;

                if ($pathOnly === $configured || str_starts_with($pathOnly, $configured.'/')) {
                    return $configured;
                }
            }

            return '';
        }

        $path = parse_url((string) config('app.url'), PHP_URL_PATH) ?? '';

        return rtrim($path, '/') ?: '';
    }
}
