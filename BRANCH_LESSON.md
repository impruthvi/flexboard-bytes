# Branch 04: Query Scopes

## Learning Objectives

By the end of this lesson, you will understand:
- What query scopes are and why they're useful
- How to create local scopes for reusable query constraints
- How to create global scopes that apply automatically
- How to make scopes dynamic with parameters

---

## What Are Query Scopes?

Scopes are reusable query constraints. Instead of repeating the same `where()` clauses everywhere, you define them once in your model.

### Without Scopes (Repetitive)

```php
// In controller A
$tasks = Task::where('is_completed', false)->get();

// In controller B
$tasks = Task::where('is_completed', false)->where('priority', 'high')->get();

// In a job
$tasks = Task::where('is_completed', false)->count();
```

### With Scopes (DRY - Don't Repeat Yourself)

```php
// In controller A
$tasks = Task::incomplete()->get();

// In controller B
$tasks = Task::incomplete()->highPriority()->get();

// In a job
$tasks = Task::incomplete()->count();
```

---

## Local Scopes

Local scopes are methods you call explicitly. They start with `scope` prefix.

### Basic Local Scope

```php
// In your model
public function scopeIncomplete(Builder $query): Builder
{
    return $query->where('is_completed', false);
}

// Usage - note: no "scope" prefix when calling!
Task::incomplete()->get();
```

### Multiple Scopes (Chainable)

```php
public function scopeHighPriority(Builder $query): Builder
{
    return $query->where('priority', 'high');
}

public function scopeRecent(Builder $query): Builder
{
    return $query->orderBy('created_at', 'desc');
}

// Chain them together!
Task::incomplete()->highPriority()->recent()->get();
```

### Dynamic Scopes (With Parameters)

```php
public function scopeOfPriority(Builder $query, string $priority): Builder
{
    return $query->where('priority', $priority);
}

public function scopeOfDifficulty(Builder $query, string $difficulty): Builder
{
    return $query->where('difficulty', $difficulty);
}

// Usage
Task::ofPriority('high')->get();
Task::ofDifficulty('nightmare')->get();
Task::ofPriority('high')->ofDifficulty('easy')->get();
```

---

## Global Scopes

Global scopes apply automatically to ALL queries on a model. Use them carefully!

### Common Use Case: Multi-tenancy

```php
// Automatically filter by current user's team
class TeamScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('team_id', auth()->user()->team_id);
    }
}

// In your model
protected static function booted(): void
{
    static::addGlobalScope(new TeamScope);
}

// Now EVERY query is filtered!
Task::all(); // Only shows current team's tasks
```

### Inline Global Scope

```php
protected static function booted(): void
{
    static::addGlobalScope('active', function (Builder $builder) {
        $builder->where('is_active', true);
    });
}
```

### Removing Global Scopes

```php
// Remove a specific global scope
Task::withoutGlobalScope('active')->get();
Task::withoutGlobalScope(TeamScope::class)->get();

// Remove ALL global scopes
Task::withoutGlobalScopes()->get();
```

---

## FlexBoard Examples

### Task Scopes

```php
class Task extends Model
{
    // Status scopes
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    // Priority scopes
    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->where('priority', 'high');
    }

    public function scopeUrgent(Builder $query): Builder
    {
        return $query->where('priority', 'high')
                     ->where('is_completed', false);
    }

    // Dynamic scope
    public function scopeOfPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    // Points scope
    public function scopeHighValue(Builder $query, int $minPoints = 50): Builder
    {
        return $query->where('points', '>=', $minPoints);
    }

    // Date scopes
    public function scopeCreatedToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeCompletedThisWeek(Builder $query): Builder
    {
        return $query->where('is_completed', true)
                     ->whereBetween('completed_at', [
                         now()->startOfWeek(),
                         now()->endOfWeek(),
                     ]);
    }
}
```

### Real Usage

```php
// Dashboard: Today's urgent tasks
$urgentTasks = Task::urgent()->createdToday()->get();

// Leaderboard: This week's completed high-value tasks
$weeklyWins = Task::completedThisWeek()->highValue(100)->get();

// Filter by user selection
$tasks = Task::ofPriority($request->priority)
             ->ofDifficulty($request->difficulty)
             ->paginate();
```

---

## Best Practices

### DO ✅

```php
// 1. Use descriptive scope names
public function scopePublished(Builder $query): Builder

// 2. Keep scopes focused (single responsibility)
public function scopeHighPriority(Builder $query): Builder

// 3. Make scopes chainable (return Builder)
return $query->where(...);

// 4. Use parameter scopes for flexibility
public function scopeOfStatus(Builder $query, string $status): Builder
```

### DON'T ❌

```php
// 1. Don't put business logic in scopes
public function scopeWithCalculations(Builder $query): Builder
{
    // Don't do complex calculations here!
}

// 2. Don't make scopes too broad
public function scopeFiltered(Builder $query): Builder
{
    // What does "filtered" mean? Be specific!
}

// 3. Don't forget to type-hint Builder
public function scopeBad($query)  // Missing type hints!
```

---

## Quick Reference

| Scope Type | Definition | Usage |
|------------|------------|-------|
| Local | `scopeName(Builder $query)` | `Model::name()` |
| Dynamic | `scopeName(Builder $query, $param)` | `Model::name($value)` |
| Global | `addGlobalScope()` in `booted()` | Auto-applied |
| Remove Global | - | `withoutGlobalScope()` |

---

## Hands-On Exercise

Add these scopes to your Task model:
1. `scopeIncomplete()` - not completed tasks
2. `scopeHighPriority()` - priority = 'high'  
3. `scopeOfPriority($priority)` - dynamic priority filter
4. `scopeCreatedToday()` - created today

Then test in tinker:
```php
Task::incomplete()->highPriority()->get();
Task::ofPriority('low')->createdToday()->count();
```

---

## Next Branch

Continue to `05-timestamps-softdeletes` to learn about automatic timestamps and soft deletes!

```bash
git checkout 05-timestamps-softdeletes
```
