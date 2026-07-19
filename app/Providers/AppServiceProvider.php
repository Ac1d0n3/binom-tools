<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Support/helpers.php');

        $this->app->singleton(\App\Playbooks\PlaybookStatsStore::class, static function (): \App\Playbooks\PlaybookStatsStore {
            return \App\Playbooks\PlaybookStatsStore::default();
        });

        $this->app->singleton(\App\SprintPlanner\BnToolsSeedStore::class, static function (): \App\SprintPlanner\BnToolsSeedStore {
            return \App\SprintPlanner\BnToolsSeedStore::default();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            app(\App\SprintPlanner\BnToolsSeedStore::class)->hydrateRuntimeFromSeeds();
        } catch (\Throwable) {
            // Ignore seed hydration failures (e.g. missing storage on first boot).
        }

        view()->composer('*', function ($view): void {
            $config = app(\App\Accounts\AccountsConfig::class);
            if (! $config->enabled()) {
                $view->with([
                    'accountsEnabled' => false,
                    'accountUser' => null,
                    'accountsReadSlugs' => [],
                    'accountsReadUrlTemplate' => null,
                ]);

                return;
            }

            $auth = app(\App\Accounts\AccountAuth::class);
            $user = $auth->user();
            $readSlugs = [];
            if ($user !== null) {
                $readSlugs = array_keys(app(\App\Accounts\ReadStateStore::class)->forUser($user->id));
            }

            $view->with([
                'accountsEnabled' => true,
                'accountUser' => $user?->toPublicArray(),
                'accountsReadSlugs' => $readSlugs,
                'accountsReadUrlTemplate' => $user !== null
                    ? str_replace('__SLUG__', '__SLUG__', locale_route('accounts.playbooks.read', ['slug' => '__SLUG__']))
                    : null,
            ]);
        });
    }
}
