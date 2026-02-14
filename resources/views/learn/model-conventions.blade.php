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
                    <span>Laravel's naming conventions for models and tables</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How to customize table names and primary keys</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-neon-green mt-1">✓</span>
                    <span>How timestamps work in Eloquent</span>
                </li>
            </ul>
        </div>
    </section>

    {{-- The Philosophy --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-4">The Convention Over Configuration Philosophy</h2>
        <p class="text-white/70 leading-relaxed">
            Laravel follows <span class="text-neon-pink font-semibold">"Convention over Configuration"</span> - if you follow naming conventions, everything works automagically. Break them, and you need manual configuration.
        </p>
    </section>

    {{-- Section 1: Model & Table Naming --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">1. Model & Table Naming</h2>

        {{-- Convention Table --}}
        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-4">The Convention (Auto-magic)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-glass-border">
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Model Name</th>
                            <th class="text-left py-3 px-4 text-white/60 font-medium">Expected Table Name</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono">
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-neon-pink">User</td>
                            <td class="py-3 px-4 text-neon-green">users</td>
                        </tr>
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-neon-pink">Project</td>
                            <td class="py-3 px-4 text-neon-green">projects</td>
                        </tr>
                        <tr class="border-b border-glass-border">
                            <td class="py-3 px-4 text-neon-pink">TaskList</td>
                            <td class="py-3 px-4 text-neon-green">task_lists</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 text-neon-pink">Category</td>
                            <td class="py-3 px-4 text-neon-green">categories</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="mt-4 text-white/60 text-sm">
                <strong>Rule:</strong> Model is <code class="text-neon-cyan">PascalCase</code> singular, table is <code class="text-neon-cyan">snake_case</code> plural.
            </p>
        </div>

        {{-- Wrong Way --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-pink">❌ Wrong Way</span>
                <span class="text-white/60 text-sm">Don't do this</span>
            </div>
            <div class="code-wrong">
                <pre><code class="language-php">// Model: Project.php
// Table: project (singular - WRONG!)

class Project extends Model
{
    // This will fail! Laravel looks for "projects" table
}</code></pre>
            </div>
        </div>

        {{-- Right Way --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-green">✅ Right Way</span>
                <span class="text-white/60 text-sm">Convention</span>
            </div>
            <div class="code-right">
                <pre><code class="language-php">// Model: Project.php  
// Table: projects (plural - CORRECT!)

class Project extends Model
{
    // Works automatically - no extra config needed!
}</code></pre>
            </div>
        </div>

        {{-- Custom Table --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-cyan">💡 Custom Table</span>
                <span class="text-white/60 text-sm">When required</span>
            </div>
            <div class="code-info">
                <pre><code class="language-php">// If you MUST use a non-conventional table name
class Project extends Model
{
    protected $table = 'tbl_projects'; // Legacy database? No problem!
}</code></pre>
            </div>
        </div>
    </section>

    {{-- Section 2: Primary Key --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">2. Primary Key Conventions</h2>

        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-3">The Convention</h3>
            <ul class="space-y-2 text-white/80">
                <li>• Primary key column: <code class="text-neon-pink">id</code></li>
                <li>• Type: Auto-incrementing integer</li>
                <li>• Laravel handles this automatically</li>
            </ul>
        </div>

        {{-- Wrong Way --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-pink">❌ Wrong Way</span>
            </div>
            <div class="code-wrong">
                <pre><code class="language-php">// Table has 'project_id' as primary key instead of 'id'
class Project extends Model
{
    // This breaks! Laravel expects 'id'
}</code></pre>
            </div>
        </div>

        {{-- Right Way --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-green">✅ Right Way</span>
                <span class="text-white/60 text-sm">Custom Primary Key</span>
            </div>
            <div class="code-right">
                <pre><code class="language-php">class Project extends Model
{
    protected $primaryKey = 'project_id';
    
    // If it's NOT auto-incrementing:
    public $incrementing = false;
    
    // If it's a string (UUID):
    protected $keyType = 'string';
}</code></pre>
            </div>
        </div>
    </section>

    {{-- Section 3: Timestamps --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">3. Timestamps</h2>

        <div class="glass-card p-6 mb-6">
            <h3 class="font-semibold text-neon-cyan mb-3">The Convention</h3>
            <p class="text-white/80">
                Laravel expects <code class="text-neon-pink">created_at</code> and <code class="text-neon-pink">updated_at</code> columns and manages them automatically.
            </p>
        </div>

        {{-- Wrong Way --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-pink">❌ Wrong Way</span>
            </div>
            <div class="code-wrong">
                <pre><code class="language-php">// Table doesn't have created_at/updated_at columns
class Project extends Model
{
    // ERROR: Column 'created_at' not found!
}</code></pre>
            </div>
        </div>

        {{-- Right Way - Disable --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-green">✅ Right Way</span>
                <span class="text-white/60 text-sm">Disable Timestamps</span>
            </div>
            <div class="code-right">
                <pre><code class="language-php">class Project extends Model
{
    public $timestamps = false; // I don't need timestamps
}</code></pre>
            </div>
        </div>

        {{-- Right Way - Custom --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="chip chip-cyan">💡 Custom Columns</span>
            </div>
            <div class="code-info">
                <pre><code class="language-php">class Project extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_modified';
}</code></pre>
            </div>
        </div>
    </section>

    {{-- Hands-On Exercise --}}
    <section class="mb-12">
        <h2 class="font-display text-2xl font-bold text-white mb-6">🛠️ Hands-On Exercise</h2>

        <div class="space-y-6">
            {{-- Step 1 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Step 1: Create a Migration</h3>
                <div class="code-info mb-4">
                    <pre><code class="language-bash">php artisan make:migration create_projects_table</code></pre>
                </div>
                <div class="code-info">
                    <pre><code class="language-php">// database/migrations/xxxx_create_projects_table.php
public function up(): void
{
    Schema::create('projects', function (Blueprint $table) {
        $table->id();                    // Creates 'id' column
        $table->string('name');
        $table->text('description')->nullable();
        $table->timestamps();            // Creates 'created_at' and 'updated_at'
    });
}</code></pre>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Step 2: Create the Model</h3>
                <div class="code-info mb-4">
                    <pre><code class="language-bash">php artisan make:model Project</code></pre>
                </div>
                <div class="code-info">
                    <pre><code class="language-php">// app/Models/Project.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // That's it! Conventions handle the rest!
}</code></pre>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="glass-card p-6">
                <h3 class="font-semibold text-neon-purple mb-4">Step 3: Test It</h3>
                <div class="code-info mb-4">
                    <pre><code class="language-bash">php artisan tinker</code></pre>
                </div>
                <div class="code-info">
                    <pre><code class="language-php">// In tinker
$project = new \App\Models\Project();
$project->name = 'FlexBoard MVP';
$project->save();

// Check it worked
\App\Models\Project::first();
// created_at and updated_at are automatically set!</code></pre>
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
                        <th class="text-left py-3 px-4 text-white/60 font-medium">What</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Convention</th>
                        <th class="text-left py-3 px-4 text-white/60 font-medium">Customization</th>
                    </tr>
                </thead>
                <tbody class="font-mono text-xs">
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-white/80">Table name</td>
                        <td class="py-3 px-4 text-neon-cyan">snake_case plural</td>
                        <td class="py-3 px-4 text-neon-pink">protected $table = 'name';</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-white/80">Primary key</td>
                        <td class="py-3 px-4 text-neon-cyan">id (auto-increment)</td>
                        <td class="py-3 px-4 text-neon-pink">protected $primaryKey = 'col';</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-white/80">Key type</td>
                        <td class="py-3 px-4 text-neon-cyan">Integer</td>
                        <td class="py-3 px-4 text-neon-pink">protected $keyType = 'string';</td>
                    </tr>
                    <tr class="border-b border-glass-border">
                        <td class="py-3 px-4 text-white/80">Auto-increment</td>
                        <td class="py-3 px-4 text-neon-cyan">Yes</td>
                        <td class="py-3 px-4 text-neon-pink">public $incrementing = false;</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-white/80">Timestamps</td>
                        <td class="py-3 px-4 text-neon-cyan">created_at, updated_at</td>
                        <td class="py-3 px-4 text-neon-pink">public $timestamps = false;</td>
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
                <pre><code class="language-bash">git checkout 01-model-conventions</code></pre>
            </div>
        </div>
    </section>
</x-learn-layout>
