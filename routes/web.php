<?php

use App\Http\Controllers\EagerLoadingDemoController;
use App\Http\Controllers\NplusOneDemoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// =========================================================================
// LESSON: N+1 Query Problem Demo Routes (Branch 09)
//
// Visit these URLs to see the N+1 problem in action!
// The response shows all queries executed - watch the count explode!
// =========================================================================

Route::prefix('n-plus-one')->name('n-plus-one.')->group(function () {
    Route::get('/projects', [NplusOneDemoController::class, 'projectsWithUsers'])
        ->name('projects');

    Route::get('/tasks', [NplusOneDemoController::class, 'tasksWithProjectsAndUsers'])
        ->name('tasks');

    Route::get('/users', [NplusOneDemoController::class, 'usersWithProjectsAndTasks'])
        ->name('users');

    Route::get('/dashboard', [NplusOneDemoController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/counts', [NplusOneDemoController::class, 'projectsWithTaskCounts'])
        ->name('counts');

    Route::get('/polymorphic', [NplusOneDemoController::class, 'tasksWithCommentsAndReactions'])
        ->name('polymorphic');
});

// =========================================================================
// LESSON: Eager Loading - The Fix! (Branch 10)
//
// Same URLs but with /eager-loading prefix - see the queries drop!
// Compare with /n-plus-one routes to see the difference!
// =========================================================================

Route::prefix('eager-loading')->name('eager-loading.')->group(function () {
    Route::get('/projects', [EagerLoadingDemoController::class, 'projectsWithUsers'])
        ->name('projects');

    Route::get('/tasks', [EagerLoadingDemoController::class, 'tasksWithProjectsAndUsers'])
        ->name('tasks');

    Route::get('/users', [EagerLoadingDemoController::class, 'usersWithProjectsAndTasks'])
        ->name('users');

    Route::get('/dashboard', [EagerLoadingDemoController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/counts', [EagerLoadingDemoController::class, 'projectsWithTaskCounts'])
        ->name('counts');

    Route::get('/polymorphic', [EagerLoadingDemoController::class, 'tasksWithCommentsAndReactions'])
        ->name('polymorphic');

    // Bonus routes
    Route::get('/lazy', [EagerLoadingDemoController::class, 'lazyEagerLoading'])
        ->name('lazy');

    Route::get('/constrained', [EagerLoadingDemoController::class, 'constrainedEagerLoading'])
        ->name('constrained');

    Route::get('/compare', [EagerLoadingDemoController::class, 'compare'])
        ->name('compare');
});
