<?php

namespace App\Providers;

use App\Models\AppNotification;
use App\Models\Goal;
use App\Models\GroupTaskAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.navbar', function ($view) {
            if (!Auth::check()) {
                $view->with('luxNotifications', collect());
                return;
            }

            $stored = AppNotification::where('user_id', Auth::id())
                ->latest()
                ->take(8)
                ->get()
                ->map(function ($notification) {
                    $data = $notification->data ?? [];
                    $notification->icon = match ($notification->type) {
                        'group_invite' => 'bi-people',
                        'group_task_assigned' => 'bi-clipboard-check',
                        'goal_completed' => 'bi-check2-circle',
                        default => 'bi-bell',
                    };
                    $notification->message = match ($notification->type) {
                        'group_invite' => "You've been invited to join " . ($data['group_name'] ?? 'a group'),
                        'group_task_assigned' => ($data['assigned_by'] ?? 'A teammate') . ' assigned you a task in ' . ($data['group_name'] ?? 'a group'),
                        'goal_completed' => $data['message'] ?? 'Goal completed',
                        default => $data['message'] ?? 'New notification',
                    };
                    return $notification;
                });

            $personalDeadlines = Goal::where('user_id', Auth::id())
                ->where('status', '!=', 'completed')
                ->whereNotNull('deadline')
                ->where('deadline', '>=', now())
                ->where('deadline', '<=', now()->addHours(48))
                ->orderBy('deadline')
                ->take(5)
                ->get()
                ->map(function ($goal) {
                    return (object) [
                        'type' => 'personal_deadline',
                        'data' => [],
                        'read_at' => null,
                        'icon' => 'bi-calendar-event',
                        'message' => "Personal deadline soon: {$goal->title}",
                        'created_at' => $goal->deadline,
                    ];
                });

            $groupDeadlines = GroupTaskAssignment::with(['task.group'])
                ->where('user_id', Auth::id())
                ->where('status', '!=', 'completed')
                ->whereHas('task', function ($query) {
                    $query->where('status', '!=', 'completed')
                        ->whereNotNull('deadline')
                        ->where('deadline', '>=', now())
                        ->where('deadline', '<=', now()->addHours(48));
                })
                ->take(5)
                ->get()
                ->map(function ($assignment) {
                    return (object) [
                        'type' => 'group_deadline',
                        'data' => [],
                        'read_at' => null,
                        'icon' => 'bi-people',
                        'message' => "Group task deadline soon: {$assignment->task->title}",
                        'created_at' => $assignment->task->deadline,
                    ];
                });

            $view->with([
                'luxNotifications' => $stored->concat($personalDeadlines)->concat($groupDeadlines)->sortByDesc('created_at')->take(12),
                'luxUnreadCount' => AppNotification::where('user_id', Auth::id())->whereNull('read_at')->count(),
            ]);
        });
    }
}
