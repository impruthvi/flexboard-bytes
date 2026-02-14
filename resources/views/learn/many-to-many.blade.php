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
                    <span>When to use Many-to-Many relationships</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How pivot tables work</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Attaching, detaching, and syncing related models</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Working with pivot table data</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- When to Use Many-to-Many --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">When to Use Many-to-Many</h2>

        <p class="text-white/70 mb-6">
            Use Many-to-Many when <strong class="text-neon-cyan">neither model "owns" the other</strong> - they're simply associated!
        </p>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="glass-card p-4">
                <div class="text-neon-pink font-mono text-sm mb-2">Task ↔ Tag</div>
                <p class="text-white/70 text-sm">A Task can have many Tags. A Tag can belong to many Tasks.</p>
            </div>
            <div class="glass-card p-4">
                <div class="text-neon-cyan font-mono text-sm mb-2">User ↔ Badge</div>
                <p class="text-white/70 text-sm">A User can have many Badges. A Badge can belong to many Users.</p>
            </div>
            <div class="glass-card p-4">
                <div class="text-neon-green font-mono text-sm mb-2">User ↔ Role</div>
                <p class="text-white/70 text-sm">A User can have many Roles. A Role can belong to many Users.</p>
            </div>
            <div class="glass-card p-4">
                <div class="text-neon-purple font-mono text-sm mb-2">Student ↔ Course</div>
                <p class="text-white/70 text-sm">A Student can enroll in many Courses. A Course can have many Students.</p>
            </div>
        </div>
    </section>

    {{-- Pivot Tables --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Pivot Tables</h2>

        <p class="text-white/70 mb-6">
            Many-to-Many needs a <strong class="text-neon-pink">"pivot" (or "junction") table</strong> to store the relationships.
        </p>

        {{-- Naming Convention --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Naming Convention</h3>
            <p class="text-white/70 mb-4">
                The pivot table name combines both model names in <strong class="text-neon-green">alphabetical order</strong>, snake_case, singular:
            </p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-glass-border">
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Models</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Pivot Table Name</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-xs">
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">Tag + Task</td>
                            <td class="py-3 px-4 text-neon-green">tag_task <span class="text-white/50">(alphabetical)</span></td>
                        </tr>
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">Badge + User</td>
                            <td class="py-3 px-4 text-neon-green">badge_user</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-white/80">Role + User</td>
                            <td class="py-3 px-4 text-neon-green">role_user</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pivot Table Migration --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-green mb-4">Pivot Table Migration</h3>
            <div class="code-info">
                <pre><code class="language-php">// Pivot tables typically only have foreign keys
Schema::create('tag_task', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
    $table->foreignId('task_id')->constrained()->cascadeOnDelete();
    $table->timestamps();

    // Prevent duplicates
    $table->unique(['tag_id', 'task_id']);
});</code></pre>
            </div>
        </div>
    </section>

    {{-- Setting Up the Relationship --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Setting Up the Relationship</h2>

        {{-- Both Models Need belongsToMany --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Both Models Need <code class="text-neon-pink">belongsToMany()</code></h3>
            <div class="code-right">
                <pre><code class="language-php">// Task.php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class);
}

// Tag.php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

public function tasks(): BelongsToMany
{
    return $this->belongsToMany(Task::class);
}</code></pre>
            </div>
        </div>

        {{-- Custom Pivot Table Name --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-pink mb-4">Custom Pivot Table Name</h3>
            <p class="text-white/70 mb-4">If your pivot table doesn't follow conventions:</p>
            <div class="code-info">
                <pre><code class="language-php">public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class, 'task_tags');
}</code></pre>
            </div>
        </div>
    </section>

    {{-- Attaching & Detaching --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Attaching & Detaching</h2>

        <div class="space-y-6">
            {{-- Attach --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">Attach (Add Relationship)</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Add a single tag
$task->tags()->attach($tagId);

// Add multiple tags
$task->tags()->attach([1, 2, 3]);

// Add with pivot data
$task->tags()->attach($tagId, ['added_by' => auth()->id()]);</code></pre>
                </div>
            </div>

            {{-- Detach --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Detach (Remove Relationship)</h3>
                <div class="code-info">
                    <pre><code class="language-php">// Remove a single tag
$task->tags()->detach($tagId);

// Remove multiple tags
$task->tags()->detach([1, 2, 3]);

// Remove ALL tags
$task->tags()->detach();</code></pre>
                </div>
            </div>

            {{-- Sync --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Sync (Replace All)</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Replace all tags with these
$task->tags()->sync([1, 2, 3]);

// Sync without detaching (only adds, never removes)
$task->tags()->syncWithoutDetaching([4, 5]);</code></pre>
                </div>
            </div>

            {{-- Toggle --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Toggle (Attach or Detach)</h3>
                <div class="code-info">
                    <pre><code class="language-php">// If attached -> detach, if detached -> attach
$task->tags()->toggle([1, 2, 3]);</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Pivot Table with Extra Columns --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Pivot Table with Extra Columns</h2>

        <p class="text-white/70 mb-6">
            Sometimes you need <strong class="text-neon-pink">extra data on the relationship itself</strong>.
        </p>

        {{-- Migration --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Migration with Extra Columns</h3>
            <div class="code-info">
                <pre><code class="language-php">Schema::create('badge_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->timestamp('earned_at');  // When was it earned?
    $table->text('notes')->nullable();
    $table->timestamps();
});</code></pre>
            </div>
        </div>

        {{-- Access Pivot Data --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-green mb-4">Access Pivot Data</h3>
            <div class="code-right">
                <pre><code class="language-php">// Tell Laravel which pivot columns to load
public function badges(): BelongsToMany
{
    return $this->belongsToMany(Badge::class)
                ->withPivot('earned_at', 'notes')
                ->withTimestamps();
}

// Access pivot data
foreach ($user->badges as $badge) {
    echo $badge->pivot->earned_at;
    echo $badge->pivot->notes;
}</code></pre>
            </div>
        </div>

        {{-- Attach with Pivot Data --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-pink mb-4">Attach with Pivot Data</h3>
            <div class="code-right">
                <pre><code class="language-php">$user->badges()->attach($badgeId, [
    'earned_at' => now(),
    'notes' => 'First task completed!',
]);</code></pre>
            </div>
        </div>
    </section>

    {{-- FlexBoard Examples --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">FlexBoard Examples</h2>

        <div class="space-y-6">
            {{-- Task <-> Tag --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Task ↔ Tag (Simple)</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Task.php
public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class, 'task_tag');
}

// Tag.php
public function tasks(): BelongsToMany
{
    return $this->belongsToMany(Task::class, 'task_tag');
}

// Usage
$task->tags()->attach([1, 2, 3]);
$task->tags;  // Collection of Tag models</code></pre>
                </div>
            </div>

            {{-- User <-> Badge --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">User ↔ Badge (With Pivot Data)</h3>
                <div class="code-right">
                    <pre><code class="language-php">// User.php
public function badges(): BelongsToMany
{
    return $this->belongsToMany(Badge::class)
                ->withPivot('earned_at', 'notes')
                ->withTimestamps();
}

// Badge.php
public function users(): BelongsToMany
{
    return $this->belongsToMany(User::class)
                ->withPivot('earned_at', 'notes')
                ->withTimestamps();
}

// Award a badge
$user->badges()->attach($badge->id, [
    'earned_at' => now(),
    'notes' => 'Completed 10 tasks!',
]);

// Check when badge was earned
$user->badges->first()->pivot->earned_at;</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Querying Many-to-Many --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Querying Many-to-Many</h2>

        <div class="space-y-6">
            {{-- Filter by Related Models --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Filter by Related Models</h3>
                <div class="code-info">
                    <pre><code class="language-php">// Tasks with a specific tag
Task::whereHas('tags', function ($query) {
    $query->where('name', 'urgent');
})->get();

// Tasks with ANY of these tags
Task::whereHas('tags', function ($query) {
    $query->whereIn('name', ['urgent', 'bug']);
})->get();</code></pre>
                </div>
            </div>

            {{-- Count Related Models --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">Count Related Models</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Users with badge count
User::withCount('badges')->get();

// $user->badges_count is now available</code></pre>
                </div>
            </div>

            {{-- Filter by Pivot Data --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Filter by Pivot Data</h3>
                <div class="code-info">
                    <pre><code class="language-php">// Users who earned badge after a date
$badge->users()
      ->wherePivot('earned_at', '>', now()->subWeek())
      ->get();</code></pre>
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
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Purpose</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-green">attach($ids)</td>
                        <td class="py-3 px-4 text-white/80">Add relationships</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-pink">detach($ids)</td>
                        <td class="py-3 px-4 text-white/80">Remove relationships</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">sync($ids)</td>
                        <td class="py-3 px-4 text-white/80">Replace all relationships</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-purple">syncWithoutDetaching($ids)</td>
                        <td class="py-3 px-4 text-white/80">Add without removing</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-orange">toggle($ids)</td>
                        <td class="py-3 px-4 text-white/80">Flip attachment state</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-green">$model->pivot</td>
                        <td class="py-3 px-4 text-white/80">Access pivot data</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-pink">withPivot()</td>
                        <td class="py-3 px-4 text-white/80">Include pivot columns</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-cyan">withTimestamps()</td>
                        <td class="py-3 px-4 text-white/80">Auto-manage pivot timestamps</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Hands-On Exercise</h2>

        <div class="glass-card p-6">
            <ol class="list-decimal list-inside space-y-3 text-white/80 mb-6">
                <li>Create Tag and Badge models with migrations</li>
                <li>Create pivot table migrations (<code class="text-neon-cyan">task_tag</code>, <code class="text-neon-cyan">badge_user</code>)</li>
                <li>Add <code class="text-neon-pink">belongsToMany</code> relationships to models</li>
                <li>Test in tinker:</li>
            </ol>

            <div class="code-info">
                <pre><code class="language-php">$task = Task::first();
$task->tags()->attach([1, 2]);
$task->tags;  // See the tags!

$user = User::first();
$user->badges()->attach(1, ['earned_at' => now()]);
$user->badges->first()->pivot->earned_at;</code></pre>
            </div>
        </div>
    </section>

    {{-- Git Checkout --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">Try the Code</h3>
            <p class="text-white/70 mb-4">Check out this branch to see the code in action:</p>
            <div class="code-info">
                <pre><code class="language-bash">git checkout 07-many-to-many</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
