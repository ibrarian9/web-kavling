<?php

use App\Models\InstallmentPayment;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use Spatie\Permission\Models\Role;

test('founder and finance can export buyer installment invoice pdf, while guest can verify publicly via qr link', function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('finance', 'web');

    $founder = User::create([
        'name' => 'Founder User',
        'email' => 'founder_invoice@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Kavling Hijau',
        'location' => 'Surabaya',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'C1',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'terjual',
        'created_by' => $founder->id,
    ]);

    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'total_price' => 150000000,
        'down_payment' => 30000000,
        'installment_count' => 12,
        'installment_amount' => 10000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    $payment = InstallmentPayment::create([
        'unit_installment_id' => $installment->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 10000000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Setoran Cicilan 1',
        'created_by' => $founder->id,
    ]);

    // 1. Founder can stream PDF
    $response = $this->actingAs($founder)->get(route('installment.invoice', $payment->uuid));
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');

    // 2. Guest can verify publicly without login
    $guestResponse = $this->get(route('verify.installment', $payment->uuid));
    $guestResponse->assertStatus(200);
    $guestResponse->assertSee('Invoice Setoran Terverifikasi Resmi');
    $guestResponse->assertSee('Unit C1');
});
