<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * LESSON: Mass Assignment with $fillable
 *
 * This Task model shows a more complete example of $fillable usage.
 * Notice which fields are included and which are excluded.
 */
class Task extends Model
{
    /**
     * Fields that CAN be mass assigned.
     *
     * INCLUDED: User-controllable fields
     * - title, description: User input
     * - priority, difficulty: User selection
     *
     * EXCLUDED (set explicitly):
     * - project_id: Set in controller based on route/auth
     * - points: Calculated or set by system
     * - is_completed: Changed via specific action
     * - completed_at: Set when task is completed
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'difficulty',
    ];

    /**
     * WRONG WAY - Too permissive:
     *
     * protected $fillable = [
     *     'title',
     *     'description',
     *     'project_id',    // ❌ User could assign to any project!
     *     'points',        // ❌ User could give themselves points!
     *     'is_completed',  // ❌ User could mark anything complete!
     * ];
     *
     * ALSO WRONG - No protection:
     *
     * protected $guarded = [];  // ❌ Allows everything!
     */
}
