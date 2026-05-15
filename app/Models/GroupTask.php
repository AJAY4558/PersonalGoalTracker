<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'assigned_by',
        'title',
        'description',
        'deadline',
        'priority',
        'status',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(GroupTaskAssignment::class);
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->deadline) {
            return null;
        }

        return now()->diffInDays($this->deadline, false);
    }
}
