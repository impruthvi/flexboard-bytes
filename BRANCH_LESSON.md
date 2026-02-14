# Branch 08: Polymorphic Relationships

## Learning Objectives

By the end of this lesson, you will understand:
- What polymorphic relationships are and when to use them
- How `morphTo` and `morphMany` work
- How to create polymorphic migrations
- Nested polymorphism (polymorphic models having polymorphic relationships)

---

## What Are Polymorphic Relationships?

Polymorphic relationships allow a model to belong to **more than one type of model** using a single association.

### Real-World Example

Imagine you want to add comments to your app. Users can comment on:
- Tasks
- Projects
- Flexes
- Maybe even other Comments!

**Without polymorphism**, you'd need:
- `task_comments` table
- `project_comments` table
- `flex_comments` table
- Separate models for each!

**With polymorphism**, you need:
- ONE `comments` table
- ONE `Comment` model
- Works with ANY model!

---

## Polymorphic Table Structure

The magic is in two special columns:

```php
Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->text('body');
    
    // morphs() creates TWO columns:
    // - commentable_id: The ID of the parent (1, 2, 3...)
    // - commentable_type: The class name ("App\Models\Task")
    $table->morphs('commentable');
    
    $table->timestamps();
});
```

### What's in the Database?

| id | user_id | body | commentable_id | commentable_type |
|----|---------|------|----------------|------------------|
| 1 | 1 | "Lit task!" | 5 | App\Models\Task |
| 2 | 1 | "Great project!" | 2 | App\Models\Project |
| 3 | 2 | "Nice flex!" | 10 | App\Models\Flex |

The SAME table stores comments for Tasks, Projects, AND Flexes!

---

## Setting Up MorphMany (Parent Side)

On the parent model (Task, Project, etc.), use `morphMany()`:

```php
// Task.php
use Illuminate\Database\Eloquent\Relations\MorphMany;

public function comments(): MorphMany
{
    return $this->morphMany(Comment::class, 'commentable');
}

// Project.php - SAME relationship definition!
public function comments(): MorphMany
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

The second parameter (`'commentable'`) must match:
- The column prefix in the migration (`commentable_id`, `commentable_type`)
- The method name in the child model

---

## Setting Up MorphTo (Child Side)

On the child model (Comment), use `morphTo()`:

```php
// Comment.php
use Illuminate\Database\Eloquent\Relations\MorphTo;

public function commentable(): MorphTo
{
    return $this->morphTo();
}
```

The method name **MUST** match the column prefix!
- Method: `commentable()`
- Columns: `commentable_id`, `commentable_type`

---

## Creating Polymorphic Records

### Via Relationship (Recommended)

```php
// Create comment on a task
$task->comments()->create([
    'user_id' => auth()->id(),
    'body' => 'This task is fire! 🔥',
]);

// Create comment on a project - SAME syntax!
$project->comments()->create([
    'user_id' => auth()->id(),
    'body' => 'Mast project hai bhai!',
]);
```

Laravel automatically fills in:
- `commentable_id` → Task/Project ID
- `commentable_type` → `App\Models\Task` or `App\Models\Project`

---

## Accessing Polymorphic Relationships

### From Parent (Task/Project)

```php
// Get all comments on a task
$task->comments;  // Collection of Comment models

// Count comments
$task->comments()->count();

// Query comments
$task->comments()->where('body', 'like', '%fire%')->get();
```

### From Child (Comment)

```php
// Get the parent model (Task, Project, etc.)
$comment->commentable;  // Returns Task OR Project OR Flex!

// Check what type it is
$comment->commentable_type;  // "App\Models\Task"
get_class($comment->commentable);  // Same thing
```

---

## Nested Polymorphism

Here's where it gets cool - **polymorphic models can have polymorphic relationships too!**

### Reactions on Comments

```php
// Comment.php - Comments can have reactions!
public function reactions(): MorphMany
{
    return $this->morphMany(Reaction::class, 'reactionable');
}

// Reaction.php - Reactions can belong to anything
public function reactionable(): MorphTo
{
    return $this->morphTo();
}
```

### Usage

```php
// React to a task
$task->reactions()->create(['user_id' => 1, 'emoji' => '🔥']);

// React to a comment ON a task
$comment = $task->comments()->first();
$comment->reactions()->create(['user_id' => 1, 'emoji' => '❤️']);

// React to a project
$project->reactions()->create(['user_id' => 1, 'emoji' => '💯']);
```

ONE Reaction model handles all of these!

---

## FlexBoard Examples

### Comment Model

```php
class Comment extends Model
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
}
```

### Reaction Model

```php
class Reaction extends Model
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
}
```

---

## Querying Polymorphic Relationships

### Find Comments on a Specific Model Type

```php
use App\Models\Comment;
use App\Models\Task;

// All comments on Tasks
Comment::where('commentable_type', Task::class)->get();

// Using whereHasMorph (more elegant)
Comment::whereHasMorph('commentable', Task::class)->get();

// Comments on Tasks OR Projects
Comment::whereHasMorph('commentable', [Task::class, Project::class])->get();
```

### Filter by Parent Attributes

```php
// Comments on high-priority tasks
Comment::whereHasMorph('commentable', Task::class, function ($query) {
    $query->where('priority', 'high');
})->get();
```

### Eager Loading

```php
// Load comments with their parent model
$comments = Comment::with('commentable')->get();

foreach ($comments as $comment) {
    if ($comment->commentable instanceof Task) {
        echo "Comment on task: {$comment->commentable->title}";
    }
}
```

---

## Quick Reference

| Relationship | Parent Model | Child Model |
|--------------|--------------|-------------|
| `morphMany` | Task/Project | Comment |
| `morphTo` | Comment | Task/Project |

| Migration Method | Creates |
|------------------|---------|
| `$table->morphs('name')` | `name_id` + `name_type` + index |
| `$table->nullableMorphs('name')` | Same but nullable |
| `$table->uuidMorphs('name')` | UUID version |

---

## Hands-On Exercise

1. Create Comment and Reaction models with migrations
2. Add `morphMany` to Task, Project, and Flex
3. Add `morphTo` to Comment and Reaction
4. Test in tinker:

```php
// Create a comment on a task
$task = Task::first();
$task->comments()->create([
    'user_id' => 1,
    'body' => 'This is lit! 🔥'
]);

// Check what the comment belongs to
$comment = Comment::first();
$comment->commentable;  // Returns the Task!

// Add reaction to the comment
$comment->reactions()->create([
    'user_id' => 1,
    'emoji' => '❤️'
]);
```

---

## Next Branch

Continue to `09-n-plus-one` to see how N+1 queries can hurt performance!

```bash
git checkout 09-n-plus-one
```
