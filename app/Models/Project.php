<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * LESSON: Soft Deletes (Branch 05)
 *
 * This model demonstrates:
 * - Branch 01: Naming conventions
 * - Branch 02: Mass assignment protection
 * - Branch 03: Accessors and mutators
 * - Branch 05: Soft deletes with cascading
 */
class Project extends Model
{
    /**
     * LESSON: SoftDeletes Trait (Branch 05)
     *
     * This trait enables soft deletes:
     * - delete() sets deleted_at instead of removing record
     * - Records with deleted_at are hidden from queries by default
     * - Use withTrashed() to include deleted records
     * - Use restore() to bring back deleted records
     */
    use SoftDeletes;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * LESSON: Cascade soft deletes to related models
     *
     * When a project is soft-deleted, its tasks should also be soft-deleted.
     * When restored, tasks should be restored too.
     */
    protected static function booted(): void
    {
        static::deleting(function (Project $project) {
            // Soft delete all tasks when project is deleted
            $project->tasks()->delete();
        });

        static::restoring(function (Project $project) {
            // Restore all tasks when project is restored
            $project->tasks()->withTrashed()->restore();
        });
    }

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

    protected function completionPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                $total = $this->tasks()->count();

                if ($total === 0) {
                    return 0;
                }

                $completed = $this->tasks()->where('is_completed', true)->count();

                return (int) round(($completed / $total) * 100);
            },
        );
    }

    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->description ?? '', 100),
        );
    }

    /**
     * @var array<string>
     */
    protected $appends = [
        'completion_percentage',
        'short_description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Task, $this>
     */
    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }
}
