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
                    <span>What the N+1 query problem is</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to identify N+1 queries in your code</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Why N+1 queries kill application performance</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to detect N+1 queries using tools</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- What is N+1? --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">What is the N+1 Query Problem?</h2>

        <p class="text-white/70 mb-6">
            The N+1 query problem occurs when your code makes <strong class="text-neon-pink">1 query to get N records</strong>, then <strong class="text-neon-pink">N additional queries</strong> to get related data for each record.
        </p>

        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Simple Example</h3>
            <div class="code-wrong">
                <pre><code class="language-php">// 1 query to get 100 projects
$projects = Project::all();

foreach ($projects as $project) {
    // 100 MORE queries - one for each user!
    echo $project->user->name;
}
// Total: 1 + 100 = 101 queries!</code></pre>
            </div>
            <p class="text-white/60 text-sm mt-4">
                If you have 1,000 projects, that's <strong class="text-neon-pink">1,001 queries</strong> just to display a list!
            </p>
        </div>
    </section>

    {{-- Why is N+1 Bad? --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Why is N+1 Bad?</h2>

        {{-- Performance Impact --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-pink mb-4">Performance Impact</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-glass-border">
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Records</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">N+1 Queries</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Optimized</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Difference</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-xs">
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">10</td>
                            <td class="py-3 px-4 text-neon-pink">11</td>
                            <td class="py-3 px-4 text-neon-green">2</td>
                            <td class="py-3 px-4 text-neon-orange">5.5x worse</td>
                        </tr>
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">100</td>
                            <td class="py-3 px-4 text-neon-pink">101</td>
                            <td class="py-3 px-4 text-neon-green">2</td>
                            <td class="py-3 px-4 text-neon-orange">50.5x worse</td>
                        </tr>
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">1,000</td>
                            <td class="py-3 px-4 text-neon-pink">1,001</td>
                            <td class="py-3 px-4 text-neon-green">2</td>
                            <td class="py-3 px-4 text-neon-orange">500x worse</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-white/80">10,000</td>
                            <td class="py-3 px-4 text-neon-pink">10,001</td>
                            <td class="py-3 px-4 text-neon-green">2</td>
                            <td class="py-3 px-4 text-neon-orange">5,000x worse</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Real Numbers --}}
        <div class="glass-card p-6 border-neon-pink/30">
            <h3 class="font-semibold text-neon-pink mb-4">Real Numbers</h3>
            <ul class="space-y-2 text-white/80">
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> Each query takes ~1-5ms (more on remote databases)
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> 100 queries = <strong class="text-neon-orange">100-500ms</strong> added
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> 1,000 queries = <strong class="text-neon-pink">1-5 SECONDS</strong> added
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> Your users are <strong class="text-neon-pink">gone</strong>!
                </li>
            </ul>
        </div>
    </section>

    {{-- Identifying N+1 in Code --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Identifying N+1 in Code</h2>

        <div class="space-y-6">
            {{-- Red Flag #1 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Red Flag #1: Loops Accessing Relationships</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">// BAD: Each iteration triggers a query!
foreach ($projects as $project) {
    $project->user->name;      // N+1!
    $project->tasks->count();  // Another N+1!
}</code></pre>
                </div>
            </div>

            {{-- Red Flag #2 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Red Flag #2: Blade Templates</h3>
                <div class="code-wrong">
                    <pre><code class="language-blade">{{-- BAD: Each $project->user triggers a query! --}}
@@foreach ($projects as $project)
    &lt;p&gt;Owner: @{{ $project->user->name }}&lt;/p&gt;
    &lt;p&gt;Tasks: @{{ $project->tasks->count() }}&lt;/p&gt;
@@endforeach</code></pre>
                </div>
            </div>

            {{-- Red Flag #3 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Red Flag #3: Nested Loops</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">// CATASTROPHIC: N+1 inside N+1!
foreach ($users as $user) {           // N users
    foreach ($user->projects as $project) {    // N queries!
        foreach ($project->tasks as $task) {   // N*M queries!
            echo $task->title;
        }
    }
}
// With 10 users, 5 projects each, 10 tasks = 511 queries!</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- FlexBoard N+1 Examples --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">FlexBoard N+1 Examples</h2>

        <p class="text-white/70 mb-6">
            This branch includes a demo controller with <strong class="text-neon-pink">intentionally BAD code</strong>.
        </p>

        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Demo Routes</h3>
            <p class="text-white/70 mb-4">Visit these URLs to see N+1 in action:</p>
            <div class="code-info">
                <pre><code class="language-text">GET /n-plus-one/projects     # Projects with users
GET /n-plus-one/tasks        # Tasks with projects and users
GET /n-plus-one/users        # Users -> Projects -> Tasks
GET /n-plus-one/counts       # Count queries N+1
GET /n-plus-one/polymorphic  # Comments & reactions N+1
GET /n-plus-one/dashboard    # Blade template N+1</code></pre>
            </div>
            <p class="text-white/60 text-sm mt-4">
                Each response includes the data, <strong class="text-neon-green">all queries executed</strong>, <strong class="text-neon-cyan">query count</strong>, and problem explanation.
            </p>
        </div>
    </section>

    {{-- Controller Examples --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Controller Examples (BAD Code!)</h2>

        <div class="space-y-6">
            {{-- Example 1 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Example 1: Basic N+1</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">public function projectsWithUsers()
{
    // 1 query
    $projects = Project::all();

    foreach ($projects as $project) {
        // N more queries - one per project!
        $project->user->name;
    }
}</code></pre>
                </div>
            </div>

            {{-- Example 2 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Example 2: Double N+1</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">public function tasksWithProjectsAndUsers()
{
    // 1 query
    $tasks = Task::all();

    foreach ($tasks as $task) {
        $task->project->name;         // N queries
        $task->project->user->name;   // N MORE queries!
    }
    // Total: 1 + N + N = 2N+1 queries
}</code></pre>
                </div>
            </div>

            {{-- Example 3 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Example 3: Count N+1</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">public function projectsWithTaskCounts()
{
    $projects = Project::all();

    foreach ($projects as $project) {
        // Each count() is a separate query!
        $project->tasks()->count();              // N queries
        $project->tasks()->completed()->count(); // N MORE!
    }
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Detecting N+1 Queries --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Detecting N+1 Queries</h2>

        <div class="space-y-6">
            {{-- Method 1 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Method 1: Query Logging</h3>
                <div class="code-info">
                    <pre><code class="language-php">use Illuminate\Support\Facades\DB;

DB::enableQueryLog();

// Your code here...

$queries = DB::getQueryLog();
dd(count($queries), $queries);</code></pre>
                </div>
            </div>

            {{-- Method 2 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">Method 2: Laravel Debugbar</h3>
                <div class="code-info">
                    <pre><code class="language-bash">composer require barryvdh/laravel-debugbar --dev</code></pre>
                </div>
                <p class="text-white/60 text-sm mt-4">
                    Shows query count and time in your browser!
                </p>
            </div>

            {{-- Method 3 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Method 3: Strict Mode (Laravel 10+)</h3>
                <div class="code-right">
                    <pre><code class="language-php">// In AppServiceProvider::boot()
Model::preventLazyLoading(! app()->isProduction());</code></pre>
                </div>
                <p class="text-white/60 text-sm mt-4">
                    This throws an exception when N+1 occurs!
                </p>
            </div>

            {{-- Method 4 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Method 4: Telescope</h3>
                <p class="text-white/70">
                    Laravel Telescope shows:
                </p>
                <ul class="space-y-1 text-white/80 mt-2">
                    <li class="flex items-center gap-2">
                        <span class="text-neon-cyan">•</span> All queries per request
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-neon-cyan">•</span> Duplicate queries highlighted
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-neon-cyan">•</span> Query timing
                    </li>
                </ul>
            </div>
        </div>
    </section>

    {{-- Quick Detection Checklist --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Quick Detection Checklist</h2>

        <div class="glass-card p-6">
            <p class="text-white/70 mb-4">Ask yourself:</p>
            <ul class="space-y-3 text-white/80">
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 border border-neon-pink rounded flex items-center justify-center text-xs">?</span>
                    <span>Am I accessing relationships in a loop?</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 border border-neon-pink rounded flex items-center justify-center text-xs">?</span>
                    <span>Am I using <code class="text-neon-cyan">->count()</code> or <code class="text-neon-cyan">->sum()</code> in a loop?</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 border border-neon-pink rounded flex items-center justify-center text-xs">?</span>
                    <span>Are my Blade templates accessing relationships?</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 border border-neon-pink rounded flex items-center justify-center text-xs">?</span>
                    <span>Do I have nested loops with relationships?</span>
                </li>
            </ul>
            <p class="text-neon-pink text-sm mt-4 font-semibold">
                If YES to any, you likely have N+1!
            </p>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Hands-On Exercise</h2>

        <div class="glass-card p-6">
            <ol class="list-decimal list-inside space-y-3 text-white/80 mb-6">
                <li>Run the seeder to create test data:</li>
            </ol>

            <div class="code-info mb-6">
                <pre><code class="language-bash">php artisan migrate:fresh --seed</code></pre>
            </div>

            <ol class="list-decimal list-inside space-y-3 text-white/80" start="2">
                <li>Visit <code class="text-neon-cyan">/n-plus-one/projects</code> and check the query count</li>
                <li>Visit <code class="text-neon-cyan">/n-plus-one/users</code> and watch queries explode!</li>
                <li>Look at the controller code in <code class="text-neon-pink">NplusOneDemoController</code></li>
                <li>Try to predict query counts before checking</li>
            </ol>
        </div>
    </section>

    {{-- What's Next? --}}
    <section class="mb-12">
        <div class="glass-card p-6 border-neon-green/30">
            <h3 class="font-semibold text-neon-green mb-3">What's Next?</h3>
            <p class="text-white/70 mb-4">
                This code is <strong class="text-neon-pink">intentionally broken</strong>!
            </p>
            <p class="text-white/70 mb-4">
                The next lesson shows how to fix all these issues using:
            </p>
            <ul class="space-y-2 text-white/80">
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> <code class="text-neon-green">with()</code> for eager loading
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> <code class="text-neon-green">withCount()</code> for efficient counts
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> <code class="text-neon-green">load()</code> for lazy eager loading
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> Query optimization techniques
                </li>
            </ul>
        </div>
    </section>

    {{-- Remember --}}
    <section class="mb-12">
        <div class="glass-card p-6 border-neon-orange/30">
            <blockquote class="text-xl text-neon-orange font-display italic">
                "The N+1 problem is the #1 performance killer in Laravel apps."
            </blockquote>
            <p class="text-white/70 mt-4">
                Every relationship access in a loop is a potential N+1. <strong class="text-neon-green">Always eager load!</strong>
            </p>
        </div>
    </section>

    {{-- Git Checkout --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">Try the Code</h3>
            <p class="text-white/70 mb-4">Check out this branch to see the code in action:</p>
            <div class="code-info">
                <pre><code class="language-bash">git checkout 09-n-plus-one</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
