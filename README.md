# FlexBoard - Learn Laravel Eloquent by Building

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
    <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.4">
    <img src="https://img.shields.io/badge/Tailwind-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind 4">
    <img src="https://img.shields.io/badge/Pest-4-F472B6?style=for-the-badge" alt="Pest 4">
</p>

## What is FlexBoard?

FlexBoard is a **gamified task completion tracker** designed to teach Laravel Eloquent concepts to junior developers. Instead of reading dry documentation, you learn by building a real application through **11 progressive Git branches**.

Each branch introduces a new Eloquent concept, building on the previous one. By the end, you'll have a complete, production-ready Laravel application and a deep understanding of Eloquent ORM.

## Why FlexBoard?

- **Learn by Doing**: Each branch has working code you can run and experiment with
- **Progressive Complexity**: Start with basics (model conventions) and advance to complex topics (polymorphic relationships, eager loading)
- **"Wrong then Right" Approach**: See intentionally broken code (N+1 queries) then learn the fix (eager loading)
- **Interactive Lessons**: Built-in `/learn` route with Gen Z Cyberpunk-styled lesson pages
- **Real-World Patterns**: Learn patterns you'll actually use in production apps

## The Learning Journey

| Branch | Topic | What You'll Learn |
|--------|-------|-------------------|
| `01-model-conventions` | Model Conventions | Naming, tables, primary keys |
| `02-mass-assignment` | Mass Assignment | `$fillable`, `$guarded`, security |
| `03-accessors-mutators` | Accessors & Mutators | `casts()`, `Attribute` class |
| `04-scopes` | Query Scopes | Reusable query logic |
| `05-timestamps-softdeletes` | Timestamps & Soft Deletes | Auto-dates, recoverable deletes |
| `06-basic-relationships` | Basic Relationships | `hasMany`, `belongsTo`, `hasOne` |
| `07-many-to-many` | Many-to-Many | Pivot tables, `attach`, `sync` |
| `08-polymorphic` | Polymorphic Relations | `morphTo`, `morphMany` |
| `09-n-plus-one` | N+1 Problem | Understanding the performance killer |
| `10-eager-loading` | Eager Loading | `with()`, `withCount()`, `load()` |
| `11-complete-app` | Complete App | All best practices combined |

## Getting Started

### Prerequisites

- PHP 8.4+
- Composer
- Node.js & npm
- SQLite (or MySQL/PostgreSQL)

### Installation

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

# Run migrations and seed data
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start the server (or use Laravel Herd)
php artisan serve
```

### Navigating Branches

Each branch builds on the previous one:

```bash
# Start from the beginning
git checkout 01-model-conventions

# Move to the next lesson
git checkout 02-mass-assignment

# Jump to any lesson
git checkout 07-many-to-many

# See the complete application
git checkout main
```

## Interactive Lessons

Visit `/learn` in your browser to access the interactive lesson pages. Each lesson includes:

- Learning objectives
- Code examples (wrong vs. right approaches)
- Visual tables and diagrams
- Hands-on exercises
- Git checkout commands

## Project Structure

```
app/
├── Http/Controllers/
│   ├── LearnController.php      # Lesson page controller
│   └── ...
├── Models/
│   ├── User.php
│   ├── Project.php
│   ├── Task.php
│   ├── Tag.php
│   ├── Comment.php
│   ├── Reaction.php
│   └── Flex.php
resources/views/
├── learn/                       # Lesson views
│   ├── model-conventions.blade.php
│   ├── mass-assignment.blade.php
│   ├── accessors-mutators.blade.php
│   ├── scopes.blade.php
│   ├── timestamps-softdeletes.blade.php
│   ├── basic-relationships.blade.php
│   ├── many-to-many.blade.php
│   ├── polymorphic.blade.php
│   ├── n-plus-one.blade.php
│   ├── eager-loading.blade.php
│   └── complete-app.blade.php
├── components/
│   └── learn-layout.blade.php   # Lesson layout component
└── welcome.blade.php            # Landing page
```

## Key Concepts Covered

### Eloquent Basics
- Model naming conventions
- Mass assignment protection (`$fillable` / `$guarded`)
- Attribute casting and accessors/mutators
- Query scopes (local and dynamic)
- Timestamps and soft deletes

### Relationships
- One-to-Many (`hasMany` / `belongsTo`)
- One-to-One (`hasOne` / `belongsTo`)
- Many-to-Many (`belongsToMany` with pivot tables)
- Polymorphic (`morphTo` / `morphMany`)

### Performance
- Understanding the N+1 query problem
- Eager loading with `with()`
- Efficient counting with `withCount()`
- Lazy eager loading with `load()`
- Constrained eager loading

## Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter=LearnControllerTest
```

## Tech Stack

- **Framework**: Laravel 12
- **PHP**: 8.4
- **CSS**: Tailwind CSS 4
- **Testing**: Pest 4
- **Design**: Gen Z Cyberpunk aesthetic (neon colors, glassmorphism, dark mode)

## Contributing

Contributions are welcome! If you'd like to:

1. Add a new lesson
2. Fix a bug
3. Improve documentation
4. Enhance the UI

Please open an issue first to discuss the change.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Acknowledgments

- Built with [Laravel](https://laravel.com)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Tested with [Pest](https://pestphp.com)

---

<p align="center">
    <strong>Happy Learning!</strong><br>
    <em>Go from Eloquent newbie to Eloquent ninja, one branch at a time.</em>
</p>
