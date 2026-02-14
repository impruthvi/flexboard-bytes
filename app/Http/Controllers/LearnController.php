<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LearnController extends Controller
{
    /**
     * All available lessons with metadata.
     *
     * @var array<string, array{title: string, description: string, icon: string, branch: string}>
     */
    protected array $lessons = [
        'model-conventions' => [
            'title' => 'Model Conventions',
            'description' => 'Laravel naming conventions for models and tables',
            'icon' => '📐',
            'branch' => '01-model-conventions',
        ],
        'mass-assignment' => [
            'title' => 'Mass Assignment',
            'description' => 'Protect your models from mass assignment vulnerabilities',
            'icon' => '🛡️',
            'branch' => '02-mass-assignment',
        ],
        'accessors-mutators' => [
            'title' => 'Accessors & Mutators',
            'description' => 'Transform data when getting and setting attributes',
            'icon' => '🔄',
            'branch' => '03-accessors-mutators',
        ],
        'scopes' => [
            'title' => 'Query Scopes',
            'description' => 'Reusable query constraints for cleaner code',
            'icon' => '🔍',
            'branch' => '04-scopes',
        ],
        'timestamps-softdeletes' => [
            'title' => 'Timestamps & Soft Deletes',
            'description' => 'Auto-manage dates and implement recoverable deletes',
            'icon' => '🕐',
            'branch' => '05-timestamps-softdeletes',
        ],
        'basic-relationships' => [
            'title' => 'Basic Relationships',
            'description' => 'One-to-One and One-to-Many relationships',
            'icon' => '🔗',
            'branch' => '06-basic-relationships',
        ],
        'many-to-many' => [
            'title' => 'Many-to-Many',
            'description' => 'Pivot tables and belongsToMany relationships',
            'icon' => '🔀',
            'branch' => '07-many-to-many',
        ],
        'polymorphic' => [
            'title' => 'Polymorphic Relations',
            'description' => 'One model, multiple relationship targets',
            'icon' => '🦎',
            'branch' => '08-polymorphic',
        ],
        'n-plus-one' => [
            'title' => 'N+1 Problem',
            'description' => 'Understand the most common performance killer',
            'icon' => '🐌',
            'branch' => '09-n-plus-one',
        ],
        'eager-loading' => [
            'title' => 'Eager Loading',
            'description' => 'The fix for N+1 - load relationships efficiently',
            'icon' => '🚀',
            'branch' => '10-eager-loading',
        ],
        'complete-app' => [
            'title' => 'Complete App',
            'description' => 'Production-ready FlexBoard with all patterns',
            'icon' => '🏆',
            'branch' => '11-complete-app',
        ],
    ];

    /**
     * Redirect to first lesson.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('learn.show', 'model-conventions');
    }

    /**
     * Show a specific lesson.
     */
    public function show(string $topic): View
    {
        abort_unless(array_key_exists($topic, $this->lessons), 404);

        $lessonKeys = array_keys($this->lessons);
        $currentIndex = array_search($topic, $lessonKeys);

        $previousTopic = $currentIndex > 0 ? $lessonKeys[$currentIndex - 1] : null;
        $nextTopic = $currentIndex < count($lessonKeys) - 1 ? $lessonKeys[$currentIndex + 1] : null;

        return view("learn.{$topic}", [
            'lessons' => $this->lessons,
            'currentTopic' => $topic,
            'currentLesson' => $this->lessons[$topic],
            'previousTopic' => $previousTopic,
            'previousLesson' => $previousTopic ? $this->lessons[$previousTopic] : null,
            'nextTopic' => $nextTopic,
            'nextLesson' => $nextTopic ? $this->lessons[$nextTopic] : null,
            'currentIndex' => $currentIndex,
            'totalLessons' => count($this->lessons),
        ]);
    }
}
