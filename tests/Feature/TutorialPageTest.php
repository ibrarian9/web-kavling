<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('authenticated user can access Tutorial System page and toggle between Founder and Umum mode', function () {
    $user = User::create([
        'name' => 'Founder Tutorial',
        'email' => 'founder_tutorial@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $user->assignRole('founder');

    $response = $this->actingAs($user)->get(route('tutorial.index'));

    $response->assertStatus(200);
    $response->assertSee('Pusat Panduan');
    $response->assertSee('Mode Founder');
    $response->assertSee('Mode Umum');
    $response->assertSee('Booking Fee / NUP');
    $response->assertSee('Pembelian Cash');
    $response->assertSee('Skema Cicilan');

    Livewire::actingAs($user)
        ->test(\App\Livewire\Tutorial\Index::class)
        ->assertSet('viewMode', 'founder')
        ->call('setViewMode', 'umum')
        ->assertSet('viewMode', 'umum')
        ->assertSee('Mode Panduan Umum');
});
