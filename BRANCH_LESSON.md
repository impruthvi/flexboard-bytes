# Branch 05: Timestamps & Soft Deletes

## Learning Objectives

By the end of this lesson, you will understand:
- How Laravel auto-manages `created_at` and `updated_at`
- How soft deletes work (mark as deleted vs actually delete)
- How to restore soft-deleted records
- Querying with and without soft-deleted records

---

## Timestamps: Auto-Magic Date Management

### How It Works

When you have `$table->timestamps()` in your migration, Laravel:
- Sets `created_at` when you CREATE a record
- Updates `updated_at` when you UPDATE a record

```php
$project = Project::create(['name' => 'FlexBoard']);
// created_at: 2024-01-15 10:30:00
// updated_at: 2024-01-15 10:30:00

$project->update(['name' => 'FlexBoard Pro']);
// created_at: 2024-01-15 10:30:00 (unchanged)
// updated_at: 2024-01-15 10:45:00 (auto-updated!)
```

### Customizing Timestamps

```php
// Disable timestamps entirely
public $timestamps = false;

// Use custom column names
const CREATED_AT = 'creation_date';
const UPDATED_AT = 'last_modified';

// Touch (update updated_at) without changing anything else
$project->touch();
```

---

## Soft Deletes: Delete Without Destroying

### The Problem with Hard Deletes

```php
// GONE FOREVER! 😱
$project->delete();

// User: "Wait, I didn't mean to delete that!"
// You: "Sorry, it's gone..."
```

### The Solution: Soft Deletes

Instead of removing the record, we mark it as deleted with a timestamp.

```php
// Record stays in database with deleted_at = 2024-01-15 10:30:00
$project->delete();

// User: "I need that back!"
// You: "No problem!" 😎
$project->restore();
```

---

## Setting Up Soft Deletes

### Step 1: Add the Column

```php
// In your migration
Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
    $table->softDeletes();  // Adds 'deleted_at' column
});
```

### Step 2: Use the Trait

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;  // Enable soft deletes!
}
```

---

## Working with Soft Deletes

### Deleting (Soft)

```php
$project->delete();
// Sets deleted_at = now()
// Record is NOT removed from database!
```

### Querying (Auto-Excludes Deleted)

```php
// By default, soft-deleted records are hidden
Project::all();  // Only returns non-deleted projects

// Explicitly include soft-deleted
Project::withTrashed()->get();

// ONLY get soft-deleted
Project::onlyTrashed()->get();
```

### Restoring

```php
// Bring it back!
$project->restore();
// Sets deleted_at = null
```

### Force Deleting (Permanent)

```php
// When you REALLY want to delete permanently
$project->forceDelete();
// Record is GONE from database!
```

---

## Checking Soft Delete Status

```php
if ($project->trashed()) {
    echo "This project is soft-deleted";
}

// Check in Blade
@if($project->trashed())
    <span class="text-red-500">Deleted</span>
@endif
```

---

## Cascading Soft Deletes

When a project is deleted, should its tasks be deleted too?

### Option 1: Manual in Model

```php
protected static function booted(): void
{
    static::deleting(function (Project $project) {
        // Soft delete all tasks when project is soft-deleted
        $project->tasks()->delete();
    });
    
    static::restoring(function (Project $project) {
        // Restore tasks when project is restored
        $project->tasks()->withTrashed()->restore();
    });
}
```

### Option 2: Use a Package

Consider `dyrynda/laravel-cascade-soft-deletes` for automatic cascading.

---

## FlexBoard Example

```php
class Project extends Model
{
    use SoftDeletes;

    /**
     * LESSON: Soft Deletes
     *
     * When you delete a project, it's not actually removed.
     * The deleted_at column is set, and Eloquent hides it by default.
     *
     * Benefits:
     * - User can restore accidentally deleted projects
     * - You can audit what was deleted and when
     * - Data is never truly lost (until force deleted)
     */

    /**
     * Cascade soft deletes to tasks.
     */
    protected static function booted(): void
    {
        static::deleting(function (Project $project) {
            $project->tasks()->delete();
        });

        static::restoring(function (Project $project) {
            $project->tasks()->withTrashed()->restore();
        });
    }
}
```

---

## Quick Reference

| Operation | Code | Result |
|-----------|------|--------|
| Soft delete | `$model->delete()` | Sets `deleted_at` |
| Restore | `$model->restore()` | Clears `deleted_at` |
| Force delete | `$model->forceDelete()` | Removes from DB |
| Get all | `Model::all()` | Excludes deleted |
| Include deleted | `Model::withTrashed()` | All records |
| Only deleted | `Model::onlyTrashed()` | Only deleted |
| Check if deleted | `$model->trashed()` | Returns bool |

---

## Common Gotchas

### 1. Unique Constraints

```php
// Problem: Deleted "FlexBoard" blocks creating new "FlexBoard"
$table->string('slug')->unique();

// Solution: Unique only when not deleted
$table->string('slug');
$table->unique(['slug', 'deleted_at']);

// Or use a composite unique in MySQL 8+
$table->rawIndex('UNIQUE INDEX projects_slug_unique (slug) WHERE deleted_at IS NULL');
```

### 2. Relationships Still Work

```php
// Even if project is soft-deleted, you can still access it
$task->project;  // Works! Returns the soft-deleted project

// To check in relationship
$task->project()->withTrashed()->first();
```

---

## Hands-On Exercise

1. Add `SoftDeletes` to your Project and Task models
2. Add `$table->softDeletes()` to the migrations
3. Run migrations fresh
4. Test in tinker:

```php
$project = Project::first();
$project->delete();
Project::all()->count();  // 0 (hidden)
Project::withTrashed()->count();  // 1 (visible)
$project->restore();
Project::all()->count();  // 1 (back!)
```

---

## Next Branch

Continue to `06-basic-relationships` to learn about model relationships!

```bash
git checkout 06-basic-relationships
```
