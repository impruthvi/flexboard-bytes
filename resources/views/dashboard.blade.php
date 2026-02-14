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
            <p class="stat-value text-glow-purple">{{ Auth::user()->projects()->where('is_archived', false)->count() }}</p>
            <p class="stat-label">Projects</p>
            <div class="mt-4">
                <a href="#" class="text-sm text-neon-purple hover:text-neon-pink transition-colors">
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
                <a href="#" class="text-sm text-neon-green hover:text-neon-cyan transition-colors">
                    View collection →
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Tasks -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Quick Actions -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-display text-xl font-bold text-white">Quick Actions</h2>
                    <div class="flex gap-2">
                        <button class="btn-neon btn-neon-cyan text-sm py-2 px-4">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                New Task
                            </span>
                        </button>
                        <button class="btn-neon btn-neon-outline text-sm py-2 px-4">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New Project
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-void-lighter flex items-center justify-center">
                        <span class="text-5xl">📝</span>
                    </div>
                    <h3 class="font-display text-xl font-bold text-white mb-2">No tasks yet, bestie</h3>
                    <p class="text-white/60 mb-6">Create your first project and start flexing on those goals!</p>
                    <button class="btn-neon btn-neon-pink">
                        Start Your Flex Journey
                    </button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-display text-xl font-bold text-white">Recent Flexes</h2>
                    <a href="#" class="text-sm text-neon-cyan hover:text-neon-pink transition-colors">View all</a>
                </div>

                <!-- Empty State -->
                <div class="text-center py-8">
                    <span class="text-4xl mb-4 block">💪</span>
                    <p class="text-white/60">Complete tasks to see your flexes here!</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Leaderboard & Feed -->
        <div class="space-y-6">
            <!-- Leaderboard Preview -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-display text-xl font-bold text-white">Leaderboard</h2>
                    <a href="#" class="text-sm text-neon-cyan hover:text-neon-pink transition-colors">See all</a>
                </div>

                <!-- Current User Rank -->
                <div class="leaderboard-row rank-1 mb-4">
                    <span class="text-2xl font-bold text-neon-yellow">#1</span>
                    <div class="avatar-ring flex-shrink-0">
                        <img class="w-10 h-10 object-cover" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->display_name }}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-white truncate">{{ Auth::user()->display_name }}</p>
                        <p class="text-sm text-white/60">{{ Auth::user()->rank_title }}</p>
                    </div>
                    <div class="points-badge text-sm">
                        {{ number_format(Auth::user()->points) }}
                    </div>
                </div>

                <p class="text-center text-white/40 text-sm py-4">
                    Add friends to compete on the leaderboard!
                </p>

                <button class="w-full btn-neon btn-neon-outline text-sm py-2">
                    Find Friends
                </button>
            </div>

            <!-- Motivation Quote -->
            <div class="glass-card p-6 border-l-4 border-neon-pink">
                <p class="text-lg text-white/90 italic mb-4">
                    "You're not lazy, you're just saving your energy for something legendary."
                </p>
                <p class="text-sm text-neon-pink">— FlexBoard Daily Motivation</p>
            </div>

            <!-- Achievement Teaser -->
            <div class="glass-card p-6 bg-gradient-to-br from-neon-purple/10 to-neon-pink/10">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-xl bg-void flex items-center justify-center text-3xl border border-neon-purple/30">
                        🎯
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Next Badge</h3>
                        <p class="text-sm text-white/60">First Flex</p>
                    </div>
                </div>
                <p class="text-sm text-white/60 mb-4">Complete your first task to unlock this badge!</p>
                <div class="progress-neon">
                    <div class="progress-neon-bar" style="width: 0%"></div>
                </div>
                <p class="text-xs text-white/40 mt-2">0 / 1 tasks completed</p>
            </div>
        </div>
    </div>
</x-app-layout>
