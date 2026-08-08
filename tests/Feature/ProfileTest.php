<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('founder can access profile page and update NIK, position, and address', function () {
    $founder = User::create([
        'name' => 'Founder Utama',
        'email' => 'founder_profile@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    Livewire::actingAs($founder)
        ->test(\App\Livewire\Profile\Index::class)
        ->assertSee('Profil Founder')
        ->set('nik', '3271999900001111')
        ->set('position', 'Direktur Utama PT. Atlantik')
        ->set('address', 'Jl. Merdeka No. 99 Bandung')
        ->call('updateProfile')
        ->assertHasNoErrors();

    $founder->refresh();
    expect($founder->nik)->toBe('3271999900001111')
        ->and($founder->position)->toBe('Direktur Utama PT. Atlantik')
        ->and($founder->address)->toBe('Jl. Merdeka No. 99 Bandung');
});

test('non-founder user cannot access profile page', function () {
    Role::findOrCreate('marketing', 'web');

    $marketing = User::create([
        'name' => 'Staf Marketing',
        'email' => 'marketing@example.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $marketing->assignRole('marketing');

    Livewire::actingAs($marketing)
        ->test(\App\Livewire\Profile\Index::class)
        ->assertForbidden();
});
