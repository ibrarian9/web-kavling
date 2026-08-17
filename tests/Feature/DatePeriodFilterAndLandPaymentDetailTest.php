<?php

use App\Livewire\Installments\Index;
use App\Models\Booking;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Livewire\Livewire;

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
});

test('land payment detail modal opens with full record and can be closed', function () {
    $this->actingAs($this->founder);

    $project = Project::create([
        'name' => 'Kavling Harmoni Permai',
        'location' => 'Bogor Timur',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'total_project_price' => 500000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $payment = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => '2026-08-15',
        'amount_paid' => 50000000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Catatan lengkap perjanjian notaris pembayaran termin ke-2 lahan kavling.',
        'created_by' => $this->founder->id,
    ]);

    Livewire::test(Index::class)
        ->set('activeTab', 'land_payments')
        ->call('showLandPaymentDetail', $payment->id)
        ->assertSet('showLandPaymentDetailModal', true)
        ->assertSee('Detail Pembayaran Lahan Proyek')
        ->assertSee('Catatan lengkap perjanjian notaris pembayaran termin ke-2 lahan kavling.')
        ->assertSee('50.000.000')
        ->call('closeLandPaymentDetailModal')
        ->assertSet('showLandPaymentDetailModal', false);
});

test('date period filter works correctly for today, yesterday, and custom range', function () {
    $this->actingAs($this->founder);

    $project = Project::create([
        'name' => 'Green Valley',
        'location' => 'Bandung Selatan',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'total_project_price' => 300000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $todayPayment = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => Carbon::today()->toDateString(),
        'amount_paid' => 10000000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Pembayaran Hari Ini',
        'created_by' => $this->founder->id,
    ]);

    $yesterdayPayment = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => Carbon::yesterday()->toDateString(),
        'amount_paid' => 20000000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Pembayaran Kemarin',
        'created_by' => $this->founder->id,
    ]);

    $pastPayment = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => Carbon::now()->subMonths(3)->toDateString(),
        'amount_paid' => 30000000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Pembayaran Lampau',
        'created_by' => $this->founder->id,
    ]);

    // Filter Today
    Livewire::test(Index::class)
        ->set('activeTab', 'land_payments')
        ->set('landDatePeriod', 'today')
        ->assertSee('Pembayaran Hari Ini')
        ->assertDontSee('Pembayaran Kemarin')
        ->assertDontSee('Pembayaran Lampau');

    // Filter Yesterday
    Livewire::test(Index::class)
        ->set('activeTab', 'land_payments')
        ->set('landDatePeriod', 'yesterday')
        ->assertSee('Pembayaran Kemarin')
        ->assertDontSee('Pembayaran Hari Ini')
        ->assertDontSee('Pembayaran Lampau');

    // Filter Custom Range
    Livewire::test(Index::class)
        ->set('activeTab', 'land_payments')
        ->set('landDatePeriod', 'custom')
        ->set('landStartDate', Carbon::yesterday()->toDateString())
        ->set('landEndDate', Carbon::today()->toDateString())
        ->assertSee('Pembayaran Hari Ini')
        ->assertSee('Pembayaran Kemarin')
        ->assertDontSee('Pembayaran Lampau');
});

test('openSetupModal works without lazy loading violations and supports booked units', function () {
    Model::preventLazyLoading(true);
    $this->actingAs($this->founder);

    $project = Project::create([
        'name' => 'Kavling Eager Test',
        'location' => 'Bogor',
        'standard_land_area' => 100,
        'base_price' => 180000000,
        'excess_price_per_sqm' => 500000,
        'total_project_price' => 300000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'BK-01',
        'land_area' => 100,
        'final_selling_price' => 180000000,
        'status' => 'booked',
        'created_by' => $this->founder->id,
    ]);

    $booking = Booking::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'buyer_name' => 'Budi Konsumen',
        'buyer_phone' => '08123456789',
        'booking_type' => 'unit',
        'booking_amount' => 5000000,
        'dp_amount' => 30000000,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $this->founder->id,
    ]);

    Livewire::test(Index::class)
        ->set('activeTab', 'unit_installments')
        ->call('openSetupModal')
        ->assertSet('showSetupModal', true)
        ->assertSet('unit_id', $unit->id)
        ->assertSet('total_price', 180000000.0)
        ->assertSet('down_payment', 30000000.0)
        ->assertSee('Setup Skema Cicilan Pembeli')
        ->assertSee('BK-01')
        ->assertSee('Budi Konsumen');
});
