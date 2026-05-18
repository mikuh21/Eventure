<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParticipantRegisteredMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Participant $participant,
        public string $digitalIdUrl,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Event Registration and Digital ID',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.participant-registered',
        );
    }
}
