<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes — Task Scheduling
|--------------------------------------------------------------------------
|
| Demonstrates Laravel task scheduling.
| Run: php artisan schedule:run  (manually)
|       or set up a cron: * * * * * php artisan schedule:run
|
*/

// Inspiring quote (default)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Scheduled Tasks ──────────────────────────────────────────────────────

// Send deadline reminder emails daily at 8:00 AM
Schedule::command('goals:send-reminders --days=3')
    ->dailyAt('08:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/scheduler.log'));

// Weekly summary (optional extension point)
Schedule::command('goals:send-reminders --days=7')
    ->weeklyOn(1, '09:00'); // Every Monday at 9 AM
