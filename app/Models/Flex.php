<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * LESSON: Polymorphic Relationships (Branch 08)
 *
 * A Flex is a celebration message when a user completes a task.
 * It demonstrates belongsTo relationships and can receive reactions.
 *
 * Branch 06: BelongsTo relationships
 * Branch 08: MorphMany (Flexes can have Reactions)
 */
class Flex extends Model
{
    /**
     * @var array<string>
     */
    protected $fillable = [
        'message',
        'points_earned',
    ];

    /**
     * Indian Gen Z flex messages for random selection.
     */
    public const FLEX_MESSAGES = [
        'Arre wah! Task crushed it! 🔥',
        'Bhai ne kar diya! No cap! 💪',
        'Main character energy! 🌟',
        'Sigma grindset activated! 🚀',
        'Slay ho gaya! 💅',
        'Ekdum jhakaas! 🔯',
        'Full on beast mode! 🦁',
        'Bindaas complete kiya! 😎',
        'Mast kaam! Taali bajao! 👏',
        'Level up! Aur ek flex! ⬆️',
    ];

    // =========================================================================
    // LESSON: BelongsTo Relationships (Branch 06)
    //
    // A Flex BELONGS TO a User - every flex has exactly one owner.
    // The foreign key (user_id) is on THIS table (flexes).
    // =========================================================================

    /**
     * LESSON: BelongsTo Relationship
     *
     * A flex belongs to the user who earned it.
     *
     * Usage: $flex->user  // Returns the User model
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * LESSON: Optional BelongsTo
     *
     * A flex might be linked to a specific task (or not).
     * Note: task_id is nullable in the migration.
     *
     * Usage: $flex->task  // Returns Task model or null
     *
     * @return BelongsTo<Task, $this>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // =========================================================================
    // POLYMORPHIC RELATIONSHIPS (Branch 08)
    // =========================================================================

    /**
     * LESSON: MorphMany - Flexes Can Have Reactions!
     *
     * Users can react to flexes with emojis (🔥, 💯, etc.)
     * Same Reaction model used for Tasks, Projects, Comments, AND Flexes!
     *
     * @return MorphMany<Reaction, $this>
     */
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }
}
