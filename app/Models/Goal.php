<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Goal Model
 * Core model for the Personal Goal Tracker.
 * 
 * Each goal belongs to a User and optionally to a Category.
 * Demonstrates Eloquent ORM, relationships, accessors, and Query Builder.
 *
 * Relationships:
 *   - belongsTo User
 *   - belongsTo Category
 */
class Goal extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'deadline',
        'status',
        'progress',
        'priority',
        'image',
        'completed_at',
    ];

    /**
     * Attribute casting for proper data types.
     */
    protected $casts = [
        'deadline'     => 'date',
        'completed_at' => 'datetime',
        'progress'     => 'integer',
    ];

    // ─────────────────────────────────────────────
    // RELATIONSHIPS
    // ─────────────────────────────────────────────

    /**
     * A goal belongs to a user (owner).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A goal belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ─────────────────────────────────────────────
    // ACCESSORS & HELPERS
    // ─────────────────────────────────────────────

    /**
     * Get URL for uploaded goal image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    /**
     * Get a human-readable status label with Bootstrap badge class.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending'     => ['label' => 'Pending',     'class' => 'bg-secondary'],
            'in_progress' => ['label' => 'In Progress', 'class' => 'bg-primary'],
            'completed'   => ['label' => 'Completed',   'class' => 'bg-success'],
            'cancelled'   => ['label' => 'Cancelled',   'class' => 'bg-danger'],
            default       => ['label' => 'Unknown',     'class' => 'bg-dark'],
        };
    }

    /**
     * Get priority badge details.
     */
    public function getPriorityBadgeAttribute(): array
    {
        return match ($this->priority) {
            'low'      => ['label' => 'Low',      'class' => 'bg-info'],
            'medium'   => ['label' => 'Medium',   'class' => 'bg-warning text-dark'],
            'high'     => ['label' => 'High',     'class' => 'bg-orange'],
            'critical' => ['label' => 'Critical', 'class' => 'bg-danger'],
            default    => ['label' => 'Medium',   'class' => 'bg-warning text-dark'],
        };
    }

    /**
     * Check if the goal is overdue (deadline passed and not completed).
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->deadline
            && $this->deadline->isPast()
            && $this->status !== 'completed';
    }

    /**
     * Days remaining until deadline.
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->deadline) return null;
        return now()->diffInDays($this->deadline, false);
    }

    // ─────────────────────────────────────────────
    // QUERY SCOPES (for filtering)
    // ─────────────────────────────────────────────

    /** Scope to get only completed goals */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /** Scope to get pending goals */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /** Scope to get in-progress goals */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /** Scope to get goals with upcoming deadlines (next 7 days) */
    public function scopeUpcoming($query)
    {
        return $query->whereNotNull('deadline')
                     ->where('deadline', '>=', now())
                     ->where('deadline', '<=', now()->addDays(7))
                     ->where('status', '!=', 'completed');
    }
}
