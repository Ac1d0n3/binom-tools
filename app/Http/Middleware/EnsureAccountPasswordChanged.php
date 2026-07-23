<?php

namespace App\Http\Middleware;

use App\Accounts\AccountAuth;
use App\Accounts\AccountsConfig;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Forces invited users to change their temporary password before using the app.
 */
final class EnsureAccountPasswordChanged
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

        $user = $this->auth->user();
        if ($user === null || ! $user->mustChangePassword) {
            return $next($request);
        }

        if ($this->isAllowedWhileForced($request)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(403, 'Password change required.');
        }

        return redirect()
            ->to(locale_route('accounts.profile'))
            ->with('status', 'must-change-password');
    }

    private function isAllowedWhileForced(Request $request): bool
    {
        if ($request->routeIs('accounts.profile', 'accounts.profile.update', 'accounts.logout')) {
            return true;
        }

        // Localized route names: de.accounts.profile / accounts.profile
        $name = (string) $request->route()?->getName();
        foreach (['accounts.profile', 'accounts.profile.update', 'accounts.logout'] as $suffix) {
            if ($name === $suffix || str_ends_with($name, '.'.$suffix)) {
                return true;
            }
        }

        return false;
    }
}
