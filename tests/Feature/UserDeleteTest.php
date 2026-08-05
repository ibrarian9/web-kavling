<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('only founder user can delete another user', function () {
    $founder = User::create([
        'name' => 'Founder User',
        'email' => 'founder_user_delete@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    $finance = User::create([
        'name' => 'Finance User',
        'email' => 'finance_user_delete@example.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);

    $targetUser = User::create([
        'name' => 'User To Delete',
        'email' => 'delete_me@example.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);

    // 1. Non-Founder attempts to access Users index -> 403 Forbidden
    Livewire::actingAs($finance)
        ->test(\App\Livewire\Users\Index::class)
        ->assertStatus(403);

    expect(User::find($targetUser->id))->not->toBeNull();

    // 2. Founder attempts to delete themselves -> Fails with session error
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Users\Index::class)
        ->call('deleteUser', $founder->id);

    expect(User::find($founder->id))->not->toBeNull();

    // 3. Founder deletes target user -> Succeeds
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Users\Index::class)
        ->call('deleteUser', $targetUser->id)
        ->assertHasNoErrors();

    expect(User::find($targetUser->id))->toBeNull();
});
