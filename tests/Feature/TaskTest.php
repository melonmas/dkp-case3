<?php

use App\Livewire\TaskIndex;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

test('unauthenticated users are redirected from dashboard to login', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('authenticated users can render dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSee('Tasks');
});

test('user can create a new task', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TaskIndex::class)
        ->set('formTitle', 'My New Assignment')
        ->set('formDescription', 'Finish coding case 3')
        ->set('formStatus', 'pending')
        ->set('formPriority', 'high')
        ->call('saveTask')
        ->assertHasNoErrors();

    expect(Task::where('user_id', $user->id)->where('title', 'My New Assignment')->exists())->toBeTrue();
});

test('user can update an existing task', function () {
    $user = User::factory()->create();
    $task = Task::create([
        'user_id' => $user->id,
        'title' => 'Initial Title',
        'status' => 'pending',
        'priority' => 'low',
    ]);

    Livewire::actingAs($user)
        ->test(TaskIndex::class)
        ->call('openEditModal', $task->id)
        ->set('formTitle', 'Updated Title')
        ->set('formStatus', 'in_progress')
        ->call('saveTask')
        ->assertHasNoErrors();

    expect($task->fresh()->title)->toBe('Updated Title');
    expect($task->fresh()->status)->toBe('in_progress');
});

test('user can soft delete and restore a task', function () {
    $user = User::factory()->create();
    $task = Task::create([
        'user_id' => $user->id,
        'title' => 'Task to delete',
        'status' => 'pending',
        'priority' => 'medium',
    ]);

    // Soft delete
    Livewire::actingAs($user)
        ->test(TaskIndex::class)
        ->call('softDelete', $task->id);

    expect($task->fresh()->trashed())->toBeTrue();

    // Restore
    Livewire::actingAs($user)
        ->test(TaskIndex::class)
        ->call('restore', $task->id);

    expect($task->fresh()->trashed())->toBeFalse();
});

test('user can force delete a task', function () {
    $user = User::factory()->create();
    $task = Task::create([
        'user_id' => $user->id,
        'title' => 'Task to purge',
        'status' => 'pending',
        'priority' => 'medium',
    ]);

    Livewire::actingAs($user)
        ->test(TaskIndex::class)
        ->call('forceDelete', $task->id);

    expect(Task::withTrashed()->find($task->id))->toBeNull();
});

test('dashboard stats are cached and can be refreshed', function () {
    $user = User::factory()->create();
    Task::create([
        'user_id' => $user->id,
        'title' => 'Cached Task',
        'status' => 'pending',
        'priority' => 'medium',
    ]);

    $component = Livewire::actingAs($user)->test(TaskIndex::class);
    $cacheKey = "dashboard.stats.{$user->id}";

    expect(Cache::has($cacheKey))->toBeTrue();

    $component->call('refreshStatsCache')
        ->assertDispatched('toast', message: 'Statistik cache berhasil diperbarui!', type: 'success');

    expect(Cache::has($cacheKey))->toBeTrue();
});
