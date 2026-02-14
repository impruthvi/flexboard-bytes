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
    {{-- Congratulations --}}
    <section class="mb-12">
        <div class="glass-card p-8 border-neon-green/50 text-center">
            <h2 class="font-display text-3xl font-bold text-neon-green mb-4">Congratulations!</h2>
            <p class="text-white/80 text-lg">
                You've completed the FlexBoard Eloquent learning journey! This branch represents a <strong class="text-neon-cyan">production-ready</strong> Laravel application with all the best practices you've learned.
            </p>
        </div>
    </section>

    {{-- What You've Learned --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">What You've Learned</h2>

        <div class="space-y-6">
            {{-- Branch 01 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-3">Branch 01: Model Conventions</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• Eloquent naming conventions (singular models, plural tables)</li>
                    <li>• Primary keys and table name overrides</li>
                    <li>• The importance of following Laravel's conventions</li>
                </ul>
                <div class="code-right">
                    <pre><code class="language-php">// Convention: Model "User" → table "users"
class User extends Model
{
    // Laravel handles everything automatically!
}</code></pre>
                </div>
            </div>

            {{-- Branch 02 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-3">Branch 02: Mass Assignment</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• <code class="text-neon-cyan">$fillable</code> vs <code class="text-neon-cyan">$guarded</code></li>
                    <li>• Protecting against mass assignment vulnerabilities</li>
                    <li>• When to use <code class="text-neon-cyan">create()</code> vs setting properties manually</li>
                </ul>
                <div class="code-right">
                    <pre><code class="language-php">class Project extends Model
{
    protected $fillable = ['name', 'slug', 'description'];
    // user_id intentionally NOT fillable - use relationship!
}

// Safe: Uses relationship to set user_id
$user->projects()->create([...]);</code></pre>
                </div>
            </div>

            {{-- Branch 03 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-3">Branch 03: Accessors & Mutators</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• Attribute casting with <code class="text-neon-cyan">casts()</code></li>
                    <li>• Custom accessors using <code class="text-neon-cyan">Attribute::get()</code></li>
                    <li>• Custom mutators using <code class="text-neon-cyan">Attribute::set()</code></li>
                </ul>
                <div class="code-right">
                    <pre><code class="language-php">protected function casts(): array
{
    return ['flex_points' => 'integer'];
}

protected function name(): Attribute
{
    return Attribute::make(
        get: fn ($value) => ucwords($value),
        set: fn ($value) => strtolower($value),
    );
}</code></pre>
                </div>
            </div>

            {{-- Branch 04 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-3">Branch 04: Query Scopes</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• Local scopes for reusable query logic</li>
                    <li>• Dynamic scopes with parameters</li>
                    <li>• Chaining scopes together</li>
                </ul>
                <div class="code-right">
                    <pre><code class="language-php">// Define scope
public function scopeCompleted(Builder $query): Builder
{
    return $query->where('is_completed', true);
}

// Use it anywhere
Task::completed()->highPriority()->get();</code></pre>
                </div>
            </div>

            {{-- Branch 05 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-orange mb-3">Branch 05: Timestamps & Soft Deletes</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• Automatic <code class="text-neon-cyan">created_at</code> and <code class="text-neon-cyan">updated_at</code></li>
                    <li>• Soft deletes with <code class="text-neon-cyan">SoftDeletes</code> trait</li>
                    <li>• <code class="text-neon-cyan">withTrashed()</code> and <code class="text-neon-cyan">onlyTrashed()</code> queries</li>
                </ul>
                <div class="code-right">
                    <pre><code class="language-php">use SoftDeletes;

// Soft delete (sets deleted_at)
$task->delete();

// Restore
$task->restore();

// Include soft deleted
Task::withTrashed()->get();</code></pre>
                </div>
            </div>

            {{-- Branch 06 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-3">Branch 06: Basic Relationships</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• <code class="text-neon-pink">hasOne</code>, <code class="text-neon-pink">hasMany</code>, <code class="text-neon-pink">belongsTo</code></li>
                    <li>• Defining inverse relationships</li>
                    <li>• Creating related models through relationships</li>
                </ul>
                <div class="code-right">
                    <pre><code class="language-php">// User has many Projects
public function projects(): HasMany
{
    return $this->hasMany(Project::class);
}

// Project belongs to User
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}</code></pre>
                </div>
            </div>

            {{-- Branch 07 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-3">Branch 07: Many-to-Many Relationships</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• <code class="text-neon-cyan">belongsToMany</code> relationships</li>
                    <li>• Pivot tables and custom pivot table names</li>
                    <li>• Attaching, detaching, and syncing</li>
                </ul>
                <div class="code-right">
                    <pre><code class="language-php">// Task has many Tags (and vice versa)
public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class, 'task_tag');
}

// Attach tags
$task->tags()->attach([1, 2, 3]);

// Sync (replace all)
$task->tags()->sync([1, 2]);</code></pre>
                </div>
            </div>

            {{-- Branch 08 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-3">Branch 08: Polymorphic Relationships</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• <code class="text-neon-cyan">morphTo</code>, <code class="text-neon-cyan">morphMany</code>, <code class="text-neon-cyan">morphOne</code></li>
                    <li>• Sharing models across multiple parents</li>
                    <li>• Configuring polymorphic types</li>
                </ul>
                <div class="code-right">
                    <pre><code class="language-php">// Comment can belong to Task, Project, or any model
public function commentable(): MorphTo
{
    return $this->morphTo();
}

// Task has many Comments
public function comments(): MorphMany
{
    return $this->morphMany(Comment::class, 'commentable');
}</code></pre>
                </div>
            </div>

            {{-- Branch 09 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-orange mb-3">Branch 09: The N+1 Problem</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• Understanding the N+1 query problem</li>
                    <li>• Identifying N+1 in loops and Blade templates</li>
                    <li>• Tools for detecting N+1 queries</li>
                </ul>
                <div class="code-wrong">
                    <pre><code class="language-php">// BAD: N+1 queries
foreach (Project::all() as $project) {
    echo $project->user->name; // Query per project!
}</code></pre>
                </div>
            </div>

            {{-- Branch 10 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-3">Branch 10: Eager Loading</h3>
                <ul class="space-y-1 text-white/80 text-sm mb-4">
                    <li>• <code class="text-neon-cyan">with()</code> for eager loading relationships</li>
                    <li>• <code class="text-neon-cyan">withCount()</code> for efficient counting</li>
                    <li>• <code class="text-neon-cyan">load()</code> for lazy eager loading</li>
                </ul>
                <div class="code-right">
                    <pre><code class="language-php">// GOOD: 2 queries total
$projects = Project::with('user')->get();
foreach ($projects as $project) {
    echo $project->user->name; // Already loaded!
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Data Model --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">FlexBoard Data Model</h2>

        <div class="glass-card p-6 overflow-x-auto">
            <pre class="text-xs text-white/80 font-mono"><code>┌─────────────────────────────────────────────────────────────────┐
│                          User                                    │
│  - id, name, email, flex_points                                 │
├─────────────────────────────────────────────────────────────────┤
│  hasMany: projects, tasks, comments, reactions, flexes          │
└─────────────────────────────────────────────────────────────────┘
         │
         │ hasMany
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                         Project                                  │
│  - id, user_id, name, slug, description, is_active              │
├─────────────────────────────────────────────────────────────────┤
│  belongsTo: user  │  hasMany: tasks  │  morphMany: comments     │
└─────────────────────────────────────────────────────────────────┘
         │
         │ hasMany
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                          Task                                    │
│  - id, project_id, title, description, priority, flex_reward    │
├─────────────────────────────────────────────────────────────────┤
│  belongsTo: project  │  belongsToMany: tags  │  morphMany: ...  │
└─────────────────────────────────────────────────────────────────┘
         │
         │ belongsToMany
         ▼
┌─────────────────────────────────────────────────────────────────┐
│    Tag (name, color)    │    Comment (morphTo)    │   Reaction  │
└─────────────────────────────────────────────────────────────────┘</code></pre>
        </div>
    </section>

    {{-- Best Practices Applied --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Best Practices Applied</h2>

        <div class="grid md:grid-cols-2 gap-4">
            <div class="glass-card p-4">
                <h4 class="font-semibold text-neon-green mb-2">1. Strict Model Conventions</h4>
                <p class="text-white/70 text-sm">Singular model names, plural tables, snake_case foreign keys</p>
            </div>
            <div class="glass-card p-4">
                <h4 class="font-semibold text-neon-pink mb-2">2. Protected Mass Assignment</h4>
                <p class="text-white/70 text-sm">Only expected fields are fillable, sensitive fields use relationships</p>
            </div>
            <div class="glass-card p-4">
                <h4 class="font-semibold text-neon-cyan mb-2">3. Consistent Casts</h4>
                <p class="text-white/70 text-sm">Booleans, dates, and integers properly typed</p>
            </div>
            <div class="glass-card p-4">
                <h4 class="font-semibold text-neon-purple mb-2">4. Reusable Scopes</h4>
                <p class="text-white/70 text-sm">Queries are readable and DRY</p>
            </div>
            <div class="glass-card p-4">
                <h4 class="font-semibold text-neon-orange mb-2">5. Soft Deletes</h4>
                <p class="text-white/70 text-sm">Tasks use soft deletes for recoverability</p>
            </div>
            <div class="glass-card p-4">
                <h4 class="font-semibold text-neon-green mb-2">6. Eager Loading Everywhere</h4>
                <p class="text-white/70 text-sm">Always eager load when accessing relationships</p>
            </div>
        </div>
    </section>

    {{-- Eloquent Cheat Sheet --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Eloquent Cheat Sheet</h2>

        {{-- Relationships Table --}}
        <div class="glass-card p-6 mb-6 overflow-x-auto">
            <h3 class="font-semibold text-neon-cyan mb-4">Relationships</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-glass-border">
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Type</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Definition</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Usage</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-green">hasOne</td>
                        <td class="py-3 px-4 text-white/80">Parent has one child</td>
                        <td class="py-3 px-4 text-neon-cyan">$user->profile</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-green">hasMany</td>
                        <td class="py-3 px-4 text-white/80">Parent has many children</td>
                        <td class="py-3 px-4 text-neon-cyan">$user->posts</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-pink">belongsTo</td>
                        <td class="py-3 px-4 text-white/80">Child belongs to parent</td>
                        <td class="py-3 px-4 text-neon-cyan">$post->user</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-purple">belongsToMany</td>
                        <td class="py-3 px-4 text-white/80">Many-to-many with pivot</td>
                        <td class="py-3 px-4 text-neon-cyan">$post->tags</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-orange">morphTo</td>
                        <td class="py-3 px-4 text-white/80">Polymorphic parent</td>
                        <td class="py-3 px-4 text-neon-cyan">$comment->commentable</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-orange">morphMany</td>
                        <td class="py-3 px-4 text-white/80">Polymorphic children</td>
                        <td class="py-3 px-4 text-neon-cyan">$post->comments</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Query Patterns --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-green mb-4">Query Patterns</h3>
            <div class="code-right">
                <pre><code class="language-php">// Create through relationship (sets foreign key)
$user->projects()->create([...]);

// Eager load
Project::with('user')->get();

// Eager load with count
Project::withCount('tasks')->get();

// Constrained eager load
Project::with(['tasks' => fn ($q) => $q->incomplete()])->get();

// Attach/detach many-to-many
$task->tags()->attach([1, 2, 3]);
$task->tags()->sync([1, 2]);

// Soft delete queries
Task::withTrashed()->get();
Task::onlyTrashed()->get();</code></pre>
            </div>
        </div>
    </section>

    {{-- Running the App --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Running the Complete App</h2>

        <div class="glass-card p-6">
            <div class="code-info">
                <pre><code class="language-bash"># Fresh install
composer install
npm install

# Setup database
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start server
php artisan serve

# Or use Herd - it's automatic!</code></pre>
            </div>
        </div>
    </section>

    {{-- Next Steps --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Next Steps for Learning</h2>

        <div class="glass-card p-6">
            <ol class="list-decimal list-inside space-y-3 text-white/80">
                <li><strong class="text-neon-cyan">Explore the Code</strong>: Read through all the models and controllers</li>
                <li><strong class="text-neon-pink">Run the Demos</strong>: Compare N+1 vs eager loading query counts</li>
                <li><strong class="text-neon-green">Enable Strict Mode</strong>: Try <code class="text-neon-cyan">Model::preventLazyLoading(true)</code></li>
                <li><strong class="text-neon-purple">Write Tests</strong>: Add feature tests for the relationships</li>
                <li><strong class="text-neon-orange">Extend the App</strong>: Add new features using what you've learned</li>
            </ol>
        </div>
    </section>

    {{-- You Did It! --}}
    <section class="mb-12">
        <div class="glass-card p-8 border-neon-green/50 text-center">
            <h2 class="font-display text-2xl font-bold text-neon-green mb-4">You Did It!</h2>
            <p class="text-white/80 mb-6">
                You now understand Laravel Eloquent from the ground up:
            </p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                <div class="glass-card p-3 text-neon-cyan">Model Conventions</div>
                <div class="glass-card p-3 text-neon-pink">Mass Assignment</div>
                <div class="glass-card p-3 text-neon-green">Accessors & Mutators</div>
                <div class="glass-card p-3 text-neon-purple">Query Scopes</div>
                <div class="glass-card p-3 text-neon-orange">Soft Deletes</div>
                <div class="glass-card p-3 text-neon-cyan">Relationships</div>
                <div class="glass-card p-3 text-neon-pink">Many-to-Many</div>
                <div class="glass-card p-3 text-neon-green">Polymorphic</div>
                <div class="glass-card p-3 text-neon-purple">Eager Loading</div>
            </div>
            <p class="text-white/80 mt-6 text-lg font-semibold">
                <span class="text-neon-green">Go build amazing Laravel applications!</span>
            </p>
        </div>
    </section>

    {{-- Resources --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">Resources</h3>
            <ul class="space-y-2 text-white/80">
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span>
                    <a href="https://laravel.com/docs/eloquent" target="_blank" class="text-neon-cyan hover:underline">Laravel Eloquent Documentation</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span>
                    <a href="https://laravel.com/docs/eloquent-relationships" target="_blank" class="text-neon-cyan hover:underline">Laravel Relationships</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span>
                    <a href="https://github.com/impruthvi/flexboard-bytes" target="_blank" class="text-neon-cyan hover:underline">FlexBoard GitHub Repository</a>
                </li>
            </ul>
        </div>
    </section>
</x-learn-layout>
