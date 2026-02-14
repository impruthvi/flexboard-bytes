# FlexBoard: Laravel Eloquent Teaching Guide

## Overview

FlexBoard is a gamified task completion tracker designed to teach Laravel Eloquent concepts to junior developers. The project uses a **cumulative branch structure** where each branch builds on the previous one, demonstrating progressively advanced Eloquent features.

---

## Quick Start

```bash
# Clone the repository
git clone https://github.com/impruthvi/flexboard-bytes.git
cd flexboard-bytes

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations and seed
php artisan migrate:fresh --seed

# Build frontend
npm run build

# Start (or use Laravel Herd)
php artisan serve
```

---

## Branch Structure

| Branch | Topic | Key Concepts |
|--------|-------|--------------|
| `main` | Starting point | Basic Laravel app |
| `01-model-conventions` | Model Conventions | Naming, table/key overrides |
| `02-mass-assignment` | Mass Assignment | `$fillable`, `$guarded`, security |
| `03-accessors-mutators` | Accessors & Mutators | `casts()`, `Attribute` class |
| `04-scopes` | Query Scopes | Local scopes, chaining |
| `05-timestamps-softdeletes` | Timestamps & Soft Deletes | `SoftDeletes`, `withTrashed()` |
| `06-basic-relationships` | Basic Relationships | `hasMany`, `belongsTo`, `hasOne` |
| `07-many-to-many` | Many-to-Many | `belongsToMany`, pivot tables |
| `08-polymorphic` | Polymorphic | `morphTo`, `morphMany` |
| `09-n-plus-one` | N+1 Problem | Intentionally broken queries |
| `10-eager-loading` | Eager Loading | `with()`, `withCount()`, `load()` |
| `11-complete-app` | Complete App | All best practices applied |

---

## Teaching Approach

### "Wrong Then Right"

Each branch demonstrates concepts by:
1. **Showing the problem** - What happens without proper implementation
2. **Explaining why it's wrong** - Performance, security, or maintainability issues
3. **Demonstrating the fix** - The correct Laravel way

### Example: N+1 Problem (Branch 09 → 10)

**Branch 09 (Wrong)**:
```php
// 28 queries for 27 projects!
$projects = Project::all();
foreach ($projects as $project) {
    echo $project->user->name; // Query per project
}
```

**Branch 10 (Right)**:
```php
// 2 queries total
$projects = Project::with('user')->get();
foreach ($projects as $project) {
    echo $project->user->name; // Already loaded!
}
```

---

## Lesson Plans

### Lesson 1: Model Conventions (30 minutes)

**Branch**: `01-model-conventions`

**Objectives**:
- Understand Eloquent naming conventions
- Know when to override defaults
- Configure custom table names and primary keys

**Activities**:
1. Compare `User` model (convention) vs custom model with overrides
2. Create a model that breaks convention, observe errors
3. Fix by adding explicit configuration

**Discussion Points**:
- Why conventions matter for team productivity
- Trade-offs of custom configurations

---

### Lesson 2: Mass Assignment (30 minutes)

**Branch**: `02-mass-assignment`

**Objectives**:
- Understand mass assignment vulnerability
- Use `$fillable` and `$guarded` correctly
- Know when to use relationship methods

**Activities**:
1. Try to create a model with unexpected fields
2. Observe mass assignment protection in action
3. Compare `Model::create()` vs `$user->projects()->create()`

**Key Insight**: `user_id` is NOT in `$fillable` for Project - you must use the relationship method!

---

### Lesson 3: Accessors & Mutators (45 minutes)

**Branch**: `03-accessors-mutators`

**Objectives**:
- Use `casts()` for automatic type conversion
- Create custom accessors with `Attribute::get()`
- Create custom mutators with `Attribute::set()`

**Activities**:
1. Observe how `casts()` converts database values
2. Create an accessor for formatted display
3. Create a mutator for data normalization

**Code Example**:
```php
protected function name(): Attribute
{
    return Attribute::make(
        get: fn ($value) => ucwords($value),
        set: fn ($value) => strtolower($value),
    );
}
```

---

### Lesson 4: Query Scopes (30 minutes)

**Branch**: `04-scopes`

**Objectives**:
- Create local scopes for reusable queries
- Chain scopes together
- Use scopes with parameters

**Activities**:
1. Find repeated `where()` clauses in the codebase
2. Refactor into scopes
3. Chain multiple scopes

**Before/After**:
```php
// Before: Repeated everywhere
Task::where('is_completed', true)->where('priority', '>=', 3)->get();

// After: Clean and reusable
Task::completed()->highPriority()->get();
```

---

### Lesson 5: Timestamps & Soft Deletes (30 minutes)

**Branch**: `05-timestamps-softdeletes`

**Objectives**:
- Understand automatic timestamps
- Implement soft deletes
- Query soft-deleted records

**Activities**:
1. Create/update records, observe timestamp changes
2. Soft delete a task, verify it's hidden
3. Use `withTrashed()` and `onlyTrashed()`
4. Restore a soft-deleted record

---

### Lesson 6: Basic Relationships (60 minutes)

**Branch**: `06-basic-relationships`

**Objectives**:
- Define `hasMany`, `belongsTo`, `hasOne`
- Access related models through relationships
- Create records through relationships

**Activities**:
1. Define User → Projects relationship
2. Define Project → User inverse relationship
3. Create a project through `$user->projects()->create()`
4. Access the user from a project

**Relationship Map**:
```
User hasMany Projects
User hasMany Tasks
Project belongsTo User
Project hasMany Tasks
Task belongsTo Project
```

---

### Lesson 7: Many-to-Many (45 minutes)

**Branch**: `07-many-to-many`

**Objectives**:
- Define `belongsToMany` relationships
- Understand pivot tables
- Use `attach()`, `detach()`, `sync()`

**Activities**:
1. Create the `task_tag` pivot table
2. Define Task ↔ Tag relationship
3. Attach tags to a task
4. Sync tags (replace all)

**Key Insight**: Custom pivot table name (`task_tag` instead of alphabetical `tag_task`) requires explicit configuration:
```php
return $this->belongsToMany(Tag::class, 'task_tag');
```

---

### Lesson 8: Polymorphic Relationships (60 minutes)

**Branch**: `08-polymorphic`

**Objectives**:
- Understand polymorphic relationships
- Define `morphTo` and `morphMany`
- Share models across different parents

**Activities**:
1. Create Comment model with `morphTo`
2. Add `comments()` to Task, Project
3. Create comments on different model types
4. Add Reactions (nested polymorphism!)

**Use Case**: Comments and Reactions can belong to Tasks, Projects, or even other Comments!

---

### Lesson 9: N+1 Problem (45 minutes)

**Branch**: `09-n-plus-one`

**Objectives**:
- Understand the N+1 query problem
- Identify N+1 in loops and templates
- Use tools to detect N+1

**Activities**:
1. Visit `/n-plus-one/projects` - note query count
2. Visit `/n-plus-one/users` - watch queries explode
3. Read `NplusOneDemoController` code
4. Enable query logging and inspect

**Demo URLs**:
```
/n-plus-one/projects     # ~28 queries
/n-plus-one/tasks        # ~207 queries
/n-plus-one/users        # ~140 queries
```

---

### Lesson 10: Eager Loading (60 minutes)

**Branch**: `10-eager-loading`

**Objectives**:
- Fix N+1 with `with()`
- Use `withCount()` for efficient counts
- Use `load()` for lazy eager loading

**Activities**:
1. Visit `/eager-loading/projects` - compare to N+1 version
2. Visit `/eager-loading/compare` - side-by-side analysis
3. Refactor N+1 code to use eager loading
4. Enable `Model::preventLazyLoading()`

**Query Comparison**:
| Scenario | N+1 | Eager | Improvement |
|----------|-----|-------|-------------|
| Projects with users | 28 | 2 | 14x |
| Tasks nested | 207 | 3 | 69x |
| User tree | 140 | 3 | 46x |

---

### Lesson 11: Complete Application (Review)

**Branch**: `11-complete-app`

**Objectives**:
- Review all concepts in context
- Understand how concepts work together
- Apply best practices consistently

**Activities**:
1. Walk through the complete data model
2. Review model configurations
3. Trace a request through controller to view
4. Run the test suite

---

## Interactive Exercises

### Exercise 1: Fix the Bug

Give students a model with N+1 queries and have them fix it:

```php
// BUG: This page is slow!
public function dashboard()
{
    $projects = Project::all();
    return view('dashboard', compact('projects'));
}

// Template accesses $project->user->name for each project
```

**Solution**: Add `with('user')` to the query.

---

### Exercise 2: Add a Feature

Have students add a new relationship:

1. Add a `Star` model (users can star projects)
2. Create migration with `user_id` and `project_id`
3. Define `belongsToMany` on both models
4. Add `$user->starredProjects()` method
5. Eager load in controller

---

### Exercise 3: Scope Challenge

Create scopes for common queries:

```php
// Turn these into scopes on Task:
Task::where('due_date', '<', now())->get();           // overdue()
Task::where('due_date', '>=', now())->get();          // upcoming()
Task::whereNull('completed_at')->get();                // pending()
Task::where('priority', '>=', 3)->get();               // important()
```

---

## Assessment Checklist

### Model Knowledge

- [ ] Can explain naming conventions
- [ ] Knows difference between `$fillable` and `$guarded`
- [ ] Can create accessors and mutators
- [ ] Can define query scopes

### Relationship Knowledge

- [ ] Can define `hasMany`/`belongsTo`
- [ ] Can define `belongsToMany` with pivot
- [ ] Can define polymorphic relationships
- [ ] Understands when to use each type

### Performance Knowledge

- [ ] Can identify N+1 queries
- [ ] Knows how to use `with()`
- [ ] Knows how to use `withCount()`
- [ ] Can enable strict mode

---

## Common Questions

**Q: Why is `user_id` not in Project's `$fillable`?**

A: To teach that sensitive foreign keys should be set through relationships, not mass assignment. Use `$user->projects()->create()` instead.

**Q: Why `task_tag` instead of `tag_task`?**

A: To teach that pivot table names can be customized, and you need to explicitly specify them in the relationship definition.

**Q: Why are branches 09 and 10 separate?**

A: Branch 09 is intentionally broken to let students experience the pain of N+1 queries before learning the fix in branch 10.

---

## Resources

- [Laravel Eloquent Docs](https://laravel.com/docs/eloquent)
- [Laravel Relationships](https://laravel.com/docs/eloquent-relationships)
- [Eloquent Performance](https://laravel.com/docs/eloquent#performance)

---

## Credits

Created for teaching Laravel Eloquent concepts through hands-on, progressive learning.

**Repository**: https://github.com/impruthvi/flexboard-bytes
