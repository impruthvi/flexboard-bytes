<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'points',
        'current_streak',
        'longest_streak',
        'last_flex_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_flex_date' => 'date',
            'points' => 'integer',
            'current_streak' => 'integer',
            'longest_streak' => 'integer',
        ];
    }

    /**
     * Get the projects for the user.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the flexes (completed tasks) for the user.
     */
    public function flexes(): HasMany
    {
        return $this->hasMany(Flex::class);
    }

    /**
     * Get the comments made by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the reactions made by the user.
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * Get the badges earned by the user.
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class)
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Get the friends for the user (accepted friendships).
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    /**
     * Get the pending friend requests received.
     */
    public function friendRequestsReceived(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->wherePivot('status', 'pending')
            ->withTimestamps();
    }

    /**
     * Get the pending friend requests sent.
     */
    public function friendRequestsSent(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->wherePivot('status', 'pending')
            ->withTimestamps();
    }

    /**
     * Get the user's avatar URL.
     * Falls back to UI Avatars if no custom avatar is set.
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->avatar) {
                    return asset('storage/'.$this->avatar);
                }

                return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=a855f7&color=fff&bold=true';
            },
        );
    }

    /**
     * Get the user's display name (username or name).
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->username ?? $this->name,
        );
    }

    /**
     * Get the streak display with fire emoji.
     */
    protected function streakDisplay(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->current_streak === 0) {
                    return 'No streak yet';
                }

                $fires = str_repeat('🔥', min($this->current_streak, 5));

                return $fires.' '.$this->current_streak.' day'.($this->current_streak > 1 ? 's' : '');
            },
        );
    }

    /**
     * Get the user's level based on points.
     */
    protected function level(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                return (int) floor($this->points / 100) + 1;
            },
        );
    }

    /**
     * Get the progress to the next level (0-100).
     */
    protected function levelProgress(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                return $this->points % 100;
            },
        );
    }

    /**
     * Get the user's rank title based on level.
     */
    protected function rankTitle(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return match (true) {
                    $this->level >= 50 => 'Legendary Flexer',
                    $this->level >= 30 => 'Elite Grinder',
                    $this->level >= 20 => 'Certified Hustler',
                    $this->level >= 10 => 'Rising Star',
                    $this->level >= 5 => 'Rookie Flexer',
                    default => 'Newbie',
                };
            },
        );
    }
}
