<x-learn-layout
    :lessons="$lessons"
    :currentTopic="$currentTopic"
    :currentLesson="$currentLesson"
    :previousTopic="$previousTopic"
    :previousLesson="$previousLesson"
    :nextTopic="$nextTopic"
    :nextLesson="$nextLesson"
    :currentIndex="$currentIndex"
    :totalLessons="$totalLessons"
>
    {{-- Learning Objectives --}}
    <section class="mb-12">
        <div class="glass-card p-6">
            <h2 class="font-display text-xl font-bold text-neon-cyan mb-4 flex items-center gap-2">
                <span>🎯</span> Learning Objectives
            </h2>
            <ul class="space-y-2 text-white/80">
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to use <code class="text-neon-pink">with()</code> to eager load relationships</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to use <code class="text-neon-pink">withCount()</code> for efficient counts</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to use <code class="text-neon-pink">load()</code> for lazy eager loading</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Best practices for optimizing Eloquent queries</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- The Solution --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">The Solution: Eager Loading</h2>

        <p class="text-white/70 mb-6">
            Eager loading tells Laravel: "Get all the related data <strong class="text-neon-green">in advance</strong> with just a few queries."
        </p>

        <div class="grid md:grid-cols-2 gap-6">
            {{-- Before --}}
            <div class="glass-card p-6 border-neon-pink/30">
                <h3 class="font-semibold text-neon-pink mb-4">Before (N+1 - Bad)</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">// 1 query to get projects
$projects = Project::all();

foreach ($projects as $project) {
    // N additional queries!
    echo $project->user->name;
}
// Total: 1 + N queries</code></pre>
                </div>
            </div>

            {{-- After --}}
            <div class="glass-card p-6 border-neon-green/30">
                <h3 class="font-semibold text-neon-green mb-4">After (Eager Loading - Good)</h3>
                <div class="code-right">
                    <pre><code class="language-php">// 2 queries total, regardless of N!
$projects = Project::with('user')->get();

foreach ($projects as $project) {
    // No additional queries!
    echo $project->user->name;
}
// Total: 2 queries (always!)</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Method 1: with() --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Method 1: <code class="text-neon-cyan">with()</code> - Eager Load When Querying</h2>

        <p class="text-white/70 mb-6">
            The most common method. Load relationships <strong class="text-neon-green">at query time</strong>.
        </p>

        {{-- Basic Usage --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Basic Usage</h3>
            <div class="code-right">
                <pre><code class="language-php">// Single relationship
$projects = Project::with('user')->get();

// Multiple relationships
$tasks = Task::with(['project', 'comments', 'reactions'])->get();

// Nested relationships (dot notation)
$tasks = Task::with('project.user')->get();

// Multiple nested relationships
$users = User::with(['projects.tasks', 'comments'])->get();</code></pre>
            </div>
        </div>

        {{-- FlexBoard Examples --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-green mb-4">FlexBoard Examples</h3>
            <div class="code-right">
                <pre><code class="language-php">// Load user for each project
$projects = Project::with('user')->get();

// Load project AND its user for each task
$tasks = Task::with('project.user')->get();

// Load the entire tree
$users = User::with('projects.tasks')->get();

// Load polymorphic relationships
$tasks = Task::with(['comments', 'reactions'])->get();</code></pre>
            </div>
        </div>
    </section>

    {{-- Method 2: withCount() --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Method 2: <code class="text-neon-cyan">withCount()</code> - Efficient Counts</h2>

        <p class="text-white/70 mb-6">
            Instead of loading entire relationships just to count them, use <code class="text-neon-pink">withCount()</code>.
        </p>

        <div class="grid md:grid-cols-2 gap-6 mb-6">
            {{-- The Problem --}}
            <div class="glass-card p-6 border-neon-pink/30">
                <h3 class="font-semibold text-neon-pink mb-4">The Problem</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">// BAD: Loads ALL tasks
$projects = Project::with('tasks')->get();
foreach ($projects as $project) {
    // Had to load ALL tasks!
    echo $project->tasks->count();
}</code></pre>
                </div>
            </div>

            {{-- The Solution --}}
            <div class="glass-card p-6 border-neon-green/30">
                <h3 class="font-semibold text-neon-green mb-4">The Solution</h3>
                <div class="code-right">
                    <pre><code class="language-php">// GOOD: Counts in the query
$projects = Project::withCount('tasks')->get();
foreach ($projects as $project) {
    // It's an attribute now!
    echo $project->tasks_count;
}</code></pre>
                </div>
            </div>
        </div>

        {{-- Advanced withCount --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Advanced withCount</h3>
            <div class="code-right">
                <pre><code class="language-php">// Count with conditions
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
}</code></pre>
            </div>
        </div>
    </section>

    {{-- Method 3: load() --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Method 3: <code class="text-neon-cyan">load()</code> - Lazy Eager Loading</h2>

        <p class="text-white/70 mb-6">
            Sometimes you already have a model and need to load relationships <strong class="text-neon-pink">later</strong>.
        </p>

        <div class="space-y-6">
            {{-- When to Use --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">When to Use</h3>
                <div class="code-info">
                    <pre><code class="language-php">// You already fetched the model
$project = Project::find($id);

// Some condition determines what to load
if ($showTasks) {
    $project->load('tasks');
}

if ($showComments) {
    $project->load('tasks.comments');
}</code></pre>
                </div>
            </div>

            {{-- Multiple Relationships --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">Load Multiple Relationships</h3>
                <div class="code-right">
                    <pre><code class="language-php">$project = Project::first();

// Load multiple relationships at once
$project->load(['user', 'tasks.comments', 'tasks.reactions']);</code></pre>
                </div>
            </div>

            {{-- loadCount --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">loadCount() - Lazy Count Loading</h3>
                <div class="code-right">
                    <pre><code class="language-php">$project = Project::first();

// Add counts to an already-loaded model
$project->loadCount(['tasks', 'comments']);

echo $project->tasks_count;
echo $project->comments_count;</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Method 4: Constrained Eager Loading --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Method 4: Constrained Eager Loading</h2>

        <p class="text-white/70 mb-6">
            You don't always want <strong class="text-neon-pink">ALL</strong> related records. Filter them!
        </p>

        <div class="space-y-6">
            {{-- Basic Constraints --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Basic Constraints</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Only load incomplete tasks, ordered by priority
$projects = Project::with([
    'tasks' => function ($query) {
        $query->where('is_completed', false)
              ->orderBy('priority', 'desc');
    },
])->get();</code></pre>
                </div>
            </div>

            {{-- Multiple Constrained Relationships --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">Multiple Constrained Relationships</h3>
                <div class="code-right">
                    <pre><code class="language-php">$projects = Project::with([
    'user',  // No constraints
    'tasks' => function ($query) {
        $query->where('is_completed', false);
    },
    'comments' => function ($query) {
        $query->latest()->limit(5);  // Only 5 most recent
    },
])->get();</code></pre>
                </div>
            </div>

            {{-- Combining --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Combining with withCount</h3>
                <div class="code-right">
                    <pre><code class="language-php">$projects = Project::with(['user'])
    ->withCount(['tasks', 'comments'])
    ->get();</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Query Count Comparison --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Query Count Comparison</h2>

        <div class="glass-card p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-glass-border">
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Scenario</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">N+1 Queries</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Eager Loading</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Improvement</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-white/80">27 projects with users</td>
                        <td class="py-3 px-4 text-neon-pink">28</td>
                        <td class="py-3 px-4 text-neon-green">2</td>
                        <td class="py-3 px-4 text-neon-cyan">14x faster</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-white/80">103 tasks with project.user</td>
                        <td class="py-3 px-4 text-neon-pink">~207</td>
                        <td class="py-3 px-4 text-neon-green">3</td>
                        <td class="py-3 px-4 text-neon-cyan">69x faster</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-white/80">10 users -> projects -> tasks</td>
                        <td class="py-3 px-4 text-neon-pink">~140</td>
                        <td class="py-3 px-4 text-neon-green">3</td>
                        <td class="py-3 px-4 text-neon-cyan">46x faster</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-white/80">Project counts (tasks + completed)</td>
                        <td class="py-3 px-4 text-neon-pink">~55</td>
                        <td class="py-3 px-4 text-neon-green">1</td>
                        <td class="py-3 px-4 text-neon-cyan">55x faster</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Best Practices --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Best Practices</h2>

        <div class="space-y-6">
            {{-- Practice 1 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">1. Always Eager Load in Controllers</h3>
                <div class="code-right">
                    <pre><code class="language-php">// GOOD: Controller handles data loading
public function index()
{
    $projects = Project::with(['user', 'tasks'])->get();
    return view('projects.index', compact('projects'));
}</code></pre>
                </div>
            </div>

            {{-- Practice 2 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">2. Use $with for Always-Needed Relationships</h3>
                <div class="code-info">
                    <pre><code class="language-php">// In your Model
class Project extends Model
{
    // Always eager load these
    protected $with = ['user'];
}</code></pre>
                </div>
                <p class="text-neon-orange text-sm mt-4">
                    Warning: Use sparingly! This loads the relationship on EVERY query.
                </p>
            </div>

            {{-- Practice 3 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">3. Enable Strict Mode in Development</h3>
                <div class="code-right">
                    <pre><code class="language-php">// In AppServiceProvider::boot()
use Illuminate\Database\Eloquent\Model;

public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());
}</code></pre>
                </div>
                <p class="text-white/60 text-sm mt-4">
                    This throws an exception when you forget to eager load!
                </p>
            </div>

            {{-- Practice 4 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">4. Use API Resources with Eager Loading</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Controller
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
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Common Patterns --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Common Patterns</h2>

        <div class="space-y-6">
            {{-- Dashboard --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Pattern 1: Dashboard Data</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Load everything the dashboard needs
$user = User::with([
    'projects' => function ($query) {
        $query->withCount('tasks')
              ->latest()
              ->limit(5);
    },
    'flexes' => fn ($q) => $q->latest()->limit(10),
])->find($userId);</code></pre>
                </div>
            </div>

            {{-- Detail Page --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Pattern 2: Detail Page</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Load all related data for a single model
$project = Project::with([
    'user',
    'tasks.tags',
    'tasks.comments.user',
    'tasks.reactions',
])->findOrFail($id);</code></pre>
                </div>
            </div>

            {{-- API Listing --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">Pattern 3: API Listing</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Paginated with eager loading
$projects = Project::with('user')
    ->withCount('tasks')
    ->latest()
    ->paginate(20);</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Quick Reference --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Quick Reference</h2>

        <div class="glass-card p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-glass-border">
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Method</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">When to Use</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Example</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-green">with()</td>
                        <td class="py-3 px-4 text-white/80">Loading at query time</td>
                        <td class="py-3 px-4 text-neon-cyan">Post::with('author')->get()</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-pink">withCount()</td>
                        <td class="py-3 px-4 text-white/80">Need counts, not models</td>
                        <td class="py-3 px-4 text-neon-cyan">Post::withCount('comments')->get()</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-purple">load()</td>
                        <td class="py-3 px-4 text-white/80">Have model, need relations</td>
                        <td class="py-3 px-4 text-neon-cyan">$post->load('comments')</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-orange">loadCount()</td>
                        <td class="py-3 px-4 text-white/80">Have model, need counts</td>
                        <td class="py-3 px-4 text-neon-cyan">$post->loadCount('comments')</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Hands-On Exercise</h2>

        <div class="glass-card p-6">
            <ol class="list-decimal list-inside space-y-3 text-white/80">
                <li>Start the server and open two browser tabs</li>
                <li>Tab 1: Visit <code class="text-neon-cyan">/n-plus-one/projects</code> - note the query count (~28)</li>
                <li>Tab 2: Visit <code class="text-neon-cyan">/eager-loading/projects</code> - note the query count (2)</li>
                <li>Compare <code class="text-neon-pink">/n-plus-one/users</code> vs <code class="text-neon-green">/eager-loading/users</code></li>
                <li>Try <code class="text-neon-purple">/eager-loading/compare</code> for a side-by-side analysis</li>
            </ol>
        </div>
    </section>

    {{-- Remember --}}
    <section class="mb-12">
        <div class="glass-card p-6 border-neon-green/30">
            <blockquote class="text-xl text-neon-green font-display italic">
                "with() is your best friend. Use it everywhere."
            </blockquote>
            <p class="text-white/70 mt-4">
                Every time you query models that will access relationships, reach for <code class="text-neon-pink">with()</code> first!
            </p>
            <div class="code-right mt-4">
                <pre><code class="language-php">// Make this your default pattern:
Model::with(['relationship'])->get();</code></pre>
            </div>
        </div>
    </section>

    {{-- Git Checkout --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">Try the Code</h3>
            <p class="text-white/70 mb-4">Check out this branch to see the code in action:</p>
            <div class="code-info">
                <pre><code class="language-bash">git checkout 10-eager-loading</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
