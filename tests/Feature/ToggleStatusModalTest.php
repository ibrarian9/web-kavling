<?php

use App\Models\User;
use App\Models\Worker;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('founder can toggle user active status', function () {
    $founder = User::create([
        'name' => 'Founder User',
        'email' => 'founder_toggle@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    $targetUser = User::create([
        'name' => 'Target User',
        'email' => 'target_toggle@example.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);

    // Deactivate user
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Users\Index::class)
        ->call('toggleStatus', $targetUser->id)
        ->assertStatus(200);

    expect($targetUser->fresh()->is_active)->toBeFalse();

    // Re-activate user
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Users\Index::class)
        ->call('toggleStatus', $targetUser->id)
        ->assertStatus(200);

    expect($targetUser->fresh()->is_active)->toBeTrue();
});

test('user can toggle worker active status', function () {
    $founder = User::create([
        'name' => 'Founder Worker',
        'email' => 'founder_worker_toggle@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    $worker = Worker::create([
        'name' => 'Tukang Tes Toggle',
        'type' => 'tukang',
        'status' => 'active',
        'phone' => '08123456789',
    ]);

    // Deactivate worker
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Workers\Index::class)
        ->call('toggleStatus', $worker->id)
        ->assertStatus(200);

    expect($worker->fresh()->status)->toBe('inactive');

    // Re-activate worker
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Workers\Index::class)
        ->call('toggleStatus', $worker->id)
        ->assertStatus(200);

    expect($worker->fresh()->status)->toBe('active');
});
