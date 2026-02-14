<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Tag names.
     *
     * @var list<string>
     */
    private array $tagNames = [
        'urgent',
        'chill',
        'grind',
        'self-care',
        'hustle',
        'creative',
        'learning',
        'health',
        'social',
        'money',
        'mindset',
        'goals',
        'daily',
        'weekly',
        'monthly',
    ];

    /**
     * Tag colors.
     *
     * @var list<string>
     */
    private array $colors = [
        '#ff2d92',
        '#00f5ff',
        '#bf5af2',
        '#39ff14',
        '#ff6b35',
        '#f7ff00',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement($this->tagNames);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'color' => fake()->randomElement($this->colors),
        ];
    }
}
