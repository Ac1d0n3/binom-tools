<?php

namespace App\Http\Middleware;

use App\Accounts\AccountAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts Admin Hub routes to users with manage-users or manage-teams flags.
 */
final class EnsureAdminHubAccess
{
    public function __construct(
        private readonly AccountAuth $auth,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $this->auth->user();
        if ($user === null) {
            abort(401);
        }

        if (! $user->canManageUsers && ! $user->canManageTeams) {
            if ($request->expectsJson()) {
                abort(403);
            }

            return redirect()->to(locale_route('profile.index'));
        }

        return $next($request);
    }
}
