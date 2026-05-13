<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

/**
 * ActivityLogService
 *
 * Provides a unified interface for logging user activity.
 * Attempts to write to MongoDB (if ext-mongodb is available),
 * otherwise falls back to Laravel's log file.
 *
 * This demonstrates MongoDB integration with a graceful fallback.
 */
class ActivityLogService
{
    /**
     * Log an activity event.
     *
     * @param string $action      e.g., 'goal.created', 'user.login'
     * @param string $description Human-readable description
     * @param array  $meta        Additional metadata
     */
    public static function log(string $action, string $description, array $meta = []): void
    {
        $user = Auth::user();

        $logData = [
            'user_id'    => $user?->id,
            'user_name'  => $user?->name,
            'action'     => $action,
            'description'=> $description,
            'meta'       => $meta,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now()->toISOString(),
        ];

        try {
            // Attempt MongoDB logging
            if (class_exists(\MongoDB\Laravel\Eloquent\Model::class) && extension_loaded('mongodb')) {
                \App\Models\ActivityLog::create($logData);
                return;
            }
        } catch (\Exception $e) {
            // Fall through to file logging
        }

        // Fallback: Log to Laravel log file (storage/logs/laravel.log)
        Log::channel('single')->info("[ActivityLog] {$action}: {$description}", $logData);
    }
}
