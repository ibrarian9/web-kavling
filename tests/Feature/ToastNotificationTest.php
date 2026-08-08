<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('toast notification floating component renders session flash messages', function () {
    $founder = User::create([
        'name' => 'Founder Toast Test',
        'email' => 'founder_toast@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    session()->flash('success', 'Data proyek berhasil diperbarui!');

    $response = $this->actingAs($founder)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('addToast');
    $response->assertSee('Data proyek berhasil diperbarui!');
});

test('livewire profile component triggers profile updated toast notification', function () {
    $founder = User::create([
        'name' => 'Founder Livewire Toast',
        'email' => 'founder_livewire_toast@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    Livewire::actingAs($founder)
        ->test(\App\Livewire\Profile\Index::class)
        ->set('name', 'Founder Updated Name')
        ->call('updateProfile')
        ->assertHasNoErrors();

    $founder->refresh();
    expect($founder->name)->toBe('Founder Updated Name');
});
