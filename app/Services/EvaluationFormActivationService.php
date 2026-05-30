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
        $manilaNow = now('Asia/Manila');

        \Log::info('EvaluationFormActivationService started', ['time' => $manilaNow->toDateTimeString()]);

        $events = Event::query()
            ->whereDate('end_date', '<=', $manilaNow->toDateString())
            ->where('evaluation_form_enabled', false)
            ->get();

        \Log::info('EvaluationFormActivationService found events', ['count' => $events->count()]);

        $events->each(function (Event $event) use ($manilaNow): void {
            try {
                // Enable the evaluation form
                $event->enableEvaluationForm();
                \Log::info('Enabled evaluation form for event', ['event_id' => $event->id, 'title' => $event->title, 'time' => $manilaNow->toDateTimeString()]);

                // Get attended participants who haven't completed their evaluation
                $participantsToNotify = $event->participants()
                    ->where('attended', true)
                    ->whereDoesntHave('evaluations')
                    ->get();

                \Log::info('Participants to notify', ['event_id' => $event->id, 'count' => $participantsToNotify->count()]);

                foreach ($participantsToNotify as $participant) {
                    try {
                        // Use send() to attempt immediate delivery (for testing live delivery via Resend)
                        Mail::to($participant->email)->send(new EvaluationFormEnabledMail($event, $participant));
                        // Rate-limit to avoid hitting 2/sec limits
                        usleep(600000);
                    } catch (\Exception $e) {
                        \Log::error('Evaluation form email failed for participant', ['participant_id' => $participant->id, 'error' => $e->getMessage()]);
                        // Continue to next participant even if one fails
                        continue;
                    }
                }
            } catch (\Exception $e) {
                \Log::error('EvaluationFormActivationService error', ['event_id' => $event->id ?? null, 'error' => $e->getMessage()]);
            }
        });

        return $events;
    }
}
