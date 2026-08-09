<?php

use App\Enums\UnitStatus;
use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use App\Livewire\Cashflow\Index as CashflowIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
});

test('founder deleting cashflow transaction tied to installment payment correctly recalculates installment balance', function () {
    $founder = User::create([
        'name' => 'Founder Cashflow Sync',
        'email' => 'founder_csync_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Sync Test',
        'location' => 'Jl. Sync',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'SYNC-01',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => UnitStatus::TERJUAL->value,
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
        'document_number' => 'SPP/SYNC/' . Str::random(5),
        'buyer_name' => 'Pembeli Sync Test',
        'buyer_contact' => '081234567',
        'issued_by' => $founder->id,
        'issued_at' => now(),
    ]);

    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'official_document_id' => $doc->id,
        'total_price' => 150000000,
        'down_payment' => 30000000,
        'installment_count' => 12,
        'installment_amount' => 10000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
        'total_paid' => 40000000,
        'remaining_balance' => 110000000,
    ]);

    $payment = InstallmentPayment::create([
        'unit_installment_id' => $installment->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 10000000,
        'payment_method' => 'Transfer',
        'created_by' => $founder->id,
    ]);

    $trx = CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'masuk',
        'category' => 'pembayaran_cicilan_pembeli',
        'amount' => 10000000,
        'transaction_date' => now()->toDateString(),
        'description' => 'Setoran Cicilan Unit SYNC-01',
        'reference_type' => InstallmentPayment::class,
        'reference_id' => $payment->id,
        'created_by' => $founder->id,
    ]);

    // Perform deletion from Cashflow Livewire component
    Livewire::actingAs($founder)
        ->test(CashflowIndex::class)
        ->call('deleteTransaction', $trx->id);

    // Verify CashflowTransaction is deleted
    expect(CashflowTransaction::find($trx->id))->toBeNull();

    // Verify InstallmentPayment is deleted
    expect(InstallmentPayment::find($payment->id))->toBeNull();

    // Verify UnitInstallment total_paid and remaining_balance updated
    $installment->refresh();
    expect((float)$installment->total_paid)->toEqual(30000000.0);
    expect((float)$installment->remaining_balance)->toEqual(120000000.0);
});

test('founder deleting cashflow transaction tied to booking reverts unit status to tersedia', function () {
    $founder = User::create([
        'name' => 'Founder Booking Sync',
        'email' => 'founder_bsync_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Booking Sync',
        'location' => 'Jl. BSync',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'BSYNC-01',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => UnitStatus::BOOKED->value,
        'created_by' => $founder->id,
    ]);

    $booking = Booking::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'buyer_name' => 'Pembeli Booking Sync',
        'buyer_phone' => '087654321',
        'booking_amount' => 5000000,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $founder->id,
    ]);

    $trx = CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'masuk',
        'category' => 'booking_fee',
        'amount' => 5000000,
        'transaction_date' => now()->toDateString(),
        'description' => 'Booking Fee Unit BSYNC-01',
        'reference_type' => Booking::class,
        'reference_id' => $booking->id,
        'created_by' => $founder->id,
    ]);

    // Perform deletion from Cashflow Livewire component
    Livewire::actingAs($founder)
        ->test(CashflowIndex::class)
        ->call('deleteTransaction', $trx->id);

    // Verify CashflowTransaction and Booking are deleted
    expect(CashflowTransaction::find($trx->id))->toBeNull();
    expect(Booking::find($booking->id))->toBeNull();

    // Verify Unit status reverted to 'tersedia'
    $unit->refresh();
    expect($unit->status)->toBe(UnitStatus::TERSEDIA->value);
});
