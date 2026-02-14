<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-bold gradient-text">
                    Your Projects
                </h1>
                <p class="text-white/60 mt-1">Organize your flex journey</p>
            </div>
            <button 
                x-data=""
                x-on:click="$dispatch('open-modal', 'create-project')"
                class="btn-neon btn-neon-pink"
            >
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Project
                </span>
            </button>
        </div>
    </x-slot>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-neon-green/20 border border-neon-green text-neon-green">
            {{ session('success') }}
        </div>
    @endif

    <!-- Active Projects Grid -->
    <div class="mb-12">
        <h2 class="font-display text-xl font-bold text-white mb-6 flex items-center gap-2">
            <span class="text-2xl">🚀</span>
            Active Projects
            <span class="chip chip-cyan text-xs">{{ $projects->count() }}</span>
        </h2>

        @if($projects->isEmpty())
            <div class="glass-card p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-void-lighter flex items-center justify-center">
                    <span class="text-5xl">📁</span>
                </div>
                <h3 class="font-display text-xl font-bold text-white mb-2">No projects yet, bestie!</h3>
                <p class="text-white/60 mb-6">Create your first project and start flexing on those goals!</p>
                <button 
                    x-data=""
                    x-on:click="$dispatch('open-modal', 'create-project')"
                    class="btn-neon btn-neon-pink"
                >
                    Create Your First Project
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="block">
                        <div class="glass-card p-6 h-full border-l-4 hover:scale-[1.02] transition-transform" style="border-left-color: {{ $project->color }}">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl" style="background: {{ $project->color }}20;">
                                    {{ $project->emoji ?? '📁' }}
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($project->completed_tasks_count > 0)
                                        <span class="chip chip-green text-xs">
                                            {{ $project->completed_tasks_count }}/{{ $project->tasks_count }}
                                        </span>
                                    @else
                                        <span class="chip text-xs">
                                            {{ $project->tasks_count }} tasks
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <h3 class="font-display text-lg font-bold text-white mb-2">{{ $project->name }}</h3>
                            
                            @if($project->description)
                                <p class="text-white/60 text-sm mb-4 line-clamp-2">{{ $project->description }}</p>
                            @endif

                            <!-- Progress Bar -->
                            @if($project->tasks_count > 0)
                                <div class="mt-4">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-white/60">Progress</span>
                                        <span style="color: {{ $project->color }}">{{ $project->completion_percentage }}%</span>
                                    </div>
                                    <div class="progress-neon">
                                        <div class="progress-neon-bar" style="width: {{ $project->completion_percentage }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-white/40">
                                <span>Created {{ $project->created_at->diffForHumans() }}</span>
                                <span class="text-neon-cyan">View →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Archived Projects -->
    @if($archivedProjects->isNotEmpty())
        <div>
            <h2 class="font-display text-xl font-bold text-white/60 mb-6 flex items-center gap-2">
                <span class="text-2xl">📦</span>
                Archived
                <span class="chip text-xs">{{ $archivedProjects->count() }}</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 opacity-60">
                @foreach($archivedProjects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="block">
                        <div class="glass-card p-6 h-full">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-void-lighter flex items-center justify-center text-2xl grayscale">
                                    {{ $project->emoji ?? '📁' }}
                                </div>
                                <span class="chip text-xs">Archived</span>
                            </div>
                            <h3 class="font-display text-lg font-bold text-white mb-2">{{ $project->name }}</h3>
                            <p class="text-white/40 text-sm">{{ $project->completed_tasks_count }}/{{ $project->tasks_count }} tasks completed</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Create Project Modal -->
    <x-modal name="create-project" :show="false" maxWidth="lg">
        <form method="POST" action="{{ route('projects.store') }}" class="p-6">
            @csrf
            
            <h2 class="font-display text-2xl font-bold gradient-text mb-6">
                Create New Project
            </h2>

            <div class="space-y-4">
                <!-- Project Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-white/80 mb-2">Project Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="input-neon" 
                        placeholder="My Awesome Project"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-neon-pink">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-white/80 mb-2">Description (optional)</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="3" 
                        class="input-neon"
                        placeholder="What's this project about?"
                    ></textarea>
                </div>

                <!-- Color & Emoji Row -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="color" class="block text-sm font-medium text-white/80 mb-2">Color</label>
                        <div class="flex gap-2">
                            @foreach(['#ff2d92', '#00f5ff', '#bf5af2', '#39ff14', '#ff6b35', '#f7ff00'] as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $color }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="w-8 h-8 rounded-full border-2 border-transparent peer-checked:border-white peer-checked:scale-110 transition-all" style="background: {{ $color }}"></div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label for="emoji" class="block text-sm font-medium text-white/80 mb-2">Emoji</label>
                        <div class="flex gap-2">
                            @foreach(['🚀', '💡', '🎯', '⚡', '🔥', '💪'] as $emoji)
                                <label class="cursor-pointer">
                                    <input type="radio" name="emoji" value="{{ $emoji }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="w-8 h-8 rounded-lg bg-void-lighter flex items-center justify-center text-lg border-2 border-transparent peer-checked:border-neon-cyan peer-checked:bg-neon-cyan/20 transition-all">
                                        {{ $emoji }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="btn-neon btn-neon-outline text-sm py-2 px-4">
                    Cancel
                </button>
                <button type="submit" class="btn-neon btn-neon-pink text-sm py-2 px-4">
                    Create Project
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
