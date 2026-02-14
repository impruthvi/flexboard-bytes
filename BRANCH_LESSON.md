# Branch 10: Eager Loading - The N+1 Fix!

## Learning Objectives

By the end of this lesson, you will understand:
- How to use `with()` to eager load relationships
- How to use `withCount()` for efficient counts
- How to use `load()` for lazy eager loading
- How to constrain eager loaded relationships
- Best practices for optimizing Eloquent queries

---

## The Solution: Eager Loading

Eager loading tells Laravel: "Get all the related data **in advance** with just a few queries."

### Before (N+1 - Bad)

```php
// 1 query to get projects
$projects = Project::all();

foreach ($projects as $project) {
    // N additional queries!
    echo $project->user->name;
}
// Total: 1 + N queries
```

### After (Eager Loading - Good)

```php
// 2 queries total, regardless of N!
$projects = Project::with('user')->get();

foreach ($projects as $project) {
    // No additional queries - already loaded!
    echo $project->user->name;
}
// Total: 2 queries (always!)
```

---

## Method 1: `with()` - Eager Load When Querying

The most common method. Load relationships at query time.

### Basic Usage

```php
// Single relationship
$projects = Project::with('user')->get();

// Multiple relationships
$tasks = Task::with(['project', 'comments', 'reactions'])->get();

// Nested relationships (dot notation)
$tasks = Task::with('project.user')->get();

// Multiple nested relationships
$users = User::with(['projects.tasks', 'comments'])->get();
```

### FlexBoard Examples

```php
// Load user for each project
$projects = Project::with('user')->get();

// Load project AND its user for each task
$tasks = Task::with('project.user')->get();

// Load the entire tree
$users = User::with('projects.tasks')->get();

// Load polymorphic relationships
$tasks = Task::with(['comments', 'reactions'])->get();
```

---

## Method 2: `withCount()` - Efficient Counts

Instead of loading entire relationships just to count them, use `withCount()`.

### The Problem

```php
// BAD: Loads ALL tasks just to count them
$projects = Project::with('tasks')->get();
foreach ($projects as $project) {
    echo $project->tasks->count(); // Had to load ALL tasks!
}
```

### The Solution

```php
// GOOD: Counts in the query itself
$projects = Project::withCount('tasks')->get();
foreach ($projects as $project) {
    echo $project->tasks_count; // It's an attribute now!
}
```

### Advanced withCount

```php
// Count with conditions
$projects = Project::withCount([
    'tasks',                              // All tasks
    'tasks as completed_count' => function ($query) {
        $query->where('is_completed', true);
    },
    'tasks as pending_count' => function ($query) {
        $query->where('is_completed', false);
    },
])->get();

// Access the counts
foreach ($projects as $project) {
    echo $project->tasks_count;     // Total
    echo $project->completed_count; // Completed only
    echo $project->pending_count;   // Pending only
}
```

---

## Method 3: `load()` - Lazy Eager Loading

Sometimes you already have a model and need to load relationships later.

### When to Use

```php
// You already fetched the model
$project = Project::find($id);

// Some condition determines what to load
if ($showTasks) {
    $project->load('tasks');
}

if ($showComments) {
    $project->load('tasks.comments');
}
```

### With Multiple Relationships

```php
$project = Project::first();

// Load multiple relationships at once
$project->load(['user', 'tasks.comments', 'tasks.reactions']);
```

### `loadCount()` - Lazy Count Loading

```php
$project = Project::first();

// Add counts to an already-loaded model
$project->loadCount(['tasks', 'comments']);

echo $project->tasks_count;
echo $project->comments_count;
```

---

## Method 4: Constrained Eager Loading

You don't always want ALL related records. Filter them!

### Basic Constraints

```php
// Only load incomplete tasks, ordered by priority
$projects = Project::with([
    'tasks' => function ($query) {
        $query->where('is_completed', false)
              ->orderBy('priority', 'desc');
    },
])->get();
```

### Multiple Constrained Relationships

```php
$projects = Project::with([
    'user',  // No constraints
    'tasks' => function ($query) {
        $query->where('is_completed', false);
    },
    'comments' => function ($query) {
        $query->latest()->limit(5);  // Only 5 most recent
    },
])->get();
```

### Combining with withCount

```php
$projects = Project::with(['user'])
    ->withCount(['tasks', 'comments'])
    ->get();
```

---

## FlexBoard Demo Routes

Visit these URLs to see eager loading in action:

```
GET /eager-loading/projects     # with('user')
GET /eager-loading/tasks        # with('project.user')
GET /eager-loading/users        # with('projects.tasks')
GET /eager-loading/counts       # withCount()
GET /eager-loading/polymorphic  # with(['comments', 'reactions'])
GET /eager-loading/lazy         # load() example
GET /eager-loading/constrained  # Filtered eager loading
GET /eager-loading/compare      # Side-by-side comparison
GET /eager-loading/dashboard    # Fixed Blade template
```

Compare with Branch 09's N+1 routes to see the difference!

---

## Query Count Comparison

| Scenario | N+1 Queries | Eager Loading | Improvement |
|----------|-------------|---------------|-------------|
| 27 projects with users | 28 | 2 | 14x |
| 103 tasks with project.user | ~207 | 3 | 69x |
| 10 users -> projects -> tasks | ~140 | 3 | 46x |
| Project counts (tasks + completed) | ~55 | 1 | 55x |

---

## Best Practices

### 1. Always Eager Load in Controllers

```php
// GOOD: Controller handles data loading
public function index()
{
    $projects = Project::with(['user', 'tasks'])->get();
    return view('projects.index', compact('projects'));
}
```

### 2. Use `$with` for Always-Needed Relationships

```php
// In your Model
class Project extends Model
{
    // Always eager load these
    protected $with = ['user'];
}
```

> **Warning**: Use sparingly! This loads the relationship on EVERY query.

### 3. Enable Strict Mode in Development

```php
// In AppServiceProvider::boot()
use Illuminate\Database\Eloquent\Model;

public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());
}
```

This throws an exception when you forget to eager load!

### 4. Use API Resources with Eager Loading

```php
// Controller
public function index()
{
    $projects = Project::with(['user', 'tasks'])->get();
    return ProjectResource::collection($projects);
}

// Resource - no N+1 because data is eager loaded!
class ProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'owner' => $this->user->name,
            'task_count' => $this->tasks->count(),
        ];
    }
}
```

---

## Common Patterns

### Pattern 1: Dashboard Data

```php
// Load everything the dashboard needs
$user = User::with([
    'projects' => function ($query) {
        $query->withCount('tasks')
              ->latest()
              ->limit(5);
    },
    'flexes' => fn ($q) => $q->latest()->limit(10),
])->find($userId);
```

### Pattern 2: Detail Page

```php
// Load all related data for a single model
$project = Project::with([
    'user',
    'tasks.tags',
    'tasks.comments.user',
    'tasks.reactions',
])->findOrFail($id);
```

### Pattern 3: API Listing

```php
// Paginated with eager loading
$projects = Project::with('user')
    ->withCount('tasks')
    ->latest()
    ->paginate(20);
```

---

## The `EagerLoadingDemoController`

This branch includes a controller with FIXED versions of all N+1 examples.

```php
class EagerLoadingDemoController extends Controller
{
    // Compare these with NplusOneDemoController!
    
    public function projectsWithUsers()
    {
        // FIXED: 2 queries instead of 28
        $projects = Project::with('user')->get();
        // ...
    }
    
    public function projectsWithTaskCounts()
    {
        // FIXED: 1 query instead of 55
        $projects = Project::withCount([
            'tasks',
            'tasks as completed_tasks_count' => fn ($q) => $q->where('is_completed', true),
        ])->get();
        // ...
    }
}
```

---

## Hands-On Exercise

1. Start the server and open two browser tabs:

```bash
php artisan serve
```

2. Tab 1: Visit `/n-plus-one/projects`
   - Note the query count (should be ~28)

3. Tab 2: Visit `/eager-loading/projects`
   - Note the query count (should be 2)

4. Compare `/n-plus-one/users` vs `/eager-loading/users`
   - Watch the dramatic difference!

5. Try `/eager-loading/compare` for a side-by-side analysis

---

## Quick Reference

| Method | When to Use | Example |
|--------|-------------|---------|
| `with()` | Loading relationships at query time | `Post::with('author')->get()` |
| `withCount()` | Need counts, not full models | `Post::withCount('comments')->get()` |
| `load()` | Already have model, need relations | `$post->load('comments')` |
| `loadCount()` | Already have model, need counts | `$post->loadCount('comments')` |

---

## What's Next?

The next branch (`11-complete-app`) brings everything together into a polished, production-ready application with:
- All Eloquent best practices applied
- Complete test coverage
- Performance optimizations
- Final UI polish

```bash
git checkout 11-complete-app
```

---

## Remember

> "with() is your best friend. Use it everywhere."

Every time you query models that will access relationships, reach for `with()` first!

```php
// Make this your default pattern:
Model::with(['relationship'])->get();
```
