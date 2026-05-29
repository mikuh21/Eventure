<?php

namespace App\Console\Commands;

use App\Services\EvaluationFormActivationService;
use Illuminate\Console\Command;

class ActivateEvaluationFormsForEndedEvents extends Command
{
    protected $signature = 'evaluation-forms:activate-ended-events';

    protected $description = 'Automatically activate evaluation forms for ended events and notify participants who haven\'t completed surveys. Runs at 10PM daily.';

    public function handle(EvaluationFormActivationService $evaluationFormActivationService): int
    {
        $events = $evaluationFormActivationService->activateForEndedEvents();

        $this->info('Activated evaluation forms for ' . $events->count() . ' event(s).');

        return self::SUCCESS;
    }
}
