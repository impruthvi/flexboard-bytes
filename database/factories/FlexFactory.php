<?php

namespace Database\Factories;

use App\Models\Flex;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flex>
 */
class FlexFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isStreakBonus = fake()->boolean(20);

        return [
            'user_id' => User::factory(),
            'task_id' => Task::factory(),
            'points_earned' => fake()->randomElement([5, 10, 15, 20, 25, 50, 100]),
            'flex_message' => Flex::randomFlexMessage(),
            'is_streak_bonus' => $isStreakBonus,
            'streak_multiplier' => $isStreakBonus ? fake()->numberBetween(2, 5) : 1,
        ];
    }

    /**
     * Indicate that the flex has a streak bonus.
     */
    public function withStreakBonus(int $multiplier = 2): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_streak_bonus' => true,
            'streak_multiplier' => $multiplier,
        ]);
    }
}
