# Branch 06: Basic Relationships

## Learning Objectives

By the end of this lesson, you will understand:
- One-to-Many relationships (`hasMany` / `belongsTo`)
- One-to-One relationships (`hasOne` / `belongsTo`)
- How foreign keys connect tables
- Querying through relationships

---

## Relationship Types Overview

| Relationship | Example | Method |
|--------------|---------|--------|
| One-to-Many | User has many Projects | `hasMany()` |
| Many-to-One | Project belongs to User | `belongsTo()` |
| One-to-One | User has one Profile | `hasOne()` |

---

## One-to-Many: User → Projects

A User can have many Projects. A Project belongs to one User.

### Database Setup

```php
// projects table migration
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
```

### Model Setup

```php
// User.php
public function projects(): HasMany
{
    return $this->hasMany(Project::class);
}

// Project.php
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

### Usage

```php
// Get all projects for a user
$user->projects;  // Collection of Project models

// Get the user who owns a project
$project->user;  // Single User model

// Create a project for a user
$user->projects()->create([
    'name' => 'New Project',
]);

// Count projects
$user->projects()->count();

// Query through relationship
$user->projects()->where('is_active', true)->get();
```

---

## One-to-Many: Project → Tasks

A Project can have many Tasks. A Task belongs to one Project.

### Model Setup

```php
// Project.php
public function tasks(): HasMany
{
    return $this->hasMany(Task::class);
}

// Task.php
public function project(): BelongsTo
{
    return $this->belongsTo(Project::class);
}
```

### Usage

```php
// Get all tasks for a project
$project->tasks;

// Get the project for a task
$task->project;

// Create a task for a project
$project->tasks()->create([
    'title' => 'Fix the bug',
    'priority' => 'high',
]);

// Chain with scopes!
$project->tasks()->incomplete()->highPriority()->get();
```

---

## One-to-One: User → Flex (Latest Flex)

Sometimes you want just ONE related record.

### Model Setup

```php
// User.php
public function latestFlex(): HasOne
{
    return $this->hasOne(Flex::class)->latestOfMany();
}

// Or get the first one
public function firstFlex(): HasOne
{
    return $this->hasOne(Flex::class)->oldestOfMany();
}
```

### Usage

```php
$user->latestFlex;  // Single Flex model (most recent)
```

---

## Foreign Key Conventions

Laravel auto-detects foreign keys based on naming:

| Model | Expected Foreign Key |
|-------|---------------------|
| `User` | `user_id` |
| `Project` | `project_id` |
| `TaskList` | `task_list_id` |

### Custom Foreign Keys

```php
// If your column isn't named conventionally
public function owner(): BelongsTo
{
    return $this->belongsTo(User::class, 'owner_id');
}

// Custom foreign key AND owner key
public function author(): BelongsTo
{
    return $this->belongsTo(User::class, 'author_uuid', 'uuid');
}
```

---

## Creating Related Records

### Method 1: Using `create()`

```php
$project->tasks()->create([
    'title' => 'New Task',
]);
// Automatically sets project_id!
```

### Method 2: Using `save()`

```php
$task = new Task(['title' => 'New Task']);
$project->tasks()->save($task);
```

### Method 3: Manual (Less Clean)

```php
$task = Task::create([
    'title' => 'New Task',
    'project_id' => $project->id,  // Manual - not recommended
]);
```

---

## Querying Relationships

### Has Relationship

```php
// Users who have at least one project
User::has('projects')->get();

// Users with 5+ projects
User::has('projects', '>=', 5)->get();
```

### Where Has (Filter by Related Data)

```php
// Users with high-priority tasks
User::whereHas('projects.tasks', function ($query) {
    $query->where('priority', 'high');
})->get();
```

### With Count

```php
// Get users with project count
User::withCount('projects')->get();

foreach ($users as $user) {
    echo $user->projects_count;  // Added attribute!
}
```

---

## FlexBoard Examples

### User Model

```php
class User extends Authenticatable
{
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function flexes(): HasMany
    {
        return $this->hasMany(Flex::class);
    }

    public function latestFlex(): HasOne
    {
        return $this->hasOne(Flex::class)->latestOfMany();
    }
}
```

### Project Model

```php
class Project extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
```

### Task Model

```php
class Task extends Model
{
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
```

---

## Quick Reference

| From | To | Relationship | Access |
|------|-----|--------------|--------|
| User | Projects | `hasMany` | `$user->projects` |
| Project | User | `belongsTo` | `$project->user` |
| Project | Tasks | `hasMany` | `$project->tasks` |
| Task | Project | `belongsTo` | `$task->project` |

---

## Common Gotchas

### 1. Accessing vs Querying

```php
// PROPERTY - returns Collection/Model (cached after first access)
$user->projects;

// METHOD - returns query builder (for chaining)
$user->projects()->where(...)->get();
```

### 2. Null Relationships

```php
// This can be null if no user is set!
$project->user;  // null if user_id is null

// Safe access
$project->user?->name;  // PHP 8 nullsafe operator
```

---

## Hands-On Exercise

1. Add relationships to User, Project, and Task models
2. Create a Flex model with a `user()` relationship
3. Test in tinker:

```php
$user = User::first();
$user->projects()->create(['name' => 'Test Project']);
$user->projects;  // See the new project!
```

---

## Next Branch

Continue to `07-many-to-many` to learn about many-to-many relationships with pivot tables!

```bash
git checkout 07-many-to-many
```
