<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Comment;
use App\Models\Flex;
use App\Models\Project;
use App\Models\Reaction;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * LESSON: N+1 Query Problem Demo Seeder (Branch 09)
 *
 * This seeder creates enough data to clearly demonstrate the N+1 problem.
 * Visit /n-plus-one/* routes after seeding to see queries explode!
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Indian names for realistic fake data.
     *
     * @var array<string>
     */
    private array $indianNames = [
        'Aarav Sharma',
        'Priya Patel',
        'Vihaan Gupta',
        'Ananya Singh',
        'Aditya Kumar',
        'Diya Reddy',
        'Arjun Nair',
        'Saanvi Iyer',
        'Vivaan Mehta',
        'Ishaan Verma',
    ];

    /**
     * Project names with Gen Z vibes.
     *
     * @var array<string>
     */
    private array $projectNames = [
        'Sigma Startup MVP',
        'Chai Break Tracker',
        'Gym Bro Fitness App',
        'Biryani Delivery Zone',
        'Cricket Score Flex',
        'Meme Generator Pro',
        'Chai Pe Charcha App',
        'Jugaad Solutions',
        'Desi Dating App',
        'Paisa Tracker Lite',
    ];

    /**
     * Task titles with work humor.
     *
     * @var array<string>
     */
    private array $taskTitles = [
        'Fix that one bug nobody understands',
        'Add dark mode (finally)',
        'Make the app load faster',
        'Write tests we keep avoiding',
        'Refactor the spaghetti code',
        'Add loading spinners everywhere',
        'Update dependencies pls',
        'Fix the CSS that works somehow',
        'Add proper error messages',
        'Remove console.log statements',
        'Add actual documentation',
        'Optimize database queries',
        'Fix mobile responsiveness',
        'Add proper validation',
        'Setup CI/CD pipeline',
    ];

    /**
     * Seed the application database.
     */
    public function run(): void
    {
        // Create 10 users
        $users = collect($this->indianNames)->map(function ($name) {
            return User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)).'@flexboard.test',
                'password' => bcrypt('password'),
            ]);
        });

        // Create tags
        $tags = collect([
            ['name' => 'urgent', 'slug' => 'urgent', 'color' => '#ef4444'],
            ['name' => 'feature', 'slug' => 'feature', 'color' => '#22c55e'],
            ['name' => 'bug', 'slug' => 'bug', 'color' => '#f59e0b'],
            ['name' => 'refactor', 'slug' => 'refactor', 'color' => '#8b5cf6'],
            ['name' => 'docs', 'slug' => 'docs', 'color' => '#06b6d4'],
        ])->map(fn ($tag) => Tag::create($tag));

        // Create badges
        $badges = collect([
            ['name' => 'First Blood', 'description' => 'Completed first task', 'icon' => 'trophy', 'rarity' => 'common'],
            ['name' => 'On Fire', 'description' => 'Completed 10 tasks', 'icon' => 'fire', 'rarity' => 'rare'],
            ['name' => 'Sigma Dev', 'description' => 'Completed 50 tasks', 'icon' => 'rocket', 'rarity' => 'legendary'],
        ])->map(fn ($badge) => Badge::create($badge));

        // Create projects for each user (10 users x 10 projects = 100 projects)
        // This creates enough data to clearly show N+1 problem!
        $users->each(function ($user, $userIndex) use ($tags, $badges) {
            // Each user gets 2-3 projects
            collect($this->projectNames)
                ->random(rand(2, 3))
                ->each(function ($projectName, $projectIndex) use ($user, $tags, $userIndex) {
                    // Use relationship to create project (sets user_id automatically)
                    // Append user index to make names unique
                    $project = $user->projects()->create([
                        'name' => $projectName.' '.($userIndex + 1),
                        'description' => "A project by {$user->name} - no cap it's gonna be fire!",
                    ]);

                    // Each project gets 3-5 tasks
                    collect($this->taskTitles)
                        ->random(rand(3, 5))
                        ->each(function ($taskTitle) use ($project, $user, $tags) {
                            $task = Task::create([
                                'project_id' => $project->id,
                                'title' => $taskTitle,
                                'description' => 'This task needs to get done ASAP bhai!',
                                'priority' => fake()->randomElement(['low', 'medium', 'high']),
                                'difficulty' => fake()->randomElement(['easy', 'medium', 'hard']),
                                'points' => rand(10, 100),
                                'is_completed' => fake()->boolean(30),
                                'completed_at' => fake()->boolean(30) ? now() : null,
                            ]);

                            // Attach random tags
                            $task->tags()->attach(
                                $tags->random(rand(1, 3))->pluck('id')
                            );

                            // Add comments to some tasks
                            if (fake()->boolean(60)) {
                                Comment::create([
                                    'user_id' => $user->id,
                                    'body' => fake()->randomElement(Comment::COMMENT_TEMPLATES),
                                    'commentable_id' => $task->id,
                                    'commentable_type' => Task::class,
                                ]);
                            }

                            // Add reactions to some tasks
                            if (fake()->boolean(70)) {
                                Reaction::create([
                                    'user_id' => $user->id,
                                    'emoji' => fake()->randomElement(array_keys(Reaction::EMOJIS)),
                                    'reactionable_id' => $task->id,
                                    'reactionable_type' => Task::class,
                                ]);
                            }

                            // Create flex for completed tasks
                            if ($task->is_completed) {
                                Flex::create([
                                    'user_id' => $user->id,
                                    'task_id' => $task->id,
                                    'message' => fake()->randomElement(Flex::FLEX_MESSAGES),
                                    'points_earned' => $task->points,
                                ]);
                            }
                        });
                });

            // Award random badges to users
            $badges->random(rand(1, 2))->each(function ($badge) use ($user) {
                $user->badges()->attach($badge->id, [
                    'earned_at' => now()->subDays(rand(1, 30)),
                    'notes' => 'Earned for being a sigma dev!',
                ]);
            });
        });
    }
}
