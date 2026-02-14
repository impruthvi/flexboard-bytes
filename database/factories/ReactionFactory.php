<?php

namespace Database\Factories;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reaction>
 */
class ReactionFactory extends Factory
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
            'emoji' => fake()->randomElement(array_keys(Reaction::EMOJIS)),
        ];
    }

    /**
     * Create a fire reaction.
     */
    public function fire(): static
    {
        return $this->state(fn () => ['emoji' => '🔥']);
    }

    /**
     * Create a perfect (100) reaction.
     */
    public function perfect(): static
    {
        return $this->state(fn () => ['emoji' => '💯']);
    }

    /**
     * Create a love reaction.
     */
    public function love(): static
    {
        return $this->state(fn () => ['emoji' => '❤️']);
    }

    /**
     * Create a rocket (ship it) reaction.
     */
    public function rocket(): static
    {
        return $this->state(fn () => ['emoji' => '🚀']);
    }
}
