<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * HTML must never be cached (Safari bfcache / 304 with stale body).
 * Otherwise @vite keeps serving old hashed CSS/JS URLs after deploy and the
 * page looks "unstyled" until someone clears the browser cache — unacceptable.
 *
 * Fingerprinted assets under /build stay long-cache + immutable via .htaccess.
 */
class PreventHtmlCaching
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethodCacheable()) {
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');
        if (! str_contains($contentType, 'text/html')) {
            return $response;
        }

        // no-store: do not keep a local copy (blocks Safari bfcache of old asset URLs).
        $response->headers->set(
            'Cache-Control',
            'no-store, no-cache, max-age=0, must-revalidate, private',
        );
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        // Stale 304 + ETag is how Safari keeps serving yesterday's HTML.
        $response->headers->remove('ETag');
        $response->headers->remove('Last-Modified');

        return $response;
    }
}
