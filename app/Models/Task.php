<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'points',
        'priority',
        'difficulty',
        'is_completed',
        'completed_at',
        'due_date',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
            'due_date' => 'date',
            'points' => 'integer',
            'order' => 'integer',
        ];
    }

    /**
     * Get the project that owns the task.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the flex record for the task.
     */
    public function flex(): HasOne
    {
        return $this->hasOne(Flex::class);
    }

    /**
     * Get the tags for the task.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'task_tag')->withTimestamps();
    }

    /**
     * Get the comments for the task.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the reactions for the task.
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    /**
     * Get the priority color for display.
     */
    protected function priorityColor(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match ($this->priority) {
                'low' => '#00f5ff',      // cyan
                'medium' => '#bf5af2',   // purple
                'high' => '#ff6b35',     // orange
                'urgent' => '#ff2d92',   // pink
                default => '#bf5af2',
            },
        );
    }

    /**
     * Get the difficulty emoji for display.
     */
    protected function difficultyEmoji(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match ($this->difficulty) {
                'easy' => 'cake',
                'medium' => 'muscle',
                'hard' => 'fire',
                'legendary' => 'skull',
                default => 'muscle',
            },
        );
    }

    /**
     * Check if the task is overdue.
     */
    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                if (! $this->due_date || $this->is_completed) {
                    return false;
                }

                return $this->due_date->isPast();
            },
        );
    }
}
