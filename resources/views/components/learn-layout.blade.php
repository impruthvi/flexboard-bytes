@props([
    'lessons',
    'currentTopic',
    'currentLesson',
    'previousTopic',
    'previousLesson',
    'nextTopic',
    'nextLesson',
    'currentIndex',
    'totalLessons',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $currentLesson['title'] }} - FlexBoard Learn</title>
    <meta name="description" content="{{ $currentLesson['description'] }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Prism.js Theme - Tomorrow Night -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Alpine.js cloak - prevent flash of unstyled content */
        [x-cloak] { display: none !important; }

        /* Prism.js Customizations for Cyberpunk Theme */
        pre[class*="language-"] {
            background: rgba(10, 10, 15, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem !important;
            margin: 0 !important;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        code[class*="language-"] {
            font-family: 'JetBrains Mono', monospace !important;
            font-size: 0.875rem;
        }

        /* Wrong code block - red glow */
        .code-wrong pre[class*="language-"] {
            border-color: rgba(255, 45, 146, 0.4);
            box-shadow: 0 0 20px rgba(255, 45, 146, 0.1), inset 0 0 30px rgba(255, 45, 146, 0.05);
        }

        /* Right code block - green glow */
        .code-right pre[class*="language-"] {
            border-color: rgba(57, 255, 20, 0.4);
            box-shadow: 0 0 20px rgba(57, 255, 20, 0.1), inset 0 0 30px rgba(57, 255, 20, 0.05);
        }

        /* Info code block - cyan glow */
        .code-info pre[class*="language-"] {
            border-color: rgba(0, 245, 255, 0.4);
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.1), inset 0 0 30px rgba(0, 245, 255, 0.05);
        }

        /* Sidebar active state */
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(0, 245, 255, 0.2), rgba(191, 90, 242, 0.2));
            border-color: rgba(0, 245, 255, 0.5);
        }
    </style>
</head>
<body class="font-sans antialiased bg-void min-h-screen" x-data="{ sidebarOpen: false }">
    <!-- Floating Orbs (static, subtle background) -->
    <div class="orb orb-pink w-72 h-72 -top-36 -left-36 opacity-30"></div>
    <div class="orb orb-cyan w-64 h-64 top-1/2 -right-32 opacity-30"></div>
    <div class="orb orb-purple w-56 h-56 bottom-20 left-1/3 opacity-30"></div>

    <div class="relative min-h-screen flex">
        <!-- Mobile Sidebar Toggle -->
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden fixed top-4 left-4 z-50 p-2 glass-card"
        >
            <!-- Hamburger Icon -->
            <svg x-show="!sidebarOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <!-- Close Icon -->
            <svg x-show="sidebarOpen" x-cloak class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:sticky top-0 left-0 z-40 w-72 h-screen bg-void-light/95 backdrop-blur-xl border-r border-glass-border transition-transform duration-300 overflow-y-auto"
        >
            <div class="p-6">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 mb-8">
                    <span class="text-2xl">💪</span>
                    <span class="font-display font-bold text-xl gradient-text">FlexBoard</span>
                </a>

                <!-- Progress -->
                <div class="mb-6">
                    <div class="flex items-center justify-between text-sm mb-2">
                        <span class="text-white/60">Progress</span>
                        <span class="text-neon-cyan font-mono">{{ $currentIndex + 1 }}/{{ $totalLessons }}</span>
                    </div>
                    <div class="h-2 bg-void rounded-full overflow-hidden">
                        <div
                            class="h-full bg-gradient-to-r from-neon-cyan to-neon-purple transition-all duration-500"
                            style="width: {{ (($currentIndex + 1) / $totalLessons) * 100 }}%"
                        ></div>
                    </div>
                </div>

                <!-- Lesson Navigation -->
                <nav class="space-y-2">
                    @foreach($lessons as $slug => $lesson)
                        @php
                            $lessonIndex = array_search($slug, array_keys($lessons));
                            $isActive = $slug === $currentTopic;
                            $isCompleted = $lessonIndex < $currentIndex;
                        @endphp
                        <a
                            href="{{ route('learn.show', $slug) }}"
                            class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl border border-transparent transition-all duration-200 hover:bg-glass-hover hover:border-glass-border {{ $isActive ? 'active' : '' }}"
                        >
                            <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg
                                {{ $isActive ? 'bg-neon-cyan/20 text-neon-cyan' : ($isCompleted ? 'bg-neon-green/20 text-neon-green' : 'bg-void text-white/40') }}
                            ">
                                @if($isCompleted)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <span class="text-sm font-mono">{{ str_pad($lessonIndex + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                @endif
                            </span>
                            <span class="text-sm {{ $isActive ? 'text-white font-medium' : 'text-white/70' }}">
                                {{ $lesson['title'] }}
                            </span>
                        </a>
                    @endforeach
                </nav>

                <!-- Bottom Links -->
                <div class="mt-8 pt-6 border-t border-glass-border space-y-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-white/60 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Back to Home
                    </a>
                    <a href="https://github.com/impruthvi/flexboard-bytes" target="_blank" class="flex items-center gap-3 px-4 py-2 text-sm text-white/60 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                        View on GitHub
                    </a>
                </div>
            </div>
        </aside>

        <!-- Sidebar Overlay (Mobile) -->
        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-void/80 backdrop-blur-sm z-30 lg:hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        <!-- Main Content -->
        <main class="flex-1 min-w-0">
            <div class="max-w-4xl mx-auto px-6 py-12 lg:px-12 pt-16 lg:pt-12">
                <!-- Lesson Header -->
                <header class="mb-12">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="chip chip-cyan">
                            Lesson {{ $currentIndex + 1 }} of {{ $totalLessons }}
                        </span>
                        <span class="chip chip-purple">
                            {{ $currentLesson['branch'] }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-4xl">{{ $currentLesson['icon'] }}</span>
                        <div>
                            <h1 class="font-display text-3xl lg:text-4xl font-bold gradient-text">
                                {{ $currentLesson['title'] }}
                            </h1>
                            <p class="text-white/60 mt-2">{{ $currentLesson['description'] }}</p>
                        </div>
                    </div>
                </header>

                <!-- Lesson Content -->
                <article class="prose prose-invert max-w-none">
                    {{ $slot }}
                </article>

                <!-- Navigation -->
                <nav class="mt-16 pt-8 border-t border-glass-border">
                    <div class="flex items-center justify-between gap-4">
                        @if($previousTopic)
                            <a href="{{ route('learn.show', $previousTopic) }}" class="group flex items-center gap-3 text-white/60 hover:text-white transition-colors">
                                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <div class="text-left">
                                    <div class="text-xs text-white/40">Previous</div>
                                    <div class="font-medium">{{ $previousLesson['title'] }}</div>
                                </div>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($nextTopic)
                            <a href="{{ route('learn.show', $nextTopic) }}" class="group flex items-center gap-3 text-white/60 hover:text-white transition-colors text-right">
                                <div>
                                    <div class="text-xs text-white/40">Next</div>
                                    <div class="font-medium">{{ $nextLesson['title'] }}</div>
                                </div>
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="btn-neon btn-neon-pink text-sm py-2 px-5">
                                Start Building!
                            </a>
                        @endif
                    </div>
                </nav>
            </div>
        </main>
    </div>

    <!-- Prism.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-sql.min.js"></script>

    @stack('scripts')
</body>
</html>
