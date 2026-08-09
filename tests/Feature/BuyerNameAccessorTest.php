<?php

use App\Models\Booking;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use Illuminate\Support\Str;

test('unit and unit_installment buyer_name accessors correctly fall back from official document to booking buyer_name', function () {
    $founder = User::factory()->create();
    $project = Project::create([
        'name' => 'Proyek Accessor Test',
        'location' => 'Jl. Accessor',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    // Unit 1: Has Booking ONLY (No OfficialDocument yet)
    $unit1 = Unit::create([
        'project_id' => $project->id,
        'code' => 'ACC-01',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'booked',
        'created_by' => $founder->id,
    ]);

    $booking = Booking::create([
        'project_id' => $project->id,
        'unit_id' => $unit1->id,
        'buyer_name' => 'Pembeli Dari Booking',
        'buyer_phone' => '081299998888',
        'booking_amount' => 5000000,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $founder->id,
    ]);

    $inst1 = UnitInstallment::create([
        'unit_id' => $unit1->id,
        'official_document_id' => null, // No official document yet
        'total_price' => 150000000,
        'down_payment' => 30000000,
        'installment_count' => 12,
        'installment_amount' => 10000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    // Test Unit1 & Inst1 buyer_name accessor
    expect($unit1->buyer_name)->toBe('Pembeli Dari Booking');
    expect($unit1->buyer_phone)->toBe('081299998888');

    $inst1->load(['unit.project', 'unit.activeBooking', 'officialDocument']);
    expect($inst1->buyer_name)->toBe('Pembeli Dari Booking');
    expect($inst1->buyer_phone)->toBe('081299998888');

    // Unit 2: Has OfficialDocument (Overrules booking buyer_name if present)
    $unit2 = Unit::create([
        'project_id' => $project->id,
        'code' => 'ACC-02',
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

    $prop2 = PriceProposal::create([
        'unit_id' => $unit2->id,
        'hpp_price' => 100000000,
        'proposed_price' => 150000000,
        'margin' => 50000000,
        'is_below_hpp' => false,
        'proposed_by' => $founder->id,
        'status' => 'disetujui',
    ]);

    $doc2 = OfficialDocument::create([
        'unit_id' => $unit2->id,
        'price_proposal_id' => $prop2->id,
        'document_number' => 'SPP/ACC/' . Str::random(5),
        'buyer_name' => 'Pembeli Dari SPJB Resmi',
        'buyer_contact' => '087711112222',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);

    $inst2 = UnitInstallment::create([
        'unit_id' => $unit2->id,
        'official_document_id' => $doc2->id,
        'total_price' => 150000000,
        'down_payment' => 30000000,
        'installment_count' => 12,
        'installment_amount' => 10000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    $inst2->load(['unit.project', 'officialDocument']);
    expect($inst2->buyer_name)->toBe('Pembeli Dari SPJB Resmi');
    expect($inst2->buyer_phone)->toBe('087711112222');
});
