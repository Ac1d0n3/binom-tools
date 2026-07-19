<?php

namespace App\Http\Middleware;

use App\Accounts\AccountAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAuthenticatedAccount
{
    public function __construct(private readonly AccountAuth $auth) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->auth->check()) {
            if ($request->expectsJson()) {
                abort(401);
            }

            return redirect()->guest(locale_route('accounts.login'));
        }

        return $next($request);
    }
}
