<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'invite_code',
        'created_by',
        'max_members',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(GroupTask::class);
    }

    public function invites(): HasMany
    {
        return $this->hasMany(GroupInvite::class);
    }

    public function getAverageProgressAttribute(): int
    {
        $assignments = $this->tasks->flatMap->assignments;

        if ($assignments->isEmpty()) {
            return 0;
        }

        return (int) round($assignments->avg('progress'));
    }
}
