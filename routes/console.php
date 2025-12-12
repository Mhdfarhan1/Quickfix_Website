<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ambil instance Schedule
$schedule = app(Schedule::class);

// Example schedule
$schedule->command('db:backup')->hourly();

// Payout polling (jika pakai)
$schedule->command('payouts:poll')->everyFiveMinutes()->withoutOverlapping();

// Payout release (INI YANG BENAR)
$schedule->command('payout:release')->everyFiveMinutes()->withoutOverlapping();
