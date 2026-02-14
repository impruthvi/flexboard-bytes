# Branch 01: Model Conventions

## Learning Objectives

By the end of this lesson, you will understand:
- Laravel's naming conventions for models and tables
- How to customize table names and primary keys
- How timestamps work in Eloquent

---

## The Convention Over Configuration Philosophy

Laravel follows **"Convention over Configuration"** - if you follow naming conventions, everything works automagically. Break them, and you need manual configuration.

---

## 1. Model & Table Naming

### The Convention (Auto-magic)

| Model Name | Expected Table Name |
|------------|---------------------|
| `User`     | `users`             |
| `Project`  | `projects`          |
| `TaskList` | `task_lists`        |
| `Category` | `categories`        |

**Rule**: Model is `PascalCase` singular, table is `snake_case` plural.

### Wrong Way (Don't do this)

```php
// Model: Project.php
// Table: project (singular - WRONG!)

class Project extends Model
{
    // This will fail! Laravel looks for "projects" table
}
```

### Right Way (Convention)

```php
// Model: Project.php  
// Table: projects (plural - CORRECT!)

class Project extends Model
{
    // Works automatically - no extra config needed!
}
```

### Right Way (Custom Table - When Required)

```php
// If you MUST use a non-conventional table name
class Project extends Model
{
    protected $table = 'tbl_projects'; // Legacy database? No problem!
}
```

---

## 2. Primary Key Conventions

### The Convention

- Primary key column: `id`
- Type: Auto-incrementing integer
- Laravel handles this automatically

### Wrong Way

```php
// Table has 'project_id' as primary key instead of 'id'
class Project extends Model
{
    // This breaks! Laravel expects 'id'
}
```

### Right Way (Custom Primary Key)

```php
class Project extends Model
{
    protected $primaryKey = 'project_id';
    
    // If it's NOT auto-incrementing:
    public $incrementing = false;
    
    // If it's a string (UUID):
    protected $keyType = 'string';
}
```

---

## 3. Timestamps

### The Convention

Laravel expects `created_at` and `updated_at` columns and manages them automatically.

### Wrong Way

```php
// Table doesn't have created_at/updated_at columns
class Project extends Model
{
    // ERROR: Column 'created_at' not found!
}
```

### Right Way (Disable Timestamps)

```php
class Project extends Model
{
    public $timestamps = false; // I don't need timestamps
}
```

### Right Way (Custom Timestamp Columns)

```php
class Project extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_modified';
}
```

---

## Hands-On Exercise

### Step 1: Create a Migration

```bash
php artisan make:migration create_projects_table
```

```php
// database/migrations/xxxx_create_projects_table.php
public function up(): void
{
    Schema::create('projects', function (Blueprint $table) {
        $table->id();                    // Creates 'id' column
        $table->string('name');
        $table->text('description')->nullable();
        $table->timestamps();            // Creates 'created_at' and 'updated_at'
    });
}
```

### Step 2: Create the Model

```bash
php artisan make:model Project
```

```php
// app/Models/Project.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // That's it! Conventions handle the rest!
}
```

### Step 3: Test It

```bash
php artisan tinker
```

```php
// In tinker
$project = new \App\Models\Project();
$project->name = 'FlexBoard MVP';
$project->save();

// Check it worked
\App\Models\Project::first();
// created_at and updated_at are automatically set!
```

---

## Quick Reference

| What | Convention | Customization |
|------|------------|---------------|
| Table name | `snake_case` plural of model | `protected $table = 'name';` |
| Primary key | `id` (auto-increment int) | `protected $primaryKey = 'col';` |
| Key type | Integer | `protected $keyType = 'string';` |
| Auto-increment | Yes | `public $incrementing = false;` |
| Timestamps | `created_at`, `updated_at` | `public $timestamps = false;` |

---

## Next Branch

Continue to `02-mass-assignment` to learn about protecting your models from mass assignment vulnerabilities!

```bash
git checkout 02-mass-assignment
```
