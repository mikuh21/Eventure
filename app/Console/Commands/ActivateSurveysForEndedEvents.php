<?php

namespace App\Console\Commands;

use App\Services\SurveyActivationService;
use Illuminate\Console\Command;

class ActivateSurveysForEndedEvents extends Command
{
    protected $signature = 'survey:activate-ended-events';

    protected $description = 'Activate surveys for ended events and notify participants.';

    public function handle(SurveyActivationService $surveyActivationService): int
    {
        $events = $surveyActivationService->activateDueEvents();

        $this->info('Activated surveys for '.$events->count().' event(s).');

        return self::SUCCESS;
    }
}
