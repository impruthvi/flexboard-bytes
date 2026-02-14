<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement(['trophy', 'star', 'fire', 'rocket', 'crown', 'gem', 'medal', 'zap']),
            'points_required' => fake()->randomElement([0, 50, 100, 250, 500, 1000]),
            'color' => fake()->randomElement(['#ff2d92', '#00f5ff', '#bf5af2', '#39ff14', '#f7ff00']),
            'rarity' => fake()->randomElement(['common', 'rare', 'epic', 'legendary']),
        ];
    }

    /**
     * Indicate that the badge is common.
     */
    public function common(): static
    {
        return $this->state(fn (array $attributes): array => [
            'rarity' => 'common',
            'points_required' => fake()->numberBetween(0, 50),
        ]);
    }

    /**
     * Indicate that the badge is rare.
     */
    public function rare(): static
    {
        return $this->state(fn (array $attributes): array => [
            'rarity' => 'rare',
            'points_required' => fake()->numberBetween(100, 250),
        ]);
    }

    /**
     * Indicate that the badge is epic.
     */
    public function epic(): static
    {
        return $this->state(fn (array $attributes): array => [
            'rarity' => 'epic',
            'points_required' => fake()->numberBetween(500, 1000),
        ]);
    }

    /**
     * Indicate that the badge is legendary.
     */
    public function legendary(): static
    {
        return $this->state(fn (array $attributes): array => [
            'rarity' => 'legendary',
            'points_required' => fake()->numberBetween(2000, 5000),
        ]);
    }
}
