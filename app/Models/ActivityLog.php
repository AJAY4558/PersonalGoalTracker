<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * ActivityLog Model — MongoDB Collection
 *
 * Stores activity logs and analytics in MongoDB.
 * Uses the mongodb/laravel-mongodb package.
 *
 * Example documents stored:
 *  - Login events
 *  - Goal creation / update / deletion
 *  - Goal completion
 *
 * NOTE: Requires ext-mongodb PHP extension and MongoDB server.
 * If unavailable, falls back to file-based logging (see ActivityLogService).
 */
class ActivityLog extends Model
{
    // MongoDB connection (defined in config/database.php)
    protected $connection = 'mongodb';

    // MongoDB collection name
    protected $collection = 'activity_logs';

    /**
     * Mass assignable fields.
     */
    protected $fillable = [
        'user_id',      // Authenticated user ID
        'user_name',    // User display name
        'action',       // e.g., 'login', 'goal.created', 'goal.completed'
        'description',  // Human-readable description
        'meta',         // Array of extra data (e.g., goal title, IP)
        'ip_address',   // Client IP address
        'user_agent',   // Browser user agent
    ];

    /**
     * Cast meta as array so it's stored as a sub-document in MongoDB.
     */
    protected $casts = [
        'meta' => 'array',
    ];
}
