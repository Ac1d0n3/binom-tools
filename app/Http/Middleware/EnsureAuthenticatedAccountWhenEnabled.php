<?php

namespace App\Http\Middleware;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Requires login only when accounts mode is enabled.
 */
final class EnsureAuthenticatedAccountWhenEnabled
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly AccountAuth $auth,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->config->enabled()) {
            return $next($request);
        }

        if (! $this->auth->check()) {
            if ($request->expectsJson()) {
                abort(401);
            }

            return redirect()->guest(locale_route('accounts.login'));
        }

        return $next($request);
    }
}
