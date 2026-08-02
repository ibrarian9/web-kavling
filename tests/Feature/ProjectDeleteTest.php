<?php

use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

test('founder can delete project', function () {
    $founder = User::factory()->create(['role' => 'founder', 'is_active' => true]);
    $project = Project::create([
        'name' => 'Proyek Tes Hapus',
        'location' => 'Lokasi Tes',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1500000,
        'base_price' => 150000000,
        'total_project_price' => 1000000000,
        'created_by' => $founder->id,
        'status' => 'aktif',
    ]);

    Livewire::actingAs($founder)
        ->test(\App\Livewire\Projects\Index::class)
        ->call('deleteProject', $project->id)
        ->assertStatus(200);

    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});

test('non founder cannot delete project', function () {
    $supervisor = User::factory()->create(['role' => 'supervisor', 'is_active' => true]);
    $project = Project::create([
        'name' => 'Proyek Tidak Boleh Hapus',
        'location' => 'Lokasi Tes',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1500000,
        'base_price' => 150000000,
        'total_project_price' => 1000000000,
        'created_by' => $supervisor->id,
        'status' => 'aktif',
    ]);

    Livewire::actingAs($supervisor)
        ->test(\App\Livewire\Projects\Index::class)
        ->call('deleteProject', $project->id)
        ->assertStatus(200);

    $this->assertDatabaseHas('projects', ['id' => $project->id]);
});
