<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * LESSON: Polymorphic Relationships (Branch 08)
 *
 * This model demonstrates:
 * - Branch 02: Mass assignment protection
 * - Branch 03: Accessors, mutators, and casting
 * - Branch 04: Local and dynamic query scopes
 * - Branch 05: Soft deletes
 * - Branch 06: BelongsTo and HasMany relationships
 * - Branch 07: BelongsToMany (Tasks ↔ Tags)
 * - Branch 08: MorphMany (Tasks have Comments & Reactions)
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
    // RELATIONSHIPS (Branch 06 & 07)
    // =========================================================================

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return HasMany<Flex, $this>
     */
    public function flexes(): HasMany
    {
        return $this->hasMany(Flex::class);
    }

    /**
     * LESSON: BelongsToMany Relationship (Branch 07)
     *
     * A Task can have many Tags (and vice versa).
     * Uses the 'task_tag' pivot table.
     *
     * Usage:
     * $task->tags  // Collection of Tag models
     * $task->tags()->attach([1, 2, 3])  // Add tags
     * $task->tags()->sync([1, 2])  // Replace all tags
     * $task->tags()->detach(1)  // Remove a tag
     *
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'task_tag')
            ->withTimestamps();
    }

    // =========================================================================
    // POLYMORPHIC RELATIONSHIPS (Branch 08)
    // =========================================================================

    /**
     * LESSON: MorphMany Relationship
     *
     * A Task can have many Comments (polymorphic).
     * Other models (Project, etc.) can also have Comments
     * using the SAME Comment model!
     *
     * Usage:
     * $task->comments  // Collection of Comment models
     * $task->comments()->create(['body' => 'Nice!'])
     *
     * @return MorphMany<Comment, $this>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * LESSON: Multiple MorphMany Relationships
     *
     * A model can have multiple polymorphic relationships!
     * Tasks have both Comments AND Reactions.
     *
     * Usage:
     * $task->reactions  // Collection of Reaction models
     * $task->reactions()->create(['emoji' => '🔥'])
     *
     * @return MorphMany<Reaction, $this>
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
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
