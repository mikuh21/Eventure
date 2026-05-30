<?php

namespace App\Console\Commands;

use App\Services\EvaluationFormActivationService;
use Illuminate\Console\Command;

class ActivateEvaluationFormsForEndedEvents extends Command
{
    protected $signature = 'evaluation-forms:activate-ended-events';

    protected $description = 'Automatically activate evaluation forms for ended events and notify participants who haven\'t completed surveys. Auto-opens at 11:00PM Manila time if admin has not opened the form manually.';

    public function handle(EvaluationFormActivationService $evaluationFormActivationService): int
    {
        \Log::info('ActivateEvaluationFormsForEndedEvents command invoked', ['time' => now('Asia/Manila')->toDateTimeString()]);

        $events = $evaluationFormActivationService->activateForEndedEvents();

        $this->info('Activated evaluation forms for ' . $events->count() . ' event(s).');

        \Log::info('ActivateEvaluationFormsForEndedEvents finished', ['activated' => $events->count(), 'time' => now('Asia/Manila')->toDateTimeString()]);

        return self::SUCCESS;
    }
}
