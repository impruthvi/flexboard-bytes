<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the user's projects.
     */
    public function index(): View
    {
        $projects = Auth::user()
            ->projects()
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->where('is_completed', true);
            }])
            ->where('is_archived', false)
            ->latest()
            ->get();

        $archivedProjects = Auth::user()
            ->projects()
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->where('is_completed', true);
            }])
            ->where('is_archived', true)
            ->latest()
            ->get();

        return view('projects.index', compact('projects', 'archivedProjects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(6);

        $project = Auth::user()->projects()->create($validated);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project created! Time to flex on those tasks! 🚀');
    }

    /**
     * Display the project with its tasks.
     */
    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load([
            'tasks' => function ($query) {
                $query->orderBy('is_completed')
                    ->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")
                    ->orderBy('order');
            },
            'tasks.tags',
        ]);

        $pendingTasks = $project->tasks->where('is_completed', false);
        $completedTasks = $project->tasks->where('is_completed', true);

        return view('projects.show', compact('project', 'pendingTasks', 'completedTasks'));
    }

    /**
     * Show the form for editing the project.
     */
    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the project.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project updated successfully! ✨');
    }

    /**
     * Archive/Unarchive the project.
     */
    public function archive(Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $project->update(['is_archived' => ! $project->is_archived]);

        $message = $project->is_archived
            ? 'Project archived. You can restore it anytime!'
            : 'Project restored! Back to flexing! 💪';

        return redirect()
            ->route('projects.index')
            ->with('success', $message);
    }

    /**
     * Soft delete the project.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted. Gone but not forgotten! 🗑️');
    }
}
