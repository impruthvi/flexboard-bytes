<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\FlexService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        protected FlexService $flexService
    ) {}

    /**
     * Display all tasks for the user across all projects.
     */
    public function index(): View
    {
        $tasks = Task::whereHas('project', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['project', 'tags'])
            ->orderBy('is_completed')
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('due_date')
            ->get();

        $pendingTasks = $tasks->where('is_completed', false);
        $completedTasks = $tasks->where('is_completed', true);

        return view('tasks.index', compact('pendingTasks', 'completedTasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        $projects = Auth::user()->projects()->where('is_archived', false)->get();

        return view('tasks.create', compact('projects'));
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('update', $project);

        $task = $project->tasks()->create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created! Ready to flex! 💪',
                'task' => $task->load('tags'),
            ]);
        }

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Task created! Ready to flex! 💪');
    }

    /**
     * Display the task.
     */
    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load(['project', 'tags', 'comments.user', 'reactions']);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the task.
     */
    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        $projects = Auth::user()->projects()->where('is_archived', false)->get();

        return view('tasks.edit', compact('task', 'projects'));
    }

    /**
     * Update the task.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated! ✨',
                'task' => $task->fresh()->load('tags'),
            ]);
        }

        return redirect()
            ->route('projects.show', $task->project)
            ->with('success', 'Task updated! ✨');
    }

    /**
     * Complete a task and award points.
     */
    public function complete(Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        if ($task->is_completed) {
            return response()->json([
                'success' => false,
                'message' => 'Task already completed!',
            ], 400);
        }

        $result = $this->flexService->completeTask($task, Auth::user());

        return response()->json([
            'success' => true,
            'message' => $result['flex_message'],
            'points_earned' => $result['points_earned'],
            'total_points' => $result['total_points'],
            'streak' => $result['streak'],
            'badge_earned' => $result['badge_earned'],
            'level_up' => $result['level_up'],
        ]);
    }

    /**
     * Uncomplete a task (undo completion).
     */
    public function uncomplete(Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        if (! $task->is_completed) {
            return response()->json([
                'success' => false,
                'message' => 'Task is not completed!',
            ], 400);
        }

        $this->flexService->uncompleteTask($task, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Task uncompleted. Points deducted.',
            'total_points' => Auth::user()->fresh()->points,
        ]);
    }

    /**
     * Soft delete the task.
     */
    public function destroy(Task $task): RedirectResponse|JsonResponse
    {
        $this->authorize('delete', $task);

        $project = $task->project;
        $task->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted! 🗑️',
            ]);
        }

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Task deleted! 🗑️');
    }
}
