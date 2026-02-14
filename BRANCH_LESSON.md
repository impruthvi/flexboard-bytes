# Branch 03: Accessors, Mutators & Attribute Casting

## Learning Objectives

By the end of this lesson, you will understand:
- How to transform data when reading (accessors)
- How to transform data when writing (mutators)
- How to use attribute casting for automatic type conversion
- Laravel 11+ attribute syntax using `Attribute` class

---

## What Are Accessors & Mutators?

- **Accessor**: Transforms data when you READ from the model
- **Mutator**: Transforms data when you WRITE to the model

Think of them as "data transformers" that sit between your code and the database.

---

## Modern Syntax (Laravel 9+)

Laravel 9+ uses the `Attribute` class for a cleaner, unified syntax:

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

protected function firstName(): Attribute
{
    return Attribute::make(
        get: fn (string $value) => ucfirst($value),  // Accessor
        set: fn (string $value) => strtolower($value), // Mutator
    );
}
```

---

## Accessors: Transform on Read

### Example: Format a Name

```php
// Database stores: "priya sharma"
// You want to display: "Priya Sharma"

protected function name(): Attribute
{
    return Attribute::make(
        get: fn (string $value) => ucwords($value),
    );
}

// Usage
$user->name; // "Priya Sharma" (even though DB has "priya sharma")
```

### Example: Computed/Virtual Attribute

```php
// Create an attribute that doesn't exist in the database

protected function fullName(): Attribute
{
    return Attribute::make(
        get: fn () => "{$this->first_name} {$this->last_name}",
    );
}

// Usage
$user->full_name; // "Priya Sharma"
```

---

## Mutators: Transform on Write

### Example: Auto-Lowercase Email

```php
protected function email(): Attribute
{
    return Attribute::make(
        set: fn (string $value) => strtolower($value),
    );
}

// Usage
$user->email = 'PRIYA@EXAMPLE.COM';
$user->save();
// Database stores: "priya@example.com"
```

### Example: Hash Password Automatically

```php
protected function password(): Attribute
{
    return Attribute::make(
        set: fn (string $value) => bcrypt($value),
    );
}

// Usage
$user->password = 'secret123';
// Database stores: "$2y$10$..." (hashed)
```

---

## Attribute Casting

Casting automatically converts attributes to common data types:

```php
protected function casts(): array
{
    return [
        'is_completed' => 'boolean',
        'points' => 'integer',
        'settings' => 'array',
        'completed_at' => 'datetime',
        'metadata' => 'object',
        'price' => 'decimal:2',
    ];
}
```

### Cast Examples

```php
// Database: is_completed = 1 (integer)
$task->is_completed; // true (boolean)

// Database: settings = '{"theme":"dark"}'
$task->settings; // ['theme' => 'dark'] (array)
$task->settings['theme']; // "dark"

// Database: completed_at = "2024-01-15 10:30:00"
$task->completed_at; // Carbon instance
$task->completed_at->diffForHumans(); // "2 hours ago"
```

---

## Practical FlexBoard Examples

### Task Model: Priority Color

```php
// Get a color based on priority
protected function priorityColor(): Attribute
{
    return Attribute::make(
        get: fn () => match($this->priority) {
            'low' => '#22c55e',    // green
            'medium' => '#f59e0b', // amber
            'high' => '#ef4444',   // red
            default => '#6b7280',  // gray
        },
    );
}

// Usage in Blade
<span style="color: {{ $task->priority_color }}">
    {{ $task->priority }}
</span>
```

### Project Model: Completion Percentage

```php
protected function completionPercentage(): Attribute
{
    return Attribute::make(
        get: function () {
            $total = $this->tasks()->count();
            if ($total === 0) return 0;
            
            $completed = $this->tasks()->where('is_completed', true)->count();
            return round(($completed / $total) * 100);
        },
    );
}

// Usage
$project->completion_percentage; // 75
```

### Auto-Generate Slug

```php
protected function name(): Attribute
{
    return Attribute::make(
        set: function (string $value) {
            return [
                'name' => $value,
                'slug' => Str::slug($value),
            ];
        },
    );
}

// Usage
$project->name = 'My Awesome Project';
// name = "My Awesome Project"
// slug = "my-awesome-project" (auto-generated!)
```

---

## Appending Computed Attributes to JSON

For API responses, append virtual attributes:

```php
protected $appends = ['full_name', 'completion_percentage'];

// Now when you do:
return $project->toJson();
// These computed attributes are included!
```

---

## Quick Reference

| Feature | Purpose | Example |
|---------|---------|---------|
| Accessor (get) | Transform on read | `ucwords($name)` |
| Mutator (set) | Transform on write | `strtolower($email)` |
| Casting | Auto type conversion | `'boolean'`, `'array'` |
| `$appends` | Include in JSON | Virtual attributes |

---

## Hands-On Exercise

Update your Task model with:

1. A `priority_color` accessor
2. A `difficulty_label` accessor  
3. Cast `is_completed` to boolean
4. Cast `completed_at` to datetime

Then test in tinker!

---

## Next Branch

Continue to `04-scopes` to learn about reusable query constraints!

```bash
git checkout 04-scopes
```
