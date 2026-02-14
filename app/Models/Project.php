<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * LESSON: Polymorphic Relationships (Branch 08)
 *
 * This model demonstrates:
 * - Branch 01: Naming conventions
 * - Branch 02: Mass assignment protection
 * - Branch 03: Accessors and mutators
 * - Branch 05: Soft deletes with cascading
 * - Branch 06: BelongsTo and HasMany relationships
 * - Branch 08: MorphMany (Projects have Comments & Reactions)
 */
class Project extends Model
{
    use SoftDeletes;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Cascade soft deletes to related models.
     */
    protected static function booted(): void
    {
        static::deleting(function (Project $project) {
            $project->tasks()->delete();
        });

        static::restoring(function (Project $project) {
            $project->tasks()->withTrashed()->restore();
        });
    }

    // =========================================================================
    // LESSON: RELATIONSHIPS (Branch 06)
    // =========================================================================

    /**
     * LESSON: BelongsTo Relationship
     *
     * A Project belongs to a User (the owner).
     * The foreign key (user_id) is on THIS table (projects).
     *
     * Usage: $project->user  // Returns the User model
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * LESSON: HasMany Relationship
     *
     * A Project has many Tasks.
     * The foreign key (project_id) is on the RELATED table (tasks).
     *
     * Usage: $project->tasks  // Returns Collection of Task models
     *        $project->tasks()->incomplete()->get()  // Chain with scopes!
     *
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // =========================================================================
    // POLYMORPHIC RELATIONSHIPS (Branch 08)
    // =========================================================================

    /**
     * LESSON: MorphMany - Projects Can Have Comments!
     *
     * The SAME Comment model works for Tasks AND Projects.
     * This is the power of polymorphic relationships!
     *
     * Usage:
     * $project->comments  // Collection of Comment models
     * $project->comments()->create(['body' => 'Great project!'])
     *
     * @return MorphMany<Comment, $this>
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * LESSON: MorphMany - Projects Can Have Reactions!
     *
     * Users can react to projects with emojis.
     *
     * @return MorphMany<Reaction, $this>
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    // =========================================================================
    // ACCESSORS (Branch 03)
    // =========================================================================

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
}
