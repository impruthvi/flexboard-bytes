<x-app-layout>
    <x-slot name="header">
        <div class="text-center">
            <h1 class="font-display text-3xl font-bold gradient-text">
                Leaderboard
            </h1>
            <p class="text-white/60 mt-1">Who's flexing the hardest?</p>
        </div>
    </x-slot>

    <!-- Current User Rank Card -->
    @auth
        <div class="glass-card p-6 mb-8 border border-neon-cyan/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="text-3xl font-bold text-neon-cyan">#{{ $currentUserRank }}</div>
                    <div class="avatar-ring">
                        <img class="w-14 h-14 object-cover" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->display_name }}">
                    </div>
                    <div>
                        <p class="font-display text-xl font-bold text-white">{{ Auth::user()->display_name }}</p>
                        <p class="text-neon-cyan">{{ Auth::user()->rank_title }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-mono font-bold text-neon-yellow">{{ number_format(Auth::user()->points) }}</p>
                    <p class="text-white/60 text-sm">points</p>
                </div>
            </div>
        </div>
    @endauth

    <!-- Top 3 Podium -->
    @if($users->count() >= 3)
        <div class="grid grid-cols-3 gap-4 mb-8">
            <!-- 2nd Place -->
            <div class="glass-card p-6 text-center mt-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-2xl font-bold text-white">
                    2
                </div>
                <div class="avatar-ring mx-auto mb-3" style="--tw-gradient-from: #c0c0c0; --tw-gradient-to: #808080;">
                    <img class="w-16 h-16 object-cover" src="{{ $users[1]->avatar_url }}" alt="{{ $users[1]->display_name }}">
                </div>
                <h3 class="font-display font-bold text-white truncate">{{ $users[1]->display_name }}</h3>
                <p class="text-2xl font-mono font-bold text-gray-400">{{ number_format($users[1]->points) }}</p>
                <p class="text-white/40 text-sm">{{ $users[1]->rank_title }}</p>
            </div>

            <!-- 1st Place -->
            <div class="glass-card p-6 text-center border-2 border-neon-yellow relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-neon-yellow via-neon-orange to-neon-yellow"></div>
                <div class="text-4xl mb-2">👑</div>
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-neon-yellow to-neon-orange flex items-center justify-center text-3xl font-bold text-void">
                    1
                </div>
                <div class="avatar-ring mx-auto mb-3">
                    <img class="w-20 h-20 object-cover" src="{{ $users[0]->avatar_url }}" alt="{{ $users[0]->display_name }}">
                </div>
                <h3 class="font-display text-xl font-bold text-white">{{ $users[0]->display_name }}</h3>
                <p class="text-3xl font-mono font-bold text-neon-yellow">{{ number_format($users[0]->points) }}</p>
                <p class="text-neon-yellow/60 text-sm">{{ $users[0]->rank_title }}</p>
                @if($users[0]->current_streak > 0)
                    <div class="mt-2 inline-flex items-center gap-1 px-2 py-1 rounded-full bg-neon-orange/20 text-neon-orange text-sm">
                        🔥 {{ $users[0]->current_streak }} day streak
                    </div>
                @endif
            </div>

            <!-- 3rd Place -->
            <div class="glass-card p-6 text-center mt-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-amber-600 to-amber-800 flex items-center justify-center text-2xl font-bold text-white">
                    3
                </div>
                <div class="avatar-ring mx-auto mb-3" style="--tw-gradient-from: #cd7f32; --tw-gradient-to: #8b4513;">
                    <img class="w-16 h-16 object-cover" src="{{ $users[2]->avatar_url }}" alt="{{ $users[2]->display_name }}">
                </div>
                <h3 class="font-display font-bold text-white truncate">{{ $users[2]->display_name }}</h3>
                <p class="text-2xl font-mono font-bold text-amber-600">{{ number_format($users[2]->points) }}</p>
                <p class="text-white/40 text-sm">{{ $users[2]->rank_title }}</p>
            </div>
        </div>
    @endif

    <!-- Rest of Leaderboard -->
    <div class="glass-card overflow-hidden">
        <div class="p-4 border-b border-white/10">
            <h2 class="font-display text-lg font-bold text-white">All Flexers</h2>
        </div>
        
        <div class="divide-y divide-white/5">
            @foreach($users as $user)
                <div class="leaderboard-row {{ $user->rank <= 3 ? 'rank-' . $user->rank : '' }} {{ Auth::check() && $user->id === Auth::id() ? 'bg-neon-cyan/10' : '' }}">
                    <!-- Rank -->
                    <div class="w-12 text-center">
                        @if($user->rank === 1)
                            <span class="text-2xl">🥇</span>
                        @elseif($user->rank === 2)
                            <span class="text-2xl">🥈</span>
                        @elseif($user->rank === 3)
                            <span class="text-2xl">🥉</span>
                        @else
                            <span class="text-xl font-bold text-white/60">#{{ $user->rank }}</span>
                        @endif
                    </div>

                    <!-- Avatar -->
                    <div class="avatar-ring flex-shrink-0">
                        <img class="w-10 h-10 object-cover" src="{{ $user->avatar_url }}" alt="{{ $user->display_name }}">
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-white truncate">
                            {{ $user->display_name }}
                            @if(Auth::check() && $user->id === Auth::id())
                                <span class="text-neon-cyan text-xs">(You)</span>
                            @endif
                        </p>
                        <p class="text-sm text-white/60">{{ $user->rank_title }}</p>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-4">
                        @if($user->current_streak > 0)
                            <div class="flex items-center gap-1 text-neon-orange">
                                <span>🔥</span>
                                <span class="font-mono text-sm">{{ $user->current_streak }}</span>
                            </div>
                        @endif
                        <div class="points-badge">
                            {{ number_format($user->points) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-8 glass-card p-6">
        <h3 class="font-display font-bold text-white mb-4">Rank Titles</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-white/40"></span>
                <span class="text-white/60">Level 1: Newbie Flexer</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-neon-cyan"></span>
                <span class="text-white/60">Level 2-4: Rookie Flexer</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-neon-purple"></span>
                <span class="text-white/60">Level 5-9: Pro Flexer</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-neon-pink"></span>
                <span class="text-white/60">Level 10-19: Elite Flexer</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-neon-orange"></span>
                <span class="text-white/60">Level 20-49: Master Flexer</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-neon-yellow"></span>
                <span class="text-white/60">Level 50+: Legendary Flexer</span>
            </div>
        </div>
    </div>
</x-app-layout>
