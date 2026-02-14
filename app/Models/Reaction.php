<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * LESSON: Polymorphic Relationships (Branch 08)
 *
 * This model demonstrates MorphTo - a Reaction can belong to ANY model:
 * - Tasks
 * - Projects
 * - Comments (polymorphism within polymorphism!)
 * - Flexes
 *
 * The same Reaction model handles all these without any code changes!
 */
class Reaction extends Model
{
    /**
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'emoji',
    ];

    /**
     * Available reaction emojis with their meanings.
     */
    public const EMOJIS = [
        '🔥' => 'Fire! Lit!',
        '💯' => 'Perfect! 100%!',
        '🙌' => 'Celebrate!',
        '❤️' => 'Love it!',
        '🚀' => 'Ship it!',
        '👀' => 'Watching...',
        '🎯' => 'On target!',
        '💪' => 'Strong!',
        '🧠' => 'Big brain!',
        '😎' => 'Cool!',
    ];

    // =========================================================================
    // LESSON: MorphTo Relationship (Branch 08)
    //
    // morphTo() lets this Reaction belong to ANY model type.
    // The method name MUST match the column prefix (reactionable_id, reactionable_type).
    // =========================================================================

    /**
     * LESSON: MorphTo (Polymorphic BelongsTo)
     *
     * Get the parent model (Task, Project, Comment, etc.) that owns this reaction.
     *
     * Usage:
     * $reaction->reactionable  // Returns Task, Project, Comment, etc.
     *
     * How it works:
     * - Reads reactionable_type (e.g., "App\Models\Task")
     * - Reads reactionable_id (e.g., 42)
     * - Dynamically queries the correct table
     *
     * @return MorphTo<Model, $this>
     */
    public function reactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The user who added this reaction.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
