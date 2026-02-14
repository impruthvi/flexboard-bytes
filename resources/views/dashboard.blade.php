<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-bold gradient-text">
                    Welcome back, {{ Auth::user()->display_name }}!
                </h1>
                <p class="text-white/60 mt-1">Ready to flex on those tasks?</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-xs text-white/40 uppercase tracking-wider">Level {{ Auth::user()->level }}</p>
                    <p class="font-display text-lg font-bold text-neon-cyan">{{ Auth::user()->rank_title }}</p>
                </div>
                <div class="avatar-ring">
                    <img class="w-12 h-12 object-cover" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->display_name }}">
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Points Card -->
        <div class="stat-card group hover:border-neon-yellow transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-3xl">✨</span>
                <span class="chip chip-cyan text-xs">Total Points</span>
            </div>
            <p class="stat-value text-glow-cyan">{{ number_format(Auth::user()->points) }}</p>
            <p class="stat-label">Lifetime Flexes</p>
            <div class="mt-4">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-white/60">Level Progress</span>
                    <span class="text-neon-cyan">{{ Auth::user()->level_progress }}%</span>
                </div>
                <div class="progress-neon">
                    <div class="progress-neon-bar" style="width: {{ Auth::user()->level_progress }}%"></div>
                </div>
            </div>
        </div>

        <!-- Streak Card -->
        <div class="stat-card group hover:border-neon-orange transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-3xl">🔥</span>
                <span class="chip chip-pink text-xs">Current Streak</span>
            </div>
            <p class="stat-value text-neon-orange">{{ Auth::user()->current_streak }}</p>
            <p class="stat-label">Days in a row</p>
            <div class="mt-4 text-sm">
                <span class="text-white/40">Longest:</span>
                <span class="text-white/80 font-mono">{{ Auth::user()->longest_streak }} days</span>
            </div>
        </div>

        <!-- Projects Card -->
        <div class="stat-card group hover:border-neon-purple transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-3xl">🚀</span>
                <span class="chip chip-purple text-xs">Active</span>
            </div>
            <p class="stat-value text-glow-purple">{{ $projects->count() }}</p>
            <p class="stat-label">Projects</p>
            <div class="mt-4">
                <a href="{{ route('projects.index') }}" class="text-sm text-neon-purple hover:text-neon-pink transition-colors">
                    View all →
                </a>
            </div>
        </div>

        <!-- Badges Card -->
        <div class="stat-card group hover:border-neon-green transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-3xl">🏆</span>
                <span class="chip chip-green text-xs">Earned</span>
            </div>
            <p class="stat-value text-glow-green">{{ Auth::user()->badges()->count() }}</p>
            <p class="stat-label">Badges</p>
            <div class="mt-4">
                <span class="text-sm text-white/40">Keep flexing to earn more!</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Tasks & Projects -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Quick Actions -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-display text-xl font-bold text-white">Quick Actions</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('projects.index') }}" class="btn-neon btn-neon-cyan text-sm py-2 px-4">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                New Project
                            </span>
                        </a>
                    </div>
                </div>

                @if($pendingTasks->isEmpty())
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-void-lighter flex items-center justify-center">
                            <span class="text-5xl">📝</span>
                        </div>
                        <h3 class="font-display text-xl font-bold text-white mb-2">No tasks yet, bestie</h3>
                        <p class="text-white/60 mb-6">Create your first project and start flexing on those goals!</p>
                        <a href="{{ route('projects.index') }}" class="btn-neon btn-neon-pink">
                            Start Your Flex Journey
                        </a>
                    </div>
                @else
                    <!-- Pending Tasks -->
                    <div class="space-y-3">
                        <h3 class="text-sm font-medium text-white/60 uppercase tracking-wider mb-4">Pending Tasks</h3>
                        @foreach($pendingTasks as $task)
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-void-lighter border border-glass-border hover:border-neon-cyan/30 transition-colors group">
                                <form action="{{ route('tasks.complete', $task) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-6 h-6 rounded-full border-2 border-white/30 hover:border-neon-cyan hover:bg-neon-cyan/20 transition-colors flex items-center justify-center group-hover:border-neon-cyan/50">
                                        <svg class="w-3 h-3 text-transparent group-hover:text-neon-cyan transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </form>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('projects.show', $task->project) }}" class="font-medium text-white hover:text-neon-cyan transition-colors truncate block">
                                        {{ $task->title }}
                                    </a>
                                    <p class="text-sm text-white/40">{{ $task->project->name }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($task->due_date)
                                        <span class="text-xs {{ $task->is_overdue ? 'text-neon-pink' : 'text-white/40' }}">
                                            {{ $task->due_date->format('M j') }}
                                        </span>
                                    @endif
                                    <span class="chip text-xs" style="background: {{ $task->priority_color }}20; color: {{ $task->priority_color }};">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{ route('projects.index') }}" class="block text-center text-sm text-neon-cyan hover:text-neon-pink transition-colors mt-4">
                            View all projects →
                        </a>
                    </div>
                @endif
            </div>

            <!-- Recent Flexes -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-display text-xl font-bold text-white">Recent Flexes</h2>
                </div>

                @if($recentFlexes->isEmpty())
                    <div class="text-center py-8">
                        <span class="text-4xl mb-4 block">💪</span>
                        <p class="text-white/60">Complete tasks to see your flexes here!</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentFlexes as $flex)
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-void-lighter border border-glass-border">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-neon-cyan to-neon-purple flex items-center justify-center text-lg">
                                    {{ $flex->points >= 20 ? '🔥' : '✨' }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-medium truncate">{{ $flex->task->title ?? 'Task completed' }}</p>
                                    <p class="text-sm text-neon-pink italic mt-1">"{{ $flex->flex_message }}"</p>
                                    <p class="text-xs text-white/40 mt-2">{{ $flex->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="points-badge text-sm">
                                    +{{ $flex->points }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Leaderboard & Badges -->
        <div class="space-y-6">
            <!-- Leaderboard Preview -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-display text-xl font-bold text-white">Leaderboard</h2>
                    <a href="{{ route('leaderboard.index') }}" class="text-sm text-neon-cyan hover:text-neon-pink transition-colors">See all</a>
                </div>

                @if($leaderboard->isEmpty())
                    <p class="text-center text-white/40 text-sm py-4">
                        No one on the leaderboard yet. Be the first!
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach($leaderboard as $user)
                            <div class="leaderboard-row {{ $user->rank <= 3 ? 'rank-' . $user->rank : '' }} {{ $user->id === Auth::id() ? 'ring-2 ring-neon-cyan' : '' }}">
                                <span class="text-lg font-bold {{ $user->rank === 1 ? 'text-neon-yellow' : ($user->rank === 2 ? 'text-white/60' : ($user->rank === 3 ? 'text-neon-orange' : 'text-white/40')) }}">
                                    #{{ $user->rank }}
                                </span>
                                <div class="avatar-ring flex-shrink-0">
                                    <img class="w-8 h-8 object-cover" src="{{ $user->avatar_url }}" alt="{{ $user->display_name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-white truncate text-sm">
                                        {{ $user->display_name }}
                                        @if($user->id === Auth::id())
                                            <span class="text-neon-cyan">(You)</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="points-badge text-xs">
                                    {{ number_format($user->points) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($currentUserRank > 5)
                        <div class="mt-4 pt-4 border-t border-glass-border">
                            <div class="leaderboard-row ring-2 ring-neon-cyan">
                                <span class="text-lg font-bold text-white/40">#{{ $currentUserRank }}</span>
                                <div class="avatar-ring flex-shrink-0">
                                    <img class="w-8 h-8 object-cover" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->display_name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-white truncate text-sm">
                                        {{ Auth::user()->display_name }}
                                        <span class="text-neon-cyan">(You)</span>
                                    </p>
                                </div>
                                <div class="points-badge text-xs">
                                    {{ number_format(Auth::user()->points) }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Motivation Quote -->
            <div class="glass-card p-6 border-l-4 border-neon-pink">
                @php
                    $quotes = [
                        "You're not lazy, you're just saving your energy for something legendary.",
                        "Main character energy loading... 99%",
                        "Today's vibe: absolutely crushing it.",
                        "Procrastination who? I don't know her.",
                        "Built different. Flexing harder.",
                    ];
                @endphp
                <p class="text-lg text-white/90 italic mb-4">
                    "{{ $quotes[array_rand($quotes)] }}"
                </p>
                <p class="text-sm text-neon-pink">— FlexBoard Daily Motivation</p>
            </div>

            <!-- Next Badge -->
            @if($nextBadge)
                <div class="glass-card p-6 bg-gradient-to-br from-neon-purple/10 to-neon-pink/10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 rounded-xl bg-void flex items-center justify-center text-3xl border border-neon-purple/30">
                            🎯
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-white">Next Badge</h3>
                            <p class="text-sm text-white/60">{{ $nextBadge['name'] }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-white/60 mb-4">{{ $nextBadge['description'] }}</p>
                    @php
                        $progress = min(100, ($nextBadge['progress'] / $nextBadge['target']) * 100);
                    @endphp
                    <div class="progress-neon">
                        <div class="progress-neon-bar" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs text-white/40 mt-2">{{ $nextBadge['progress'] }} / {{ $nextBadge['target'] }}</p>
                </div>
            @else
                <div class="glass-card p-6 bg-gradient-to-br from-neon-green/10 to-neon-cyan/10">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-void flex items-center justify-center text-3xl border border-neon-green/30">
                            👑
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-white">All Badges Earned!</h3>
                            <p class="text-sm text-white/60">You're a true FlexBoard legend!</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Projects -->
            @if($projects->isNotEmpty())
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-display text-lg font-bold text-white">Your Projects</h2>
                        <a href="{{ route('projects.index') }}" class="text-sm text-neon-cyan hover:text-neon-pink transition-colors">View all</a>
                    </div>
                    <div class="space-y-3">
                        @foreach($projects as $project)
                            <a href="{{ route('projects.show', $project) }}" class="block p-4 rounded-xl border border-glass-border hover:border-neon-cyan/30 transition-colors bg-void-lighter">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-2xl">{{ $project->emoji }}</span>
                                    <span class="font-medium text-white truncate">{{ $project->name }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-white/40">{{ $project->completed_tasks_count }}/{{ $project->tasks_count }} tasks</span>
                                    <span class="text-neon-cyan">{{ $project->completion_percentage }}%</span>
                                </div>
                                <div class="progress-neon mt-2 h-1">
                                    <div class="progress-neon-bar" style="width: {{ $project->completion_percentage }}%"></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
