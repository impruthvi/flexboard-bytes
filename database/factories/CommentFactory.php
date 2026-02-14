<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'body' => fake()->randomElement(Comment::COMMENT_TEMPLATES),
        ];
    }

    /**
     * Use a random Indian-flavored comment.
     */
    public function indianStyle(): static
    {
        return $this->state(fn () => [
            'body' => fake()->randomElement(Comment::COMMENT_TEMPLATES),
        ]);
    }

    /**
     * Use a longer, more detailed comment.
     */
    public function detailed(): static
    {
        return $this->state(fn () => [
            'body' => fake()->paragraph(),
        ]);
    }
}
