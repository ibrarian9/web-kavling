<?php

use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('authenticated user can stream Surat Perjanjian Jual Beli (SPJB) PDF', function () {
    $user = User::create([
        'name' => 'Founder User SPJB',
        'email' => 'founder_spjb@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $user->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Perumahan Indah',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 100000000,
        'created_by' => $user->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'A1',
        'type' => 'Tipe 36',
        'land_area' => 100,
        'building_area' => 36,
        'category' => 'rumah',
        'status' => 'terjual',
        'created_by' => $user->id,
    ]);

    $proposal = PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 100000000,
        'proposed_price' => 150000000,
        'margin' => 50000000,
        'proposed_by' => $user->id,
        'status' => 'disetujui',
    ]);

    $doc = OfficialDocument::create([
        'unit_id' => $unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/APA/PROY/A1/2026/0001',
        'buyer_name' => 'Budi Santoso',
        'buyer_nik' => '1471012304850009',
        'buyer_contact' => '08123456789',
        'buyer_address' => 'Jl. Merdeka No. 10',
        'seller_name' => 'Hani Kuswandari',
        'seller_nik' => '1471012304850001',
        'seller_position' => 'Direktur Utama',
        'issued_by' => $user->id,
        'issued_at' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('documents.spjb-pdf', $doc->id));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('public guest can verify Surat Perjanjian Jual Beli (SPJB) via public QR code page', function () {
    $user = User::create([
        'name' => 'Founder User SPJB QR',
        'email' => 'founder_spjb_qr@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    $project = Project::create([
        'name' => 'Proyek Perumahan Indah QR',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 100000000,
        'created_by' => $user->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'B2',
        'type' => 'Tipe 45',
        'land_area' => 120,
        'category' => 'rumah',
        'status' => 'terjual',
        'created_by' => $user->id,
    ]);

    $proposal = PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 120000000,
        'proposed_price' => 180000000,
        'margin' => 60000000,
        'proposed_by' => $user->id,
        'status' => 'disetujui',
    ]);

    $doc = OfficialDocument::create([
        'unit_id' => $unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/APA/PROY/B2/2026/0002',
        'buyer_name' => 'Siti Rahma',
        'buyer_nik' => '1471098765430001',
        'buyer_contact' => '08987654321',
        'buyer_address' => 'Jl. Sudirman No. 5',
        'issued_by' => $user->id,
        'issued_at' => now(),
    ]);

    $response = $this->get(route('verify.spjb', $doc->id));

    $response->assertStatus(200);
    $response->assertSee('SURAT PERJANJIAN JUAL BELI (SPJB) SAH');
    $response->assertSee('Siti Rahma');
    $response->assertSee('1471098765430001');
});
