<?php

use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\Unit;
use App\Models\Project;
use App\Models\User;

test('weekly material purchase can be created with correct attributes and casts', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Material Test',
        'location' => 'Jl. Material',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'MAT-A1',
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

    $worker = Worker::create([
        'name' => 'Pak Mandor Mat',
        'type' => 'mandor',
        'phone' => '081',
        'status' => 'active',
    ]);

    $purchase = WeeklyMaterialPurchase::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'pengawas_id' => $founder->id,
        'purchase_date' => '2026-08-01',
        'item_name' => 'Semen Tiga Roda (50 kg)',
        'quantity' => 20,
        'unit_measure' => 'sak',
        'unit_price' => 55000,
        'total_price' => 1100000,
        'notes' => 'Kebutuhan pondasi',
    ]);

    expect($purchase)->not->toBeNull();
    expect($purchase->item_name)->toBe('Semen Tiga Roda (50 kg)');
    expect((float)$purchase->quantity)->toBe(20.00);
    expect((float)$purchase->unit_price)->toBe(55000.00);
    expect((float)$purchase->total_price)->toBe(1100000.00);
    expect($purchase->purchase_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('weekly material purchase has correct relationships: project, unit, worker, pengawas', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Relasi Mat',
        'location' => 'Jl. Relasi Mat',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'RLM-B1',
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

    $worker = Worker::create([
        'name' => 'Pak Tukang Rel',
        'type' => 'tukang',
        'phone' => '082',
        'status' => 'active',
    ]);

    $purchase = WeeklyMaterialPurchase::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'pengawas_id' => $founder->id,
        'purchase_date' => now()->toDateString(),
        'item_name' => 'Bata Merah',
        'quantity' => 1000,
        'unit_measure' => 'pcs',
        'unit_price' => 800,
        'total_price' => 800000,
    ]);

    expect($purchase->project->id)->toBe($project->id);
    expect($purchase->unit->id)->toBe($unit->id);
    expect($purchase->worker->id)->toBe($worker->id);
    expect($purchase->pengawas->id)->toBe($founder->id);
});
