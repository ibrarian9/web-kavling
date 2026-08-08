<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\CashflowTransaction;
use App\Models\WorkerAssignment;
use App\Models\ProjectPayment;

test('project model can be created and has correct attributes', function () {
    $user = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Grand Emerald Residen',
        'location' => 'Jl. Boulevard No. 88',
        'standard_land_area' => 120.00,
        'excess_price_per_sqm' => 2000000.00,
        'base_price' => 250000000.00,
        'total_project_price' => 750000000.00,
        'status' => 'aktif',
        'created_by' => $user->id,
    ]);

    expect($project)->not->toBeNull();
    expect($project->name)->toBe('Grand Emerald Residen');
    expect((float)$project->base_price)->toBe(250000000.00);
    expect((float)$project->standard_land_area)->toBe(120.00);
    expect((float)$project->excess_price_per_sqm)->toBe(2000000.00);
    expect($project->status)->toBe('aktif');
});

test('project has correct relationships with units, creator, cashflows, assignments, and payments', function () {
    $user = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Harmoni',
        'location' => 'Jl. Harmoni',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $user->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'H1',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $user->id,
    ]);

    $cashflow = CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 5000000,
        'transaction_date' => now()->toDateString(),
        'description' => 'Tanda Jadi H1',
        'created_by' => $user->id,
    ]);

    $assignment = WorkerAssignment::create([
        'project_id' => $project->id,
        'user_id' => $user->id,
        'assigned_role' => 'Pengawas Lapangan',
        'status' => 'active',
    ]);

    expect($project->creator->id)->toBe($user->id);
    expect($project->units)->toHaveCount(1);
    expect($project->units->first()->id)->toBe($unit->id);
    expect($project->cashflows)->toHaveCount(1);
    expect($project->cashflows->first()->id)->toBe($cashflow->id);
    expect($project->assignments)->toHaveCount(1);
    expect($project->assignments->first()->id)->toBe($assignment->id);
});
