<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('survey:activate-ended-events')->hourly();
// Activate evaluation forms at 22:00 Manila time (10:00 PM) for production/testing
Schedule::command('evaluation-forms:activate-ended-events')
    ->dailyAt('22:00')
    ->timezone('Asia/Manila');
// Disable evaluation forms shortly after midnight Manila time to keep forms open until 00:05
Schedule::command('evaluation-forms:disable-ended-events')
    ->dailyAt('00:05')
    ->timezone('Asia/Manila');
