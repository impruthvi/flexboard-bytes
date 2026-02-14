<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * LESSON: Many-to-Many Relationships (Branch 07)
 *
 * A Tag can belong to many Tasks.
 * A Task can have many Tags.
 * This is a Many-to-Many relationship using a pivot table.
 */
class Tag extends Model
{
    /**
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * Auto-generate slug from name.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                return [
                    'name' => $value,
                    'slug' => Str::slug($value),
                ];
            },
        );
    }

    // =========================================================================
    // LESSON: MANY-TO-MANY RELATIONSHIP (Branch 07)
    // =========================================================================

    /**
     * LESSON: BelongsToMany Relationship
     *
     * A Tag belongs to many Tasks (and vice versa).
     *
     * We specify 'task_tag' as the pivot table name because:
     * - Convention would be alphabetical: 'tag_task'
     * - We chose 'task_tag' to be explicit
     *
     * Usage:
     * $tag->tasks  // Collection of Task models
     * $tag->tasks()->attach($taskId)  // Add relationship
     *
     * @return BelongsToMany<Task, $this>
     */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_tag')
            ->withTimestamps();
    }
}
