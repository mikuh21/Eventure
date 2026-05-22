<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventStaffCredentialsMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $password,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Eventure Event Staff New Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-staff-credentials',
        );
    }
}