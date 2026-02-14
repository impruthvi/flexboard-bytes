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
                    <span>How Laravel auto-manages <code class="text-neon-pink">created_at</code> and <code class="text-neon-pink">updated_at</code></span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How soft deletes work (mark as deleted vs actually delete)</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to restore soft-deleted records</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Querying with and without soft-deleted records</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- Timestamps --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-4">Timestamps: Auto-Magic Date Management</h2>
        <div class="glass-card p-6">
            <p class="text-white/80 mb-4">
                When you have <code class="text-neon-cyan">$table->timestamps()</code> in your migration, Laravel automatically:
            </p>
            <ul class="space-y-2 text-white/80">
                <li class="flex items-start gap-3">
                    <span class="text-neon-green">•</span>
                    <span>Sets <code class="text-neon-pink">created_at</code> when you <strong>CREATE</strong> a record</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-cyan">•</span>
                    <span>Updates <code class="text-neon-pink">updated_at</code> when you <strong>UPDATE</strong> a record</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- Timestamp Example --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">How Timestamps Work</h2>

        <div class="code-right">
            <pre><code class="language-php">$project = Project::create(['name' => 'FlexBoard']);
// created_at: 2024-01-15 10:30:00
// updated_at: 2024-01-15 10:30:00

$project->update(['name' => 'FlexBoard Pro']);
// created_at: 2024-01-15 10:30:00 (unchanged)
// updated_at: 2024-01-15 10:45:00 (auto-updated!)</code></pre>
        </div>
    </section>

    {{-- Customizing Timestamps --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Customizing Timestamps</h2>

        <div class="code-info">
            <pre><code class="language-php">class Project extends Model
{
    // Disable timestamps entirely
    public $timestamps = false;

    // Use custom column names
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_modified';
}

// Touch (update updated_at) without changing anything else
$project->touch();</code></pre>
        </div>
    </section>

    {{-- Soft Deletes Problem --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">The Problem with Hard Deletes</h2>

        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <h3 class="font-semibold text-neon-pink mb-3">Hard Delete (Permanent)</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">// GONE FOREVER! 😱
$project->delete();

// User: "Wait, I didn't mean to delete that!"
// You: "Sorry, it's gone..."</code></pre>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-neon-green mb-3">Soft Delete (Recoverable)</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Record stays in database
$project->delete();

// User: "I need that back!"
// You: "No problem!" 😎
$project->restore();</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Setting Up Soft Deletes --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Setting Up Soft Deletes</h2>

        <div class="space-y-6">
            {{-- Step 1 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Step 1: Add the Column</h3>
                <div class="code-info">
                    <pre><code class="language-php">// In your migration
Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
    $table->softDeletes();  // Adds 'deleted_at' column
});</code></pre>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Step 2: Use the Trait</h3>
                <div class="code-right">
                    <pre><code class="language-php">use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;  // Enable soft deletes!
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Working with Soft Deletes --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Working with Soft Deletes</h2>

        <div class="space-y-6">
            {{-- Deleting --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Deleting (Soft)</h3>
                <div class="code-info">
                    <pre><code class="language-php">$project->delete();
// Sets deleted_at = now()
// Record is NOT removed from database!</code></pre>
                </div>
            </div>

            {{-- Querying --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Querying (Auto-Excludes Deleted)</h3>
                <div class="code-info">
                    <pre><code class="language-php">// By default, soft-deleted records are HIDDEN
Project::all();  // Only returns non-deleted projects

// Explicitly include soft-deleted
Project::withTrashed()->get();

// ONLY get soft-deleted
Project::onlyTrashed()->get();</code></pre>
                </div>
            </div>

            {{-- Restoring --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">Restoring</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Bring it back!
$project->restore();
// Sets deleted_at = null</code></pre>
                </div>
            </div>

            {{-- Force Deleting --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-orange mb-4">Force Deleting (Permanent)</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">// When you REALLY want to delete permanently
$project->forceDelete();
// Record is GONE from database!</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Checking Status --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Checking Soft Delete Status</h2>

        <div class="code-info">
            <pre><code class="language-php">if ($project->trashed()) {
    echo "This project is soft-deleted";
}

// Check in Blade
@@if($project->trashed())
    &lt;span class="text-red-500"&gt;Deleted&lt;/span&gt;
@@endif</code></pre>
        </div>
    </section>

    {{-- Cascading Soft Deletes --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Cascading Soft Deletes</h2>

        <p class="text-white/70 mb-6">
            When a project is deleted, should its tasks be deleted too? Handle this in the model:
        </p>

        <div class="code-right">
            <pre><code class="language-php">class Project extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        // Soft delete all tasks when project is soft-deleted
        static::deleting(function (Project $project) {
            $project->tasks()->delete();
        });

        // Restore tasks when project is restored
        static::restoring(function (Project $project) {
            $project->tasks()->withTrashed()->restore();
        });
    }
}</code></pre>
        </div>
    </section>

    {{-- Quick Reference --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">📋 Quick Reference</h2>

        <div class="glass-card p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-glass-border">
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Operation</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Code</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Result</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-pink">Soft delete</td>
                        <td class="py-3 px-4 text-white/80">$model->delete()</td>
                        <td class="py-3 px-4 text-neon-cyan">Sets deleted_at</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-green">Restore</td>
                        <td class="py-3 px-4 text-white/80">$model->restore()</td>
                        <td class="py-3 px-4 text-neon-cyan">Clears deleted_at</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-orange">Force delete</td>
                        <td class="py-3 px-4 text-white/80">$model->forceDelete()</td>
                        <td class="py-3 px-4 text-neon-cyan">Removes from DB</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-purple">Get all</td>
                        <td class="py-3 px-4 text-white/80">Model::all()</td>
                        <td class="py-3 px-4 text-neon-cyan">Excludes deleted</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-purple">Include deleted</td>
                        <td class="py-3 px-4 text-white/80">Model::withTrashed()</td>
                        <td class="py-3 px-4 text-neon-cyan">All records</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-purple">Only deleted</td>
                        <td class="py-3 px-4 text-white/80">Model::onlyTrashed()</td>
                        <td class="py-3 px-4 text-neon-cyan">Only deleted</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-purple">Check if deleted</td>
                        <td class="py-3 px-4 text-white/80">$model->trashed()</td>
                        <td class="py-3 px-4 text-neon-cyan">Returns bool</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Common Gotchas --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">⚠️ Common Gotchas</h2>

        <div class="space-y-6">
            {{-- Unique Constraints --}}
            <div class="glass-card p-6 border-neon-orange/30">
                <h3 class="font-semibold text-neon-orange mb-4">1. Unique Constraints</h3>
                <div class="code-wrong mb-4">
                    <pre><code class="language-php">// Problem: Deleted "FlexBoard" blocks creating new "FlexBoard"
$table->string('slug')->unique();</code></pre>
                </div>
                <div class="code-right">
                    <pre><code class="language-php">// Solution: Use unique validation with withoutTrashed
Rule::unique('projects')->withoutTrashed();</code></pre>
                </div>
            </div>

            {{-- Relationships Still Work --}}
            <div class="glass-card p-6 border-neon-cyan/30">
                <h3 class="font-semibold text-neon-cyan mb-4">2. Relationships Still Work</h3>
                <div class="code-info">
                    <pre><code class="language-php">// Even if project is soft-deleted, you can still access it
$task->project;  // Works! Returns the soft-deleted project

// To explicitly include soft-deleted in relationship
$task->project()->withTrashed()->first();</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">🛠️ Hands-On Exercise</h2>

        <div class="glass-card p-6">
            <ol class="list-decimal list-inside space-y-3 text-white/80 mb-6">
                <li>Add <code class="text-neon-cyan">SoftDeletes</code> to your Project and Task models</li>
                <li>Add <code class="text-neon-cyan">$table->softDeletes()</code> to the migrations</li>
                <li>Run migrations fresh</li>
                <li>Test in tinker:</li>
            </ol>

            <div class="code-info">
                <pre><code class="language-php">$project = Project::first();
$project->delete();
Project::all()->count();  // 0 (hidden)
Project::withTrashed()->count();  // 1 (visible)
$project->restore();
Project::all()->count();  // 1 (back!)</code></pre>
            </div>
        </div>
    </section>

    {{-- Git Checkout --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">🔀 Try the Code</h3>
            <p class="text-white/70 mb-4">Check out this branch to see the code in action:</p>
            <div class="code-info">
                <pre><code class="language-bash">git checkout 05-timestamps-softdeletes</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
