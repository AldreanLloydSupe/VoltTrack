<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('bills:apply-overdue-penalties')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('deleted-records:prune --days=30')
    ->dailyAt('00:30')
    ->withoutOverlapping()
    ->runInBackground();
