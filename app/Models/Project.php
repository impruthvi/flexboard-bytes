<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'color',
        'cover_image',
        'emoji',
        'is_archived',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_archived' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the comments for the project.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the reactions for the project.
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    /**
     * Get the completion percentage.
     */
    protected function completionPercentage(): Attribute
    {
        return Attribute::make(
            get: function (): int {
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
     * Get the total points for the project.
     */
    protected function totalPoints(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->tasks()->sum('points'),
        );
    }

    /**
     * Get the earned points for the project.
     */
    protected function earnedPoints(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->tasks()->where('is_completed', true)->sum('points'),
        );
    }
}
