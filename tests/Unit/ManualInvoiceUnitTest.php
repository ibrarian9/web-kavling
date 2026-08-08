<?php

use App\Models\ManualInvoice;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;

test('manual invoice auto-generates UUID and invoice number on creation', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Invoice Test',
        'location' => 'Jl. Invoice',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $invoice = ManualInvoice::create([
        'project_id' => $project->id,
        'recipient_name' => 'PT. Maju Jaya',
        'recipient_phone' => '021-5551234',
        'type' => 'masuk',
        'category' => 'operasional',
        'amount' => 25000000,
        'invoice_date' => now()->toDateString(),
        'due_date' => now()->addDays(30)->toDateString(),
        'payment_method' => 'Transfer Bank',
        'status' => 'draf',
        'description' => 'Tagihan operasional bulanan',
        'record_cashflow' => false,
        'created_by' => $founder->id,
    ]);

    expect($invoice->uuid)->not->toBeNull();
    expect(strlen($invoice->uuid))->toBe(36); // UUID v4 format
    expect($invoice->invoice_number)->not->toBeNull();
    expect($invoice->invoice_number)->toStartWith('INV-MANUAL-');
});

test('manual invoice has correct attributes and casts', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Cast Test',
        'location' => 'Jl. Cast',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $invoice = ManualInvoice::create([
        'project_id' => $project->id,
        'recipient_name' => 'CV. Bersama',
        'recipient_phone' => '081222333444',
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 50000000,
        'invoice_date' => '2026-08-01',
        'due_date' => '2026-08-31',
        'status' => 'lunas',
        'record_cashflow' => true,
        'created_by' => $founder->id,
    ]);

    expect((float)$invoice->amount)->toBe(50000000.00);
    expect($invoice->invoice_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($invoice->due_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($invoice->record_cashflow)->toBeTrue();
});

test('manual invoice has correct relationships: project, unit, creator', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Relasi Invoice',
        'location' => 'Jl. Relasi Inv',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'INV-U1',
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

    $invoice = ManualInvoice::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'recipient_name' => 'Klien Test',
        'recipient_phone' => '081',
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 10000000,
        'invoice_date' => now()->toDateString(),
        'status' => 'draf',
        'record_cashflow' => false,
        'created_by' => $founder->id,
    ]);

    expect($invoice->project->id)->toBe($project->id);
    expect($invoice->unit->id)->toBe($unit->id);
    expect($invoice->creator->id)->toBe($founder->id);
});
