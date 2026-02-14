<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    /** @use HasFactory<\Database\Factories\BadgeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'points_required',
        'color',
        'rarity',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points_required' => 'integer',
        ];
    }

    /**
     * Get the users that have this badge.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Get the rarity color for display.
     */
    protected function rarityColor(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match ($this->rarity) {
                'common' => '#00f5ff',    // cyan
                'rare' => '#bf5af2',      // purple
                'epic' => '#ff6b35',      // orange
                'legendary' => '#f7ff00', // yellow
                default => '#00f5ff',
            },
        );
    }
}
