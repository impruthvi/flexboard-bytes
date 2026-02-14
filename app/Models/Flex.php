<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flex extends Model
{
    /** @use HasFactory<\Database\Factories\FlexFactory> */
    use HasFactory;

    /**
     * Gen Z flex messages for task completion (with Indian flavor).
     *
     * @var list<string>
     */
    public const array FLEX_MESSAGES = [
        'No cap, that was easy',
        'Crushed it fr fr',
        'Built different',
        'Main character energy',
        'Lowkey a legend',
        'Hits different when you finish',
        'Ate and left no crumbs',
        'Understood the assignment',
        'Slay mode activated',
        'Its giving productivity',
        'Mummy would be proud',
        'Sharma ji ka beta who?',
        'Aaj toh kar hi diya',
        'Procrastination ko haraaya',
        'Bohot hard bhai',
        'Apna time aa gaya',
        'Zindagi set hai',
        'First attempt mein done',
        'Padh liya finally',
        'Grind never stops yaar',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'points_earned',
        'flex_message',
        'is_streak_bonus',
        'streak_multiplier',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points_earned' => 'integer',
            'is_streak_bonus' => 'boolean',
            'streak_multiplier' => 'integer',
        ];
    }

    /**
     * Get the user that made the flex.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task that was flexed.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get a random flex message.
     */
    public static function randomFlexMessage(): string
    {
        return self::FLEX_MESSAGES[array_rand(self::FLEX_MESSAGES)];
    }
}
