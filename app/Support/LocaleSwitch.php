<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

final class LocaleSwitch
{
    /**
     * @return array{de: string, en: string}
     */
    public static function urls(): array
    {
        $route = request()->route();
        $baseName = Locale::routeBaseName($route?->getName());

        if (is_string($baseName) && Route::has($baseName)) {
            $params = collect($route->parameters())->except('locale')->all();

            return [
                'de' => locale_route($baseName, $params, 'de'),
                'en' => locale_route($baseName, $params, 'en'),
            ];
        }

        $requestPath = '/'.ltrim(request()->getPathInfo(), '/');

        return [
            'de' => url(LocaleUrl::path($requestPath, 'de')),
            'en' => url(LocaleUrl::path($requestPath, 'en')),
        ];
    }
}
