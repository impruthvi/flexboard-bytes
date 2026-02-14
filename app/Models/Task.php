<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * LESSON: Accessors, Mutators & Casting (Branch 03)
 *
 * This model demonstrates:
 * - Branch 02: Mass assignment protection with $fillable
 * - Branch 03: Accessors, mutators, and attribute casting
 */
class Task extends Model
{
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
     * LESSON: Attribute Casting
     *
     * Casts automatically convert database values to PHP types.
     * - 'boolean': 1/0 becomes true/false
     * - 'datetime': String becomes Carbon instance
     * - 'integer': Ensures numeric type
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',   // DB: 1 → PHP: true
            'completed_at' => 'datetime',  // DB: "2024-01-15 10:30:00" → Carbon
            'points' => 'integer',         // Ensures it's always an int
        ];
    }

    /**
     * LESSON: Accessor - Transform data when READING
     *
     * Get a color code based on priority level.
     * This is a "virtual" attribute - it doesn't exist in the database!
     *
     * Usage: $task->priority_color  // Returns "#ef4444" for high priority
     */
    protected function priorityColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->priority) {
                'low' => '#22c55e',     // Green - chill vibes
                'medium' => '#f59e0b',  // Amber - getting spicy
                'high' => '#ef4444',    // Red - it's urgent fam!
                default => '#6b7280',   // Gray - undefined
            },
        );
    }

    /**
     * LESSON: Accessor - Another example
     *
     * Get difficulty with an emoji for extra Gen Z energy.
     *
     * Usage: $task->difficulty_label  // Returns "🔥 Hard"
     */
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

    /**
     * LESSON: Accessor - Format title
     *
     * Ensure title is always properly capitalized when reading.
     * The database might have "fix the bug" but we display "Fix The Bug".
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
            // No mutator - we store as-is, just display nicely
        );
    }

    /**
     * LESSON: Include virtual attributes in JSON/array output
     *
     * When you call $task->toArray() or return JSON from API,
     * these computed attributes will be included.
     *
     * @var array<string>
     */
    protected $appends = [
        'priority_color',
        'difficulty_label',
    ];

    /**
     * LESSON: Combined Accessor + Mutator Example
     *
     * Here's how you'd do both in one method (not used here, just for reference):
     *
     * protected function email(): Attribute
     * {
     *     return Attribute::make(
     *         get: fn (string $value) => strtolower($value),  // Always read as lowercase
     *         set: fn (string $value) => strtolower($value),  // Always store as lowercase
     *     );
     * }
     */
}
