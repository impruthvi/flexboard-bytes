<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Task titles with Indian Gen Z flavor.
     *
     * @var list<string>
     */
    private array $taskTitles = [
        'Actually respond to WhatsApp messages',
        'Call mummy back finally',
        'Complete DSA problems (at least try)',
        'Gym jaana hai bhai',
        'Stop scrolling Instagram reels',
        'Finish that Udemy course',
        'Apply for internships',
        'Update LinkedIn profile',
        'Practice coding daily',
        'Read that book gathering dust',
        'Drink 8 glasses paani',
        'Sleep before 12 AM challenge',
        'Clean room before mummy sees',
        'Budget track karo yaar',
        'Learn new skill this week',
        'Network with seniors',
        'Complete assignment before deadline',
        'Prepare for viva',
        'Watch lecture recordings',
        'Submit project report',
        'Practice mock interviews',
        'Update resume ASAP',
        'Apply for that scholarship',
        'Join coding contest',
        'Attend webinar',
        'Send cold emails',
        'Work on side project',
        'Learn Git properly this time',
        'Document the code bro',
        'Fix those bugs finally',
        'Touch grass for 30 mins',
        'Meditate karle thoda',
        'Call relatives (ugh)',
        'Pay bills on time',
        'Organize Google Drive mess',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isCompleted = fake()->boolean(30);

        return [
            'project_id' => Project::factory(),
            'title' => fake()->randomElement($this->taskTitles),
            'description' => fake()->optional(0.6)->randomElement([
                'Bahut important hai yeh',
                'Deadline aa rahi hai bhai',
                'Kal se pakka karunga',
                'Procrastinate mat kar',
                'Mummy ne bola hai',
                'Interview ke liye zaruri',
                'Career ke liye must hai',
                'Health is wealth yaad rakh',
                null,
            ]),
            'points' => fake()->randomElement([5, 10, 15, 20, 25, 50, 100]),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'difficulty' => fake()->randomElement(['easy', 'medium', 'hard', 'legendary']),
            'is_completed' => $isCompleted,
            'completed_at' => $isCompleted ? fake()->dateTimeBetween('-30 days', 'now') : null,
            'due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+30 days'),
            'order' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_completed' => true,
            'completed_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the task is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate that the task is urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes): array => [
            'priority' => 'urgent',
            'due_date' => fake()->dateTimeBetween('now', '+3 days'),
        ]);
    }

    /**
     * Indicate that the task is legendary difficulty.
     */
    public function legendary(): static
    {
        return $this->state(fn (array $attributes): array => [
            'difficulty' => 'legendary',
            'points' => 100,
        ]);
    }
}
