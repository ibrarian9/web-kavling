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

test('units index summary KPI cards dynamically adjust when filtered by project and category', function () {
    $founder = User::create([
        'name' => 'Founder Utama 2',
        'email' => 'founder_units2@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $projectA = Project::create([
        'name' => 'Proyek Alpha',
        'location' => 'Lokasi A',
        'standard_land_area' => 100,
        'base_price' => 100000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $founder->id,
    ]);

    $projectB = Project::create([
        'name' => 'Proyek Beta',
        'location' => 'Lokasi B',
        'standard_land_area' => 100,
        'base_price' => 100000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $founder->id,
    ]);

    // Project A: 3 tersedia kavling, 2 booked kavling, 1 terjual rumah
    Unit::create(['project_id' => $projectA->id, 'code' => 'PA-01', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'tersedia', 'created_by' => $founder->id]);
    Unit::create(['project_id' => $projectA->id, 'code' => 'PA-02', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'tersedia', 'created_by' => $founder->id]);
    Unit::create(['project_id' => $projectA->id, 'code' => 'PA-03', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'tersedia', 'created_by' => $founder->id]);
    Unit::create(['project_id' => $projectA->id, 'code' => 'PA-04', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'booked', 'created_by' => $founder->id]);
    Unit::create(['project_id' => $projectA->id, 'code' => 'PA-05', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'booked', 'created_by' => $founder->id]);
    Unit::create(['project_id' => $projectA->id, 'code' => 'PA-06', 'category' => 'rumah', 'type' => 'rumah', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'terjual', 'created_by' => $founder->id]);

    // Project B: 2 tersedia kavling, 3 terjual kavling
    Unit::create(['project_id' => $projectB->id, 'code' => 'PB-01', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'tersedia', 'created_by' => $founder->id]);
    Unit::create(['project_id' => $projectB->id, 'code' => 'PB-02', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'tersedia', 'created_by' => $founder->id]);
    Unit::create(['project_id' => $projectB->id, 'code' => 'PB-03', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'terjual', 'created_by' => $founder->id]);
    Unit::create(['project_id' => $projectB->id, 'code' => 'PB-04', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'terjual', 'created_by' => $founder->id]);
    Unit::create(['project_id' => $projectB->id, 'code' => 'PB-05', 'category' => 'kavling', 'type' => 'kavling', 'land_area' => 100, 'hpp' => 100000000, 'status' => 'terjual', 'created_by' => $founder->id]);

    // 1. Overall: Total = 11, Tersedia = 5, Booked = 2, Terjual = 4
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Index::class)
        ->assertViewHas('totalUnitsCount', 11)
        ->assertViewHas('availableUnitsCount', 5)
        ->assertViewHas('bookedUnitsCount', 2)
        ->assertViewHas('soldUnitsCount', 4);

    // 2. Filter Project A: Total = 6, Tersedia = 3, Booked = 2, Terjual = 1
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Index::class)
        ->set('project_id', $projectA->id)
        ->assertViewHas('totalUnitsCount', 6)
        ->assertViewHas('availableUnitsCount', 3)
        ->assertViewHas('bookedUnitsCount', 2)
        ->assertViewHas('soldUnitsCount', 1);

    // 3. Filter Project A + Category Rumah: Total = 1, Tersedia = 0, Booked = 0, Terjual = 1
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Index::class)
        ->set('project_id', $projectA->id)
        ->set('category_filter', 'rumah')
        ->assertViewHas('totalUnitsCount', 1)
        ->assertViewHas('availableUnitsCount', 0)
        ->assertViewHas('bookedUnitsCount', 0)
        ->assertViewHas('soldUnitsCount', 1);

    // 4. Filter Project B: Total = 5, Tersedia = 2, Booked = 0, Terjual = 3
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Index::class)
        ->set('project_id', $projectB->id)
        ->assertViewHas('totalUnitsCount', 5)
        ->assertViewHas('availableUnitsCount', 2)
        ->assertViewHas('bookedUnitsCount', 0)
        ->assertViewHas('soldUnitsCount', 3);
});
