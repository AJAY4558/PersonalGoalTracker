<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupTaskAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_task_id',
        'user_id',
        'progress',
        'status',
    ];

    protected $casts = [
        'progress' => 'integer',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(GroupTask::class, 'group_task_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
