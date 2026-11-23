<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('login page can be rendered', function () {
    $this->get(route('login'))->assertSuccessful();
});

test('register page can be rendered', function () {
    $this->get(route('register'))->assertSuccessful();
});

test('users can register with hashed password', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test Student')
        ->set('email', 'student@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertRedirect(route('dashboard'));

    $user = User::where('email', 'student@example.com')->first();
    expect($user)->not->toBeNull();
    expect(Hash::check('password123', $user->password))->toBeTrue();
    expect(Auth::check())->toBeTrue();
});

test('users can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make('secret123'),
    ]);

    Livewire::test(Login::class)
        ->set('email', 'user@example.com')
        ->set('password', 'secret123')
        ->call('login')
        ->assertRedirect(route('dashboard'));

    expect(Auth::id())->toBe($user->id);
});

test('users cannot login with invalid password', function () {
    User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make('secret123'),
    ]);

    Livewire::test(Login::class)
        ->set('email', 'user@example.com')
        ->set('password', 'wrongpassword')
        ->call('login')
        ->assertHasErrors(['email']);

    expect(Auth::check())->toBeFalse();
});
