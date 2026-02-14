<?php

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
