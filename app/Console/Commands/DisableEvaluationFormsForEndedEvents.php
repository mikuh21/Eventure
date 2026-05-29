<?php

namespace App\Console\Commands;

use App\Services\EvaluationFormDisableService;
use Illuminate\Console\Command;

class DisableEvaluationFormsForEndedEvents extends Command
{
    protected $signature = 'evaluation-forms:disable-ended-events';

    protected $description = 'Automatically disable evaluation forms for events that have ended.';

    public function handle(EvaluationFormDisableService $evaluationFormDisableService): int
    {
        $events = $evaluationFormDisableService->disableForEndedEvents();

        $this->info('Disabled evaluation forms for ' . $events->count() . ' event(s).');

        return self::SUCCESS;
    }
}
