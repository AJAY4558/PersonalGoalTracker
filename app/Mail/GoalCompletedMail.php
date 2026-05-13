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
 * GoalCompletedMail
 * Sent when a user marks a goal as completed.
 */
class GoalCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Goal $goal) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏆 Congratulations! Goal Completed: ' . $this->goal->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.goal_completed',
            with: [
                'user' => $this->user,
                'goal' => $this->goal,
            ],
        );
    }
}
