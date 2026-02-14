<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.index') }}" class="text-white/60 hover:text-neon-cyan transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl" style="background: {{ $project->color }}20;">
                    {{ $project->emoji ?? '📁' }}
                </div>
                <div>
                    <h1 class="font-display text-2xl font-bold text-white">{{ $project->name }}</h1>
                    @if($project->description)
                        <p class="text-white/60 text-sm mt-1">{{ $project->description }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button 
                    x-data=""
                    x-on:click="$dispatch('open-modal', 'create-task')"
                    class="btn-neon btn-neon-cyan text-sm py-2 px-4"
                >
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Task
                    </span>
                </button>
                <div x-data="{ open: false }" class="relative">
                    <button x-on:click="open = !open" class="p-2 rounded-lg bg-void-lighter hover:bg-void-card transition-colors">
                        <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                    <div 
                        x-show="open" 
                        x-on:click.away="open = false"
                        x-cloak
                        class="absolute right-0 mt-2 w-48 rounded-lg bg-void-card border border-glass-border shadow-lg overflow-hidden z-50"
                    >
                        <button 
                            x-on:click="$dispatch('open-modal', 'edit-project'); open = false"
                            class="w-full px-4 py-2 text-left text-sm text-white/80 hover:bg-neon-cyan/10 hover:text-neon-cyan transition-colors"
                        >
                            Edit Project
                        </button>
                        <form action="{{ route('projects.archive', $project) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 text-left text-sm text-white/80 hover:bg-neon-orange/10 hover:text-neon-orange transition-colors">
                                {{ $project->is_archived ? 'Restore Project' : 'Archive Project' }}
                            </button>
                        </form>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="w-full px-4 py-2 text-left text-sm text-white/80 hover:bg-neon-pink/10 hover:text-neon-pink transition-colors"
                                onclick="return confirm('Are you sure you want to delete this project? All tasks will be deleted too!')"
                            >
                                Delete Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-neon-green/20 border border-neon-green text-neon-green">
            {{ session('success') }}
        </div>
    @endif

    <!-- Project Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card">
            <p class="stat-value text-glow-cyan text-2xl">{{ $project->tasks->count() }}</p>
            <p class="stat-label text-xs">Total Tasks</p>
        </div>
        <div class="stat-card">
            <p class="stat-value text-glow-green text-2xl">{{ $completedTasks->count() }}</p>
            <p class="stat-label text-xs">Completed</p>
        </div>
        <div class="stat-card">
            <p class="stat-value text-glow-purple text-2xl">{{ $pendingTasks->count() }}</p>
            <p class="stat-label text-xs">Pending</p>
        </div>
        <div class="stat-card">
            <p class="stat-value text-glow-pink text-2xl">{{ $project->total_points }}</p>
            <p class="stat-label text-xs">Total Points</p>
        </div>
    </div>

    <!-- Progress Bar -->
    @if($project->tasks->count() > 0)
        <div class="glass-card p-4 mb-8">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-white/60">Project Progress</span>
                <span class="text-neon-cyan font-bold">{{ $project->completion_percentage }}%</span>
            </div>
            <div class="progress-neon h-3">
                <div class="progress-neon-bar" style="width: {{ $project->completion_percentage }}%"></div>
            </div>
        </div>
    @endif

    <!-- Tasks Section -->
    <div x-data="taskManager()" class="space-y-8" x-on:keydown.escape.window="closeCelebration()">
        <!-- Celebration Overlay -->
        <div 
            x-show="showCelebration" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 z-50 flex items-center justify-center bg-void/80 backdrop-blur-sm cursor-pointer"
            x-on:click="closeCelebration()"
        >
            <div class="text-center" x-on:click.stop>
                <div class="text-8xl mb-4 animate-bounce">🎉</div>
                <h2 class="font-display text-3xl font-bold gradient-text mb-2" x-text="celebrationMessage"></h2>
                <p class="text-neon-yellow text-2xl font-mono font-bold">+<span x-text="pointsEarned"></span> points!</p>
                <template x-if="badgeEarned">
                    <div class="mt-4">
                        <p class="text-neon-purple">🏆 Badge Unlocked: <span x-text="badgeEarned.name"></span></p>
                    </div>
                </template>
                <button 
                    x-on:click="closeCelebration()" 
                    class="mt-6 btn-neon btn-neon-cyan text-sm py-2 px-6"
                >
                    Keep Flexing!
                </button>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div>
            <h2 class="font-display text-xl font-bold text-white mb-4 flex items-center gap-2">
                <span class="text-2xl">📋</span>
                Pending Tasks
                <span class="chip chip-purple text-xs">{{ $pendingTasks->count() }}</span>
            </h2>

            @if($pendingTasks->isEmpty())
                <div class="glass-card p-8 text-center">
                    <span class="text-4xl mb-4 block">✨</span>
                    <p class="text-white/60">No pending tasks! Add one to start flexing.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($pendingTasks as $task)
                        <div 
                            class="task-card group flex items-center gap-4"
                            x-data="{ completing: false }"
                        >
                            <!-- Complete Button -->
                            <button 
                                x-on:click="completeTask({{ $task->id }})"
                                x-bind:disabled="completing"
                                class="w-8 h-8 rounded-full border-2 border-neon-green/50 hover:border-neon-green hover:bg-neon-green/20 flex items-center justify-center transition-all group-hover:scale-110"
                            >
                                <svg x-show="!completing" class="w-4 h-4 text-neon-green opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <svg x-show="completing" x-cloak class="w-4 h-4 text-neon-green animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>

                            <!-- Task Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-medium text-white truncate">{{ $task->title }}</h3>
                                    <span class="chip text-xs" style="background: {{ $task->priority_color }}20; border-color: {{ $task->priority_color }}; color: {{ $task->priority_color }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                @if($task->description)
                                    <p class="text-white/50 text-sm truncate">{{ $task->description }}</p>
                                @endif
                                <div class="flex items-center gap-3 mt-2 text-xs text-white/40">
                                    <span class="flex items-center gap-1">
                                        <span>⭐</span>{{ $task->points }} pts
                                    </span>
                                    @if($task->due_date)
                                        <span class="flex items-center gap-1 {{ $task->is_overdue ? 'text-neon-pink' : '' }}">
                                            <span>📅</span>{{ $task->due_date->format('M j') }}
                                        </span>
                                    @endif
                                    @if($task->tags->count() > 0)
                                        @foreach($task->tags->take(2) as $tag)
                                            <span class="px-2 py-0.5 rounded-full text-xs" style="background: {{ $tag->color }}20; color: {{ $tag->color }}">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button 
                                    x-on:click="$dispatch('open-modal', 'edit-task-{{ $task->id }}')"
                                    class="p-2 rounded-lg hover:bg-neon-cyan/20 text-white/60 hover:text-neon-cyan transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        class="p-2 rounded-lg hover:bg-neon-pink/20 text-white/60 hover:text-neon-pink transition-colors"
                                        onclick="return confirm('Delete this task?')"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Completed Tasks -->
        @if($completedTasks->isNotEmpty())
            <div>
                <h2 class="font-display text-xl font-bold text-white/60 mb-4 flex items-center gap-2">
                    <span class="text-2xl">✅</span>
                    Completed
                    <span class="chip chip-green text-xs">{{ $completedTasks->count() }}</span>
                </h2>

                <div class="space-y-3 opacity-60">
                    @foreach($completedTasks as $task)
                        <div class="task-card completed flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-neon-green/20 border-2 border-neon-green flex items-center justify-center">
                                <svg class="w-4 h-4 text-neon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-white/80 line-through">{{ $task->title }}</h3>
                                <p class="text-white/40 text-xs mt-1">
                                    Completed {{ $task->completed_at->diffForHumans() }} • +{{ $task->points }} pts
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Create Task Modal -->
    <x-modal name="create-task" :show="false" maxWidth="lg">
        <form method="POST" action="{{ route('tasks.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            
            <h2 class="font-display text-2xl font-bold gradient-text mb-6">
                Add New Task
            </h2>

            <div class="space-y-4">
                <!-- Task Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-white/80 mb-2">Task Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        class="input-neon" 
                        placeholder="What needs to be done?"
                        required
                    >
                </div>

                <!-- Description -->
                <div>
                    <label for="task_description" class="block text-sm font-medium text-white/80 mb-2">Description (optional)</label>
                    <textarea 
                        name="description" 
                        id="task_description" 
                        rows="2" 
                        class="input-neon"
                        placeholder="Any extra details?"
                    ></textarea>
                </div>

                <!-- Points, Priority, Difficulty Row -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="points" class="block text-sm font-medium text-white/80 mb-2">Points</label>
                        <input 
                            type="number" 
                            name="points" 
                            id="points" 
                            class="input-neon" 
                            value="10"
                            min="1"
                            max="100"
                        >
                    </div>
                    <div>
                        <label for="priority" class="block text-sm font-medium text-white/80 mb-2">Priority</label>
                        <select name="priority" id="priority" class="input-neon">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-white/80 mb-2">Difficulty</label>
                        <select name="difficulty" id="difficulty" class="input-neon">
                            <option value="easy">Easy</option>
                            <option value="medium" selected>Medium</option>
                            <option value="hard">Hard</option>
                            <option value="legendary">Legendary</option>
                        </select>
                    </div>
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-white/80 mb-2">Due Date (optional)</label>
                    <input 
                        type="date" 
                        name="due_date" 
                        id="due_date" 
                        class="input-neon"
                        min="{{ date('Y-m-d') }}"
                    >
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="btn-neon btn-neon-outline text-sm py-2 px-4">
                    Cancel
                </button>
                <button type="submit" class="btn-neon btn-neon-cyan text-sm py-2 px-4">
                    Add Task
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Project Modal -->
    <x-modal name="edit-project" :show="false" maxWidth="lg">
        <form method="POST" action="{{ route('projects.update', $project) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <h2 class="font-display text-2xl font-bold gradient-text mb-6">
                Edit Project
            </h2>

            <div class="space-y-4">
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-white/80 mb-2">Project Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="edit_name" 
                        class="input-neon" 
                        value="{{ $project->name }}"
                        required
                    >
                </div>

                <div>
                    <label for="edit_description" class="block text-sm font-medium text-white/80 mb-2">Description</label>
                    <textarea 
                        name="description" 
                        id="edit_description" 
                        rows="3" 
                        class="input-neon"
                    >{{ $project->description }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Color</label>
                        <div class="flex gap-2">
                            @foreach(['#ff2d92', '#00f5ff', '#bf5af2', '#39ff14', '#ff6b35', '#f7ff00'] as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $color }}" class="hidden peer" {{ $project->color === $color ? 'checked' : '' }}>
                                    <div class="w-8 h-8 rounded-full border-2 border-transparent peer-checked:border-white peer-checked:scale-110 transition-all" style="background: {{ $color }}"></div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Emoji</label>
                        <div class="flex gap-2">
                            @foreach(['🚀', '💡', '🎯', '⚡', '🔥', '💪'] as $emoji)
                                <label class="cursor-pointer">
                                    <input type="radio" name="emoji" value="{{ $emoji }}" class="hidden peer" {{ $project->emoji === $emoji ? 'checked' : '' }}>
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
                    Save Changes
                </button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function taskManager() {
            return {
                showCelebration: false,
                celebrationMessage: '',
                pointsEarned: 0,
                badgeEarned: null,

                async completeTask(taskId) {
                    try {
                        const response = await fetch(`/tasks/${taskId}/complete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.celebrationMessage = data.message;
                            this.pointsEarned = data.points_earned;
                            this.badgeEarned = data.badge_earned;
                            this.showCelebration = true;

                            // Update points in header if exists
                            const pointsEl = document.querySelector('[data-points]');
                            if (pointsEl) {
                                pointsEl.textContent = data.total_points.toLocaleString();
                            }
                        }
                    } catch (error) {
                        console.error('Error completing task:', error);
                    }
                },

                closeCelebration() {
                    this.showCelebration = false;
                    window.location.reload();
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
