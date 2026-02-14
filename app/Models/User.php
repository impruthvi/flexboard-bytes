<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * LESSON: Many-to-Many Relationships (Branch 07)
 *
 * The User model now includes:
 * - Branch 06: HasMany, HasOne, HasManyThrough
 * - Branch 07: BelongsToMany (Users ↔ Badges)
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
    // RELATIONSHIPS (Branch 06 & 07)
    // =========================================================================

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * @return HasMany<Flex, $this>
     */
    public function flexes(): HasMany
    {
        return $this->hasMany(Flex::class);
    }

    /**
     * @return HasOne<Flex, $this>
     */
    public function latestFlex(): HasOne
    {
        return $this->hasOne(Flex::class)->latestOfMany();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<Task, Project, $this>
     */
    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Project::class);
    }

    /**
     * LESSON: BelongsToMany with Pivot Data (Branch 07)
     *
     * A User can have many Badges (achievements).
     * The pivot table stores when the badge was earned.
     *
     * Usage:
     * $user->badges  // Collection of Badge models
     * $user->badges()->attach($badgeId, ['earned_at' => now()])
     * $user->badges->first()->pivot->earned_at  // Access pivot data
     *
     * @return BelongsToMany<Badge, $this>
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class)
            ->withPivot('earned_at', 'notes')  // Include extra pivot columns
            ->withTimestamps();                 // Auto-manage pivot timestamps
    }
}
