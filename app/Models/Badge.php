<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * LESSON: Many-to-Many with Pivot Data (Branch 07)
 *
 * A Badge can belong to many Users.
 * A User can have many Badges.
 * The pivot table stores WHEN the badge was earned.
 */
class Badge extends Model
{
    /**
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
        'rarity',
        'points_required',
    ];

    /**
     * Auto-generate slug from name.
     */
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

    /**
     * Get color based on rarity.
     */
    protected function rarityColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->rarity) {
                'common' => '#9ca3af',     // Gray
                'rare' => '#3b82f6',       // Blue
                'epic' => '#a855f7',       // Purple
                'legendary' => '#f59e0b',  // Gold
                default => '#6b7280',
            },
        );
    }

    /**
     * @var array<string>
     */
    protected $appends = ['rarity_color'];

    // =========================================================================
    // LESSON: MANY-TO-MANY WITH PIVOT DATA (Branch 07)
    // =========================================================================

    /**
     * LESSON: BelongsToMany with Pivot Columns
     *
     * This relationship includes extra data from the pivot table:
     * - earned_at: When the user earned this badge
     * - notes: Any notes about why they earned it
     *
     * Usage:
     * $badge->users  // Collection of User models
     * $badge->users->first()->pivot->earned_at  // Access pivot data
     *
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('earned_at', 'notes')  // Include these pivot columns
            ->withTimestamps();                 // Auto-manage created_at/updated_at
    }
}
