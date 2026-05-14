<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * WelcomeMail
 * Sent to a new user upon registration.
 * Demonstrates Laravel Mail system with Blade templates.
 */
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Altair — Built for Ambition! ✨',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.welcome',
            with: ['user' => $this->user],
        );
    }
}
