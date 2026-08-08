<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Booking;
use App\Models\UnitInstallment;
use App\Models\InstallmentPayment;
use App\Models\CashflowTransaction;
use App\Models\OfficialDocument;
use App\Livewire\Projects\Index as ProjectIndex;
use App\Livewire\Bookings\Index as BookingIndex;
use App\Livewire\Units\Show as UnitShow;
use App\Livewire\Installments\Index as InstallmentIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
    Role::firstOrCreate(['name' => 'marketing']);
});

test('end to end property sale workflow: project creation -> unit setup -> booking -> dp acc -> installment scheme -> settlement', function () {
    // 1. Setup Users
    $founder = User::create([
        'name' => 'Founder E2E',
        'email' => 'founder_e2e_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $finance = User::create([
        'name' => 'Finance E2E',
        'email' => 'finance_e2e_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    $marketing = User::create([
        'name' => 'Marketing E2E',
        'email' => 'marketing_e2e_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $marketing->assignRole('marketing');

    // 2. Founder Creates Project
    Livewire::actingAs($founder)
        ->test(ProjectIndex::class)
        ->set('name', 'Perumahan Atlantik Residence')
        ->set('location', 'Jl. Atlantik Utama No. 1')
        ->set('standard_land_area', 100)
        ->set('excess_price_per_sqm', 1500000)
        ->set('base_price', 200000000)
        ->call('saveProject')
        ->assertHasNoErrors();

    $project = Project::where('name', 'Perumahan Atlantik Residence')->first();
    expect($project)->not->toBeNull();

    // 3. Create Unit in Project
    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'ATL-A01',
        'category' => 'rumah',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 45,
        'hpp' => 120000000,
        'final_selling_price' => 200000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    expect($unit->status)->toBe('tersedia');

    // 4. Marketing Records Booking Fee for Buyer (Rp 5,000,000)
    Livewire::actingAs($marketing)
        ->test(BookingIndex::class)
        ->set('project_id', $project->id)
        ->set('unit_id', $unit->id)
        ->set('buyer_name', 'Bambang Trihatmodjo')
        ->set('buyer_phone', '081298765432')
        ->set('booking_type', 'unit')
        ->set('booking_amount', 5000000)
        ->set('dp_amount', 20000000)
        ->set('booking_date', now()->toDateString())
        ->call('save')
        ->assertHasNoErrors();

    $booking = Booking::where('buyer_name', 'Bambang Trihatmodjo')->first();
    expect($booking)->not->toBeNull();
    expect(in_array($booking->status, ['active', 'converted']))->toBeTrue();
    expect($unit->fresh()->status)->toBe('booked');

    // Cashflow Inflow for Booking Fee
    $bookingCashflow = CashflowTransaction::where('reference_type', Booking::class)
        ->where('reference_id', $booking->id)
        ->first();
    expect($bookingCashflow)->not->toBeNull();
    expect((float)$bookingCashflow->amount)->toBe(5000000.0);

    // 5. Finance Approves DP Booking Fee
    Livewire::actingAs($finance)
        ->test(BookingIndex::class)
        ->call('approveDp', $booking->id)
        ->assertHasNoErrors();

    expect($booking->fresh()->status)->toBe('converted');

    // 6. Setup Installment Scheme (DP 20M, Total Price 200M, Tenor 18 Months)
    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('openSetupInstallmentModal')
        ->set('setup_total_price', 200000000)
        ->set('setup_down_payment', 20000000)
        ->set('setup_installment_count', 18)
        ->set('setup_start_date', now()->toDateString())
        ->call('saveSetupInstallment')
        ->assertHasNoErrors();

    $installment = UnitInstallment::where('unit_id', $unit->id)->first();
    expect($installment)->not->toBeNull();
    expect((float)$installment->total_price)->toBe(200000000.0);
    expect((float)$installment->down_payment)->toBe(20000000.0);
    expect((float)$installment->remaining_balance)->toBe(180000000.0);

    // Issue Official SPP/SPJB Document
    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->set('spp_buyer_name', 'Bambang Trihatmodjo')
        ->set('spp_buyer_contact', '081298765432')
        ->set('spp_cash_price', 200000000)
        ->call('saveDirectSpp')
        ->assertHasNoErrors();

    $document = OfficialDocument::where('unit_id', $unit->id)->first();
    expect($document)->not->toBeNull();

    // 7. Finance Records Monthly Installment Payments until Full Settlement
    Livewire::actingAs($finance)
        ->test(InstallmentIndex::class)
        ->set('selectedInstallmentId', $installment->id)
        ->set('payment_amount', 180000000)
        ->set('payment_date', now()->toDateString())
        ->set('payment_method', 'Transfer Bank')
        ->call('submitPayment')
        ->assertHasNoErrors();

    expect($installment->fresh()->status)->toBe('lunas');
    expect((float)$installment->fresh()->remaining_balance)->toBe(0.0);

    // Verify Cashflow & Activity Log
    $totalCashflowIn = CashflowTransaction::where('project_id', $project->id)
        ->where('type', 'masuk')
        ->sum('amount');

    expect((float)$totalCashflowIn)->toBeGreaterThanOrEqual(200000000.0);
});
