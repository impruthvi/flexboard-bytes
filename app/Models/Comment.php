<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * LESSON: Polymorphic Relationships (Branch 08)
 *
 * This model demonstrates:
 * - MorphTo: A Comment can belong to ANY model (Task, Project, etc.)
 * - MorphMany: A Comment can have many Reactions (polymorphic!)
 *
 * Key Concept: The same Comment model works for Tasks, Projects,
 * or any future model without changing any code!
 */
class Comment extends Model
{
    /**
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'body',
    ];

    /**
     * Indian Gen Z comment templates for random selection.
     */
    public const COMMENT_TEMPLATES = [
        'Bhai ye toh bahut easy hai! 😎',
        'Arre yaar, kab khatam hoga ye? 😫',
        'Mast progress! Keep it up! 🚀',
        'Ekdum first class kaam! 💯',
        'This is giving main character energy! ✨',
        'No cap, this is fire! 🔥',
        'Slay kiya finally! 💅',
        'Pehle wala approach zyada better tha...',
        'Let me cook on this one! 👨‍🍳',
        'Real ID se aa bhai! 😂',
    ];

    // =========================================================================
    // LESSON: MorphTo Relationship (Branch 08)
    //
    // morphTo() defines the INVERSE of a polymorphic relationship.
    // The method name MUST match the column prefix (commentable_id, commentable_type).
    // =========================================================================

    /**
     * LESSON: MorphTo (Polymorphic BelongsTo)
     *
     * Get the parent model (Task, Project, etc.) that owns this comment.
     *
     * Usage:
     * $comment->commentable  // Returns Task, Project, or any parent model
     *
     * Behind the scenes:
     * - Reads commentable_type (e.g., "App\Models\Task")
     * - Reads commentable_id (e.g., 5)
     * - Queries the appropriate table
     *
     * @return MorphTo<Model, $this>
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The user who wrote this comment.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================================
    // LESSON: MorphMany on a Polymorphic Model (Branch 08)
    //
    // Comments can ALSO have reactions - polymorphism all the way down!
    // This creates a nested polymorphic structure.
    // =========================================================================

    /**
     * LESSON: Comments Can Have Reactions Too!
     *
     * This is polymorphism within polymorphism - a Comment (which is
     * polymorphic itself) can have many polymorphic Reactions.
     *
     * @return MorphMany<Reaction, $this>
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }
}
