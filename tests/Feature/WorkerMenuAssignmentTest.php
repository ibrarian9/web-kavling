<?php

use App\Livewire\Workers\Index as WorkersIndex;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Worker;
use Livewire\Livewire;

test('user can quickly assign mandor to project and unit from workers menu', function () {
    $founder = User::factory()->create(['role' => 'founder']);
    $this->actingAs($founder);

    $project = Project::create([
        'name' => 'Proyek Workers Menu Test',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'WRK-01',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $mandor = Worker::create([
        'name' => 'Mandor Joko Workers Menu',
        'phone' => '081122334455',
        'type' => 'mandor',
        'specialty' => 'Struktur Bangunan',
        'status' => 'active',
    ]);

    Livewire::test(WorkersIndex::class)
        ->call('openAssignModal', $mandor->id)
        ->set('assignProjectId', $project->id)
        ->set('assignUnitId', $unit->id)
        ->set('assignedRole', 'Mandor Utama Kawasan')
        ->call('saveAssignment')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('worker_assignments', [
        'worker_id' => $mandor->id,
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'assigned_role' => 'Mandor Utama Kawasan',
        'status' => 'active',
    ]);

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $founder->id,
        'action' => 'WORKER_ASSIGNED',
    ]);
});
