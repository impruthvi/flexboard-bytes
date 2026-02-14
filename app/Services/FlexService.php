<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Flex;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class FlexService
{
    /**
     * Complete a task and award points to the user.
     *
     * @return array{
     *     flex_message: string,
     *     points_earned: int,
     *     total_points: int,
     *     streak: int,
     *     badge_earned: array|null,
     *     level_up: bool
     * }
     */
    public function completeTask(Task $task, User $user): array
    {
        $previousLevel = $user->level;

        // Calculate streak and multiplier
        $streakData = $this->calculateStreak($user);
        $streakMultiplier = $this->getStreakMultiplier($streakData['streak']);

        // Calculate points earned
        $basePoints = $task->points;
        $pointsEarned = (int) ($basePoints * $streakMultiplier);

        // Mark task as completed
        $task->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Create flex record
        $flexMessage = Flex::randomFlexMessage();
        Flex::create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'points_earned' => $pointsEarned,
            'flex_message' => $flexMessage,
            'is_streak_bonus' => $streakMultiplier > 1,
            'streak_multiplier' => $streakMultiplier,
        ]);

        // Update user points and streak
        $user->update([
            'points' => $user->points + $pointsEarned,
            'current_streak' => $streakData['streak'],
            'longest_streak' => max($user->longest_streak, $streakData['streak']),
            'last_flex_date' => now()->toDateString(),
        ]);

        // Check for badges
        $badgeEarned = $this->checkAndAwardBadges($user->fresh());

        // Check for level up
        $newLevel = $user->fresh()->level;
        $levelUp = $newLevel > $previousLevel;

        return [
            'flex_message' => $flexMessage,
            'points_earned' => $pointsEarned,
            'total_points' => $user->fresh()->points,
            'streak' => $streakData['streak'],
            'badge_earned' => $badgeEarned,
            'level_up' => $levelUp,
        ];
    }

    /**
     * Uncomplete a task and deduct points.
     */
    public function uncompleteTask(Task $task, User $user): void
    {
        // Find the flex record
        $flex = Flex::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->first();

        if ($flex) {
            // Deduct points
            $user->update([
                'points' => max(0, $user->points - $flex->points_earned),
            ]);

            // Delete the flex
            $flex->delete();
        }

        // Mark task as incomplete
        $task->update([
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }

    /**
     * Calculate the user's current streak.
     *
     * @return array{streak: int, is_new_day: bool}
     */
    protected function calculateStreak(User $user): array
    {
        $today = Carbon::today();
        $lastFlexDate = $user->last_flex_date ? Carbon::parse($user->last_flex_date) : null;

        if (! $lastFlexDate) {
            // First ever flex
            return ['streak' => 1, 'is_new_day' => true];
        }

        if ($lastFlexDate->isSameDay($today)) {
            // Already flexed today, maintain streak
            return ['streak' => $user->current_streak, 'is_new_day' => false];
        }

        if ($lastFlexDate->isSameDay($today->copy()->subDay())) {
            // Flexed yesterday, increment streak
            return ['streak' => $user->current_streak + 1, 'is_new_day' => true];
        }

        // Streak broken, reset to 1
        return ['streak' => 1, 'is_new_day' => true];
    }

    /**
     * Get the streak multiplier for bonus points.
     */
    protected function getStreakMultiplier(int $streak): float
    {
        return match (true) {
            $streak >= 30 => 2.0,   // 30+ days: 2x points
            $streak >= 14 => 1.75,  // 14+ days: 1.75x points
            $streak >= 7 => 1.5,    // 7+ days: 1.5x points
            $streak >= 3 => 1.25,   // 3+ days: 1.25x points
            default => 1.0,
        };
    }

    /**
     * Check and award any badges the user has earned.
     *
     * @return array{name: string, icon: string, description: string}|null
     */
    protected function checkAndAwardBadges(User $user): ?array
    {
        // Define badge criteria
        $badgeCriteria = [
            'first-flex' => fn () => $this->getUserCompletedTaskCount($user) >= 1,
            'getting-started' => fn () => $this->getUserCompletedTaskCount($user) >= 5,
            'task-master' => fn () => $this->getUserCompletedTaskCount($user) >= 25,
            'century' => fn () => $user->points >= 100,
            'high-roller' => fn () => $user->points >= 500,
            'legend' => fn () => $user->points >= 1000,
            'on-fire' => fn () => $user->current_streak >= 7,
            'unstoppable' => fn () => $user->current_streak >= 30,
        ];

        // Check each badge
        foreach ($badgeCriteria as $slug => $criteria) {
            // Skip if user already has this badge
            if ($user->badges()->where('slug', $slug)->exists()) {
                continue;
            }

            // Check if criteria is met
            if ($criteria()) {
                $badge = Badge::where('slug', $slug)->first();
                if ($badge) {
                    $user->badges()->attach($badge->id, ['earned_at' => now()]);

                    return [
                        'name' => $badge->name,
                        'icon' => $badge->icon,
                        'description' => $badge->description,
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Get the count of completed tasks for a user.
     */
    protected function getUserCompletedTaskCount(User $user): int
    {
        return Task::whereHas('project', fn ($q) => $q->where('user_id', $user->id))
            ->where('is_completed', true)
            ->count();
    }
}
