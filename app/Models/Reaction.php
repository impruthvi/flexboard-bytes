<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    /** @use HasFactory<\Database\Factories\ReactionFactory> */
    use HasFactory;

    /**
     * Available reaction emojis.
     *
     * @var list<string>
     */
    public const array EMOJIS = [
        'fire',
        'rocket',
        'heart',
        '100',
        'clap',
        'muscle',
        'star',
        'trophy',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'reactionable_type',
        'reactionable_id',
        'emoji',
    ];

    /**
     * Get the user that made the reaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent reactionable model (Project, Task, or Flex).
     */
    public function reactionable(): MorphTo
    {
        return $this->morphTo();
    }
}
