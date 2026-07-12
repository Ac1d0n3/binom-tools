<?php

namespace App\Http\Middleware;

use App\Support\Locale;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocaleFromRoute
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Locale::setCurrent($request->route('locale'));
        view()->share('currentLocale', $locale);

        return $next($request);
    }
}
