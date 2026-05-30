<?php

namespace App\Services;

use App\Mail\EvaluationFormEnabledMail;
use App\Models\Event;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class EvaluationFormActivationService
{
    /**
     * Automatically activate evaluation forms for ended events
     * and send emails to participants who haven't completed surveys.
     * Runs daily at 9:00PM (21:00) after event ends (for testing).
     */
    public function activateForEndedEvents(): Collection
    {
        $events = Event::query()
            ->whereDate('end_date', '<=', now()->toDateString())
            ->where('evaluation_form_enabled', false)
            ->get();

        $events->each(function (Event $event): void {
            // Enable the evaluation form
            $event->enableEvaluationForm();

            // Get attended participants who haven't completed their evaluation
            $participantsToNotify = $event->participants()
                ->where('attended', true)
                ->whereDoesntHave('evaluations')
                ->get();

            foreach ($participantsToNotify as $participant) {
                try {
                    Mail::to($participant->email)->send(new EvaluationFormEnabledMail($event, $participant));
                    // 600ms delay = max ~1.6 emails/sec, safely under Resend's 2/sec limit
                    usleep(600000);
                } catch (\Exception $e) {
                    \Log::error('Evaluation form email failed for participant ' . $participant->id . ': ' . $e->getMessage());
                    // Continue to next participant even if one fails
                    continue;
                }
            }
        });

        return $events;
    }
}
