<?php

require_once dirname(__DIR__).'/app/Support/helpers.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: [
            \App\Http\Controllers\Playbooks\PlaybookStatsController::ENGAGEMENT_COOKIE,
        ]);
        $middleware->alias([
            'accounts.enabled' => \App\Http\Middleware\EnsureAccountsEnabled::class,
            'accounts.auth' => \App\Http\Middleware\EnsureAuthenticatedAccount::class,
            'accounts.auth.whenEnabled' => \App\Http\Middleware\EnsureAuthenticatedAccountWhenEnabled::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SetLocaleFromRoute::class,
            \App\Http\Middleware\EnsureToolEnabled::class,
            \App\Http\Middleware\EnsureToolLogin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
