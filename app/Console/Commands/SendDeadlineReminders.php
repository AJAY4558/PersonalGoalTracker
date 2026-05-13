<?php

namespace App\Console\Commands;

use App\Mail\DeadlineReminderMail;
use App\Models\Goal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * SendDeadlineReminders — Custom Artisan Command
 *
 * Finds goals with deadlines within the next 3 days
 * and sends reminder emails to their owners.
 *
 * Run:    php artisan goals:send-reminders
 * Schedule: daily (see routes/console.php)
 *
 * Demonstrates: Artisan commands, Mail, Eloquent
 */
class SendDeadlineReminders extends Command
{
    /**
     * The name and signature of the console command.
     * Usage: php artisan goals:send-reminders [--days=3]
     */
    protected $signature = 'goals:send-reminders
                            {--days=3 : Number of days ahead to check for deadlines}';

    /**
     * Human-readable description shown in php artisan list
     */
    protected $description = 'Send deadline reminder emails for goals due within N days';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $this->info("Checking for goals due within {$days} days...");

        // Find non-completed goals with upcoming deadlines
        $goals = Goal::with('user')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [now()->startOfDay(), now()->addDays($days)->endOfDay()])
            ->get();

        if ($goals->isEmpty()) {
            $this->info('No upcoming deadlines found.');
            return self::SUCCESS;
        }

        $sent = 0;

        foreach ($goals as $goal) {
            try {
                Mail::to($goal->user->email)->send(
                    new DeadlineReminderMail($goal->user, $goal)
                );
                $this->line("  ✓ Reminder sent to {$goal->user->email} for: {$goal->title}");
                $sent++;
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to send to {$goal->user->email}: {$e->getMessage()}");
            }
        }

        $this->info("Done! Sent {$sent} reminder(s).");
        return self::SUCCESS;
    }
}
