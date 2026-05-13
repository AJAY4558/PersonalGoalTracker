<?php

namespace App\Mail;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * DeadlineReminderMail
 * Sent when a goal's deadline is approaching (within 3 days).
 * Triggered by the SendDeadlineReminders Artisan command.
 */
class DeadlineReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Goal $goal) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⏰ Deadline Reminder: ' . $this->goal->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.deadline_reminder',
            with: [
                'user' => $this->user,
                'goal' => $this->goal,
            ],
        );
    }
}
