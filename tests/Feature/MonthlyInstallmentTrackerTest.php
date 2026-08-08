<?php

use App\Models\InstallmentPayment;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use App\Livewire\Installments\Index as InstallmentIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
});

test('monthly installment filter accurately separates unpaid and paid buyers for current month', function () {
    $founder = User::create([
        'name' => 'Founder Tracker',
        'email' => 'founder_track_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Tracker Test',
        'location' => 'Jl. Tracker',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    // Unit 1: Unpaid this month
    $unit1 = Unit::create([
        'project_id' => $project->id,
        'code' => 'TRK-01',
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

    $prop1 = PriceProposal::create([
        'unit_id' => $unit1->id,
        'hpp_price' => 100000000,
        'proposed_price' => 150000000,
        'margin' => 50000000,
        'is_below_hpp' => false,
        'proposed_by' => $founder->id,
        'status' => 'disetujui',
    ]);

    $doc1 = OfficialDocument::create([
        'unit_id' => $unit1->id,
        'price_proposal_id' => $prop1->id,
        'document_number' => 'SPP/TRK/001/' . Str::random(5),
        'buyer_name' => 'Pembeli Belum Bayar',
        'buyer_contact' => '081234567',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);

    $inst1 = UnitInstallment::create([
        'unit_id' => $unit1->id,
        'official_document_id' => $doc1->id,
        'total_price' => 150000000,
        'down_payment' => 30000000,
        'installment_count' => 12,
        'installment_amount' => 10000000,
        'start_date' => now()->subMonths(2)->toDateString(),
        'status' => 'berjalan',
    ]);

    // Payment last month (NOT this month)
    InstallmentPayment::create([
        'unit_installment_id' => $inst1->id,
        'payment_date' => now()->subMonth()->startOfMonth()->toDateString(),
        'amount_paid' => 10000000,
        'payment_method' => 'Transfer',
        'created_by' => $founder->id,
    ]);

    // Unit 2: Paid this month
    $unit2 = Unit::create([
        'project_id' => $project->id,
        'code' => 'TRK-02',
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
        'document_number' => 'SPP/TRK/002/' . Str::random(5),
        'buyer_name' => 'Pembeli Sudah Bayar',
        'buyer_contact' => '087654321',
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
        'start_date' => now()->subMonths(2)->toDateString(),
        'status' => 'berjalan',
    ]);

    // Payment THIS month
    InstallmentPayment::create([
        'unit_installment_id' => $inst2->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 10000000,
        'payment_method' => 'Transfer',
        'created_by' => $founder->id,
    ]);

    // 1. Test 'unpaid_this_month' filter
    Livewire::actingAs($founder)
        ->test(InstallmentIndex::class)
        ->set('search', 'TRK-01')
        ->set('monthlyFilter', 'unpaid_this_month')
        ->assertSee('TRK-01')
        ->assertSee('Belum Bayar Bulan Ini');

    // 2. Test 'paid_this_month' filter
    Livewire::actingAs($founder)
        ->test(InstallmentIndex::class)
        ->set('search', 'TRK-02')
        ->set('monthlyFilter', 'paid_this_month')
        ->assertSee('TRK-02')
        ->assertSee('Sudah Bayar Bulan Ini');

    // 3. Record payment for Unit 1 and verify it transitions from unpaid to paid
    Livewire::actingAs($founder)
        ->test(InstallmentIndex::class)
        ->call('openPaymentModal', $inst1->id)
        ->set('payment_amount', 10000000)
        ->set('payment_date', now()->toDateString())
        ->call('submitPayment')
        ->assertHasNoErrors();

    // Now Unit 1 should appear in 'paid_this_month' filter
    Livewire::actingAs($founder)
        ->test(InstallmentIndex::class)
        ->set('search', 'TRK-01')
        ->set('monthlyFilter', 'paid_this_month')
        ->assertSee('TRK-01')
        ->assertSee('Sudah Bayar Bulan Ini');
});
