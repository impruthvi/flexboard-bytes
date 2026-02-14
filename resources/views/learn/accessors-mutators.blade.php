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
                    <span>How to transform data when reading (accessors)</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to transform data when writing (mutators)</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to use attribute casting for automatic type conversion</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Laravel 9+ attribute syntax using <code class="text-neon-pink">Attribute</code> class</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- What Are Accessors & Mutators --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-4">What Are Accessors & Mutators?</h2>
        <div class="glass-card p-6">
            <ul class="space-y-3 text-white/80">
                <li class="flex items-start gap-3">
                    <span class="text-neon-cyan font-bold">Accessor:</span>
                    <span>Transforms data when you <strong class="text-neon-green">READ</strong> from the model</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-pink font-bold">Mutator:</span>
                    <span>Transforms data when you <strong class="text-neon-orange">WRITE</strong> to the model</span>
                </li>
            </ul>
            <p class="mt-4 text-white/60 text-sm">
                Think of them as "data transformers" that sit between your code and the database.
            </p>
        </div>
    </section>

    {{-- Modern Syntax --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Modern Syntax (Laravel 9+)</h2>

        <p class="text-white/70 mb-6">
            Laravel 9+ uses the <code class="text-neon-cyan">Attribute</code> class for a cleaner, unified syntax:
        </p>

        <div class="code-info">
            <pre><code class="language-php">use Illuminate\Database\Eloquent\Casts\Attribute;

protected function firstName(): Attribute
{
    return Attribute::make(
        get: fn (string $value) => ucfirst($value),  // Accessor
        set: fn (string $value) => strtolower($value), // Mutator
    );
}</code></pre>
        </div>
    </section>

    {{-- Accessors --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Accessors: Transform on Read</h2>

        {{-- Example 1 --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Example: Format a Name</h3>
            <div class="code-right">
                <pre><code class="language-php">// Database stores: "priya sharma"
// You want to display: "Priya Sharma"

protected function name(): Attribute
{
    return Attribute::make(
        get: fn (string $value) => ucwords($value),
    );
}

// Usage
$user->name; // "Priya Sharma" (even though DB has "priya sharma")</code></pre>
            </div>
        </div>

        {{-- Example 2 --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Example: Computed/Virtual Attribute</h3>
            <div class="code-right">
                <pre><code class="language-php">// Create an attribute that doesn't exist in the database

protected function fullName(): Attribute
{
    return Attribute::make(
        get: fn () => "{$this->first_name} {$this->last_name}",
    );
}

// Usage
$user->full_name; // "Priya Sharma"</code></pre>
            </div>
        </div>
    </section>

    {{-- Mutators --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Mutators: Transform on Write</h2>

        {{-- Example 1 --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-pink mb-4">Example: Auto-Lowercase Email</h3>
            <div class="code-right">
                <pre><code class="language-php">protected function email(): Attribute
{
    return Attribute::make(
        set: fn (string $value) => strtolower($value),
    );
}

// Usage
$user->email = 'PRIYA@EXAMPLE.COM';
$user->save();
// Database stores: "priya@example.com"</code></pre>
            </div>
        </div>

        {{-- Example 2 --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-pink mb-4">Example: Hash Password Automatically</h3>
            <div class="code-right">
                <pre><code class="language-php">protected function password(): Attribute
{
    return Attribute::make(
        set: fn (string $value) => bcrypt($value),
    );
}

// Usage
$user->password = 'secret123';
// Database stores: "$2y$10$..." (hashed)</code></pre>
            </div>
        </div>
    </section>

    {{-- Attribute Casting --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Attribute Casting</h2>

        <p class="text-white/70 mb-6">
            Casting automatically converts attributes to common data types:
        </p>

        <div class="code-info mb-6">
            <pre><code class="language-php">protected function casts(): array
{
    return [
        'is_completed' => 'boolean',
        'points' => 'integer',
        'settings' => 'array',
        'completed_at' => 'datetime',
        'metadata' => 'object',
        'price' => 'decimal:2',
    ];
}</code></pre>
        </div>

        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Cast Examples</h3>
            <div class="code-info">
                <pre><code class="language-php">// Database: is_completed = 1 (integer)
$task->is_completed; // true (boolean)

// Database: settings = '{"theme":"dark"}'
$task->settings; // ['theme' => 'dark'] (array)
$task->settings['theme']; // "dark"

// Database: completed_at = "2024-01-15 10:30:00"
$task->completed_at; // Carbon instance
$task->completed_at->diffForHumans(); // "2 hours ago"</code></pre>
            </div>
        </div>
    </section>

    {{-- FlexBoard Examples --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Practical FlexBoard Examples</h2>

        <div class="space-y-6">
            {{-- Priority Color --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Task Model: Priority Color</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Get a color based on priority
protected function priorityColor(): Attribute
{
    return Attribute::make(
        get: fn () => match($this->priority) {
            'low' => '#22c55e',    // green
            'medium' => '#f59e0b', // amber
            'high' => '#ef4444',   // red
            default => '#6b7280',  // gray
        },
    );
}

// Usage in Blade
&lt;span style="color: {{ $task->priority_color }}"&gt;
    {{ $task->priority }}
&lt;/span&gt;</code></pre>
                </div>
            </div>

            {{-- Completion Percentage --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Project Model: Completion Percentage</h3>
                <div class="code-right">
                    <pre><code class="language-php">protected function completionPercentage(): Attribute
{
    return Attribute::make(
        get: function () {
            $total = $this->tasks()->count();
            if ($total === 0) return 0;
            
            $completed = $this->tasks()
                ->where('is_completed', true)->count();
            return round(($completed / $total) * 100);
        },
    );
}

// Usage
$project->completion_percentage; // 75</code></pre>
                </div>
            </div>

            {{-- Auto-Generate Slug --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Auto-Generate Slug</h3>
                <div class="code-right">
                    <pre><code class="language-php">protected function name(): Attribute
{
    return Attribute::make(
        set: function (string $value) {
            return [
                'name' => $value,
                'slug' => Str::slug($value),
            ];
        },
    );
}

// Usage
$project->name = 'My Awesome Project';
// name = "My Awesome Project"
// slug = "my-awesome-project" (auto-generated!)</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Appending to JSON --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Appending Computed Attributes to JSON</h2>

        <p class="text-white/70 mb-6">
            For API responses, append virtual attributes:
        </p>

        <div class="code-info">
            <pre><code class="language-php">protected $appends = ['full_name', 'completion_percentage'];

// Now when you do:
return $project->toJson();
// These computed attributes are included!</code></pre>
        </div>
    </section>

    {{-- Quick Reference --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">📋 Quick Reference</h2>

        <div class="glass-card p-6 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-glass-border">
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Feature</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Purpose</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Example</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-cyan">Accessor (get)</td>
                        <td class="py-3 px-4 text-white/80">Transform on read</td>
                        <td class="py-3 px-4 text-neon-green">ucwords($name)</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-pink">Mutator (set)</td>
                        <td class="py-3 px-4 text-white/80">Transform on write</td>
                        <td class="py-3 px-4 text-neon-green">strtolower($email)</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-purple">Casting</td>
                        <td class="py-3 px-4 text-white/80">Auto type conversion</td>
                        <td class="py-3 px-4 text-neon-green">'boolean', 'array'</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-orange">$appends</td>
                        <td class="py-3 px-4 text-white/80">Include in JSON</td>
                        <td class="py-3 px-4 text-neon-green">Virtual attributes</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">🛠️ Hands-On Exercise</h2>

        <div class="glass-card p-6">
            <p class="text-white/70 mb-4">Update your Task model with:</p>
            <ol class="list-decimal list-inside space-y-2 text-white/80 mb-6">
                <li>A <code class="text-neon-cyan">priority_color</code> accessor</li>
                <li>A <code class="text-neon-cyan">difficulty_label</code> accessor</li>
                <li>Cast <code class="text-neon-pink">is_completed</code> to boolean</li>
                <li>Cast <code class="text-neon-pink">completed_at</code> to datetime</li>
            </ol>
            <p class="text-white/60 text-sm">Then test in tinker!</p>
        </div>
    </section>

    {{-- Git Checkout --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">🔀 Try the Code</h3>
            <p class="text-white/70 mb-4">Check out this branch to see the code in action:</p>
            <div class="code-info">
                <pre><code class="language-bash">git checkout 03-accessors-mutators</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
