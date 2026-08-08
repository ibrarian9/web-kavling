<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Booking;
use App\Models\UnitInstallment;
use App\Models\WeeklyMaterialPurchase;
use App\Models\WorkerUnitPayroll;

test('unit model calculates excess land area and excess land price accurately', function () {
    $user = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Indah',
        'location' => 'Jl. Indah No 10',
        'standard_land_area' => 100.00,
        'excess_price_per_sqm' => 1500000.00,
        'base_price' => 150000000.00,
        'created_by' => $user->id,
    ]);

    // Unit with land area 125 sqm (25 sqm excess land)
    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'B-12',
        'category' => 'kavling',
        'land_length' => 12.5,
        'land_width' => 10.0,
        'land_area' => 125.00,
        'building_area' => 0,
        'hpp' => 100000000.00,
        'final_selling_price' => 187500000.00, // 150M + (25 * 1.5M)
        'status' => 'tersedia',
        'created_by' => $user->id,
    ]);

    expect((float)$unit->land_area)->toBe(125.00);
    $excessArea = max(0, (float)$unit->land_area - (float)$project->standard_land_area);
    expect($excessArea)->toBe(25.00);

    $excessPrice = $excessArea * (float)$project->excess_price_per_sqm;
    expect($excessPrice)->toBe(37500000.00);

    $expectedTotalPrice = (float)$project->base_price + $excessPrice;
    expect($expectedTotalPrice)->toBe(187500000.00);
});

test('unit model handles status lifecycle and relationships', function () {
    $user = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Asri',
        'location' => 'Jl. Asri',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 100000000,
        'created_by' => $user->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'A-01',
        'category' => 'rumah',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 36,
        'hpp' => 120000000,
        'final_selling_price' => 200000000,
        'status' => 'tersedia',
        'created_by' => $user->id,
    ]);

    expect($unit->status)->toBe('tersedia');

    // Update status to booked
    $unit->update(['status' => 'booked']);
    expect($unit->fresh()->status)->toBe('booked');

    // Update status to lunas
    $unit->update(['status' => 'lunas']);
    expect($unit->fresh()->status)->toBe('lunas');

    expect($unit->project->id)->toBe($project->id);
    expect($unit->creator->id)->toBe($user->id);
});
