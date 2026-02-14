# Branch 09: The N+1 Query Problem

## Learning Objectives

By the end of this lesson, you will understand:
- What the N+1 query problem is
- How to identify N+1 queries in your code
- Why N+1 queries kill application performance
- How to detect N+1 queries using tools

---

## What is the N+1 Query Problem?

The N+1 query problem occurs when your code makes **1 query to get N records**, then **N additional queries** to get related data for each record.

### Simple Example

```php
// 1 query to get 100 projects
$projects = Project::all();

foreach ($projects as $project) {
    // 100 MORE queries - one for each user!
    echo $project->user->name;
}
// Total: 1 + 100 = 101 queries!
```

If you have 1,000 projects, that's **1,001 queries** just to display a list!

---

## Why is N+1 Bad?

### Performance Impact

| Records | N+1 Queries | Optimized | Difference |
|---------|-------------|-----------|------------|
| 10 | 11 | 2 | 5.5x worse |
| 100 | 101 | 2 | 50.5x worse |
| 1,000 | 1,001 | 2 | 500x worse |
| 10,000 | 10,001 | 2 | 5,000x worse |

### Real Numbers

- Each query takes ~1-5ms (more on remote databases)
- 100 queries = 100-500ms added
- 1,000 queries = 1-5 SECONDS added
- Your users are gone!

---

## Identifying N+1 in Code

### Red Flag #1: Loops Accessing Relationships

```php
// BAD: Each iteration triggers a query!
foreach ($projects as $project) {
    $project->user->name;      // N+1!
    $project->tasks->count();  // Another N+1!
}
```

### Red Flag #2: Blade Templates

```blade
{{-- BAD: Each $project->user triggers a query! --}}
@foreach ($projects as $project)
    <p>Owner: {{ $project->user->name }}</p>
    <p>Tasks: {{ $project->tasks->count() }}</p>
@endforeach
```

### Red Flag #3: Nested Loops

```php
// CATASTROPHIC: N+1 inside N+1!
foreach ($users as $user) {           // N users
    foreach ($user->projects as $project) {    // N queries!
        foreach ($project->tasks as $task) {   // N*M queries!
            echo $task->title;
        }
    }
}
// With 10 users, 5 projects each, 10 tasks = 511 queries!
```

---

## FlexBoard N+1 Examples

This branch includes a demo controller with intentionally BAD code.

### Demo Routes

Visit these URLs to see N+1 in action:

```
GET /n-plus-one/projects     # Projects with users
GET /n-plus-one/tasks        # Tasks with projects and users
GET /n-plus-one/users        # Users -> Projects -> Tasks
GET /n-plus-one/counts       # Count queries N+1
GET /n-plus-one/polymorphic  # Comments & reactions N+1
GET /n-plus-one/dashboard    # Blade template N+1
```

Each response includes:
- The data
- **All queries executed**
- **Query count**
- Problem explanation

---

## Controller Examples (BAD Code!)

### Example 1: Basic N+1

```php
public function projectsWithUsers()
{
    // 1 query
    $projects = Project::all();

    foreach ($projects as $project) {
        // N more queries - one per project!
        $project->user->name;
    }
}
```

### Example 2: Double N+1

```php
public function tasksWithProjectsAndUsers()
{
    // 1 query
    $tasks = Task::all();

    foreach ($tasks as $task) {
        $task->project->name;         // N queries
        $task->project->user->name;   // N MORE queries!
    }
    // Total: 1 + N + N = 2N+1 queries
}
```

### Example 3: Count N+1

```php
public function projectsWithTaskCounts()
{
    $projects = Project::all();

    foreach ($projects as $project) {
        // Each count() is a separate query!
        $project->tasks()->count();           // N queries
        $project->tasks()->completed()->count(); // N MORE!
    }
}
```

---

## Detecting N+1 Queries

### Method 1: Query Logging

```php
use Illuminate\Support\Facades\DB;

DB::enableQueryLog();

// Your code here...

$queries = DB::getQueryLog();
dd(count($queries), $queries);
```

### Method 2: Laravel Debugbar

Install the package:

```bash
composer require barryvdh/laravel-debugbar --dev
```

Shows query count and time in your browser!

### Method 3: Strict Mode (Laravel 10+)

```php
// In AppServiceProvider::boot()
Model::preventLazyLoading(! app()->isProduction());
```

This throws an exception when N+1 occurs!

### Method 4: Telescope

Laravel Telescope shows:
- All queries per request
- Duplicate queries highlighted
- Query timing

---

## Quick Detection Checklist

Ask yourself:

- [ ] Am I accessing relationships in a loop?
- [ ] Am I using `->count()` or `->sum()` in a loop?
- [ ] Are my Blade templates accessing relationships?
- [ ] Do I have nested loops with relationships?

If YES to any, you likely have N+1!

---

## Hands-On Exercise

1. Run the seeder to create test data:

```bash
php artisan migrate:fresh --seed
```

2. Visit `/n-plus-one/projects` and check the query count

3. Visit `/n-plus-one/users` and watch queries explode!

4. Look at the controller code in `NplusOneDemoController`

5. Try to predict query counts before checking

---

## What's Next?

This code is **intentionally broken**!

The next branch (`10-eager-loading`) shows how to fix all these issues using:
- `with()` for eager loading
- `withCount()` for efficient counts
- `load()` for lazy eager loading
- Query optimization techniques

```bash
git checkout 10-eager-loading
```

---

## Remember

> "The N+1 problem is the #1 performance killer in Laravel apps."

Every relationship access in a loop is a potential N+1.
Always eager load!
