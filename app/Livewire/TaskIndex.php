<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Dashboard & Tasks')]
#[Layout('layouts.app')]
class TaskIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $status = '';

    #[Url(history: true)]
    public string $priority = '';

    #[Url(history: true)]
    public string $view = 'active'; // 'active' or 'trash'

    #[Url(history: true)]
    public string $sortBy = 'created_at';

    #[Url(history: true)]
    public string $sortOrder = 'desc';

    public int $perPage = 10;

    // Modal state
    public bool $showModal = false;

    public ?int $editingTaskId = null;

    public string $formTitle = '';

    public string $formDescription = '';

    public string $formStatus = 'pending';

    public string $formPriority = 'medium';

    public ?string $formDueDate = null;

    protected function rules(): array
    {
        return [
            'formTitle' => 'required|string|max:255',
            'formDescription' => 'nullable|string',
            'formStatus' => 'required|in:pending,in_progress,completed',
            'formPriority' => 'required|in:low,medium,high',
            'formDueDate' => 'nullable|date',
        ];
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingPriority(): void
    {
        $this->resetPage();
    }

    public function updatingView(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['status', 'priority', 'view', 'sortBy', 'sortOrder']);
        $this->resetPage();
    }

    public function setView(string $view): void
    {
        $this->view = in_array($view, ['active', 'trash']) ? $view : 'active';
        $this->status = '';
        $this->resetPage();
    }

    public function setStatusFilter(string $status): void
    {
        $this->view = 'active';
        $this->status = $this->status === $status ? '' : $status;
        $this->resetPage();
    }

    public function toggleTaskStatus(int $taskId): void
    {
        $userId = Auth::id();
        $task = Task::where('user_id', $userId)->findOrFail($taskId);

        $newStatus = $task->status === 'completed' ? 'pending' : 'completed';
        $task->update(['status' => $newStatus]);

        Cache::forget("dashboard.stats.{$userId}");
        $msg = $newStatus === 'completed' ? 'Task ditandai selesai.' : 'Task dikembalikan ke pending.';
        $this->dispatch('toast', message: $msg, type: 'success');
        session()->flash('toast', $msg);
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->editingTaskId = null;
        $this->formTitle = '';
        $this->formDescription = '';
        $this->formStatus = 'pending';
        $this->formPriority = 'medium';
        $this->formDueDate = null;
        $this->showModal = true;
    }

    public function openEditModal(int $taskId): void
    {
        $this->resetValidation();
        $task = Task::where('user_id', Auth::id())->findOrFail($taskId);

        $this->editingTaskId = $task->id;
        $this->formTitle = $task->title;
        $this->formDescription = $task->description ?? '';
        $this->formStatus = $task->status;
        $this->formPriority = $task->priority;
        $this->formDueDate = $task->due_date ? $task->due_date->format('Y-m-d') : null;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function saveTask(): void
    {
        $this->validate();

        $userId = Auth::id();

        if ($this->editingTaskId) {
            $task = Task::where('user_id', $userId)->findOrFail($this->editingTaskId);
            $task->update([
                'title' => $this->formTitle,
                'description' => $this->formDescription ?: null,
                'status' => $this->formStatus,
                'priority' => $this->formPriority,
                'due_date' => $this->formDueDate ?: null,
            ]);
            $msg = 'Task berhasil diperbarui!';
        } else {
            Task::create([
                'user_id' => $userId,
                'title' => $this->formTitle,
                'description' => $this->formDescription ?: null,
                'status' => $this->formStatus,
                'priority' => $this->formPriority,
                'due_date' => $this->formDueDate ?: null,
            ]);
            $msg = 'Task baru berhasil dibuat!';
        }

        // Invalidate cached stats
        Cache::forget("dashboard.stats.{$userId}");

        $this->dispatch('toast', message: $msg, type: 'success');
        session()->flash('toast', $msg);

        $this->showModal = false;
    }

    public function softDelete(int $taskId): void
    {
        $userId = Auth::id();
        $task = Task::where('user_id', $userId)->findOrFail($taskId);
        $task->delete();

        Cache::forget("dashboard.stats.{$userId}");
        $this->dispatch('toast', message: 'Task dipindahkan ke sampah.', type: 'success');
        session()->flash('toast', 'Task dipindahkan ke sampah.');
    }

    public function restore(int $taskId): void
    {
        $userId = Auth::id();
        $task = Task::withTrashed()->where('user_id', $userId)->findOrFail($taskId);
        $task->restore();

        Cache::forget("dashboard.stats.{$userId}");
        $this->dispatch('toast', message: 'Task berhasil dipulihkan!', type: 'success');
        session()->flash('toast', 'Task berhasil dipulihkan!');
    }

    public function forceDelete(int $taskId): void
    {
        $userId = Auth::id();
        $task = Task::withTrashed()->where('user_id', $userId)->findOrFail($taskId);
        $task->forceDelete();

        Cache::forget("dashboard.stats.{$userId}");
        $this->dispatch('toast', message: 'Task berhasil dihapus permanen.', type: 'success');
        session()->flash('toast', 'Task berhasil dihapus permanen.');
    }

    public function refreshStatsCache(): void
    {
        $userId = Auth::id();
        Cache::forget("dashboard.stats.{$userId}");
        $this->dispatch('toast', message: 'Statistik cache berhasil diperbarui!', type: 'success');
        session()->flash('toast', 'Statistik cache berhasil diperbarui!');
    }

    public function mount(): void
    {
        $this->status = request()->query('status', '');
        $this->priority = request()->query('priority', '');
        $this->view = request()->query('view', 'active');
        $this->sortBy = request()->query('sortBy', 'created_at');
        $this->sortOrder = request()->query('sortOrder', 'desc');
        $this->perPage = request()->query('perPage', 10);
    }

    public function render()
    {
        $userId = Auth::id();

        // 1 Halaman Ber-Cache (A.7): Cached statistics with 60s TTL
        $stats = Cache::remember("dashboard.stats.{$userId}", 60, function () use ($userId) {
            return [
                'total' => Task::where('user_id', $userId)->count(),
                'pending' => Task::where('user_id', $userId)->where('status', 'pending')->count(),
                'in_progress' => Task::where('user_id', $userId)->where('status', 'in_progress')->count(),
                'completed' => Task::where('user_id', $userId)->where('status', 'completed')->count(),
                'trash' => Task::onlyTrashed()->where('user_id', $userId)->count(),
                'cached_at' => now()->format('H:i:s d M Y'),
            ];
        });

        // Query tasks
        $query = Task::where('user_id', $userId);

        if ($this->view === 'trash') {
            $query->onlyTrashed();
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->priority !== '') {
            $query->where('priority', $this->priority);
        }

        $tasks = $query->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->perPage);

        return view('livewire.tasks.index', [
            'tasks' => $tasks,
            'stats' => $stats,
        ]);
    }
}
