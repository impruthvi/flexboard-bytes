# Branch 07: Many-to-Many Relationships

## Learning Objectives

By the end of this lesson, you will understand:
- When to use Many-to-Many relationships
- How pivot tables work
- Attaching, detaching, and syncing related models
- Working with pivot table data

---

## When to Use Many-to-Many

Use Many-to-Many when:
- A Task can have many Tags
- A Tag can belong to many Tasks
- A User can have many Badges
- A Badge can belong to many Users

Neither "owns" the other - they're associated!

---

## Pivot Tables

Many-to-Many needs a "pivot" (or "junction") table to store the relationships.

### Naming Convention

The pivot table name combines both model names in **alphabetical order**, snake_case, singular:

| Models | Pivot Table Name |
|--------|------------------|
| Tag + Task | `tag_task` (alphabetical) |
| Badge + User | `badge_user` |
| Role + User | `role_user` |

### Pivot Table Migration

```php
// Pivot tables typically only have foreign keys
Schema::create('tag_task', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
    $table->foreignId('task_id')->constrained()->cascadeOnDelete();
    $table->timestamps();

    // Prevent duplicates
    $table->unique(['tag_id', 'task_id']);
});
```

---

## Setting Up the Relationship

### Both Models Need `belongsToMany()`

```php
// Task.php
public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class);
}

// Tag.php
public function tasks(): BelongsToMany
{
    return $this->belongsToMany(Task::class);
}
```

### Custom Pivot Table Name

If your pivot table doesn't follow conventions:

```php
public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class, 'task_tags');
}
```

---

## Attaching & Detaching

### Attach (Add Relationship)

```php
// Add a single tag
$task->tags()->attach($tagId);

// Add multiple tags
$task->tags()->attach([1, 2, 3]);

// Add with pivot data
$task->tags()->attach($tagId, ['added_by' => auth()->id()]);
```

### Detach (Remove Relationship)

```php
// Remove a single tag
$task->tags()->detach($tagId);

// Remove multiple tags
$task->tags()->detach([1, 2, 3]);

// Remove ALL tags
$task->tags()->detach();
```

### Sync (Replace All)

```php
// Replace all tags with these
$task->tags()->sync([1, 2, 3]);

// Sync without detaching (only adds, never removes)
$task->tags()->syncWithoutDetaching([4, 5]);
```

### Toggle (Attach or Detach)

```php
// If attached -> detach, if detached -> attach
$task->tags()->toggle([1, 2, 3]);
```

---

## Pivot Table with Extra Columns

Sometimes you need extra data on the relationship itself.

### Migration

```php
Schema::create('badge_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->timestamp('earned_at');  // When was it earned?
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

### Access Pivot Data

```php
// Tell Laravel which pivot columns to load
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
}
```

### Attach with Pivot Data

```php
$user->badges()->attach($badgeId, [
    'earned_at' => now(),
    'notes' => 'First task completed!',
]);
```

---

## FlexBoard Examples

### Task ↔ Tag (Simple)

```php
// Task.php
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
$task->tags;  // Collection of Tag models
```

### User ↔ Badge (With Pivot Data)

```php
// User.php
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
$user->badges->first()->pivot->earned_at;
```

---

## Querying Many-to-Many

### Filter by Related Models

```php
// Tasks with a specific tag
Task::whereHas('tags', function ($query) {
    $query->where('name', 'urgent');
})->get();

// Tasks with ANY of these tags
Task::whereHas('tags', function ($query) {
    $query->whereIn('name', ['urgent', 'bug']);
})->get();
```

### Count Related Models

```php
// Users with badge count
User::withCount('badges')->get();

// $user->badges_count is now available
```

### Filter by Pivot Data

```php
// Users who earned badge after a date
$badge->users()
      ->wherePivot('earned_at', '>', now()->subWeek())
      ->get();
```

---

## Quick Reference

| Method | Purpose |
|--------|---------|
| `attach($ids)` | Add relationships |
| `detach($ids)` | Remove relationships |
| `sync($ids)` | Replace all relationships |
| `syncWithoutDetaching($ids)` | Add without removing |
| `toggle($ids)` | Flip attachment state |
| `$model->pivot` | Access pivot data |
| `withPivot()` | Include pivot columns |
| `withTimestamps()` | Auto-manage pivot timestamps |

---

## Hands-On Exercise

1. Create Tag and Badge models with migrations
2. Create pivot table migrations (`task_tag`, `badge_user`)
3. Add `belongsToMany` relationships to models
4. Test in tinker:

```php
$task = Task::first();
$task->tags()->attach([1, 2]);
$task->tags;  // See the tags!

$user = User::first();
$user->badges()->attach(1, ['earned_at' => now()]);
$user->badges->first()->pivot->earned_at;
```

---

## Next Branch

Continue to `08-polymorphic` to learn about polymorphic relationships!

```bash
git checkout 08-polymorphic
```
