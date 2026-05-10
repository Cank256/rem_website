<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the YouTube live-status check.
// The interval is read from the live_streams table so it respects whatever
// the admin configured. The command itself is a no-op when auto_detect is
// disabled, so it is safe to always register this schedule.
Schedule::command('youtube:check-live')
    ->everyMinute()
    ->when(function () {
        // Only run on ticks that align with the configured interval.
        $interval = \App\Models\LiveStream::value('check_interval_minutes') ?? 5;
        return (int) now()->format('i') % $interval === 0;
    })
    ->withoutOverlapping()
    ->name('youtube-live-check');
