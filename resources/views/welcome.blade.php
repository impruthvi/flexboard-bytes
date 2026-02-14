<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FlexBoard - Learn Laravel Eloquent by Building</title>
    <meta name="description" content="A gamified task tracker that teaches Laravel Eloquent through 11 progressive branches. Learn by doing, not just reading.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-void text-white overflow-x-hidden" x-data="{ mobileMenuOpen: false }">
    <!-- Animated Background Orbs -->
    <div class="orb orb-pink w-96 h-96 -top-48 -left-48 animate-pulse"></div>
    <div class="orb orb-cyan w-80 h-80 top-1/3 -right-40 animate-pulse" style="animation-delay: 1s;"></div>
    <div class="orb orb-purple w-72 h-72 bottom-1/4 left-1/4 animate-pulse" style="animation-delay: 2s;"></div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-void/80 backdrop-blur-xl border-b border-glass-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💪</span>
                    <span class="font-display font-bold text-xl gradient-text">FlexBoard</span>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('learn.index') }}" class="nav-link-neon flex items-center gap-1.5 text-neon-cyan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Learn
                    </a>
                    <a href="#features" class="nav-link-neon">Features</a>
                    <a href="#branches" class="nav-link-neon">Branches</a>
                    <a href="#demo" class="nav-link-neon">Demo</a>
                    <a href="https://github.com/impruthvi/flexboard-bytes" target="_blank" class="nav-link-neon flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        GitHub
                    </a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-neon btn-neon-cyan text-sm py-2 px-5">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link-neon">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-neon btn-neon-pink text-sm py-2 px-5">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-white/70 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition class="md:hidden py-4 border-t border-glass-border">
                <div class="flex flex-col gap-4">
                    <a href="{{ route('learn.index') }}" class="nav-link-neon flex items-center gap-2 text-neon-cyan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Learn Eloquent
                    </a>
                    <a href="#features" class="nav-link-neon">Features</a>
                    <a href="#branches" class="nav-link-neon">Branches</a>
                    <a href="#demo" class="nav-link-neon">Demo</a>
                    <a href="https://github.com/impruthvi/flexboard-bytes" target="_blank" class="nav-link-neon">GitHub</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-neon btn-neon-cyan text-sm py-2 px-5 text-center">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link-neon">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-neon btn-neon-pink text-sm py-2 px-5 text-center">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-16 bg-mesh">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 mb-8 animate-fade-in">
                    <span class="chip chip-cyan">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-neon-cyan opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-neon-cyan"></span>
                        </span>
                        Laravel 12 + Eloquent
                    </span>
                </div>

                <!-- Headline -->
                <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-bold mb-6 leading-tight animate-slide-up">
                    Learn <span class="gradient-text">Eloquent</span><br>
                    by <span class="text-glow-cyan">Building</span>
                </h1>

                <!-- Subheadline -->
                <p class="text-xl sm:text-2xl text-white/70 mb-10 max-w-2xl mx-auto animate-slide-up" style="animation-delay: 0.1s;">
                    A gamified task tracker that teaches Laravel Eloquent through 
                    <span class="text-neon-pink font-semibold">11 progressive branches</span>. 
                    Learn by doing, not just reading.
                </p>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16 animate-slide-up" style="animation-delay: 0.2s;">
                    <a href="{{ route('learn.index') }}" class="btn-neon btn-neon-pink text-lg py-3 px-8">
                        <span class="flex items-center gap-2">
                            <span>Start Learning</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </a>
                    <a href="https://github.com/impruthvi/flexboard-bytes" target="_blank" class="btn-neon btn-neon-outline text-lg py-3 px-8">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            <span>View on GitHub</span>
                        </span>
                    </a>
                </div>

                <!-- Code Preview -->
                <div class="glass-card p-6 max-w-2xl mx-auto text-left animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="ml-4 text-white/40 text-sm font-mono">EagerLoadingDemoController.php</span>
                    </div>
                    <pre class="font-mono text-sm overflow-x-auto"><code class="text-white/90"><span class="text-white/40">// Branch 10: The N+1 Fix!</span>
<span class="text-neon-purple">$projects</span> = <span class="text-neon-cyan">Project</span>::<span class="text-neon-green">with</span>(<span class="text-neon-yellow">'user'</span>)-><span class="text-neon-green">get</span>();

<span class="text-white/40">// 2 queries instead of 28!</span>
<span class="text-neon-purple">foreach</span> (<span class="text-neon-purple">$projects</span> <span class="text-neon-purple">as</span> <span class="text-neon-purple">$project</span>) {
    <span class="text-neon-purple">echo</span> <span class="text-neon-purple">$project</span>-><span class="text-white">user</span>-><span class="text-white">name</span>;
}</code></pre>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-void-light relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl sm:text-5xl font-bold mb-4">
                    Why <span class="gradient-text">FlexBoard</span>?
                </h2>
                <p class="text-xl text-white/60 max-w-2xl mx-auto">
                    Not another boring tutorial. Learn Eloquent the way it sticks.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="glass-card p-6 group hover:border-neon-cyan transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-neon-cyan/20 border border-neon-cyan/30 flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition-transform">
                        🎓
                    </div>
                    <h3 class="font-display text-xl font-bold mb-3 text-white">Progressive Learning</h3>
                    <p class="text-white/60">
                        11 cumulative branches from model conventions to eager loading. Each builds on the last.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card p-6 group hover:border-neon-pink transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-neon-pink/20 border border-neon-pink/30 flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition-transform">
                        🔥
                    </div>
                    <h3 class="font-display text-xl font-bold mb-3 text-white">Wrong Then Right</h3>
                    <p class="text-white/60">
                        See broken code first, understand why it fails, then learn the proper fix. Mistakes teach best.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card p-6 group hover:border-neon-purple transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-neon-purple/20 border border-neon-purple/30 flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition-transform">
                        🎮
                    </div>
                    <h3 class="font-display text-xl font-bold mb-3 text-white">Gamified Experience</h3>
                    <p class="text-white/60">
                        Flex points, streaks, badges, and leaderboards. Learning should be fun, not a chore.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card p-6 group hover:border-neon-green transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-neon-green/20 border border-neon-green/30 flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition-transform">
                        🚀
                    </div>
                    <h3 class="font-display text-xl font-bold mb-3 text-white">Production-Ready</h3>
                    <p class="text-white/60">
                        Real Laravel 12 app with Tailwind CSS v4, Pest tests, and best practices throughout.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Branches Section -->
    <section id="branches" class="py-24 bg-mesh relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl sm:text-5xl font-bold mb-4">
                    The <span class="text-glow-pink">Learning</span> Journey
                </h2>
                <p class="text-xl text-white/60 max-w-2xl mx-auto">
                    11 branches, 11 concepts, one complete understanding of Eloquent.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $branches = [
                        ['num' => '01', 'name' => 'Model Conventions', 'desc' => 'Naming, tables, keys', 'color' => 'cyan', 'slug' => 'model-conventions'],
                        ['num' => '02', 'name' => 'Mass Assignment', 'desc' => '$fillable & $guarded', 'color' => 'pink', 'slug' => 'mass-assignment'],
                        ['num' => '03', 'name' => 'Accessors & Mutators', 'desc' => 'casts() & Attribute', 'color' => 'purple', 'slug' => 'accessors-mutators'],
                        ['num' => '04', 'name' => 'Query Scopes', 'desc' => 'Reusable query logic', 'color' => 'green', 'slug' => null],
                        ['num' => '05', 'name' => 'Soft Deletes', 'desc' => 'Timestamps & recovery', 'color' => 'orange', 'slug' => null],
                        ['num' => '06', 'name' => 'Basic Relationships', 'desc' => 'hasMany, belongsTo', 'color' => 'cyan', 'slug' => null],
                        ['num' => '07', 'name' => 'Many-to-Many', 'desc' => 'Pivot tables & sync', 'color' => 'pink', 'slug' => null],
                        ['num' => '08', 'name' => 'Polymorphic', 'desc' => 'morphTo & morphMany', 'color' => 'purple', 'slug' => null],
                        ['num' => '09', 'name' => 'N+1 Problem', 'desc' => 'Intentionally broken!', 'color' => 'orange', 'slug' => null],
                        ['num' => '10', 'name' => 'Eager Loading', 'desc' => 'with(), withCount()', 'color' => 'green', 'slug' => null],
                        ['num' => '11', 'name' => 'Complete App', 'desc' => 'All best practices', 'color' => 'yellow', 'slug' => null],
                    ];
                @endphp

                @foreach ($branches as $branch)
                    @if ($branch['slug'])
                        <a href="{{ route('learn.show', $branch['slug']) }}" class="glass-card p-4 flex items-center gap-4 hover:border-neon-{{ $branch['color'] }} transition-all group cursor-pointer">
                    @else
                        <div class="glass-card p-4 flex items-center gap-4 hover:border-neon-{{ $branch['color'] }} transition-all group opacity-70">
                    @endif
                        <div class="w-12 h-12 rounded-lg bg-neon-{{ $branch['color'] }}/20 border border-neon-{{ $branch['color'] }}/30 flex items-center justify-center font-mono font-bold text-neon-{{ $branch['color'] }} group-hover:scale-110 transition-transform">
                            {{ $branch['num'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-display font-bold text-white truncate">{{ $branch['name'] }}</h4>
                            <p class="text-sm text-white/50 truncate">{{ $branch['desc'] }}</p>
                        </div>
                        @if ($branch['slug'])
                            <svg class="w-5 h-5 text-white/30 group-hover:text-neon-{{ $branch['color'] }} group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        @else
                            <span class="chip chip-{{ $branch['color'] }} text-xs">Soon</span>
                        @endif
                    @if ($branch['slug'])
                        </a>
                    @else
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('learn.index') }}" class="btn-neon btn-neon-cyan">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Start Learning Now</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section id="demo" class="py-24 bg-void-light relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl sm:text-5xl font-bold mb-4">
                    See It In <span class="text-glow-cyan">Action</span>
                </h2>
                <p class="text-xl text-white/60 max-w-2xl mx-auto">
                    Compare N+1 queries vs eager loading side by side.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Bad Example -->
                <div class="glass-card p-6 border-l-4 border-neon-orange">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="chip chip-pink">Branch 09</span>
                        <span class="text-neon-orange font-mono font-bold">N+1 Problem</span>
                    </div>
                    <div class="bg-void rounded-lg p-4 mb-4">
                        <pre class="font-mono text-sm overflow-x-auto"><code class="text-white/80"><span class="text-white/40">// BAD: 28 queries for 27 projects!</span>
<span class="text-neon-purple">$projects</span> = <span class="text-neon-cyan">Project</span>::<span class="text-neon-green">all</span>();

<span class="text-neon-purple">foreach</span> (<span class="text-neon-purple">$projects</span> <span class="text-neon-purple">as</span> <span class="text-neon-purple">$project</span>) {
    <span class="text-neon-orange">// Query per project!</span>
    <span class="text-neon-purple">echo</span> <span class="text-neon-purple">$project</span>-><span class="text-white">user</span>-><span class="text-white">name</span>;
}</code></pre>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl font-mono font-bold text-neon-orange">28</span>
                            <span class="text-white/60 text-sm">queries</span>
                        </div>
                        <div class="flex-1 h-2 bg-void rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-neon-orange to-red-500 w-full"></div>
                        </div>
                        <span class="text-2xl">💀</span>
                    </div>
                </div>

                <!-- Good Example -->
                <div class="glass-card p-6 border-l-4 border-neon-green">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="chip chip-green">Branch 10</span>
                        <span class="text-neon-green font-mono font-bold">Eager Loading</span>
                    </div>
                    <div class="bg-void rounded-lg p-4 mb-4">
                        <pre class="font-mono text-sm overflow-x-auto"><code class="text-white/80"><span class="text-white/40">// GOOD: Always 2 queries!</span>
<span class="text-neon-purple">$projects</span> = <span class="text-neon-cyan">Project</span>::<span class="text-neon-green">with</span>(<span class="text-neon-yellow">'user'</span>)-><span class="text-neon-green">get</span>();

<span class="text-neon-purple">foreach</span> (<span class="text-neon-purple">$projects</span> <span class="text-neon-purple">as</span> <span class="text-neon-purple">$project</span>) {
    <span class="text-neon-green">// Already loaded!</span>
    <span class="text-neon-purple">echo</span> <span class="text-neon-purple">$project</span>-><span class="text-white">user</span>-><span class="text-white">name</span>;
}</code></pre>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl font-mono font-bold text-neon-green">2</span>
                            <span class="text-white/60 text-sm">queries</span>
                        </div>
                        <div class="flex-1 h-2 bg-void rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-neon-green to-neon-cyan" style="width: 7%;"></div>
                        </div>
                        <span class="text-2xl">🚀</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <p class="text-white/60 mb-4">
                    <span class="text-neon-green font-bold">14x improvement</span> with just one method call!
                </p>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-neon-purple/20 via-neon-pink/10 to-neon-cyan/20"></div>
        <div class="absolute inset-0 bg-mesh"></div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-6xl mb-8 block">💪</span>
            <h2 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">
                Ready to <span class="gradient-text">Flex</span> on Eloquent?
            </h2>
            <p class="text-xl text-white/70 mb-10 max-w-2xl mx-auto">
                Join developers who learn Laravel the fun way. Complete tasks, earn points, and become an Eloquent master.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-neon btn-neon-pink text-lg py-4 px-10">
                        <span class="flex items-center gap-2">
                            <span>Get Started Free</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </a>
                @endif
                <a href="https://github.com/impruthvi/flexboard-bytes/blob/main/PRESENTATION.md" target="_blank" class="btn-neon btn-neon-outline text-lg py-4 px-10">
                    Teaching Guide
                </a>
            </div>

            <p class="mt-8 text-white/40 text-sm">
                Open source. Built with Laravel 12, Tailwind CSS v4, and Pest.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 border-t border-glass-border bg-void">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-xl">💪</span>
                    <span class="font-display font-bold gradient-text">FlexBoard</span>
                    <span class="text-white/40">|</span>
                    <span class="text-white/40 text-sm">Learn Eloquent by Building</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="https://github.com/impruthvi/flexboard-bytes" target="_blank" class="text-white/60 hover:text-neon-cyan transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    <span class="text-white/40 text-sm">
                        Made with <span class="text-neon-pink">♥</span> for Laravel learners
                    </span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
