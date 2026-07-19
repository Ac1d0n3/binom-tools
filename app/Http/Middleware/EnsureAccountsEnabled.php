<?php

namespace App\Http\Middleware;

use App\Accounts\AccountsConfig;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureAccountsEnabled
{
    public function __construct(private readonly AccountsConfig $config) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->config->enabled()) {
            abort(404);
        }

        return $next($request);
    }
}
