<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * LESSON: Accessors & Mutators (Branch 03)
 *
 * This model demonstrates:
 * - Branch 01: Naming conventions
 * - Branch 02: Mass assignment protection
 * - Branch 03: Accessors, mutators, and attribute manipulation
 */
class Project extends Model
{
    /**
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * LESSON: Mutator - Transform data when WRITING
     *
     * This mutator does TWO things when you set the name:
     * 1. Stores the name as-is
     * 2. Auto-generates a URL-friendly slug
     *
     * Usage:
     * $project->name = 'My Awesome Project';
     * // name = "My Awesome Project"
     * // slug = "my-awesome-project" (auto-generated!)
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

    /**
     * LESSON: Accessor - Computed attribute
     *
     * Calculate completion percentage from related tasks.
     * This attribute doesn't exist in database - it's computed!
     *
     * Usage: $project->completion_percentage  // Returns 75
     *
     * Note: This creates an N+1 problem when listing projects!
     * We'll fix this in Branch 09-10 with eager loading.
     */
    protected function completionPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Note: This queries the database each time!
                // We'll learn to optimize this later.
                $total = $this->tasks()->count();

                if ($total === 0) {
                    return 0;
                }

                $completed = $this->tasks()->where('is_completed', true)->count();

                return (int) round(($completed / $total) * 100);
            },
        );
    }

    /**
     * LESSON: Accessor - Format description for preview
     *
     * Truncate description for card previews.
     */
    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->description ?? '', 100),
        );
    }

    /**
     * Include computed attributes in JSON output.
     *
     * @var array<string>
     */
    protected $appends = [
        'completion_percentage',
        'short_description',
    ];

    /**
     * PREVIEW: Relationship (coming in Branch 06)
     *
     * This is a hasMany relationship - a project has many tasks.
     * We're adding it now because completion_percentage accessor needs it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Task, $this>
     */
    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }
}
