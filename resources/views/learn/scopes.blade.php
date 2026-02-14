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
                    <span>What query scopes are and why they're useful</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to create local scopes using the modern <code class="text-neon-pink">#[Scope]</code> attribute (Laravel 12+)</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>The legacy <code class="text-neon-cyan">scopeMethodName()</code> convention (Laravel 10 and earlier)</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to make scopes dynamic with parameters</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Global scopes that apply automatically to all queries</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- What Are Query Scopes --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-4">What Are Query Scopes?</h2>
        <div class="glass-card p-6">
            <p class="text-white/80 mb-4">
                Scopes are <strong class="text-neon-cyan">reusable query constraints</strong>. Instead of repeating the same <code class="text-neon-pink">where()</code> clauses everywhere, you define them once in your model.
            </p>
        </div>
    </section>

    {{-- Without vs With Scopes --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Without vs With Scopes</h2>

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Without Scopes --}}
            <div>
                <h3 class="font-semibold text-neon-pink mb-3">Without Scopes (Repetitive)</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">// In controller A
$tasks = Task::where('is_completed', false)->get();

// In controller B
$tasks = Task::where('is_completed', false)
    ->where('priority', 'high')->get();

// In a job
$tasks = Task::where('is_completed', false)->count();</code></pre>
                </div>
            </div>

            {{-- With Scopes --}}
            <div>
                <h3 class="font-semibold text-neon-green mb-3">With Scopes (DRY)</h3>
                <div class="code-right">
                    <pre><code class="language-php">// In controller A
$tasks = Task::incomplete()->get();

// In controller B
$tasks = Task::incomplete()->highPriority()->get();

// In a job
$tasks = Task::incomplete()->count();</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Modern Syntax - #[Scope] Attribute --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Modern Syntax: #[Scope] Attribute <span class="chip chip-green ml-2">Laravel 11+</span></h2>

        <p class="text-white/70 mb-6">
            Laravel 11 introduced the <code class="text-neon-cyan">#[Scope]</code> PHP attribute for defining local scopes. This is now the <strong class="text-neon-green">recommended approach</strong>.
        </p>

        <div class="code-right mb-6">
            <pre><code class="language-php">&lt;?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Scope a query to only include incomplete tasks.
     */
    #[Scope]
    protected function incomplete(Builder $query): void
    {
        $query->where('is_completed', false);
    }

    /**
     * Scope a query to only include high priority tasks.
     */
    #[Scope]
    protected function highPriority(Builder $query): void
    {
        $query->where('priority', 'high');
    }
}</code></pre>
        </div>

        <div class="glass-card p-6 border-neon-cyan/30">
            <h3 class="font-semibold text-neon-cyan mb-3">Key Points</h3>
            <ul class="space-y-2 text-white/80 text-sm">
                <li class="flex items-start gap-2">
                    <span class="text-neon-cyan">•</span>
                    <span>Method is <code class="text-neon-pink">protected</code> (not public)</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-neon-cyan">•</span>
                    <span>Returns <code class="text-neon-pink">void</code> - just modifies the query</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-neon-cyan">•</span>
                    <span>First parameter is always <code class="text-neon-pink">Builder $query</code></span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-neon-cyan">•</span>
                    <span>Import: <code class="text-neon-pink">Illuminate\Database\Eloquent\Attributes\Scope</code></span>
                </li>
            </ul>
        </div>
    </section>

    {{-- Legacy Syntax --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Legacy Syntax: scopeMethodName() <span class="chip chip-purple ml-2">Laravel 10 &amp; Earlier</span></h2>

        <p class="text-white/70 mb-6">
            Before the <code class="text-neon-cyan">#[Scope]</code> attribute, scopes were defined using a naming convention: prefix with <code class="text-neon-pink">scope</code>.
        </p>

        <div class="code-info mb-6">
            <pre><code class="language-php">class Task extends Model
{
    /**
     * Legacy scope syntax - prefix method with "scope"
     */
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->where('priority', 'high');
    }
}

// Usage - no "scope" prefix when calling!
Task::incomplete()->highPriority()->get();</code></pre>
        </div>

        <div class="glass-card p-6 border-neon-orange/30">
            <h3 class="font-semibold text-neon-orange mb-3">Legacy vs Modern Comparison</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-glass-border">
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Aspect</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Legacy (scope prefix)</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Modern (#[Scope])</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-xs">
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">Definition</td>
                            <td class="py-3 px-4 text-neon-pink">scopeIncomplete()</td>
                            <td class="py-3 px-4 text-neon-green">#[Scope] incomplete()</td>
                        </tr>
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">Visibility</td>
                            <td class="py-3 px-4 text-neon-pink">public</td>
                            <td class="py-3 px-4 text-neon-green">protected</td>
                        </tr>
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">Return</td>
                            <td class="py-3 px-4 text-neon-pink">Builder</td>
                            <td class="py-3 px-4 text-neon-green">void</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-white/80">Usage</td>
                            <td class="py-3 px-4 text-neon-cyan" colspan="2">Task::incomplete()->get()</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Dynamic Scopes --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Dynamic Scopes (With Parameters)</h2>

        <p class="text-white/70 mb-6">
            Sometimes you need scopes that accept parameters. Add parameters after the <code class="text-neon-cyan">$query</code> parameter.
        </p>

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Modern --}}
            <div>
                <h3 class="font-semibold text-neon-green mb-3">Modern (#[Scope])</h3>
                <div class="code-right">
                    <pre><code class="language-php">#[Scope]
protected function ofPriority(
    Builder $query, 
    string $priority
): void {
    $query->where('priority', $priority);
}

#[Scope]
protected function highValue(
    Builder $query, 
    int $minPoints = 50
): void {
    $query->where('points', '>=', $minPoints);
}

// Usage
Task::ofPriority('high')->get();
Task::highValue(100)->get();</code></pre>
                </div>
            </div>

            {{-- Legacy --}}
            <div>
                <h3 class="font-semibold text-neon-purple mb-3">Legacy (scope prefix)</h3>
                <div class="code-info">
                    <pre><code class="language-php">public function scopeOfPriority(
    Builder $query, 
    string $priority
): Builder {
    return $query->where('priority', $priority);
}

public function scopeHighValue(
    Builder $query, 
    int $minPoints = 50
): Builder {
    return $query->where('points', '>=', $minPoints);
}

// Usage - same!
Task::ofPriority('high')->get();
Task::highValue(100)->get();</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Chaining Scopes --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Chaining Scopes</h2>

        <p class="text-white/70 mb-6">
            Scopes can be chained together for powerful, readable queries:
        </p>

        <div class="code-right">
            <pre><code class="language-php">// Chain multiple scopes - reads like English!
$tasks = Task::incomplete()
    ->highPriority()
    ->createdToday()
    ->orderBy('due_date')
    ->get();

// Combine with regular query methods
$tasks = Task::incomplete()
    ->where('user_id', auth()->id())
    ->withCount('comments')
    ->paginate(20);</code></pre>
        </div>
    </section>

    {{-- Global Scopes --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Global Scopes</h2>

        <p class="text-white/70 mb-6">
            Global scopes apply <strong class="text-neon-orange">automatically to ALL queries</strong> on a model. Use them carefully!
        </p>

        {{-- Anonymous Global Scope --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Anonymous Global Scope</h3>
            <div class="code-info">
                <pre><code class="language-php">class Task extends Model
{
    protected static function booted(): void
    {
        // Only show active tasks by default
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }
}

// Now EVERY query is filtered!
Task::all(); // Only returns active tasks
Task::find(1); // Returns null if task is inactive!</code></pre>
            </div>
        </div>

        {{-- Removing Global Scopes --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-pink mb-4">Removing Global Scopes</h3>
            <div class="code-info">
                <pre><code class="language-php">// Remove a specific global scope
Task::withoutGlobalScope('active')->get();

// Remove ALL global scopes
Task::withoutGlobalScopes()->get();

// Remove multiple specific scopes
Task::withoutGlobalScopes(['active', 'team'])->get();</code></pre>
            </div>
        </div>
    </section>

    {{-- FlexBoard Examples --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Practical FlexBoard Examples</h2>

        <div class="code-right">
            <pre><code class="language-php">class Task extends Model
{
    // Status scopes
    #[Scope]
    protected function incomplete(Builder $query): void
    {
        $query->where('is_completed', false);
    }

    #[Scope]
    protected function completed(Builder $query): void
    {
        $query->where('is_completed', true);
    }

    // Priority scopes
    #[Scope]
    protected function highPriority(Builder $query): void
    {
        $query->where('priority', 'high');
    }

    #[Scope]
    protected function urgent(Builder $query): void
    {
        $query->where('priority', 'high')
              ->where('is_completed', false);
    }

    // Dynamic scope
    #[Scope]
    protected function ofPriority(Builder $query, string $priority): void
    {
        $query->where('priority', $priority);
    }

    // Date scopes
    #[Scope]
    protected function createdToday(Builder $query): void
    {
        $query->whereDate('created_at', today());
    }

    #[Scope]
    protected function completedThisWeek(Builder $query): void
    {
        $query->where('is_completed', true)
              ->whereBetween('completed_at', [
                  now()->startOfWeek(),
                  now()->endOfWeek(),
              ]);
    }
}</code></pre>
        </div>

        <div class="glass-card p-6 mt-6">
            <h3 class="font-semibold text-neon-purple mb-4">Real Usage</h3>
            <div class="code-info">
                <pre><code class="language-php">// Dashboard: Today's urgent tasks
$urgentTasks = Task::urgent()->createdToday()->get();

// Leaderboard: This week's completed high-value tasks
$weeklyWins = Task::completedThisWeek()->highValue(100)->get();

// Filter by user selection
$tasks = Task::ofPriority($request->priority)
    ->ofDifficulty($request->difficulty)
    ->paginate();</code></pre>
            </div>
        </div>
    </section>

    {{-- Best Practices --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Best Practices</h2>

        <div class="grid gap-6 lg:grid-cols-2">
            {{-- DO --}}
            <div class="glass-card p-6 border-neon-green/30">
                <h3 class="font-semibold text-neon-green mb-4">DO ✅</h3>
                <div class="code-right">
                    <pre><code class="language-php">// 1. Use descriptive scope names
#[Scope]
protected function published(Builder $query): void

// 2. Keep scopes focused (single responsibility)
#[Scope]
protected function highPriority(Builder $query): void

// 3. Use parameter scopes for flexibility
#[Scope]
protected function ofStatus(
    Builder $query, 
    string $status
): void

// 4. Add type hints everywhere
#[Scope]
protected function active(Builder $query): void</code></pre>
                </div>
            </div>

            {{-- DON'T --}}
            <div class="glass-card p-6 border-neon-pink/30">
                <h3 class="font-semibold text-neon-pink mb-4">DON'T ❌</h3>
                <div class="code-wrong">
                    <pre><code class="language-php">// 1. Don't put business logic in scopes
#[Scope]
protected function withCalculations(Builder $query): void
{
    // Don't do complex calculations here!
}

// 2. Don't make scopes too broad
#[Scope]
protected function filtered(Builder $query): void
{
    // What does "filtered" mean? Be specific!
}

// 3. Don't forget type hints
protected function bad($query)  // Missing types!</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Quick Reference --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">📋 Quick Reference</h2>

        <div class="glass-card p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-glass-border">
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Scope Type</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Definition</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Usage</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">Local (Modern)</td>
                        <td class="py-3 px-4 text-neon-green">#[Scope] name(Builder $query)</td>
                        <td class="py-3 px-4 text-white/80">Model::name()</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">Local (Legacy)</td>
                        <td class="py-3 px-4 text-neon-purple">scopeName(Builder $query)</td>
                        <td class="py-3 px-4 text-white/80">Model::name()</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">Dynamic</td>
                        <td class="py-3 px-4 text-neon-green">#[Scope] name(Builder $query, $param)</td>
                        <td class="py-3 px-4 text-white/80">Model::name($value)</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">Global</td>
                        <td class="py-3 px-4 text-neon-orange">addGlobalScope() in booted()</td>
                        <td class="py-3 px-4 text-white/80">Auto-applied</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-cyan">Remove Global</td>
                        <td class="py-3 px-4 text-neon-pink">-</td>
                        <td class="py-3 px-4 text-white/80">withoutGlobalScope()</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">🛠️ Hands-On Exercise</h2>

        <div class="glass-card p-6">
            <p class="text-white/70 mb-4">Add these scopes to your Task model:</p>
            <ol class="list-decimal list-inside space-y-2 text-white/80 mb-6">
                <li><code class="text-neon-cyan">incomplete()</code> - not completed tasks</li>
                <li><code class="text-neon-cyan">highPriority()</code> - priority = 'high'</li>
                <li><code class="text-neon-cyan">ofPriority($priority)</code> - dynamic priority filter</li>
                <li><code class="text-neon-cyan">createdToday()</code> - created today</li>
            </ol>

            <p class="text-white/60 text-sm mb-4">Then test in tinker:</p>
            <div class="code-info">
                <pre><code class="language-bash">php artisan tinker</code></pre>
            </div>
            <div class="code-info mt-3">
                <pre><code class="language-php">Task::incomplete()->highPriority()->get();
Task::ofPriority('low')->createdToday()->count();</code></pre>
            </div>
        </div>
    </section>

    {{-- Git Checkout --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">🔀 Try the Code</h3>
            <p class="text-white/70 mb-4">Check out this branch to see the code in action:</p>
            <div class="code-info">
                <pre><code class="language-bash">git checkout 04-scopes</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
