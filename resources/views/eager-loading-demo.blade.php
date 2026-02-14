{{--
    LESSON: Eager Loading in Blade Templates (Branch 10)

    This template is the FIXED version of n-plus-one-demo.blade.php.
    Because we used with(['user', 'tasks']) in the controller,
    NO extra queries are made here!

    Before (N+1):
    - 1 query for projects
    - 10 queries for users
    - 10 queries for tasks
    = 21 queries!

    After (Eager Loading):
    - 1 query for projects
    - 1 query for users (WHERE IN)
    - 1 query for tasks (WHERE IN)
    = 3 queries! (Always, regardless of count!)
--}}

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eager Loading Demo | FlexBoard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-green-500">
                Eager Loading - The Fix!
            </h1>
            <p class="text-gray-400 mt-2">
                Same data, but with eager loading. Only 3 queries total!
            </p>
        </header>

        {{--
            No N+1 here! Everything was pre-loaded with:
            Project::with(['user', 'tasks'])->get()
        --}}
        @foreach ($projects as $project)
            <div class="bg-gray-800 rounded-lg p-6 mb-4 border border-green-500/30">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-white">
                            {{ $project->name }}
                        </h2>
                        {{--
                            FIXED: $project->user is already loaded!
                            No extra query triggered.
                        --}}
                        <p class="text-gray-400">
                            Owner: {{ $project->user->name }}
                        </p>
                    </div>
                    <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm">
                        Optimized!
                    </span>
                </div>

                {{--
                    FIXED: $project->tasks is already loaded!
                    No extra query triggered.
                --}}
                <div class="space-y-2">
                    <h3 class="text-sm font-medium text-gray-300">
                        Tasks ({{ $project->tasks->count() }})
                    </h3>
                    @foreach ($project->tasks as $task)
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <span class="{{ $task->is_completed ? 'line-through' : '' }}">
                                {{ $task->title }}
                            </span>
                            <span class="text-xs" style="color: {{ $task->priority_color }}">
                                {{ $task->priority }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="mt-8 p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
            <h3 class="text-green-400 font-semibold">What We Fixed</h3>
            <ul class="list-disc list-inside text-gray-400 mt-2 space-y-1">
                <li>Used <code class="text-green-300">with(['user', 'tasks'])</code> in controller</li>
                <li>Laravel fetches all users in ONE query using WHERE IN</li>
                <li>Laravel fetches all tasks in ONE query using WHERE IN</li>
                <li>10 projects = <strong class="text-green-300">3 queries</strong></li>
                <li>100 projects = <strong class="text-green-300">still 3 queries!</strong></li>
            </ul>
        </div>

        <div class="mt-4 flex gap-4">
            <a href="{{ url('/n-plus-one/dashboard') }}"
               class="text-red-400 hover:text-red-300 underline">
                Compare with N+1 Version
            </a>
            <a href="{{ url('/eager-loading/compare') }}"
               class="text-blue-400 hover:text-blue-300 underline">
                See Side-by-Side Comparison
            </a>
        </div>
    </div>
</body>
</html>
