<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Collection;

class EvaluationFormDisableService
{
    /**
     * Automatically disable evaluation forms for ended events.
     * Runs daily at 12AM.
     */
    public function disableForEndedEvents(): Collection
    {
        $events = Event::query()
            ->whereDate('end_date', '<', now()->toDateString())
            ->where('evaluation_form_enabled', true)
            ->get();

        $events->each(function (Event $event): void {
            $event->disableEvaluationForm();
        });

        return $events;
    }
}
