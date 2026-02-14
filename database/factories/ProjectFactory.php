<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Project names with Indian Gen Z flavor.
     *
     * @var list<string>
     */
    private array $projectNames = [
        'Side Hustle Goals',
        'Glow Up Journey',
        'Main Character Arc',
        'Vibe Check Tasks',
        'No Cap Projects',
        'Slay Mode Activated',
        'Padhai Ka Time',
        'Fitness Grind',
        'Coding Sikho Bhai',
        'UPSC Prep Mode',
        'MBA Dreams',
        'Startup Banao',
        'YouTube Channel Goals',
        'Instagram Reels Grind',
        'Freelance Hustle',
        'DSA Practice Daily',
        'Campus Placement Prep',
        'CA Final Mission',
        'Gate Exam Grind',
        'JEE/NEET Warrior',
        'Foreign Study Plans',
        'Wedding Planning Chaos',
        'Gym Bro Era',
        'Cooking Skills Arc',
        'Budget Travel Plans',
    ];

    /**
     * Neon colors for projects.
     *
     * @var list<string>
     */
    private array $colors = [
        '#ff2d92', // pink
        '#00f5ff', // cyan
        '#bf5af2', // purple
        '#39ff14', // green
        '#ff6b35', // orange
        '#f7ff00', // yellow
    ];

    /**
     * Project emojis.
     *
     * @var list<string>
     */
    private array $emojis = [
        'rocket',
        'fire',
        'star',
        'sparkles',
        'zap',
        'target',
        'gem',
        'crown',
        'muscle',
        'brain',
        'books',
        'laptop',
        'money',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement($this->projectNames);

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->randomNumber(4),
            'description' => fake()->optional()->randomElement([
                'Time to lock in and grind fr fr',
                'No distractions, only dedication',
                'Mummy papa proud karna hai',
                'Building something legendary here',
                'Focus mode activated bhai',
                'Abhi nahi toh kabhi nahi',
                'Success loading... please wait',
                'Dream big, work harder',
            ]),
            'color' => fake()->randomElement($this->colors),
            'emoji' => fake()->randomElement($this->emojis),
            'is_archived' => false,
        ];
    }

    /**
     * Indicate that the project is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_archived' => true,
        ]);
    }
}
