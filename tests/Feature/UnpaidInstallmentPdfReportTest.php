<?php

use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
});

test('founder and finance can export unpaid monthly installments pdf report', function () {
    $founder = User::create([
        'name' => 'Founder PDF Test',
        'email' => 'founder_pdf_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek PDF Test',
        'location' => 'Jl. PDF',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'PDF-01',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'terjual',
        'created_by' => $founder->id,
    ]);

    $prop = PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 100000000,
        'proposed_price' => 150000000,
        'margin' => 50000000,
        'is_below_hpp' => false,
        'proposed_by' => $founder->id,
        'status' => 'disetujui',
    ]);

    $doc = OfficialDocument::create([
        'unit_id' => $unit->id,
        'price_proposal_id' => $prop->id,
        'document_number' => 'SPP/PDF/' . Str::random(5),
        'buyer_name' => 'Konsumen PDF Tunggakan',
        'buyer_contact' => '08123456789',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);

    UnitInstallment::create([
        'unit_id' => $unit->id,
        'official_document_id' => $doc->id,
        'total_price' => 150000000,
        'down_payment' => 30000000,
        'installment_count' => 12,
        'installment_amount' => 10000000,
        'start_date' => now()->subMonths(2)->toDateString(),
        'status' => 'berjalan',
    ]);

    // Test PDF export route
    $response = $this->actingAs($founder)->get(route('installments.unpaid-pdf', [
        'project_id' => $project->id,
        'search' => 'Konsumen PDF',
    ]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');

    // Test public QR verification route
    $verifyResponse = $this->get(route('verify.unpaid-installments'));
    $verifyResponse->assertStatus(200);
    $verifyResponse->assertSee('Laporan Tunggakan Cicilan Pembeli');
});
