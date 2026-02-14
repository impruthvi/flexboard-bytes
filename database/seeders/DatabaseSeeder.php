<?php

namespace Database\Seeders;

use App\Models\Flex;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed badges and tags first
        $this->call([
            BadgeSeeder::class,
            TagSeeder::class,
        ]);

        // Create the main demo user
        $demoUser = User::factory()->create([
            'name' => 'Demo User',
            'username' => 'flexmaster',
            'email' => 'demo@flexboard.test',
            'points' => 420,
            'current_streak' => 5,
            'longest_streak' => 12,
            'last_flex_date' => now(),
        ]);

        // Create some additional users for the leaderboard
        $users = User::factory(5)->create();

        // Get all tags
        $tags = Tag::all();

        // Create projects for demo user
        $projects = [
            [
                'name' => 'Side Hustle Goals',
                'slug' => 'side-hustle-goals',
                'description' => 'Building that passive income stream fr fr',
                'color' => '#ff2d92',
                'emoji' => 'rocket',
            ],
            [
                'name' => 'Glow Up Journey',
                'slug' => 'glow-up-journey',
                'description' => 'Becoming the best version of myself no cap',
                'color' => '#00f5ff',
                'emoji' => 'sparkles',
            ],
            [
                'name' => 'Learn to Code Arc',
                'slug' => 'learn-to-code-arc',
                'description' => 'Main character energy in the tech world',
                'color' => '#bf5af2',
                'emoji' => 'brain',
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create([
                'user_id' => $demoUser->id,
                ...$projectData,
            ]);

            // Create tasks for each project
            $taskCount = rand(3, 6);
            for ($i = 0; $i < $taskCount; $i++) {
                $isCompleted = $i < rand(1, 3);

                $task = Task::factory()->create([
                    'project_id' => $project->id,
                    'is_completed' => $isCompleted,
                    'completed_at' => $isCompleted ? now()->subDays(rand(0, 7)) : null,
                    'order' => $i,
                ]);

                // Attach random tags
                $task->tags()->attach($tags->random(rand(1, 3))->pluck('id'));

                // Create flex record for completed tasks
                if ($isCompleted) {
                    Flex::create([
                        'user_id' => $demoUser->id,
                        'task_id' => $task->id,
                        'points_earned' => $task->points,
                        'flex_message' => Flex::randomFlexMessage(),
                        'is_streak_bonus' => rand(0, 1),
                        'streak_multiplier' => 1,
                    ]);
                }
            }
        }

        // Create projects for other users
        foreach ($users as $user) {
            $projectCount = rand(1, 3);
            for ($i = 0; $i < $projectCount; $i++) {
                $project = Project::factory()->create([
                    'user_id' => $user->id,
                ]);

                $taskCount = rand(2, 5);
                for ($j = 0; $j < $taskCount; $j++) {
                    $isCompleted = rand(0, 1);

                    $task = Task::factory()->create([
                        'project_id' => $project->id,
                        'is_completed' => $isCompleted,
                        'completed_at' => $isCompleted ? now()->subDays(rand(0, 14)) : null,
                        'order' => $j,
                    ]);

                    $task->tags()->attach($tags->random(rand(1, 2))->pluck('id'));

                    if ($isCompleted) {
                        Flex::create([
                            'user_id' => $user->id,
                            'task_id' => $task->id,
                            'points_earned' => $task->points,
                            'flex_message' => Flex::randomFlexMessage(),
                            'is_streak_bonus' => false,
                            'streak_multiplier' => 1,
                        ]);

                        // Update user points
                        $user->increment('points', $task->points);
                    }
                }
            }
        }

        // Give demo user some badges
        $demoUser->badges()->attach([1, 2, 3, 4], ['earned_at' => now()]);
    }
}
