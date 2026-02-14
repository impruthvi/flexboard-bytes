# Branch 02: Mass Assignment Protection

## Learning Objectives

By the end of this lesson, you will understand:
- What mass assignment is and why it's dangerous
- How to use `$fillable` to whitelist attributes
- How to use `$guarded` to blacklist attributes
- Best practices for protecting your models

---

## What is Mass Assignment?

Mass assignment is when you set multiple model attributes at once using an array:

```php
// This is mass assignment
$project = Project::create([
    'name' => 'FlexBoard MVP',
    'description' => 'The best task tracker ever',
    'user_id' => 1,
]);

// This is also mass assignment
$project->update($request->all());
```

---

## The Danger: Mass Assignment Vulnerability

### The Attack Scenario

Imagine your User model has an `is_admin` column. A malicious user could:

```php
// Your innocent form
POST /register
{
    "name": "Priya Sharma",
    "email": "priya@example.com",
    "password": "secret123"
}

// Hacker's modified request
POST /register
{
    "name": "Hacker Singh",
    "email": "hacker@evil.com",
    "password": "secret123",
    "is_admin": true  // <- INJECTED!
}
```

### Vulnerable Code (NEVER DO THIS!)

```php
// ❌ DANGEROUS - accepts any field from request
User::create($request->all());

// Hacker is now an admin! 🚨
```

---

## Solution 1: $fillable (Whitelist)

Define which attributes ARE mass assignable.

```php
class Project extends Model
{
    // Only these fields can be mass assigned
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];
    
    // 'is_admin', 'total_points', etc. are protected!
}
```

### Now the attack fails:

```php
Project::create([
    'name' => 'Legit Project',
    'is_admin' => true,  // IGNORED! Not in $fillable
]);
```

---

## Solution 2: $guarded (Blacklist)

Define which attributes are NOT mass assignable.

```php
class Project extends Model
{
    // These fields CANNOT be mass assigned
    protected $guarded = [
        'id',
        'is_admin',
        'total_points',
    ];
    
    // Everything else is allowed
}
```

### Special Case: Empty Guarded

```php
// ⚠️ DANGER: Allows ALL fields to be mass assigned
protected $guarded = [];

// Only use this if you REALLY know what you're doing
// and are manually validating every input!
```

---

## Best Practices

### DO ✅

```php
// 1. Always use $fillable (preferred) or $guarded
protected $fillable = ['name', 'description'];

// 2. Validate input before mass assignment
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
]);
Project::create($validated);

// 3. Set sensitive fields explicitly
$project = Project::create($validated);
$project->user_id = auth()->id();  // Set separately
$project->save();
```

### DON'T ❌

```php
// 1. Never use $request->all() blindly
Project::create($request->all());  // DANGEROUS!

// 2. Never leave both $fillable and $guarded empty
class Project extends Model
{
    // No protection = vulnerability!
}

// 3. Don't put sensitive fields in $fillable
protected $fillable = ['name', 'is_admin'];  // BAD!
```

---

## Hands-On Exercise

### Step 1: Update Your Project Model

```php
// app/Models/Project.php
class Project extends Model
{
    /**
     * LESSON: Mass Assignment Protection
     * 
     * We whitelist ONLY the fields users should be able to set.
     * Sensitive fields like 'user_id' should be set explicitly in controller.
     */
    protected $fillable = [
        'name',
        'description',
    ];
}
```

### Step 2: Safe Controller Usage

```php
// In your controller
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);
    
    // Mass assign validated data, set user_id explicitly
    $project = new Project($validated);
    $project->user_id = auth()->id();
    $project->save();
    
    // Or use create() with merged data
    $project = Project::create([
        ...$validated,
        'user_id' => auth()->id(),
    ]);
}
```

### Step 3: Test in Tinker

```bash
php artisan tinker
```

```php
// This works (fields are in $fillable)
$project = \App\Models\Project::create([
    'name' => 'Test Project',
    'description' => 'Testing mass assignment',
    'user_id' => 1,
]);

// Try to inject a field not in $fillable
$project = \App\Models\Project::create([
    'name' => 'Hacker Project',
    'is_admin' => true,  // This will be ignored!
]);

$project->is_admin; // null or default - injection failed!
```

---

## Quick Reference

| Approach | Use When | Example |
|----------|----------|---------|
| `$fillable` | You know exactly which fields are safe | `['name', 'email', 'bio']` |
| `$guarded` | Most fields are safe, few are sensitive | `['id', 'is_admin']` |
| Empty `$guarded` | Full control via validation (advanced) | `[]` |

---

## Next Branch

Continue to `03-accessors-mutators` to learn about transforming data in and out of your models!

```bash
git checkout 03-accessors-mutators
```
