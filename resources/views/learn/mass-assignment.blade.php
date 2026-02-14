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
                    <span>What mass assignment is and why it's dangerous</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to use <code class="text-neon-pink">$fillable</code> to whitelist attributes</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to use <code class="text-neon-pink">$guarded</code> to blacklist attributes</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Best practices for protecting your models</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- What is Mass Assignment --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-4">What is Mass Assignment?</h2>
        <p class="text-white/70 leading-relaxed mb-6">
            Mass assignment is when you set multiple model attributes at once using an array:
        </p>
        <div class="code-info">
            <pre><code class="language-php">// This is mass assignment
$project = Project::create([
    'name' => 'FlexBoard MVP',
    'description' => 'The best task tracker ever',
    'user_id' => 1,
]);

// This is also mass assignment
$project->update($request->all());</code></pre>
        </div>
    </section>

    {{-- The Danger --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">The Danger: Mass Assignment Vulnerability</h2>

        {{-- Attack Scenario --}}
        <div class="glass-card p-6 mb-6 border-neon-orange/30">
            <h3 class="font-semibold text-neon-orange mb-4">⚠️ The Attack Scenario</h3>
            <p class="text-white/70 mb-4">Imagine your User model has an <code class="text-neon-pink">is_admin</code> column. A malicious user could:</p>
            <div class="code-wrong">
                <pre><code class="language-php">// Your innocent form
POST /register
{
    "name": "Priya Sharma",
    "email": "priya@example.com",
    "password": "secret123"
}

// Hacker's modified request
POST /register
{
    "name": "Hacker Singh",
    "email": "hacker@evil.com",
    "password": "secret123",
    "is_admin": true  // <- INJECTED!
}</code></pre>
            </div>
        </div>

        {{-- Vulnerable Code --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-pink">❌ NEVER DO THIS</span>
            </div>
            <div class="code-wrong">
                <pre><code class="language-php">// DANGEROUS - accepts any field from request
User::create($request->all());

// Hacker is now an admin! 🚨</code></pre>
            </div>
        </div>
    </section>

    {{-- Solution 1: $fillable --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Solution 1: $fillable (Whitelist)</h2>

        <p class="text-white/70 mb-6">Define which attributes <strong class="text-neon-green">ARE</strong> mass assignable.</p>

        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-green">✅ Recommended</span>
            </div>
            <div class="code-right">
                <pre><code class="language-php">class Project extends Model
{
    // Only these fields can be mass assigned
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];
    
    // 'is_admin', 'total_points', etc. are protected!
}</code></pre>
            </div>
        </div>

        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-green mb-3">Now the attack fails:</h3>
            <div class="code-right">
                <pre><code class="language-php">Project::create([
    'name' => 'Legit Project',
    'is_admin' => true,  // IGNORED! Not in $fillable
]);</code></pre>
            </div>
        </div>
    </section>

    {{-- Solution 2: $guarded --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Solution 2: $guarded (Blacklist)</h2>

        <p class="text-white/70 mb-6">Define which attributes are <strong class="text-neon-pink">NOT</strong> mass assignable.</p>

        <div class="mb-6">
            <div class="code-info">
                <pre><code class="language-php">class Project extends Model
{
    // These fields CANNOT be mass assigned
    protected $guarded = [
        'id',
        'is_admin',
        'total_points',
    ];
    
    // Everything else is allowed
}</code></pre>
            </div>
        </div>

        {{-- Empty Guarded Warning --}}
        <div class="glass-card p-6 border-neon-orange/30">
            <h3 class="font-semibold text-neon-orange mb-3">⚠️ Special Case: Empty Guarded</h3>
            <div class="code-wrong">
                <pre><code class="language-php">// DANGER: Allows ALL fields to be mass assigned
protected $guarded = [];

// Only use this if you REALLY know what you're doing
// and are manually validating every input!</code></pre>
            </div>
        </div>
    </section>

    {{-- Best Practices --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Best Practices</h2>

        <div class="grid md:grid-cols-2 gap-6">
            {{-- DO --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4 flex items-center gap-2">
                    <span>✅</span> DO
                </h3>
                <div class="code-right">
                    <pre><code class="language-php">// 1. Always use $fillable (preferred)
protected $fillable = ['name', 'description'];

// 2. Validate input before mass assignment
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
]);
Project::create($validated);

// 3. Set sensitive fields explicitly
$project = Project::create($validated);
$project->user_id = auth()->id();
$project->save();</code></pre>
                </div>
            </div>

            {{-- DON'T --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4 flex items-center gap-2">
                    <span>❌</span> DON'T
                </h3>
                <div class="code-wrong">
                    <pre><code class="language-php">// 1. Never use $request->all() blindly
Project::create($request->all());

// 2. Never leave both empty
class Project extends Model
{
    // No protection = vulnerability!
}

// 3. Don't put sensitive fields in $fillable
protected $fillable = [
    'name',
    'is_admin'  // BAD!
];</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">🛠️ Hands-On Exercise</h2>

        <div class="space-y-6">
            {{-- Step 1 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Step 1: Update Your Project Model</h3>
                <div class="code-right">
                    <pre><code class="language-php">// app/Models/Project.php
class Project extends Model
{
    /**
     * LESSON: Mass Assignment Protection
     * 
     * We whitelist ONLY the fields users should be able to set.
     * Sensitive fields like 'user_id' should be set explicitly.
     */
    protected $fillable = [
        'name',
        'description',
    ];
}</code></pre>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Step 2: Safe Controller Usage</h3>
                <div class="code-right">
                    <pre><code class="language-php">// In your controller
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);
    
    // Mass assign validated data, set user_id explicitly
    $project = new Project($validated);
    $project->user_id = auth()->id();
    $project->save();
    
    // Or use create() with merged data
    $project = Project::create([
        ...$validated,
        'user_id' => auth()->id(),
    ]);
}</code></pre>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Step 3: Test in Tinker</h3>
                <div class="code-info mb-4">
                    <pre><code class="language-bash">php artisan tinker</code></pre>
                </div>
                <div class="code-info">
                    <pre><code class="language-php">// This works (fields are in $fillable)
$project = \App\Models\Project::create([
    'name' => 'Test Project',
    'description' => 'Testing mass assignment',
    'user_id' => 1,
]);

// Try to inject a field not in $fillable
$project = \App\Models\Project::create([
    'name' => 'Hacker Project',
    'is_admin' => true,  // This will be ignored!
]);

$project->is_admin; // null - injection failed!</code></pre>
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
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Approach</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Use When</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Example</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-green">$fillable</td>
                        <td class="py-3 px-4 text-white/80">You know exactly which fields are safe</td>
                        <td class="py-3 px-4 text-neon-cyan">['name', 'email', 'bio']</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-neon-orange">$guarded</td>
                        <td class="py-3 px-4 text-white/80">Most fields are safe, few are sensitive</td>
                        <td class="py-3 px-4 text-neon-cyan">['id', 'is_admin']</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-neon-pink">Empty $guarded</td>
                        <td class="py-3 px-4 text-white/80">Full control via validation (advanced)</td>
                        <td class="py-3 px-4 text-neon-cyan">[]</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Git Checkout --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">🔀 Try the Code</h3>
            <p class="text-white/70 mb-4">Check out this branch to see the code in action:</p>
            <div class="code-info">
                <pre><code class="language-bash">git checkout 02-mass-assignment</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
