<?php

namespace Database\Factories;

use App\Models\Flex;
use App\Models\Project;
use App\Models\Reaction;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
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
            'reactionable_type' => fake()->randomElement([Project::class, Task::class, Flex::class]),
            'reactionable_id' => fake()->numberBetween(1, 100),
            'emoji' => fake()->randomElement(Reaction::EMOJIS),
        ];
    }

    /**
     * Indicate that the reaction is on a flex.
     */
    public function forFlex(Flex $flex): static
    {
        return $this->state(fn (array $attributes): array => [
            'reactionable_type' => Flex::class,
            'reactionable_id' => $flex->id,
        ]);
    }

    /**
     * Indicate that the reaction is on a task.
     */
    public function forTask(Task $task): static
    {
        return $this->state(fn (array $attributes): array => [
            'reactionable_type' => Task::class,
            'reactionable_id' => $task->id,
        ]);
    }
}
