<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * LESSON: N+1 Query Problem (Branch 09)
 *
 * This controller demonstrates the N+1 query problem - a common
 * performance issue in Laravel applications.
 *
 * WARNING: This code is intentionally BAD to show what NOT to do!
 * The next branch (10-eager-loading) shows how to fix these issues.
 */
class NplusOneDemoController extends Controller
{
    /**
     * BAD EXAMPLE: N+1 when listing projects with their owners.
     *
     * Problem: For each project, Laravel makes a SEPARATE query
     * to fetch the user. 10 projects = 11 queries!
     *
     * @return array<string, mixed>
     */
    public function projectsWithUsers()
    {
        // Enable query logging
        DB::enableQueryLog();

        // BAD: No eager loading - causes N+1!
        $projects = Project::all();

        $results = [];
        foreach ($projects as $project) {
            // Each access to $project->user triggers a NEW query!
            $results[] = [
                'project' => $project->name,
                'owner' => $project->user->name, // N+1 HERE!
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'problem' => 'N+1 Query! Each project triggers a separate user query.',
        ];
    }

    /**
     * BAD EXAMPLE: N+1 when listing tasks with their projects AND users.
     *
     * Problem: This is even worse - N+1 for projects AND users!
     * 10 tasks = 1 + 10 (projects) + 10 (users) = 21 queries!
     *
     * @return array<string, mixed>
     */
    public function tasksWithProjectsAndUsers()
    {
        DB::enableQueryLog();

        // BAD: No eager loading!
        $tasks = Task::all();

        $results = [];
        foreach ($tasks as $task) {
            // Each triggers TWO extra queries!
            $results[] = [
                'task' => $task->title,
                'project' => $task->project->name,         // N+1 for project
                'owner' => $task->project->user->name,     // N+1 for user
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'problem' => 'Double N+1! Each task triggers queries for project AND user.',
        ];
    }

    /**
     * BAD EXAMPLE: N+1 when loading nested relationships.
     *
     * Problem: Users -> Projects -> Tasks creates multiple levels of N+1!
     *
     * @return array<string, mixed>
     */
    public function usersWithProjectsAndTasks()
    {
        DB::enableQueryLog();

        // BAD: No eager loading for the nested relationships!
        $users = User::all();

        $results = [];
        foreach ($users as $user) {
            $userProjects = [];
            foreach ($user->projects as $project) { // N+1 for projects
                $projectTasks = [];
                foreach ($project->tasks as $task) { // N+1 for tasks!
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
            'problem' => 'Nested N+1! Users -> Projects -> Tasks = explosion of queries!',
        ];
    }

    /**
     * BAD EXAMPLE: N+1 in blade templates.
     *
     * This returns data for a Blade view that would trigger N+1.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // BAD: No eager loading - the Blade template will trigger N+1
        $projects = Project::all();

        return view('n-plus-one-demo', compact('projects'));
    }

    /**
     * BAD EXAMPLE: N+1 with counts.
     *
     * Problem: Using count() in a loop causes N+1!
     *
     * @return array<string, mixed>
     */
    public function projectsWithTaskCounts()
    {
        DB::enableQueryLog();

        $projects = Project::all();

        $results = [];
        foreach ($projects as $project) {
            // Each count() triggers a separate COUNT query!
            $results[] = [
                'project' => $project->name,
                'task_count' => $project->tasks()->count(),    // N+1!
                'completed' => $project->tasks()->completed()->count(), // Another N+1!
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'problem' => 'Count N+1! Each project triggers TWO count queries.',
        ];
    }

    /**
     * BAD EXAMPLE: N+1 with polymorphic relationships.
     *
     * @return array<string, mixed>
     */
    public function tasksWithCommentsAndReactions()
    {
        DB::enableQueryLog();

        $tasks = Task::all();

        $results = [];
        foreach ($tasks as $task) {
            $results[] = [
                'task' => $task->title,
                'comments' => $task->comments->pluck('body'),     // N+1!
                'reactions' => $task->reactions->pluck('emoji'),   // N+1!
            ];
        }

        return [
            'data' => $results,
            'queries' => DB::getQueryLog(),
            'query_count' => count(DB::getQueryLog()),
            'problem' => 'Polymorphic N+1! Each task triggers queries for comments AND reactions.',
        ];
    }
}
