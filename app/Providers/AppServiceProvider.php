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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
