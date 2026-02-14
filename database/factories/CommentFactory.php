<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Indian Gen Z comment templates.
     *
     * @var list<string>
     */
    private array $comments = [
        'Bhai kya kaam kiya hai',
        'Slay bestie, you got this',
        'No cap this looks fire',
        'Bohot hard bhai',
        'Understood the assignment fr',
        'Lowkey obsessed with this',
        'The vibes are immaculate',
        'Period. No notes.',
        'Mummy proud hogi',
        'Sharma ji ke bete ko dikha do',
        'We love to see it yaar',
        'Built different for real',
        'Go off king/queen',
        'Grinding like a boss',
        'Padhai ho gayi finally',
        'Abhi toh party banti hai',
        'First attempt mein? Legend!',
        'Yeh toh bahut accha hai',
        'Keep it up bro',
        'Productivity king/queen',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'commentable_type' => fake()->randomElement([Project::class, Task::class]),
            'commentable_id' => fake()->numberBetween(1, 100),
            'body' => fake()->randomElement($this->comments),
        ];
    }

    /**
     * Indicate that the comment is on a project.
     */
    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes): array => [
            'commentable_type' => Project::class,
            'commentable_id' => $project->id,
        ]);
    }

    /**
     * Indicate that the comment is on a task.
     */
    public function forTask(Task $task): static
    {
        return $this->state(fn (array $attributes): array => [
            'commentable_type' => Task::class,
            'commentable_id' => $task->id,
        ]);
    }
}
