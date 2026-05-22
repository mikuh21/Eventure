<?php

namespace App\Mail;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestDigitalIdMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Guest $guest,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Eventure Guest Digital ID',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.guest-digital-id',
        );
    }
}
