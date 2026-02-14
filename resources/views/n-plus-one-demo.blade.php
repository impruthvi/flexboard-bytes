{{--
    LESSON: N+1 Query Problem in Blade Templates (Branch 09)

    WARNING: This template demonstrates the N+1 problem!
    Each $project->user and $project->tasks access triggers extra queries!

    If you have 10 projects, this page makes:
    - 1 query for all projects
    - 10 queries for users (N+1!)
    - 10 queries for tasks (N+1!)
    = 21 queries total!

    Check your Laravel debugbar or telescope to see the queries.
--}}

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>N+1 Problem Demo | FlexBoard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-red-500">
                N+1 Query Problem Demo
            </h1>
            <p class="text-gray-400 mt-2">
                This page has intentional N+1 queries! Check your query log.
            </p>
        </header>

        {{-- Each iteration triggers N+1 queries! --}}
        @foreach ($projects as $project)
            <div class="bg-gray-800 rounded-lg p-6 mb-4 border border-red-500/30">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-white">
                            {{ $project->name }}
                        </h2>
                        {{--
                            BAD: $project->user triggers a query!
                            With 10 projects = 10 extra queries!
                        --}}
                        <p class="text-gray-400">
                            Owner: {{ $project->user->name }}
                        </p>
                    </div>
                    <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-sm">
                        N+1 Alert!
                    </span>
                </div>

                {{--
                    BAD: $project->tasks triggers another query!
                    With 10 projects = 10 more extra queries!
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

        <div class="mt-8 p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
            <h3 class="text-red-400 font-semibold">What's Wrong Here?</h3>
            <ul class="list-disc list-inside text-gray-400 mt-2 space-y-1">
                <li>Each <code class="text-red-300">$project->user</code> triggers a DB query</li>
                <li>Each <code class="text-red-300">$project->tasks</code> triggers another query</li>
                <li>10 projects = 1 + 10 + 10 = <strong class="text-red-300">21 queries!</strong></li>
                <li>100 projects = <strong class="text-red-300">201 queries!</strong></li>
            </ul>
            <p class="text-gray-400 mt-4">
                See Branch 10 (eager-loading) for the fix!
            </p>
        </div>
    </div>
</body>
</html>
