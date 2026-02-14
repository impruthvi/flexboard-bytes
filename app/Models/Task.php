<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * LESSON: Basic Relationships (Branch 06)
 *
 * This model demonstrates:
 * - Branch 02: Mass assignment protection
 * - Branch 03: Accessors, mutators, and casting
 * - Branch 04: Local and dynamic query scopes
 * - Branch 05: Soft deletes
 * - Branch 06: BelongsTo and HasMany relationships
 */
class Task extends Model
{
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
    // LESSON: RELATIONSHIPS (Branch 06)
    // =========================================================================

    /**
     * LESSON: BelongsTo Relationship
     *
     * A Task belongs to a Project.
     * The foreign key (project_id) is on THIS table (tasks).
     *
     * Usage: $task->project  // Returns the Project model
     *
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * LESSON: HasMany Relationship
     *
     * A Task can have many Flexes (celebration messages).
     *
     * Usage: $task->flexes  // Returns Collection of Flex models
     *
     * @return HasMany<Flex, $this>
     */
    public function flexes(): HasMany
    {
        return $this->hasMany(Flex::class);
    }

    // =========================================================================
    // LOCAL SCOPES (Branch 04)
    // =========================================================================

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->where('priority', 'high')
            ->where('is_completed', false);
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->where('priority', 'high');
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeOfPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeOfDifficulty(Builder $query, string $difficulty): Builder
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeHighValue(Builder $query, int $minPoints = 50): Builder
    {
        return $query->where('points', '>=', $minPoints);
    }

    /**
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeCreatedToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
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
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    // =========================================================================
    // ACCESSORS (Branch 03)
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
