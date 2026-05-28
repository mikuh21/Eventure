<?php

namespace App\Services;

use App\Mail\SurveyInvitationMail;
use App\Models\Event;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SurveyActivationService
{
    public function activateDueEvents(): Collection
    {
        $events = Event::query()
            ->whereDate('start_date', '<', now()->toDateString())
            ->whereNull('survey_activated_at')
            ->get();

        $events->each(function (Event $event): void {
            $participants = $event->participants()->get();

            foreach ($participants as $participant) {
                try {
                    Mail::to($participant->email)->send(new SurveyInvitationMail(
                        event: $event,
                        participant: $participant,
                        surveyUrl: route('participants.digital-id.show', $participant),
                    ));
                    // 600ms delay = max ~1.6 emails/sec, safely under Resend's 2/sec limit
                    usleep(600000);
                } catch (\Exception $e) {
                    \Log::error('Survey activation email failed for participant ' . $participant->id . ': ' . $e->getMessage());
                    // Continue to next participant even if one fails
                    continue;
                }
            }

            $event->update([
                'survey_activated_at' => now(),
            ]);
        });

        return $events;
    }
}
