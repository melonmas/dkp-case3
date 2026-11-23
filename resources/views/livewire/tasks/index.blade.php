<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-semibold text-zinc-100 tracking-tight">
                {{ $view === 'trash' ? 'Sampah' : 'Tasks' }}
            </h1>
            <p class="text-xs text-zinc-500 mt-0.5">
                @if($view === 'trash')
                    Task yang dihapus masih dapat dipulihkan
                @else
                    Kelola dan pantau seluruh task Anda
                @endif
            </p>
        </div>

        @if($view === 'active')
            <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
                Task Baru
            </flux:button>
        @else
            <flux:button wire:click="setView('active')" variant="subtle" icon="arrow-left">
                Kembali ke Tasks
            </flux:button>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3" role="region" aria-label="Ringkasan Statistik Task">
        <!-- Total -->
        <div class="col-span-2 sm:col-span-1 p-4 rounded-lg bg-zinc-900 border border-zinc-800 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-400">Total Task</span>
                <flux:button wire:click="refreshStatsCache" size="xs" variant="ghost" icon="arrow-path" aria-label="Segarkan cache statistik" title="Segarkan cache" />
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-semibold text-zinc-100 tabular-nums">{{ $stats['total'] }}</span>
                <span class="text-[10px] text-zinc-500">Cache: {{ $stats['cached_at'] ?? 'Aktif' }}</span>
            </div>
        </div>

        <!-- Pending -->
        <button wire:click="setStatusFilter('pending')"
                type="button"
                aria-pressed="{{ $view === 'active' && $status === 'pending' ? 'true' : 'false' }}"
                aria-label="Filter status Pending: {{ $stats['pending'] }} task"
                class="text-left p-4 rounded-lg bg-zinc-900 border transition cursor-pointer focus:outline-none focus:ring-2 focus:ring-amber-500/50 {{ $view === 'active' && $status === 'pending' ? 'border-amber-500/60 ring-1 ring-amber-500/30 bg-amber-500/5' : 'border-zinc-800 hover:border-zinc-700' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-400">Pending</span>
                <span class="w-2 h-2 rounded-full bg-amber-400/80"></span>
            </div>
            <p class="text-2xl font-semibold text-amber-400 mt-2 tabular-nums">{{ $stats['pending'] }}</p>
        </button>

        <!-- In Progress -->
        <button wire:click="setStatusFilter('in_progress')"
                type="button"
                aria-pressed="{{ $view === 'active' && $status === 'in_progress' ? 'true' : 'false' }}"
                aria-label="Filter status In Progress: {{ $stats['in_progress'] }} task"
                class="text-left p-4 rounded-lg bg-zinc-900 border transition cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/50 {{ $view === 'active' && $status === 'in_progress' ? 'border-blue-500/60 ring-1 ring-blue-500/30 bg-blue-500/5' : 'border-zinc-800 hover:border-zinc-700' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-400">In Progress</span>
                <span class="w-2 h-2 rounded-full bg-blue-400/80"></span>
            </div>
            <p class="text-2xl font-semibold text-blue-400 mt-2 tabular-nums">{{ $stats['in_progress'] }}</p>
        </button>

        <!-- Completed -->
        <button wire:click="setStatusFilter('completed')"
                type="button"
                aria-pressed="{{ $view === 'active' && $status === 'completed' ? 'true' : 'false' }}"
                aria-label="Filter status Selesai: {{ $stats['completed'] }} task"
                class="text-left p-4 rounded-lg bg-zinc-900 border transition cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/50 {{ $view === 'active' && $status === 'completed' ? 'border-emerald-500/60 ring-1 ring-emerald-500/30 bg-emerald-500/5' : 'border-zinc-800 hover:border-zinc-700' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-400">Completed</span>
                <span class="w-2 h-2 rounded-full bg-emerald-400/80"></span>
            </div>
            <p class="text-2xl font-semibold text-emerald-400 mt-2 tabular-nums">{{ $stats['completed'] }}</p>
        </button>

        <!-- Trash -->
        <button wire:click="setView('trash')"
                type="button"
                aria-pressed="{{ $view === 'trash' ? 'true' : 'false' }}"
                aria-label="Buka Sampah: {{ $stats['trash'] }} task terhapus"
                class="text-left p-4 rounded-lg bg-zinc-900 border transition cursor-pointer focus:outline-none focus:ring-2 focus:ring-rose-500/50 {{ $view === 'trash' ? 'border-rose-500/60 ring-1 ring-rose-500/30 bg-rose-500/5' : 'border-zinc-800 hover:border-zinc-700' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-400">Sampah</span>
                <span class="w-2 h-2 rounded-full bg-rose-400/80"></span>
            </div>
            <p class="text-2xl font-semibold text-rose-400 mt-2 tabular-nums">{{ $stats['trash'] }}</p>
        </button>
    </div>

    <!-- Filter Bar / Context Bar -->
    @if($view === 'trash')
        <div class="flex items-center justify-between p-3 rounded-lg bg-rose-950/20 border border-rose-900/30">
            <div class="flex items-center gap-2 text-xs text-rose-300">
                <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Task di dalam sampah dapat dipulihkan atau dihapus secara permanen.</span>
            </div>
            <flux:button wire:click="setView('active')" size="xs" variant="ghost" icon="arrow-left">
                Kembali ke Task Aktif
            </flux:button>
        </div>
    @else
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3">
                <div class="w-40 sm:w-44">
                    <flux:select wire:model.live="status" aria-label="Filter berdasarkan status">
                        <flux:select.option value="">Semua Status</flux:select.option>
                        <flux:select.option value="pending">Pending</flux:select.option>
                        <flux:select.option value="in_progress">In Progress</flux:select.option>
                        <flux:select.option value="completed">Completed</flux:select.option>
                    </flux:select>
                </div>

                <div class="w-40 sm:w-44">
                    <flux:select wire:model.live="priority" aria-label="Filter berdasarkan prioritas">
                        <flux:select.option value="">Semua Prioritas</flux:select.option>
                        <flux:select.option value="low">Low</flux:select.option>
                        <flux:select.option value="medium">Medium</flux:select.option>
                        <flux:select.option value="high">High</flux:select.option>
                    </flux:select>
                </div>

                @if($status !== '' || $priority !== '')
                    <flux:button wire:click="resetFilters" variant="ghost" icon="x-mark" title="Reset filter" aria-label="Reset semua filter aktif">
                        Reset Filter
                    </flux:button>
                @endif
            </div>

            @if($status !== '' || $priority !== '')
                <div class="text-xs text-zinc-400">
                    Filter aktif:
                    @if($status !== '')
                        <span class="text-zinc-200 capitalize font-medium">{{ str_replace('_', ' ', $status) }}</span>
                    @endif
                    @if($status !== '' && $priority !== '') &bull; @endif
                    @if($priority !== '')
                        <span class="text-zinc-200 capitalize font-medium">{{ $priority }}</span>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- Task List -->
    <div class="rounded-lg bg-zinc-900 border border-zinc-800 overflow-hidden">
        @if($tasks->isEmpty())
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-12 h-12 rounded-lg bg-zinc-950 border border-zinc-800 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($view === 'trash')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        @endif
                    </svg>
                </div>
                <p class="text-sm font-medium text-zinc-300">
                    @if($view === 'trash')
                        Sampah kosong
                    @elseif($status !== '' || $priority !== '')
                        Tidak ada task yang cocok dengan filter
                    @else
                        Belum ada task
                    @endif
                </p>
                <p class="text-xs text-zinc-500 mt-1 max-w-xs">
                    @if($view === 'trash')
                        Task yang dihapus akan muncul di sini sebelum dihapus permanen.
                    @elseif($status !== '' || $priority !== '')
                        Coba ubah atau reset filter yang dipilih.
                    @else
                        Mulai dengan membuat task pertama Anda.
                    @endif
                </p>
                @if($view === 'active' && $status === '' && $priority === '')
                    <div class="mt-5">
                        <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
                            Task Baru
                        </flux:button>
                    </div>
                @elseif($status !== '' || $priority !== '')
                    <div class="mt-5">
                        <flux:button wire:click="resetFilters" variant="subtle">
                            Reset Filter
                        </flux:button>
                    </div>
                @endif
            </div>
        @else
            <!-- Desktop List -->
            <div class="hidden sm:block divide-y divide-zinc-800">
                @foreach($tasks as $task)
                    <div class="flex items-start gap-4 p-4 hover:bg-zinc-800/30 transition">
                        <!-- Status Checkbox -->
                        @if(!$task->deleted_at)
                            <button wire:click="toggleTaskStatus({{ $task->id }})"
                                    wire:loading.attr="disabled"
                                    type="button"
                                    class="mt-0.5 flex-shrink-0 w-5 h-5 rounded border flex items-center justify-center transition cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/50 {{ $task->status === 'completed' ? 'bg-emerald-500 border-emerald-500' : 'border-zinc-600 hover:border-zinc-400' }}"
                                    aria-label="{{ $task->status === 'completed' ? 'Tandai task pending' : 'Tandai task selesai' }}"
                                    title="{{ $task->status === 'completed' ? 'Tandai pending' : 'Tandai selesai' }}">
                                @if($task->status === 'completed')
                                    <svg class="w-3 h-3 text-zinc-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @endif
                            </button>
                        @endif

                        <!-- Task Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-medium {{ $task->status === 'completed' ? 'text-zinc-500 line-through' : 'text-zinc-100' }} break-words">
                                    {{ $task->title }}
                                </h3>

                                @if($task->priority === 'high')
                                    <flux:badge color="red" size="sm">High</flux:badge>
                                @elseif($task->priority === 'medium')
                                    <flux:badge color="amber" size="sm">Medium</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">Low</flux:badge>
                                @endif

                                @if($task->deleted_at)
                                    <flux:badge color="rose" size="sm">Di Sampah</flux:badge>
                                @endif
                            </div>

                            @if($task->description)
                                <p class="text-xs text-zinc-400 mt-1 line-clamp-2 break-words">{{ $task->description }}</p>
                            @endif

                            <div class="flex items-center gap-3 mt-2 text-[11px] text-zinc-500">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $task->due_date ? $task->due_date->format('d M Y') : 'Tanpa due date' }}
                                </span>
                                @if($task->deleted_at)
                                    <span class="text-rose-400/80">Dihapus {{ $task->deleted_at->format('d M Y, H:i') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            @if($task->deleted_at)
                                <flux:button wire:click="restore({{ $task->id }})" wire:loading.attr="disabled" size="xs" variant="subtle" color="emerald" aria-label="Pulihkan task {{ $task->title }}">
                                    Pulihkan
                                </flux:button>
                                <flux:button x-data x-on:click="if (confirm('Hapus permanen task ini? Tindakan ini tidak dapat dibatalkan.')) $wire.call('forceDelete', {{ $task->id }})" wire:loading.attr="disabled" size="xs" variant="danger" aria-label="Hapus permanen task {{ $task->title }}">
                                    Hapus Permanen
                                </flux:button>
                            @else
                                <flux:button wire:click="openEditModal({{ $task->id }})" size="xs" variant="ghost" icon="pencil-square" title="Edit task" aria-label="Edit task {{ $task->title }}" />
                                <flux:button x-data x-on:click="if (confirm('Pindahkan task ini ke sampah?')) $wire.call('softDelete', {{ $task->id }})" size="xs" variant="ghost" icon="trash" title="Pindahkan ke sampah" aria-label="Pindahkan task {{ $task->title }} ke sampah" />
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Mobile List -->
            <div class="sm:hidden divide-y divide-zinc-800">
                @foreach($tasks as $task)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-medium {{ $task->status === 'completed' ? 'text-zinc-500 line-through' : 'text-zinc-100' }} break-words">
                                        {{ $task->title }}
                                    </h3>

                                    @if($task->priority === 'high')
                                        <flux:badge color="red" size="sm">High</flux:badge>
                                    @elseif($task->priority === 'medium')
                                        <flux:badge color="amber" size="sm">Medium</flux:badge>
                                    @else
                                        <flux:badge color="zinc" size="sm">Low</flux:badge>
                                    @endif
                                </div>

                                @if($task->description)
                                    <p class="text-xs text-zinc-400 mt-1 line-clamp-2 break-words">{{ $task->description }}</p>
                                @endif

                                <div class="flex items-center gap-3 mt-2 text-[11px] text-zinc-500">
                                    <span>{{ $task->due_date ? $task->due_date->format('d M Y') : 'Tanpa due date' }}</span>
                                    @if($task->deleted_at)
                                        <span class="text-rose-400">Dihapus {{ $task->deleted_at->format('d M Y, H:i') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5 flex-shrink-0">
                                @if($task->deleted_at)
                                    <flux:button wire:click="restore({{ $task->id }})" wire:loading.attr="disabled" size="xs" variant="subtle" color="emerald" aria-label="Pulihkan task {{ $task->title }}">
                                        Pulihkan
                                    </flux:button>
                                    <flux:button x-data x-on:click="if (confirm('Hapus permanen task ini? Tindakan ini tidak dapat dibatalkan.')) $wire.call('forceDelete', {{ $task->id }})" wire:loading.attr="disabled" size="xs" variant="danger" aria-label="Hapus permanen task {{ $task->title }}">
                                        Hapus Permanen
                                    </flux:button>
                                @else
                                    <flux:button wire:click="openEditModal({{ $task->id }})" size="xs" variant="ghost" icon="pencil-square" aria-label="Edit task {{ $task->title }}">
                                        Edit
                                    </flux:button>
                                    <flux:button x-data x-on:click="if (confirm('Pindahkan task ini ke sampah?')) $wire.call('softDelete', {{ $task->id }})" size="xs" variant="ghost" icon="trash" aria-label="Hapus task {{ $task->title }}">
                                        Hapus
                                    </flux:button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($tasks->hasPages())
        <div class="flex items-center justify-between">
            <p class="text-xs text-zinc-500">
                Menampilkan {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} dari {{ $tasks->total() }} task
            </p>
            {{ $tasks->links() }}
        </div>
    @endif

    <!-- Create/Edit Task Modal -->
    <div x-data="{ show: $wire.entangle('showModal') }"
         x-show="show"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.window.escape="show = false">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" x-on:click="show = false"></div>

        <div x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             role="dialog"
             aria-modal="true"
             aria-labelledby="modal-title"
             class="relative w-full max-w-lg bg-zinc-900 border border-zinc-800 rounded-lg shadow-xl p-6"
             x-on:click.stop>
            <!-- Modal Header -->
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 id="modal-title" class="text-base font-semibold text-zinc-100">
                        {{ $editingTaskId ? 'Edit Task' : 'Task Baru' }}
                    </h3>
                    <p class="text-xs text-zinc-500 mt-0.5">
                        {{ $editingTaskId ? 'Perbarui informasi task' : 'Tambahkan task baru ke daftar Anda' }}
                    </p>
                </div>
                <button type="button"
                        x-on:click="show = false"
                        aria-label="Tutup modal"
                        class="p-1.5 rounded-md text-zinc-500 hover:text-zinc-300 hover:bg-zinc-800 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit="saveTask"
                  @keydown.cmd.enter.prevent="$wire.saveTask()"
                  @keydown.ctrl.enter.prevent="$wire.saveTask()"
                  class="space-y-4">
                <flux:input
                    wire:model="formTitle"
                    label="Judul Task *"
                    placeholder="Masukkan judul task"
                    required
                />

                <flux:textarea
                    wire:model="formDescription"
                    label="Deskripsi"
                    placeholder="Tambahkan deskripsi (opsional)"
                    rows="3"
                />

                <div class="grid grid-cols-2 gap-3">
                    <flux:select wire:model="formStatus" label="Status">
                        <flux:select.option value="pending">Pending</flux:select.option>
                        <flux:select.option value="in_progress">In Progress</flux:select.option>
                        <flux:select.option value="completed">Completed</flux:select.option>
                    </flux:select>

                    <flux:select wire:model="formPriority" label="Prioritas">
                        <flux:select.option value="low">Low</flux:select.option>
                        <flux:select.option value="medium">Medium</flux:select.option>
                        <flux:select.option value="high">High</flux:select.option>
                    </flux:select>
                </div>

                <flux:input
                    type="date"
                    wire:model="formDueDate"
                    label="Due Date"
                />

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-zinc-800">
                    <flux:button type="button" variant="ghost" x-on:click="show = false">
                        Batal
                    </flux:button>

                    <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveTask">{{ $editingTaskId ? 'Simpan Perubahan' : 'Buat Task' }}</span>
                        <span wire:loading wire:target="saveTask" class="flex items-center gap-2">
                            Menyimpan...
                        </span>
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
