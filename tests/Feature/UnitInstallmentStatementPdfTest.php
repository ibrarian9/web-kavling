<?php

use App\Models\InstallmentPayment;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('finance', 'web');
    Role::findOrCreate('marketing', 'web');
});

test('founder and finance can export unit installment statement PDF', function () {
    $founder = User::create([
        'name' => 'Pak Direktur',
        'email' => 'founder_statement@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Kavling Harmoni Asri',
        'location' => 'Riau',
        'standard_land_area' => 100,
        'base_price' => 100000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'A-01',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_area' => 100,
        'hpp' => 80000000,
        'status' => 'terjual',
        'created_by' => $founder->id,
    ]);

    \App\Models\Booking::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'booking_date' => '2026-01-10',
        'buyer_name' => 'Budi Santoso',
        'buyer_phone' => '081234567890',
        'booking_amount' => 5000000,
        'dp_amount' => 20000000,
        'status' => 'converted',
        'created_by' => $founder->id,
    ]);

    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'total_price' => 120000000,
        'down_payment' => 20000000,
        'installment_count' => 10,
        'installment_amount' => 10000000,
        'start_date' => '2026-01-15',
        'status' => 'berjalan',
    ]);

    // Add 2 payment records
    InstallmentPayment::create([
        'unit_installment_id' => $installment->id,
        'payment_date' => '2026-02-15',
        'amount_paid' => 10000000,
        'payment_method' => 'Transfer Bank Mandiri',
        'notes' => 'Setoran cicilan bulan ke-1',
        'created_by' => $founder->id,
    ]);

    InstallmentPayment::create([
        'unit_installment_id' => $installment->id,
        'payment_date' => '2026-03-15',
        'amount_paid' => 10000000,
        'payment_method' => 'Transfer Bank Mandiri',
        'notes' => 'Setoran cicilan bulan ke-2',
        'created_by' => $founder->id,
    ]);

    // Test Founder access to PDF
    $response = $this->actingAs($founder)->get(route('installments.unit-statement-pdf', $installment->id));
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');

    // Test Public QR Code verification route
    $publicResponse = $this->get(route('verify.installment-statement', $installment->id));
    $publicResponse->assertStatus(200)
        ->assertSee('UNIT A-01')
        ->assertSee('Budi Santoso')
        ->assertSee('Kavling Harmoni Asri')
        ->assertSee('120.000.000')
        ->assertSee('40.000.000'); // Total paid (DP 20jt + 2x 10jt)
});

test('unauthorized user cannot export unit installment statement PDF', function () {
    $marketing = User::create([
        'name' => 'Marketing Staff',
        'email' => 'marketing_statement@example.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $marketing->assignRole('marketing');

    $founder = User::create([
        'name' => 'Pak Direktur 2',
        'email' => 'founder_statement2@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Kavling Harmoni Asri 2',
        'location' => 'Riau',
        'standard_land_area' => 100,
        'base_price' => 100000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'B-02',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_area' => 100,
        'hpp' => 80000000,
        'status' => 'terjual',
        'created_by' => $founder->id,
    ]);

    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'total_price' => 100000000,
        'down_payment' => 10000000,
        'installment_count' => 10,
        'installment_amount' => 9000000,
        'start_date' => '2026-01-15',
        'status' => 'berjalan',
    ]);

    $this->actingAs($marketing)
        ->get(route('installments.unit-statement-pdf', $installment->id))
        ->assertStatus(403);
});
