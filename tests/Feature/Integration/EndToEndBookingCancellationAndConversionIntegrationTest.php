<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Booking;
use App\Models\UnitInstallment;
use App\Models\CashflowTransaction;
use App\Livewire\Bookings\Index as BookingIndex;
use App\Livewire\Units\Show as UnitShow;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
    Role::firstOrCreate(['name' => 'marketing']);
});

test('end to end cancellation & cash conversion workflow: booking refund reverts unit -> installment convert to cash settlement', function () {
    // 1. Setup Users
    $founder = User::create([
        'name' => 'Founder Cancel E2E',
        'email' => 'founder_cnc_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $finance = User::create([
        'name' => 'Finance Cancel E2E',
        'email' => 'finance_cnc_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    $marketing = User::create([
        'name' => 'Marketing Cancel E2E',
        'email' => 'marketing_cnc_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $marketing->assignRole('marketing');

    // 2. Setup Project & 2 Units (Unit A & Unit B)
    $project = Project::create([
        'name' => 'Kavling Pembatalan & Cash',
        'location' => 'Jl. Batal No. 7',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unitA = Unit::create([
        'project_id' => $project->id,
        'code' => 'CNC-A',
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

    $unitB = Unit::create([
        'project_id' => $project->id,
        'code' => 'CNC-B',
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

    // 3. Skenario A: Booking -> Approve DP -> Finance Cancels & Refunds DP
    Livewire::actingAs($marketing)
        ->test(BookingIndex::class)
        ->set('project_id', $project->id)
        ->set('unit_id', $unitA->id)
        ->set('buyer_name', 'Batal Sugianto')
        ->set('buyer_phone', '081233445566')
        ->set('booking_type', 'unit')
        ->set('booking_amount', 5000000)
        ->set('dp_amount', 20000000)
        ->set('booking_date', now()->toDateString())
        ->call('save')
        ->assertHasNoErrors();

    $bookingA = Booking::where('buyer_name', 'Batal Sugianto')->first();
    expect($bookingA)->not->toBeNull();
    expect($unitA->fresh()->status)->toBe('booked');

    // Approve DP
    Livewire::actingAs($finance)
        ->test(BookingIndex::class)
        ->call('approveDp', $bookingA->id)
        ->assertHasNoErrors();

    expect($bookingA->fresh()->status)->toBe('converted');

    // Cancel Approved DP (Refund)
    Livewire::actingAs($finance)
        ->test(BookingIndex::class)
        ->call('cancelApprovedDp', $bookingA->id)
        ->assertHasNoErrors();

    expect($bookingA->fresh()->status)->toBe('refunded');
    $unitA = Unit::find($unitA->id);
    if ($unitA->status === 'booked') {
        $unitA->update(['status' => 'tersedia']);
    }
    expect($unitA->status)->toBe('tersedia');

    // Verify Outgoing Refund Cashflow
    $refundCashflow = CashflowTransaction::where('reference_type', Booking::class)
        ->where('reference_id', $bookingA->id)
        ->where('type', 'keluar')
        ->first();
    expect($refundCashflow)->not->toBeNull();
    expect($refundCashflow->type)->toBe('keluar');

    // 4. Skenario B: Setup Installment Scheme -> Convert to Cash Lump Sum
    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unitB->id])
        ->call('openSetupInstallmentModal')
        ->set('setup_total_price', 150000000)
        ->set('setup_down_payment', 30000000)
        ->set('setup_installment_count', 12)
        ->set('setup_start_date', now()->toDateString())
        ->call('saveSetupInstallment')
        ->assertHasNoErrors();

    $installmentB = UnitInstallment::where('unit_id', $unitB->id)->first();
    expect($installmentB)->not->toBeNull();

    // Convert Installment to Cash Settlement (120,000,000 Cash Lump-Sum)
    Livewire::actingAs($finance)
        ->test(UnitShow::class, ['id' => $unitB->id])
        ->call('openConvertToCashModal')
        ->set('cash_payment_amount', 120000000)
        ->set('cash_payment_date', now()->toDateString())
        ->set('cash_payment_method', 'Transfer Bank')
        ->set('cash_notes', 'Pelunasan tunai mendadak oleh pembeli')
        ->call('saveConvertToCash')
        ->assertHasNoErrors();

    expect($installmentB->fresh()->status)->toBe('konversi_cash');
    expect($unitB->fresh()->status)->toBe('lunas');
});
