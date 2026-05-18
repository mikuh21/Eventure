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
            ->whereDate('event_date', '<', now()->toDateString())
            ->whereNull('survey_activated_at')
            ->get();

        $events->each(function (Event $event): void {
            $participants = $event->participants()->get();

            foreach ($participants as $participant) {
                Mail::to($participant->email)->send(new SurveyInvitationMail(
                    event: $event,
                    participant: $participant,
                    surveyUrl: route('participants.evaluations.create', $participant),
                ));
            }

            $event->update([
                'survey_activated_at' => now(),
            ]);
        });

        return $events;
    }
}
