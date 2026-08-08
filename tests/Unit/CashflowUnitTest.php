<?php

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\User;
use App\Models\Booking;

test('cashflow transaction model handles incoming and outgoing categories and polymorphic references', function () {
    $user = User::factory()->create(['role' => 'finance']);

    $project = Project::create([
        'name' => 'Project Cashflow Test',
        'location' => 'Jl. Keuangan',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 100000000,
        'created_by' => $user->id,
    ]);

    $booking = Booking::create([
        'project_id' => $project->id,
        'buyer_name' => 'Budi Santoso',
        'buyer_phone' => '081122334455',
        'booking_type' => 'project',
        'booking_amount' => 10000000.00,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $user->id,
    ]);

    // Incoming cashflow
    $inflow = CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 10000000.00,
        'transaction_date' => now()->toDateString(),
        'description' => 'Tanda jadi booking Budi Santoso',
        'reference_type' => Booking::class,
        'reference_id' => $booking->id,
        'created_by' => $user->id,
    ]);

    // Outgoing cashflow
    $outflow = CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'keluar',
        'category' => 'pembelian_material',
        'amount' => 3500000.00,
        'transaction_date' => now()->toDateString(),
        'description' => 'Pembelian Semen & Pasir Proyek',
        'created_by' => $user->id,
    ]);

    expect($inflow->type)->toBe('masuk');
    expect((float)$inflow->amount)->toBe(10000000.00);
    expect($inflow->reference_type)->toBe(Booking::class);
    expect($inflow->reference_id)->toBe($booking->id);

    expect($outflow->type)->toBe('keluar');
    expect((float)$outflow->amount)->toBe(3500000.00);

    expect($project->cashflows)->toHaveCount(2);
});
