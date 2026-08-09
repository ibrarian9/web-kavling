<?php

use App\Enums\UnitStatus;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;

test('unit status enum provides standardized helpers for sold, booked and available statuses', function () {
    expect(UnitStatus::isSold('terjual'))->toBeTrue();
    expect(UnitStatus::isSold('disetujui'))->toBeTrue();
    expect(UnitStatus::isSold('booked'))->toBeTrue();
    expect(UnitStatus::isSold('converted'))->toBeTrue();
    expect(UnitStatus::isSold('tersedia'))->toBeFalse();

    expect(UnitStatus::isAvailable('tersedia'))->toBeTrue();
    expect(UnitStatus::isAvailable('terjual'))->toBeFalse();

    expect(UnitStatus::isBooked('booked'))->toBeTrue();
    expect(UnitStatus::isBooked('dibooking'))->toBeTrue();
    expect(UnitStatus::isBooked('menunggu_persetujuan'))->toBeTrue();
    expect(UnitStatus::isBooked('tersedia'))->toBeFalse();

    expect(UnitStatus::label('tersedia'))->toBe('Tersedia');
    expect(UnitStatus::label('booked'))->toBe('Booked');
    expect(UnitStatus::label('converted'))->toBe('Konversi Cash');
});

test('unit model attributes interact cleanly with unit status enum', function () {
    $founder = User::factory()->create();
    $project = Project::create([
        'name' => 'Proyek Enum Test',
        'location' => 'Jl. Enum',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'ENUM-01',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => UnitStatus::TERSEDIA->value,
        'created_by' => $founder->id,
    ]);

    expect($unit->is_available)->toBeTrue();
    expect($unit->is_sold)->toBeFalse();
    expect($unit->status_label)->toBe('Tersedia');

    $unit->update(['status' => UnitStatus::BOOKED->value]);
    expect($unit->is_booked)->toBeTrue();
    expect($unit->is_sold)->toBeTrue();

    $unit->update(['status' => UnitStatus::TERJUAL->value]);
    expect($unit->is_sold)->toBeTrue();
    expect($unit->status_label)->toBe('Terjual');
});
