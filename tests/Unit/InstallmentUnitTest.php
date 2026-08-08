<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\UnitInstallment;
use App\Models\InstallmentPayment;

test('unit installment scheme calculates remaining balance and tracks payments accurately', function () {
    $user = User::factory()->create(['role' => 'finance']);

    $project = Project::create([
        'name' => 'Kavling Mulia',
        'location' => 'Jl. Mulia No 5',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 120000000,
        'created_by' => $user->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'M-02',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 80000000,
        'final_selling_price' => 120000000,
        'status' => 'booked',
        'created_by' => $user->id,
    ]);

    // Installment setup: Total price 120M, DP 20M, Installment amount 10M x 10 months
    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'total_price' => 120000000.00,
        'down_payment' => 20000000.00,
        'installment_count' => 10,
        'installment_amount' => 10000000.00,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    expect((float)$installment->total_price)->toBe(120000000.00);
    expect((float)$installment->down_payment)->toBe(20000000.00);
    expect((float)$installment->total_paid)->toBe(20000000.00);
    expect((float)$installment->remaining_balance)->toBe(100000000.00);

    // Record 1st payment: 10,000,000
    $p1 = InstallmentPayment::create([
        'unit_installment_id' => $installment->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 10000000.00,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Angsuran ke-1',
        'created_by' => $user->id,
    ]);

    $installment->refresh();
    expect($installment->payments)->toHaveCount(1);
    expect((float)$installment->total_paid)->toBe(30000000.00);
    expect((float)$installment->remaining_balance)->toBe(90000000.00);

    // Record 2nd payment: 90,000,000 (Full Settlement)
    $p2 = InstallmentPayment::create([
        'unit_installment_id' => $installment->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 90000000.00,
        'payment_method' => 'Cash / Tunai',
        'notes' => 'Pelunasan Angsuran 2-10',
        'created_by' => $user->id,
    ]);

    $installment->refresh();
    expect($installment->payments)->toHaveCount(2);
    expect((float)$installment->total_paid)->toBe(120000000.00);
    expect((float)$installment->remaining_balance)->toBe(0.00);

    // Update status to lunas
    $installment->update(['status' => 'lunas']);
    expect($installment->fresh()->status)->toBe('lunas');
});
