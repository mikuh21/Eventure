<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SurveyInvitationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Event $event,
        public Participant $participant,
        public string $surveyUrl,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Please Complete the Event Survey',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.survey-invitation',
        );
    }
}
