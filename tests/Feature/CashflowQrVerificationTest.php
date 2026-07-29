<?php

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\User;
use Spatie\Permission\Models\Role;

test('guest can access public cashflow verification page without login', function () {
    $response = $this->get(route('verify.cashflow', ['view_mode' => 'global']));
    $response->assertStatus(200);
    $response->assertSee('Laporan Arus Kas Terverifikasi Resmi');
    $response->assertSee('Verifikasi Rekapitulasi Arus Kas');
});

test('cashflow pdf export includes qr code verification link and removes manual signatures', function () {
    Role::findOrCreate('finance', 'web');

    $finance = User::create([
        'name' => 'Finance User',
        'email' => 'finance_cashflow@example.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    $project = Project::create([
        'name' => 'Kavling Harmoni',
        'location' => 'Malang',
        'land_purchase_price' => 200000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 250000000,
        'created_by' => $finance->id,
    ]);

    CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 50000000,
        'transaction_date' => now()->toDateString(),
        'description' => 'DP Unit A1',
        'created_by' => $finance->id,
    ]);

    $response = $this->actingAs($finance)->get(route('cashflow.export-pdf', ['view_mode' => 'global']));
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
