<?php

namespace App\Http\Middleware;

use App\Support\ToolsNav;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abort 404 when a tool route is disabled via TOOL_{ID}_ENABLED=false.
 */
final class EnsureToolEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        $name = $request->route()?->getName() ?? '';
        $name = str_replace('localized.', '', $name);

        if ($name === 'prompt-studio.config') {
            abort_unless(ToolsNav::isToolEnabled('prompt-studio'), 404);

            return $next($request);
        }

        if (! str_starts_with($name, 'tools.')) {
            return $next($request);
        }

        $toolId = substr($name, strlen('tools.'));

        if (in_array($toolId, ['overview', 'landing'], true)) {
            return $next($request);
        }

        abort_unless(ToolsNav::isToolEnabled($toolId), 404);

        return $next($request);
    }
}
