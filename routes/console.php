<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('bn-tools:backup-plans')->dailyAt('02:15');
Schedule::command('bn-tools:sync-governance-radar-feeds')->hourly();
Schedule::command('calendar:holidays-sync --seed')->weeklyOn(1, '03:30');
