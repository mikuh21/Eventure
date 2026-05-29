<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('survey:activate-ended-events')->hourly();
Schedule::command('evaluation-forms:activate-ended-events')->dailyAt('23:00');
Schedule::command('evaluation-forms:disable-ended-events')->dailyAt('00:00');
