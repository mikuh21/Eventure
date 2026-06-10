<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('survey:activate-ended-events')->hourly();
// Auto-open evaluation forms at 23:00 Manila time (11:00 PM) if admin forgot to open manually
Schedule::command('evaluation-forms:activate-ended-events')
    ->dailyAt('23:00')
    ->timezone('Asia/Manila');
// Evaluation forms are closed manually by admin only - auto-disable removed
