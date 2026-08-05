<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('units index supports status filter and siteplan full collection view', function () {
    $founder = User::create([
        'name' => 'Founder Utama',
        'email' => 'founder_units@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Kavling Harmoni',
        'location' => 'Pekanbaru, Riau',
        'standard_land_area' => 100,
        'base_price' => 100000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $founder->id,
    ]);

    // Create 15 units (5 tersedia, 5 booked, 5 terjual)
    for ($i = 1; $i <= 5; $i++) {
        Unit::create(['project_id' => $project->id, 'code' => "A-0{$i}", 'type' => 'kavling', 'category' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'tersedia', 'created_by' => $founder->id]);
        Unit::create(['project_id' => $project->id, 'code' => "B-0{$i}", 'type' => 'kavling', 'category' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'booked', 'created_by' => $founder->id]);
        Unit::create(['project_id' => $project->id, 'code' => "C-0{$i}", 'type' => 'kavling', 'category' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'terjual', 'created_by' => $founder->id]);
    }

    // Test status filter = tersedia
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Index::class)
        ->set('status_filter', 'tersedia')
        ->assertSee('A-01')
        ->assertDontSee('C-01');

    // Test siteplan view mode displays all 15 units together without pagination cutoff
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Index::class)
        ->set('viewMode', 'siteplan')
        ->assertSee('A-01')
        ->assertSee('B-01')
        ->assertSee('C-01');
});
