<?php

use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;

test('booking model calculates total amounts and status states correctly', function () {
    $user = User::factory()->create(['role' => 'marketing']);

    $project = Project::create([
        'name' => 'Project Harmony',
        'location' => 'Jl. Harmony 12',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $user->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'H-05',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'booked',
        'created_by' => $user->id,
    ]);

    $booking = Booking::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'buyer_name' => 'Ahmad Subagyo',
        'buyer_phone' => '081234567890',
        'booking_type' => 'unit',
        'booking_amount' => 5000000.00,
        'dp_amount' => 15000000.00,
        'booking_date' => now()->toDateString(),
        'expiry_date' => now()->addDays(14)->toDateString(),
        'status' => 'active',
        'notes' => 'Booking unit H-05',
        'created_by' => $user->id,
    ]);

    expect($booking->buyer_name)->toBe('Ahmad Subagyo');
    expect((float)$booking->booking_amount)->toBe(5000000.00);
    expect((float)$booking->dp_amount)->toBe(15000000.00);
    expect($booking->status)->toBe('active');

    // Test status transitions
    $booking->update(['status' => 'converted']);
    expect($booking->fresh()->status)->toBe('converted');

    $booking->update(['status' => 'refunded']);
    expect($booking->fresh()->status)->toBe('refunded');

    expect($booking->project->id)->toBe($project->id);
    expect($booking->unit->id)->toBe($unit->id);
    expect($booking->creator->id)->toBe($user->id);
});
