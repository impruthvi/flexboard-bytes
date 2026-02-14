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
                    <span>What polymorphic relationships are and when to use them</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How <code class="text-neon-pink">morphTo</code> and <code class="text-neon-pink">morphMany</code> work</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to create polymorphic migrations</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>Nested polymorphism (polymorphic models having polymorphic relationships)</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- What Are Polymorphic Relationships? --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">What Are Polymorphic Relationships?</h2>

        <p class="text-white/70 mb-6">
            Polymorphic relationships allow a model to belong to <strong class="text-neon-cyan">more than one type of model</strong> using a single association.
        </p>

        {{-- Real-World Example --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-pink mb-4">Real-World Example</h3>
            <p class="text-white/70 mb-4">
                Imagine you want to add comments to your app. Users can comment on:
            </p>
            <ul class="space-y-2 text-white/80 mb-4">
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> Tasks
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> Projects
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> Flexes
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-neon-cyan">•</span> Maybe even other Comments!
                </li>
            </ul>
        </div>

        {{-- Without vs With Polymorphism --}}
        <div class="grid md:grid-cols-2 gap-6">
            <div class="glass-card p-6 border-neon-pink/30">
                <h4 class="font-semibold text-neon-pink mb-3">Without Polymorphism</h4>
                <div class="code-wrong">
                    <pre><code class="language-text">// You'd need:
- task_comments table
- project_comments table
- flex_comments table
- Separate models for each!</code></pre>
                </div>
            </div>
            <div class="glass-card p-6 border-neon-green/30">
                <h4 class="font-semibold text-neon-green mb-3">With Polymorphism</h4>
                <div class="code-right">
                    <pre><code class="language-text">// You need:
- ONE comments table
- ONE Comment model
- Works with ANY model!</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Polymorphic Table Structure --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Polymorphic Table Structure</h2>

        <p class="text-white/70 mb-6">
            The magic is in <strong class="text-neon-pink">two special columns</strong>:
        </p>

        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Migration</h3>
            <div class="code-info">
                <pre><code class="language-php">Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->text('body');
    
    // morphs() creates TWO columns:
    // - commentable_id: The ID of the parent (1, 2, 3...)
    // - commentable_type: The class name ("App\Models\Task")
    $table->morphs('commentable');
    
    $table->timestamps();
});</code></pre>
            </div>
        </div>

        {{-- What's in the Database --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-green mb-4">What's in the Database?</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-glass-border">
                            <th class="text-left py-3 px-4 text-white/60 font-medium">id</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">user_id</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">body</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">commentable_id</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">commentable_type</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-xs">
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">1</td>
                            <td class="py-3 px-4 text-white/80">1</td>
                            <td class="py-3 px-4 text-neon-cyan">"Lit task!"</td>
                            <td class="py-3 px-4 text-neon-green">5</td>
                            <td class="py-3 px-4 text-neon-pink">App\Models\Task</td>
                        </tr>
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-white/80">2</td>
                            <td class="py-3 px-4 text-white/80">1</td>
                            <td class="py-3 px-4 text-neon-cyan">"Great project!"</td>
                            <td class="py-3 px-4 text-neon-green">2</td>
                            <td class="py-3 px-4 text-neon-pink">App\Models\Project</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-white/80">3</td>
                            <td class="py-3 px-4 text-white/80">2</td>
                            <td class="py-3 px-4 text-neon-cyan">"Nice flex!"</td>
                            <td class="py-3 px-4 text-neon-green">10</td>
                            <td class="py-3 px-4 text-neon-pink">App\Models\Flex</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-white/60 text-sm mt-4">
                The <strong>SAME</strong> table stores comments for Tasks, Projects, AND Flexes!
            </p>
        </div>
    </section>

    {{-- Setting Up MorphMany --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Setting Up MorphMany (Parent Side)</h2>

        <p class="text-white/70 mb-6">
            On the parent model (Task, Project, etc.), use <code class="text-neon-pink">morphMany()</code>:
        </p>

        <div class="glass-card p-6 mb-6">
            <div class="code-right">
                <pre><code class="language-php">// Task.php
use Illuminate\Database\Eloquent\Relations\MorphMany;

public function comments(): MorphMany
{
    return $this->morphMany(Comment::class, 'commentable');
}

// Project.php - SAME relationship definition!
public function comments(): MorphMany
{
    return $this->morphMany(Comment::class, 'commentable');
}</code></pre>
            </div>
        </div>

        <div class="glass-card p-4 border-neon-orange/30">
            <p class="text-white/70 text-sm">
                The second parameter (<code class="text-neon-cyan">'commentable'</code>) must match the column prefix in the migration (<code class="text-neon-green">commentable_id</code>, <code class="text-neon-green">commentable_type</code>) and the method name in the child model.
            </p>
        </div>
    </section>

    {{-- Setting Up MorphTo --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Setting Up MorphTo (Child Side)</h2>

        <p class="text-white/70 mb-6">
            On the child model (Comment), use <code class="text-neon-pink">morphTo()</code>:
        </p>

        <div class="glass-card p-6 mb-6">
            <div class="code-right">
                <pre><code class="language-php">// Comment.php
use Illuminate\Database\Eloquent\Relations\MorphTo;

public function commentable(): MorphTo
{
    return $this->morphTo();
}</code></pre>
            </div>
        </div>

        <div class="glass-card p-4 border-neon-pink/30">
            <p class="text-white/70 text-sm">
                The method name <strong class="text-neon-pink">MUST</strong> match the column prefix!
                <br>Method: <code class="text-neon-cyan">commentable()</code> → Columns: <code class="text-neon-green">commentable_id</code>, <code class="text-neon-green">commentable_type</code>
            </p>
        </div>
    </section>

    {{-- Creating Polymorphic Records --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Creating Polymorphic Records</h2>

        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-green mb-4">Via Relationship (Recommended)</h3>
            <div class="code-right">
                <pre><code class="language-php">// Create comment on a task
$task->comments()->create([
    'user_id' => auth()->id(),
    'body' => 'This task is fire!',
]);

// Create comment on a project - SAME syntax!
$project->comments()->create([
    'user_id' => auth()->id(),
    'body' => 'Great project!',
]);</code></pre>
            </div>
            <p class="text-white/60 text-sm mt-4">
                Laravel automatically fills in <code class="text-neon-cyan">commentable_id</code> and <code class="text-neon-cyan">commentable_type</code>!
            </p>
        </div>
    </section>

    {{-- Accessing Polymorphic Relationships --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Accessing Polymorphic Relationships</h2>

        <div class="space-y-6">
            {{-- From Parent --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">From Parent (Task/Project)</h3>
                <div class="code-info">
                    <pre><code class="language-php">// Get all comments on a task
$task->comments;  // Collection of Comment models

// Count comments
$task->comments()->count();

// Query comments
$task->comments()->where('body', 'like', '%fire%')->get();</code></pre>
                </div>
            </div>

            {{-- From Child --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">From Child (Comment)</h3>
                <div class="code-info">
                    <pre><code class="language-php">// Get the parent model (Task, Project, etc.)
$comment->commentable;  // Returns Task OR Project OR Flex!

// Check what type it is
$comment->commentable_type;  // "App\Models\Task"
get_class($comment->commentable);  // Same thing</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Nested Polymorphism --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Nested Polymorphism</h2>

        <p class="text-white/70 mb-6">
            Here's where it gets cool - <strong class="text-neon-pink">polymorphic models can have polymorphic relationships too!</strong>
        </p>

        {{-- Reactions on Comments --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">Reactions on Comments</h3>
            <div class="code-right">
                <pre><code class="language-php">// Comment.php - Comments can have reactions!
public function reactions(): MorphMany
{
    return $this->morphMany(Reaction::class, 'reactionable');
}

// Reaction.php - Reactions can belong to anything
public function reactionable(): MorphTo
{
    return $this->morphTo();
}</code></pre>
            </div>
        </div>

        {{-- Usage --}}
        <div class="glass-card p-6">
            <h3 class="font-semibold text-neon-green mb-4">Usage</h3>
            <div class="code-right">
                <pre><code class="language-php">// React to a task
$task->reactions()->create(['user_id' => 1, 'emoji' => '🔥']);

// React to a comment ON a task
$comment = $task->comments()->first();
$comment->reactions()->create(['user_id' => 1, 'emoji' => '❤️']);

// React to a project
$project->reactions()->create(['user_id' => 1, 'emoji' => '💯']);</code></pre>
            </div>
            <p class="text-white/60 text-sm mt-4">
                <strong class="text-neon-pink">ONE</strong> Reaction model handles all of these!
            </p>
        </div>
    </section>

    {{-- FlexBoard Examples --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">FlexBoard Examples</h2>

        <div class="space-y-6">
            {{-- Comment Model --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Comment Model</h3>
                <div class="code-right">
                    <pre><code class="language-php">class Comment extends Model
{
    protected $fillable = ['user_id', 'body'];

    // Who wrote this comment?
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // What was this comment on? (Task, Project, etc.)
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    // Reactions on this comment
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }
}</code></pre>
                </div>
            </div>

            {{-- Reaction Model --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Reaction Model</h3>
                <div class="code-right">
                    <pre><code class="language-php">class Reaction extends Model
{
    protected $fillable = ['user_id', 'emoji'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // What was this reaction on? (Task, Project, Comment, Flex...)
    public function reactionable(): MorphTo
    {
        return $this->morphTo();
    }
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Querying Polymorphic Relationships --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Querying Polymorphic Relationships</h2>

        <div class="space-y-6">
            {{-- Find by Model Type --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-cyan mb-4">Find Comments on a Specific Model Type</h3>
                <div class="code-info">
                    <pre><code class="language-php">use App\Models\Comment;
use App\Models\Task;

// All comments on Tasks
Comment::where('commentable_type', Task::class)->get();

// Using whereHasMorph (more elegant)
Comment::whereHasMorph('commentable', Task::class)->get();

// Comments on Tasks OR Projects
Comment::whereHasMorph('commentable', [Task::class, Project::class])->get();</code></pre>
                </div>
            </div>

            {{-- Filter by Parent Attributes --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-pink mb-4">Filter by Parent Attributes</h3>
                <div class="code-info">
                    <pre><code class="language-php">// Comments on high-priority tasks
Comment::whereHasMorph('commentable', Task::class, function ($query) {
    $query->where('priority', 'high');
})->get();</code></pre>
                </div>
            </div>

            {{-- Eager Loading --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-green mb-4">Eager Loading</h3>
                <div class="code-right">
                    <pre><code class="language-php">// Load comments with their parent model
$comments = Comment::with('commentable')->get();

foreach ($comments as $comment) {
    if ($comment->commentable instanceof Task) {
        echo "Comment on task: {$comment->commentable->title}";
    }
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    {{-- Quick Reference --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Quick Reference</h2>

        <div class="space-y-6">
            <div class="glass-card p-6 overflow-x-auto">
                <h3 class="font-semibold text-neon-cyan mb-4">Relationship Methods</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-glass-border">
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Relationship</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Parent Model</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Child Model</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-xs">
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-neon-green">morphMany</td>
                            <td class="py-3 px-4 text-white/80">Task/Project</td>
                            <td class="py-3 px-4 text-white/80">Comment</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-neon-pink">morphTo</td>
                            <td class="py-3 px-4 text-white/80">Comment</td>
                            <td class="py-3 px-4 text-white/80">Task/Project</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="glass-card p-6 overflow-x-auto">
                <h3 class="font-semibold text-neon-pink mb-4">Migration Methods</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-glass-border">
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Method</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Creates</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono text-xs">
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-neon-green">$table->morphs('name')</td>
                            <td class="py-3 px-4 text-white/80">name_id + name_type + index</td>
                        </tr>
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-neon-cyan">$table->nullableMorphs('name')</td>
                            <td class="py-3 px-4 text-white/80">Same but nullable</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-neon-purple">$table->uuidMorphs('name')</td>
                            <td class="py-3 px-4 text-white/80">UUID version</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">Hands-On Exercise</h2>

        <div class="glass-card p-6">
            <ol class="list-decimal list-inside space-y-3 text-white/80 mb-6">
                <li>Create Comment and Reaction models with migrations</li>
                <li>Add <code class="text-neon-pink">morphMany</code> to Task, Project, and Flex</li>
                <li>Add <code class="text-neon-cyan">morphTo</code> to Comment and Reaction</li>
                <li>Test in tinker:</li>
            </ol>

            <div class="code-info">
                <pre><code class="language-php">// Create a comment on a task
$task = Task::first();
$task->comments()->create([
    'user_id' => 1,
    'body' => 'This is lit!'
]);

// Check what the comment belongs to
$comment = Comment::first();
$comment->commentable;  // Returns the Task!

// Add reaction to the comment
$comment->reactions()->create([
    'user_id' => 1,
    'emoji' => '❤️'
]);</code></pre>
            </div>
        </div>
    </section>

    {{-- Git Checkout --}}
    <section>
        <div class="glass-card p-6 border-neon-purple/30">
            <h3 class="font-semibold text-neon-purple mb-3">Try the Code</h3>
            <p class="text-white/70 mb-4">Check out this branch to see the code in action:</p>
            <div class="code-info">
                <pre><code class="language-bash">git checkout 08-polymorphic</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
