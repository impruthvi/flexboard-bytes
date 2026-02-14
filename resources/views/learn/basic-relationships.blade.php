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
                    <span>One-to-Many relationships (<code class="text-neon-pink">hasMany</code> / <code class="text-neon-pink">belongsTo</code>)</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>One-to-One relationships (<code class="text-neon-pink">hasOne</code> / <code class="text-neon-pink">belongsTo</code>)</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How foreign keys connect tables</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Querying through relationships</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- Relationship Types Overview --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Relationship Types Overview</h2>

        <div class="glass-card p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-glass-border">
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Relationship</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Example</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Method</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">One-to-Many</td>
                        <td class="py-3 px-4 text-white/80">User has many Projects</td>
                        <td class="py-3 px-4 text-neon-green">hasMany()</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-pink">Many-to-One</td>
                        <td class="py-3 px-4 text-white/80">Project belongs to User</td>
                        <td class="py-3 px-4 text-neon-green">belongsTo()</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-purple">One-to-One</td>
                        <td class="py-3 px-4 text-white/80">User has one Profile</td>
                        <td class="py-3 px-4 text-neon-green">hasOne()</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- One-to-Many: User -> Projects --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">One-to-Many: User → Projects</h2>

        <p class="text-white/70 mb-6">
            A User can have <strong class="text-neon-cyan">many Projects</strong>. A Project <strong class="text-neon-pink">belongs to one</strong> User.
        </p>

        {{-- Database Setup --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Database Setup</h3>
            <div class="code-info">
                <pre><code class="language-php">// projects table migration
$table->foreignId('user_id')->constrained()->cascadeOnDelete();</code></pre>
            </div>
        </div>

        {{-- Model Setup --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-green mb-4">Model Setup</h3>
            <div class="code-right">
                <pre><code class="language-php">// User.php
use Illuminate\Database\Eloquent\Relations\HasMany;

public function projects(): HasMany
{
    return $this->hasMany(Project::class);
}

// Project.php
use Illuminate\Database\Eloquent\Relations\BelongsTo;

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}</code></pre>
            </div>
        </div>

        {{-- Usage --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-purple mb-4">Usage</h3>
            <div class="code-info">
                <pre><code class="language-php">// Get all projects for a user
$user->projects;  // Collection of Project models

// Get the user who owns a project
$project->user;  // Single User model

// Create a project for a user
$user->projects()->create([
    'name' => 'New Project',
]);

// Count projects
$user->projects()->count();

// Query through relationship
$user->projects()->where('is_active', true)->get();</code></pre>
            </div>
        </div>
    </section>

    {{-- One-to-Many: Project -> Tasks --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">One-to-Many: Project → Tasks</h2>

        <p class="text-white/70 mb-6">
            A Project can have <strong class="text-neon-cyan">many Tasks</strong>. A Task <strong class="text-neon-pink">belongs to one</strong> Project.
        </p>

        <div class="code-right">
            <pre><code class="language-php">// Project.php
public function tasks(): HasMany
{
    return $this->hasMany(Task::class);
}

// Task.php
public function project(): BelongsTo
{
    return $this->belongsTo(Project::class);
}

// Usage
$project->tasks;         // Get all tasks
$task->project;          // Get the project

// Create a task for a project
$project->tasks()->create([
    'title' => 'Fix the bug',
    'priority' => 'high',
]);

// Chain with scopes!
$project->tasks()->incomplete()->highPriority()->get();</code></pre>
        </div>
    </section>

    {{-- One-to-One --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">One-to-One: User → Latest Flex</h2>

        <p class="text-white/70 mb-6">
            Sometimes you want just <strong class="text-neon-pink">ONE</strong> related record.
        </p>

        <div class="code-right">
            <pre><code class="language-php">// User.php
use Illuminate\Database\Eloquent\Relations\HasOne;

public function latestFlex(): HasOne
{
    return $this->hasOne(Flex::class)->latestOfMany();
}

// Or get the first one
public function firstFlex(): HasOne
{
    return $this->hasOne(Flex::class)->oldestOfMany();
}

// Usage
$user->latestFlex;  // Single Flex model (most recent)</code></pre>
        </div>
    </section>

    {{-- Foreign Key Conventions --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Foreign Key Conventions</h2>

        <p class="text-white/70 mb-6">
            Laravel auto-detects foreign keys based on naming:
        </p>

        <div class="glass-card p-6 overflow-x-auto mb-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-glass-border">
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Model</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Expected Foreign Key</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">User</td>
                        <td class="py-3 px-4 text-neon-green">user_id</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">Project</td>
                        <td class="py-3 px-4 text-neon-green">project_id</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-cyan">TaskList</td>
                        <td class="py-3 px-4 text-neon-green">task_list_id</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Custom Foreign Keys --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-pink mb-4">Custom Foreign Keys</h3>
            <div class="code-info">
                <pre><code class="language-php">// If your column isn't named conventionally
public function owner(): BelongsTo
{
    return $this->belongsTo(User::class, 'owner_id');
}

// Custom foreign key AND owner key
public function author(): BelongsTo
{
    return $this->belongsTo(User::class, 'author_uuid', 'uuid');
}</code></pre>
            </div>
        </div>
    </section>

    {{-- Creating Related Records --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Creating Related Records</h2>

        <div class="space-y-6">
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">Method 1: Using create() (Recommended)</h3>
                <div class="code-right">
                    <pre><code class="language-php">$project->tasks()->create([
    'title' => 'New Task',
]);
// Automatically sets project_id!</code></pre>
                </div>
            </div>

            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Method 2: Using save()</h3>
                <div class="code-info">
                    <pre><code class="language-php">$task = new Task(['title' => 'New Task']);
$project->tasks()->save($task);</code></pre>
                </div>
            </div>

            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Method 3: Manual (Less Clean)</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">$task = Task::create([
    'title' => 'New Task',
    'project_id' => $project->id,  // Manual - not recommended
]);</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Querying Relationships --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Querying Relationships</h2>

        <div class="space-y-6">
            {{-- Has Relationship --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Has Relationship</h3>
                <div class="code-info">
                    <pre><code class="language-php">// Users who have at least one project
User::has('projects')->get();

// Users with 5+ projects
User::has('projects', '>=', 5)->get();</code></pre>
                </div>
            </div>

            {{-- Where Has --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Where Has (Filter by Related Data)</h3>
                <div class="code-info">
                    <pre><code class="language-php">// Users with high-priority tasks
User::whereHas('projects.tasks', function ($query) {
    $query->where('priority', 'high');
})->get();</code></pre>
                </div>
            </div>

            {{-- With Count --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">With Count</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Get users with project count
User::withCount('projects')->get();

foreach ($users as $user) {
    echo $user->projects_count;  // Added attribute!
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Property vs Method --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">⚠️ Property vs Method</h2>

        <div class="glass-card p-6 border-neon-orange/30">
            <div class="code-info">
                <pre><code class="language-php">// PROPERTY - returns Collection/Model (cached after first access)
$user->projects;  // Returns Collection

// METHOD - returns query builder (for chaining)
$user->projects()->where(...)->get();  // Returns query builder first</code></pre>
            </div>
            <p class="text-white/60 text-sm mt-4">
                Use the <strong>property</strong> when you want the data. Use the <strong>method</strong> when you want to add more query constraints.
            </p>
        </div>
    </section>

    {{-- Quick Reference --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">📋 Quick Reference</h2>

        <div class="glass-card p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-glass-border">
                        <th class="text-left py-3 px-4 text-white/60 font-medium">From</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">To</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Relationship</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Access</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">User</td>
                        <td class="py-3 px-4 text-white/80">Projects</td>
                        <td class="py-3 px-4 text-neon-green">hasMany</td>
                        <td class="py-3 px-4 text-neon-pink">$user->projects</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">Project</td>
                        <td class="py-3 px-4 text-white/80">User</td>
                        <td class="py-3 px-4 text-neon-green">belongsTo</td>
                        <td class="py-3 px-4 text-neon-pink">$project->user</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">Project</td>
                        <td class="py-3 px-4 text-white/80">Tasks</td>
                        <td class="py-3 px-4 text-neon-green">hasMany</td>
                        <td class="py-3 px-4 text-neon-pink">$project->tasks</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-cyan">Task</td>
                        <td class="py-3 px-4 text-white/80">Project</td>
                        <td class="py-3 px-4 text-neon-green">belongsTo</td>
                        <td class="py-3 px-4 text-neon-pink">$task->project</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">🛠️ Hands-On Exercise</h2>

        <div class="glass-card p-6">
            <ol class="list-decimal list-inside space-y-3 text-white/80 mb-6">
                <li>Add relationships to User, Project, and Task models</li>
                <li>Create a Flex model with a <code class="text-neon-cyan">user()</code> relationship</li>
                <li>Test in tinker:</li>
            </ol>

            <div class="code-info">
                <pre><code class="language-php">$user = User::first();
$user->projects()->create(['name' => 'Test Project']);
$user->projects;  // See the new project!</code></pre>
            </div>
        </div>
    </section>

    {{-- Git Checkout --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">🔀 Try the Code</h3>
            <p class="text-white/70 mb-4">Check out this branch to see the code in action:</p>
            <div class="code-info">
                <pre><code class="language-bash">git checkout 06-basic-relationships</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
