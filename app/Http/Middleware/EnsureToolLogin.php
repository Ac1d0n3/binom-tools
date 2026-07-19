<?php

namespace App\Http\Middleware;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use App\Support\ToolsNav;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect to login when a tool requires auth and accounts mode is enabled.
 */
final class EnsureToolLogin
{
    public function __construct(
        private readonly AccountsConfig $accounts,
        private readonly AccountAuth $auth,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->accounts->enabled()) {
            return $next($request);
        }

        $name = $request->route()?->getName() ?? '';
        $name = str_replace('localized.', '', $name);

        if ($name === 'prompt-studio.config') {
            if (ToolsNav::isToolLoginRequired('prompt-studio') && ! $this->auth->check()) {
                return $this->deny($request);
            }

            return $next($request);
        }

        if (! str_starts_with($name, 'tools.')) {
            return $next($request);
        }

        $toolId = substr($name, strlen('tools.'));

        if (in_array($toolId, ['overview', 'landing'], true)) {
            return $next($request);
        }

        if (! ToolsNav::isToolLoginRequired($toolId)) {
            return $next($request);
        }

        if ($this->auth->check()) {
            return $next($request);
        }

        return $this->deny($request);
    }

    private function deny(Request $request): Response
    {
        if ($request->expectsJson()) {
            abort(401);
        }

        return redirect()->guest(locale_route('accounts.login'));
    }
}
