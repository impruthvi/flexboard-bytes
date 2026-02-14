<?php

namespace App\Http\Controllers;

use App\Models\Flex;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with user stats and recent activity.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get user's projects with task counts
        $projects = $user->projects()
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->where('is_completed', true);
            }])
            ->where('is_archived', false)
            ->latest()
            ->take(3)
            ->get();

        // Get pending tasks across all projects
        $pendingTasks = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('is_archived', false);
        })
            ->with('project')
            ->where('is_completed', false)
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Get recent flexes (completed tasks)
        $recentFlexes = Flex::where('user_id', $user->id)
            ->with('task.project')
            ->latest()
            ->take(5)
            ->get();

        // Get leaderboard (top 5 users)
        $leaderboard = User::query()
            ->select(['id', 'name', 'username', 'avatar', 'points', 'current_streak'])
            ->orderByDesc('points')
            ->take(5)
            ->get()
            ->map(function ($user, $index) {
                $user->rank = $index + 1;

                return $user;
            });

        // Get current user's rank
        $currentUserRank = User::where('points', '>', $user->points)->count() + 1;

        // Get next badge to earn
        $nextBadge = $this->getNextBadge($user);

        return view('dashboard', compact(
            'projects',
            'pendingTasks',
            'recentFlexes',
            'leaderboard',
            'currentUserRank',
            'nextBadge'
        ));
    }

    /**
     * Get the next badge the user can earn.
     */
    protected function getNextBadge(User $user): ?array
    {
        $earnedBadgeSlugs = $user->badges()->pluck('slug')->toArray();

        $badges = [
            ['slug' => 'first-flex', 'name' => 'First Flex', 'description' => 'Complete your first task', 'progress' => $this->getCompletedTaskCount($user), 'target' => 1],
            ['slug' => 'getting-started', 'name' => 'Getting Started', 'description' => 'Complete 5 tasks', 'progress' => $this->getCompletedTaskCount($user), 'target' => 5],
            ['slug' => 'task-master', 'name' => 'Task Master', 'description' => 'Complete 25 tasks', 'progress' => $this->getCompletedTaskCount($user), 'target' => 25],
            ['slug' => 'century', 'name' => 'Century', 'description' => 'Earn 100 points', 'progress' => $user->points, 'target' => 100],
            ['slug' => 'high-roller', 'name' => 'High Roller', 'description' => 'Earn 500 points', 'progress' => $user->points, 'target' => 500],
            ['slug' => 'on-fire', 'name' => 'On Fire', 'description' => '7-day streak', 'progress' => $user->current_streak, 'target' => 7],
        ];

        foreach ($badges as $badge) {
            if (! in_array($badge['slug'], $earnedBadgeSlugs)) {
                return $badge;
            }
        }

        return null;
    }

    /**
     * Get the count of completed tasks for the user.
     */
    protected function getCompletedTaskCount(User $user): int
    {
        return Task::whereHas('project', fn ($q) => $q->where('user_id', $user->id))
            ->where('is_completed', true)
            ->count();
    }
}
