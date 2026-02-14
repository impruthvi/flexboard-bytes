<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * LESSON: Basic Relationships (Branch 06)
 *
 * The User model is the center of our relationship web.
 * A User has many Projects, and through projects, has many Tasks.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================================
    // LESSON: RELATIONSHIPS (Branch 06)
    // =========================================================================

    /**
     * LESSON: HasMany Relationship
     *
     * A User has many Projects.
     * The foreign key (user_id) is on the RELATED table (projects).
     *
     * Usage: $user->projects  // Returns Collection of Project models
     *
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * LESSON: HasMany Relationship
     *
     * A User has many Flexes (celebration messages).
     *
     * Usage: $user->flexes  // Returns Collection of Flex models
     *
     * @return HasMany<Flex, $this>
     */
    public function flexes(): HasMany
    {
        return $this->hasMany(Flex::class);
    }

    /**
     * LESSON: HasOne Relationship (Latest of Many)
     *
     * Get just the most recent flex for a user.
     * Uses latestOfMany() to get the newest one.
     *
     * Usage: $user->latestFlex  // Returns single Flex model or null
     *
     * @return HasOne<Flex, $this>
     */
    public function latestFlex(): HasOne
    {
        return $this->hasOne(Flex::class)->latestOfMany();
    }

    /**
     * LESSON: HasManyThrough (Preview for later)
     *
     * Get all tasks for a user across all their projects.
     * This traverses: User -> Projects -> Tasks
     *
     * Usage: $user->tasks  // All tasks from all user's projects
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<Task, Project, $this>
     */
    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Project::class);
    }
}
