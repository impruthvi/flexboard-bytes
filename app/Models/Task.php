<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * LESSON: Soft Deletes (Branch 05)
 *
 * This model demonstrates:
 * - Branch 02: Mass assignment protection
 * - Branch 03: Accessors, mutators, and casting
 * - Branch 04: Local and dynamic query scopes
 * - Branch 05: Soft deletes
 */
class Task extends Model
{
    /**
     * LESSON: SoftDeletes Trait (Branch 05)
     *
     * Tasks can be soft-deleted. When a project is deleted,
     * its tasks are cascaded (see Project model's booted method).
     */
    use SoftDeletes;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'difficulty',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
            'points' => 'integer',
        ];
    }

    // =========================================================================
    // LESSON: LOCAL SCOPES (Branch 04)
    //
    // Scopes are reusable query constraints. Define once, use everywhere!
    // Method name starts with 'scope', but you call it without that prefix.
    // =========================================================================

    /**
     * LESSON: Basic Local Scope
     *
     * Filter to only incomplete tasks.
     *
     * Usage: Task::incomplete()->get();
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    /**
     * LESSON: Another Basic Scope
     *
     * Filter to only completed tasks.
     *
     * Usage: Task::completed()->get();
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    /**
     * LESSON: Combined Scope
     *
     * High priority + incomplete = urgent!
     * Scopes can combine multiple conditions.
     *
     * Usage: Task::urgent()->get();
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->where('priority', 'high')
            ->where('is_completed', false);
    }

    /**
     * LESSON: Priority Scope
     *
     * Usage: Task::highPriority()->get();
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->where('priority', 'high');
    }

    /**
     * LESSON: Dynamic Scope with Parameter
     *
     * Pass a parameter to make scopes flexible!
     *
     * Usage: Task::ofPriority('high')->get();
     *        Task::ofPriority('low')->get();
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeOfPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    /**
     * LESSON: Another Dynamic Scope
     *
     * Usage: Task::ofDifficulty('nightmare')->get();
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeOfDifficulty(Builder $query, string $difficulty): Builder
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * LESSON: Scope with Optional Parameter
     *
     * Usage: Task::highValue()->get();      // Points >= 50 (default)
     *        Task::highValue(100)->get();   // Points >= 100
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeHighValue(Builder $query, int $minPoints = 50): Builder
    {
        return $query->where('points', '>=', $minPoints);
    }

    /**
     * LESSON: Date-based Scope
     *
     * Usage: Task::createdToday()->get();
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeCreatedToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * LESSON: Complex Date Scope
     *
     * Completed tasks from this week - great for leaderboards!
     *
     * Usage: Task::completedThisWeek()->get();
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeCompletedThisWeek(Builder $query): Builder
    {
        return $query->where('is_completed', true)
            ->whereBetween('completed_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
    }

    /**
     * LESSON: Ordering Scope
     *
     * Usage: Task::recent()->get();
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    // =========================================================================
    // ACCESSORS (from Branch 03)
    // =========================================================================

    protected function priorityColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->priority) {
                'low' => '#22c55e',
                'medium' => '#f59e0b',
                'high' => '#ef4444',
                default => '#6b7280',
            },
        );
    }

    protected function difficultyLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->difficulty) {
                'easy' => '😎 Easy',
                'medium' => '💪 Medium',
                'hard' => '🔥 Hard',
                'nightmare' => '💀 Nightmare',
                default => '❓ Unknown',
            },
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
        );
    }

    /**
     * @var array<string>
     */
    protected $appends = [
        'priority_color',
        'difficulty_label',
    ];
}
