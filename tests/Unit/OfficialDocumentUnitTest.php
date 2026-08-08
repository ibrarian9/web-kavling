<?php

use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Unit;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Str;

test('official document model can be created with correct attributes and casts', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Document Test',
        'location' => 'Jl. Dokumen',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'DOC-A1',
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

    $proposal = PriceProposal::create([
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
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/TEST/' . Str::random(8),
        'buyer_name' => 'Budi Santoso',
        'buyer_nik' => '1471012345678901',
        'buyer_contact' => '08123456789',
        'buyer_address' => 'Jl. Pembeli No. 1',
        'seller_name' => 'PT. Atlantik',
        'seller_nik' => '1471019876543210',
        'seller_position' => 'Direktur',
        'seller_address' => 'Jl. Kantor No. 88',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);

    expect($doc)->not->toBeNull();
    expect($doc->document_number)->toStartWith('SPP/TEST/');
    expect($doc->buyer_name)->toBe('Budi Santoso');
    expect($doc->issued_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('official document has correct relationships: unit, proposal, issuer', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Relasi Doc',
        'location' => 'Jl. Relasi Doc',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'RD-B1',
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

    $proposal = PriceProposal::create([
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
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/REL/' . Str::random(8),
        'buyer_name' => 'Siti Aminah',
        'buyer_contact' => '08199887766',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);

    expect($doc->unit->id)->toBe($unit->id);
    expect($doc->proposal->id)->toBe($proposal->id);
    expect($doc->issuer->id)->toBe($founder->id);
});

test('effective buyer NIK accessor returns buyer_nik or generates fallback', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling NIK Test',
        'location' => 'Jl. NIK',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'NIK-C1',
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

    $proposal = PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 100000000,
        'proposed_price' => 150000000,
        'margin' => 50000000,
        'is_below_hpp' => false,
        'proposed_by' => $founder->id,
        'status' => 'disetujui',
    ]);

    // With buyer_nik set
    $docWithNik = OfficialDocument::create([
        'unit_id' => $unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/NIK/' . Str::random(8),
        'buyer_name' => 'Ahmad',
        'buyer_nik' => '1471012345678901',
        'buyer_contact' => '081',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);
    expect($docWithNik->effective_buyer_nik)->toBe('1471012345678901');

    // Without buyer_nik -> fallback
    $docNoNik = OfficialDocument::create([
        'unit_id' => $unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/NIK/' . Str::random(8),
        'buyer_name' => 'Budi',
        'buyer_contact' => '082',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);
    expect($docNoNik->effective_buyer_nik)->toStartWith('14710');
});

test('effective seller accessors cascade through document -> issuer -> founder', function () {
    $founder = User::factory()->create([
        'role' => 'founder',
        'name' => 'Founder Seller Test',
        'nik' => '1471019999888877',
        'position' => 'CEO PT. Test',
        'address' => 'Jl. CEO No. 1',
    ]);

    $project = Project::create([
        'name' => 'Kavling Seller Test',
        'location' => 'Jl. Seller',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'SEL-D1',
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

    $proposal = PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 100000000,
        'proposed_price' => 150000000,
        'margin' => 50000000,
        'is_below_hpp' => false,
        'proposed_by' => $founder->id,
        'status' => 'disetujui',
    ]);

    // Doc with explicit seller fields
    $docExplicit = OfficialDocument::create([
        'unit_id' => $unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/SEL/' . Str::random(8),
        'buyer_name' => 'Pembeli A',
        'buyer_contact' => '081',
        'seller_name' => 'PT. Explicit Corp',
        'seller_nik' => '1111222233334444',
        'seller_position' => 'Direktur Explicit',
        'seller_address' => 'Jl. Explicit No. 99',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);
    expect($docExplicit->effective_seller_name)->toBe('PT. Explicit Corp');
    expect($docExplicit->effective_seller_nik)->toBe('1111222233334444');
    expect($docExplicit->effective_seller_position)->toBe('Direktur Explicit');
    expect($docExplicit->effective_seller_address)->toBe('Jl. Explicit No. 99');

    // Doc without seller fields -> falls back to issuer (founder) profile
    $docFallback = OfficialDocument::create([
        'unit_id' => $unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/SEL/' . Str::random(8),
        'buyer_name' => 'Pembeli B',
        'buyer_contact' => '082',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);
    expect($docFallback->effective_seller_name)->toBe('Founder Seller Test');
    expect($docFallback->effective_seller_nik)->toBe('1471019999888877');
    expect($docFallback->effective_seller_position)->toBe('CEO PT. Test');
    expect($docFallback->effective_seller_address)->toBe('Jl. CEO No. 1');
});
