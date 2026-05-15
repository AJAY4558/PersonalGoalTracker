<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * User Model
 * Represents a registered user of the Goal Tracker application.
 *
 * Relationships:
 *   - hasMany Goals
 *
 * Supports:
 *   - Remember me token (auth)
 *   - Email notifications
 *   - Locale preference (en/hi)
 *   - Avatar upload
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Relationship: A user can have many goals.
     */
    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function createdGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function groupMemberships(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    public function groupTaskAssignments(): HasMany
    {
        return $this->hasMany(GroupTaskAssignment::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    /**
     * Get user's avatar URL or default placeholder.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(storage_path('app/public/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }
        // Generate initials-based avatar
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=6366f1&color=fff&size=128";
    }
}
