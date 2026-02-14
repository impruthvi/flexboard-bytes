<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * LESSON: Eager Loading - The N+1 Fix! (Branch 10)
 *
 * This controller shows the FIXED versions of the N+1 queries
 * from Branch 09. Compare query counts to see the improvement!
 *
 * Key Methods:
 * - with() - Eager load when querying
 * - withCount() - Efficient counts without loading models
 * - load() - Lazy eager loading after query
 */
class EagerLoadingDemoController extends Controller
{
    /**
     * FIXED: Using with() to eager load users.
     *
     * Before: 1 + N queries (28 for 27 projects)
     * After: 2 queries (always!)
     *
     * @return array<string, mixed>
     */
    public function projectsWithUsers()
    {
        DB::enableQueryLog();

        // FIXED: with() eager loads the user relationship!
        $projects = Project::with('user')->get();

        $results = [];
        foreach ($projects as $project) {
            // No additional queries - user is already loaded!
            $results[] = [
                'project' => $project->name,
                'owner' => $project->user->name,
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'fix' => 'Used with("user") - only 2 queries regardless of project count!',
        ];
    }

    /**
     * FIXED: Eager loading nested relationships.
     *
     * Before: 1 + N + N queries (up to 55 queries!)
     * After: 3 queries (always!)
     *
     * @return array<string, mixed>
     */
    public function tasksWithProjectsAndUsers()
    {
        DB::enableQueryLog();

        // FIXED: Eager load nested relationships with dot notation!
        $tasks = Task::with('project.user')->get();

        $results = [];
        foreach ($tasks as $task) {
            // All relationships already loaded!
            $results[] = [
                'task' => $task->title,
                'project' => $task->project->name,
                'owner' => $task->project->user->name,
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'fix' => 'Used with("project.user") - dot notation for nested relations!',
        ];
    }

    /**
     * FIXED: Eager loading multiple levels.
     *
     * Before: Could be 100+ queries!
     * After: 3 queries (always!)
     *
     * @return array<string, mixed>
     */
    public function usersWithProjectsAndTasks()
    {
        DB::enableQueryLog();

        // FIXED: Eager load entire tree!
        $users = User::with('projects.tasks')->get();

        $results = [];
        foreach ($users as $user) {
            $userProjects = [];
            foreach ($user->projects as $project) {
                $projectTasks = [];
                foreach ($project->tasks as $task) {
                    $projectTasks[] = $task->title;
                }
                $userProjects[] = [
                    'name' => $project->name,
                    'tasks' => $projectTasks,
                ];
            }
            $results[] = [
                'user' => $user->name,
                'projects' => $userProjects,
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'fix' => 'Used with("projects.tasks") - one call loads everything!',
        ];
    }

    /**
     * FIXED: Using withCount() for efficient counts.
     *
     * Before: 1 + 2N queries (every count was a separate query!)
     * After: 1 query (counts included in main query!)
     *
     * @return array<string, mixed>
     */
    public function projectsWithTaskCounts()
    {
        DB::enableQueryLog();

        // FIXED: withCount() adds counts as attributes!
        $projects = Project::withCount([
            'tasks',
            'tasks as completed_tasks_count' => function ($query) {
                $query->where('is_completed', true);
            },
        ])->get();

        $results = [];
        foreach ($projects as $project) {
            // Counts are attributes - no extra queries!
            $results[] = [
                'project' => $project->name,
                'task_count' => $project->tasks_count,        // Added by withCount
                'completed' => $project->completed_tasks_count, // Custom alias
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'fix' => 'Used withCount() - counts as attributes, not queries!',
        ];
    }

    /**
     * FIXED: Eager loading polymorphic relationships.
     *
     * Before: 1 + 2N queries
     * After: 3 queries
     *
     * @return array<string, mixed>
     */
    public function tasksWithCommentsAndReactions()
    {
        DB::enableQueryLog();

        // FIXED: Eager load multiple relationships at once!
        $tasks = Task::with(['comments', 'reactions'])->get();

        $results = [];
        foreach ($tasks as $task) {
            $results[] = [
                'task' => $task->title,
                'comments' => $task->comments->pluck('body'),
                'reactions' => $task->reactions->pluck('emoji'),
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'fix' => 'Used with(["comments", "reactions"]) - array for multiple!',
        ];
    }

    /**
     * FIXED: Dashboard view with eager loading.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // FIXED: Eager load everything the view needs!
        $projects = Project::with(['user', 'tasks'])->get();

        return view('eager-loading-demo', compact('projects'));
    }

    /**
     * BONUS: Using load() for lazy eager loading.
     *
     * Sometimes you already have a model and need to load relations later.
     *
     * @return array<string, mixed>
     */
    public function lazyEagerLoading()
    {
        DB::enableQueryLog();

        // Get a single project (maybe from a form submission)
        $project = Project::first();

        // Later, we decide we need the tasks and comments
        // load() eager loads AFTER the initial query
        $project->load(['tasks.comments', 'user']);

        $data = [
            'project' => $project->name,
            'owner' => $project->user->name,
            'tasks' => $project->tasks->map(function ($task) {
                return [
                    'title' => $task->title,
                    'comments' => $task->comments->pluck('body'),
                ];
            }),
        ];

        return [
            'data' => $data,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'fix' => 'Used load() for lazy eager loading after initial query!',
        ];
    }

    /**
     * BONUS: Constraining eager loaded relationships.
     *
     * Sometimes you don't want ALL related models, just some.
     *
     * @return array<string, mixed>
     */
    public function constrainedEagerLoading()
    {
        DB::enableQueryLog();

        // Eager load ONLY incomplete tasks for each project
        $projects = Project::with([
            'user',
            'tasks' => function ($query) {
                $query->where('is_completed', false)
                    ->orderBy('priority', 'desc');
            },
        ])->get();

        $results = [];
        foreach ($projects as $project) {
            $results[] = [
                'project' => $project->name,
                'owner' => $project->user->name,
                'incomplete_tasks' => $project->tasks->pluck('title'),
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'fix' => 'Constrained eager loading - filter related models in the query!',
        ];
    }

    /**
     * Compare N+1 vs Eager Loading side by side.
     *
     * @return array<string, mixed>
     */
    public function compare()
    {
        // Test N+1 (bad)
        DB::enableQueryLog();
        $projects1 = Project::all();
        foreach ($projects1 as $p) {
            $p->user->name;
        }
        $badQueries = count(DB::getQueryLog());

        // Reset and test eager loading (good)
        DB::flushQueryLog();
        DB::enableQueryLog();
        $projects2 = Project::with('user')->get();
        foreach ($projects2 as $p) {
            $p->user->name;
        }
        $goodQueries = count(DB::getQueryLog());

        return [
            'project_count' => $projects1->count(),
            'n_plus_one_queries' => $badQueries,
            'eager_loading_queries' => $goodQueries,
            'improvement' => round($badQueries / $goodQueries, 1).'x faster',
            'message' => "Reduced {$badQueries} queries to just {$goodQueries}!",
        ];
    }
}
