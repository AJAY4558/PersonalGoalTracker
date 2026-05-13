<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Category Model
 * Represents a goal category (e.g., Health, Career, Finance).
 *
 * Relationships:
 *   - hasMany Goals
 */
class Category extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = ['name', 'slug', 'color', 'icon'];

    /**
     * Relationship: A category can have many goals.
     */
    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }
}
