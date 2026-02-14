# Branch 11: The Complete FlexBoard Application

## Congratulations!

You've completed the FlexBoard Eloquent learning journey! This branch represents a **production-ready** Laravel application with all the best practices you've learned.

---

## What You've Learned

### Branch 01: Model Conventions

- Eloquent naming conventions (singular models, plural tables)
- Primary keys and table name overrides
- The importance of following Laravel's conventions

```php
// Convention: Model "User" → table "users"
class User extends Model
{
    // Laravel handles everything automatically!
}
```

### Branch 02: Mass Assignment

- `$fillable` vs `$guarded`
- Protecting against mass assignment vulnerabilities
- When to use `create()` vs setting properties manually

```php
class Project extends Model
{
    protected $fillable = ['name', 'slug', 'description'];
    // user_id intentionally NOT fillable - use relationship!
}

// Safe: Uses relationship to set user_id
$user->projects()->create([...]);
```

### Branch 03: Accessors & Mutators

- Attribute casting with `casts()`
- Custom accessors using `Attribute::get()`
- Custom mutators using `Attribute::set()`

```php
protected function casts(): array
{
    return ['flex_points' => 'integer'];
}

protected function name(): Attribute
{
    return Attribute::make(
        get: fn ($value) => ucwords($value),
        set: fn ($value) => strtolower($value),
    );
}
```

### Branch 04: Query Scopes

- Local scopes for reusable query logic
- Dynamic scopes with parameters
- Chaining scopes together

```php
// Define scope
public function scopeCompleted(Builder $query): Builder
{
    return $query->where('is_completed', true);
}

// Use it anywhere
Task::completed()->highPriority()->get();
```

### Branch 05: Timestamps & Soft Deletes

- Automatic `created_at` and `updated_at`
- Soft deletes with `SoftDeletes` trait
- `withTrashed()` and `onlyTrashed()` queries

```php
use SoftDeletes;

// Soft delete (sets deleted_at)
$task->delete();

// Restore
$task->restore();

// Include soft deleted
Task::withTrashed()->get();
```

### Branch 06: Basic Relationships

- `hasOne`, `hasMany`, `belongsTo`
- Defining inverse relationships
- Creating related models through relationships

```php
// User has many Projects
public function projects(): HasMany
{
    return $this->hasMany(Project::class);
}

// Project belongs to User
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

### Branch 07: Many-to-Many Relationships

- `belongsToMany` relationships
- Pivot tables and custom pivot table names
- Attaching, detaching, and syncing

```php
// Task has many Tags (and vice versa)
public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class, 'task_tag');
}

// Attach tags
$task->tags()->attach([1, 2, 3]);

// Sync (replace all)
$task->tags()->sync([1, 2]);
```

### Branch 08: Polymorphic Relationships

- `morphTo`, `morphMany`, `morphOne`
- Sharing models across multiple parents
- Configuring polymorphic types

```php
// Comment can belong to Task, Project, or any model
public function commentable(): MorphTo
{
    return $this->morphTo();
}

// Task has many Comments
public function comments(): MorphMany
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

### Branch 09: The N+1 Problem

- Understanding the N+1 query problem
- Identifying N+1 in loops and Blade templates
- Tools for detecting N+1 queries

```php
// BAD: N+1 queries
foreach (Project::all() as $project) {
    echo $project->user->name; // Query per project!
}
```

### Branch 10: Eager Loading

- `with()` for eager loading relationships
- `withCount()` for efficient counting
- `load()` for lazy eager loading
- Constrained eager loading

```php
// GOOD: 2 queries total
$projects = Project::with('user')->get();
foreach ($projects as $project) {
    echo $project->user->name; // Already loaded!
}
```

---

## FlexBoard Data Model

```
┌─────────────────────────────────────────────────────────────────┐
│                          User                                    │
│  - id, name, email, flex_points                                 │
├─────────────────────────────────────────────────────────────────┤
│  hasMany: projects, tasks, comments, reactions, flexes          │
└─────────────────────────────────────────────────────────────────┘
         │
         │ hasMany
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                         Project                                  │
│  - id, user_id, name, slug, description, is_active              │
├─────────────────────────────────────────────────────────────────┤
│  belongsTo: user                                                 │
│  hasMany: tasks                                                  │
│  morphMany: comments, reactions                                  │
└─────────────────────────────────────────────────────────────────┘
         │
         │ hasMany
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                          Task                                    │
│  - id, project_id, title, description, priority, flex_reward    │
│  - is_completed, completed_at, due_date (soft deletes)          │
├─────────────────────────────────────────────────────────────────┤
│  belongsTo: project                                              │
│  belongsToMany: tags (pivot: task_tag)                          │
│  morphMany: comments, reactions                                  │
└─────────────────────────────────────────────────────────────────┘
         │
         │ belongsToMany
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                           Tag                                    │
│  - id, name, color                                               │
├─────────────────────────────────────────────────────────────────┤
│  belongsToMany: tasks                                            │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         Comment                                  │
│  - id, user_id, commentable_type, commentable_id, body          │
├─────────────────────────────────────────────────────────────────┤
│  belongsTo: user                                                 │
│  morphTo: commentable (Task, Project, etc.)                     │
│  morphMany: reactions                                            │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         Reaction                                 │
│  - id, user_id, reactable_type, reactable_id, emoji             │
├─────────────────────────────────────────────────────────────────┤
│  belongsTo: user                                                 │
│  morphTo: reactable (Task, Project, Comment, Flex, etc.)        │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                           Flex                                   │
│  - id, user_id, task_id, message, flex_points                   │
├─────────────────────────────────────────────────────────────────┤
│  belongsTo: user, task                                           │
│  morphMany: reactions                                            │
└─────────────────────────────────────────────────────────────────┘
```

---

## Best Practices Applied

### 1. Strict Model Conventions

All models follow Laravel naming conventions:
- Singular model names: `User`, `Task`, `Project`
- Plural table names: `users`, `tasks`, `projects`
- Foreign keys: `user_id`, `project_id`

### 2. Protected Mass Assignment

```php
// Only expected fields are fillable
protected $fillable = ['name', 'description', 'priority'];

// Sensitive fields like user_id use relationships
$user->projects()->create($data);
```

### 3. Consistent Casts

```php
protected function casts(): array
{
    return [
        'is_completed' => 'boolean',
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'flex_points' => 'integer',
    ];
}
```

### 4. Reusable Scopes

```php
// Scopes make queries readable and DRY
Task::completed()->highPriority()->dueThisWeek()->get();
```

### 5. Soft Deletes Where Appropriate

```php
// Tasks use soft deletes for recoverability
use SoftDeletes;
```

### 6. Eager Loading Everywhere

```php
// Always eager load when accessing relationships
$projects = Project::with(['user', 'tasks.tags'])->get();
```

### 7. Strict Mode in Development

```php
// AppServiceProvider.php
public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());
}
```

---

## Eloquent Cheat Sheet

### Relationships

| Type | Definition | Usage |
|------|------------|-------|
| `hasOne` | Parent has one child | `$user->profile` |
| `hasMany` | Parent has many children | `$user->posts` |
| `belongsTo` | Child belongs to parent | `$post->user` |
| `belongsToMany` | Many-to-many with pivot | `$post->tags` |
| `morphTo` | Polymorphic parent | `$comment->commentable` |
| `morphMany` | Polymorphic children | `$post->comments` |

### Query Patterns

```php
// Create through relationship (sets foreign key)
$user->projects()->create([...]);

// Eager load
Project::with('user')->get();

// Eager load with count
Project::withCount('tasks')->get();

// Constrained eager load
Project::with(['tasks' => fn ($q) => $q->incomplete()])->get();

// Attach/detach many-to-many
$task->tags()->attach([1, 2, 3]);
$task->tags()->sync([1, 2]);

// Soft delete queries
Task::withTrashed()->get();
Task::onlyTrashed()->get();
```

### Scope Examples

```php
// Boolean scope
public function scopeActive(Builder $query): Builder
{
    return $query->where('is_active', true);
}

// Parameter scope
public function scopePriority(Builder $query, int $level): Builder
{
    return $query->where('priority', '>=', $level);
}

// Usage
Project::active()->get();
Task::priority(3)->get();
```

---

## Demo Routes Summary

### N+1 Problem (Branch 09)

```
GET /n-plus-one/projects     # See N+1 in action
GET /n-plus-one/tasks        # Nested N+1
GET /n-plus-one/users        # Cascading N+1
GET /n-plus-one/counts       # Count N+1
GET /n-plus-one/polymorphic  # Polymorphic N+1
GET /n-plus-one/dashboard    # Blade template N+1
```

### Eager Loading (Branch 10)

```
GET /eager-loading/projects     # with() fix
GET /eager-loading/tasks        # Nested fix
GET /eager-loading/users        # Cascading fix
GET /eager-loading/counts       # withCount() fix
GET /eager-loading/polymorphic  # Polymorphic fix
GET /eager-loading/lazy         # load() example
GET /eager-loading/constrained  # Filtered eager load
GET /eager-loading/compare      # Side-by-side comparison
GET /eager-loading/dashboard    # Fixed Blade template
```

---

## Running the Complete App

```bash
# Fresh install
composer install
npm install

# Setup database
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start server
php artisan serve

# Or use Herd - it's automatic!
```

---

## Next Steps for Learning

1. **Explore the Code**: Read through all the models and controllers
2. **Run the Demos**: Compare N+1 vs eager loading query counts
3. **Enable Strict Mode**: Try `Model::preventLazyLoading(true)`
4. **Write Tests**: Add feature tests for the relationships
5. **Extend the App**: Add new features using what you've learned

---

## Resources

- [Laravel Eloquent Documentation](https://laravel.com/docs/eloquent)
- [Laravel Relationships](https://laravel.com/docs/eloquent-relationships)
- [Eloquent Performance Patterns](https://laravel.com/docs/eloquent#performance)

---

## You Did It!

You now understand:
- Laravel model conventions and why they matter
- Mass assignment protection
- Accessors, mutators, and casts
- Query scopes for reusable logic
- Timestamps and soft deletes
- All relationship types (hasMany, belongsTo, belongsToMany, morphTo, morphMany)
- The N+1 problem and how to fix it with eager loading

**Go build amazing Laravel applications!**
