<?php

use App\Livewire\DailyActivityReports\Index as DailyActivityReportsIndex;
use App\Livewire\Projects\Show as ProjectsShow;
use App\Livewire\Units\Index as UnitsIndex;
use App\Livewire\Units\Show as UnitsShow;
use App\Models\Booking;
use App\Models\DailyActivityReport;
use App\Models\InstallmentPayment;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitCommission;
use App\Models\UnitInstallment;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerUnitPayroll;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'marketing', 'guard_name' => 'web']);

    $this->founder = User::create([
        'name' => 'Founder Utama',
        'email' => 'founder_scope@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $this->founder->assignRole('founder');

    $this->admin = User::create([
        'name' => 'Admin Utama',
        'email' => 'admin_scope@test.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_active' => true,
    ]);
    $this->admin->assignRole('admin');

    $this->marketingA = User::create([
        'name' => 'Marketing Alpha',
        'email' => 'alpha@test.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $this->marketingA->assignRole('marketing');

    $this->marketingB = User::create([
        'name' => 'Marketing Beta',
        'email' => 'beta@test.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $this->marketingB->assignRole('marketing');

    $this->project = Project::create([
        'name' => 'Cluster Harmoni Scoping',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 180000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    // Unit A: dealt by Marketing A via DailyActivityReport ('deal')
    $this->unitA = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'A-01',
        'type' => 'rumah',
        'category' => 'rumah',
        'land_area' => 100,
        'hpp' => 120000000,
        'final_selling_price' => 250000000,
        'status' => 'terjual',
        'created_by' => $this->founder->id,
    ]);

    // Unit B: dealt by Marketing B via DailyActivityReport ('deal') + Booking
    $this->unitB = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'B-02',
        'type' => 'rumah',
        'category' => 'rumah',
        'land_area' => 100,
        'hpp' => 120000000,
        'final_selling_price' => 260000000,
        'status' => 'terjual',
        'created_by' => $this->founder->id,
    ]);

    // Unit C: warm prospect only (not closed deal), sold independently
    $this->unitC = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'C-03',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_area' => 100,
        'hpp' => 90000000,
        'final_selling_price' => 200000000,
        'status' => 'terjual',
        'created_by' => $this->founder->id,
    ]);

    $this->worker = Worker::create([
        'name' => 'Pak Tukang',
        'type' => 'tukang',
        'phone' => '08123456700',
        'status' => 'active',
        'created_by' => $this->founder->id,
    ]);

    // Deal reports
    $this->reportA = DailyActivityReport::create([
        'user_id' => $this->marketingA->id,
        'project_id' => $this->project->id,
        'unit_id' => $this->unitA->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Pembeli Alpha Satu',
        'client_phone' => '08111111111',
        'lead_source' => 'whatsapp',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'deal',
        'payment_type' => 'cicilan_kpr_pribadi',
        'deal_amount' => 250000000,
    ]);

    $this->reportB = DailyActivityReport::create([
        'user_id' => $this->marketingB->id,
        'project_id' => $this->project->id,
        'unit_id' => $this->unitB->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Pembeli Beta Dua',
        'client_phone' => '08222222222',
        'lead_source' => 'walk_in',
        'interaction_type' => 'survey_lokasi',
        'lead_stage' => 'booking',
        'payment_type' => 'booking_fee',
        'deal_amount' => 260000000,
    ]);

    // Warm prospect on Unit C by Marketing A does NOT count as closed deal
    DailyActivityReport::create([
        'user_id' => $this->marketingA->id,
        'project_id' => $this->project->id,
        'unit_id' => $this->unitC->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Prospek Warm C',
        'client_phone' => '08333333333',
        'lead_source' => 'instagram',
        'interaction_type' => 'telepon',
        'lead_stage' => 'warm',
        'payment_type' => 'tanpa_dp',
        'deal_amount' => 0,
    ]);

    // Bookings
    Booking::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unitA->id,
        'buyer_name' => 'Pembeli Alpha Satu',
        'buyer_phone' => '08111111111',
        'booking_amount' => 5000000,
        'dp_amount' => 25000000,
        'total_price' => 250000000,
        'status' => 'converted',
        'booking_date' => now()->toDateString(),
        'created_by' => $this->marketingA->id,
    ]);

    Booking::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unitB->id,
        'buyer_name' => 'Pembeli Beta Dua',
        'buyer_phone' => '08222222222',
        'booking_amount' => 7000000,
        'dp_amount' => 30000000,
        'total_price' => 260000000,
        'status' => 'converted',
        'booking_date' => now()->toDateString(),
        'created_by' => $this->marketingB->id,
    ]);

    // Installments on A, B, C
    $this->installmentA = UnitInstallment::create([
        'unit_id' => $this->unitA->id,
        'total_price' => 250000000,
        'down_payment' => 25000000,
        'installment_count' => 12,
        'installment_amount' => 18750000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);
    InstallmentPayment::create([
        'unit_installment_id' => $this->installmentA->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 18750000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Cicilan A ke-1',
        'created_by' => $this->founder->id,
    ]);

    $this->installmentB = UnitInstallment::create([
        'unit_id' => $this->unitB->id,
        'total_price' => 260000000,
        'down_payment' => 30000000,
        'installment_count' => 10,
        'installment_amount' => 23000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);
    InstallmentPayment::create([
        'unit_installment_id' => $this->installmentB->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 23000000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Cicilan B ke-1',
        'created_by' => $this->founder->id,
    ]);

    // Expenses & Payroll on A and B
    WeeklyMaterialPurchase::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unitA->id,
        'worker_id' => $this->worker->id,
        'purchase_date' => now()->toDateString(),
        'item_name' => 'Semen Merah Putih Unit A',
        'store_name' => 'TB Jaya A',
        'quantity' => 10,
        'unit_measure' => 'sak',
        'unit_price' => 75000,
        'total_price' => 750000,
        'payment_status' => 'lunas',
        'pengawas_id' => $this->founder->id,
    ]);
    WorkerUnitPayroll::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unitA->id,
        'worker_id' => $this->worker->id,
        'agreed_salary' => 15000000,
        'paid_amount' => 5000000,
        'payment_frequency' => 'fleksibel',
        'status' => 'berjalan',
        'created_by' => $this->founder->id,
    ]);

    WeeklyMaterialPurchase::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unitB->id,
        'worker_id' => $this->worker->id,
        'purchase_date' => now()->toDateString(),
        'item_name' => 'Besi Beton Rahasia Unit B',
        'store_name' => 'TB Jaya B',
        'quantity' => 20,
        'unit_measure' => 'batang',
        'unit_price' => 120000,
        'total_price' => 2400000,
        'payment_status' => 'lunas',
        'pengawas_id' => $this->founder->id,
    ]);

    // Commissions
    UnitCommission::create([
        'unit_id' => $this->unitA->id,
        'marketing_id' => $this->marketingA->id,
        'seller_name' => 'Marketing Alpha',
        'commission_amount' => 7500000,
        'paid_amount' => 2500000,
        'status' => 'berjalan',
        'created_by' => $this->founder->id,
    ]);
    UnitCommission::create([
        'unit_id' => $this->unitB->id,
        'marketing_id' => $this->marketingB->id,
        'seller_name' => 'Marketing Beta',
        'commission_amount' => 8000000,
        'paid_amount' => 4000000,
        'status' => 'berjalan',
        'created_by' => $this->founder->id,
    ]);
});

test('marketing user can view cicilan and biaya ONLY for units they closed deal on', function () {
    expect($this->marketingA->hasDealtUnit($this->unitA->id))->toBeTrue();
    expect($this->marketingA->hasDealtUnit($this->unitB->id))->toBeFalse();
    expect($this->marketingA->hasDealtUnit($this->unitC->id))->toBeFalse();

    // Unit A (Marketing A's deal): CAN view installments, expenses, own commission (read-only)
    Livewire::actingAs($this->marketingA)
        ->test(UnitsShow::class, ['id' => $this->unitA->id])
        ->assertSee('Skema Cicilan & Pembayaran Pembeli', false)
        ->assertSee('Cicilan A ke-1')
        ->assertSee('Total Setoran')
        ->assertSee('Realisasi Biaya')
        ->assertSee('Rincian Biaya Pengeluaran & Belanja Unit', false)
        ->assertSee('Semen Merah Putih Unit A')
        ->assertSee('Komisi Penjual Unit')
        ->assertSee('Pembeli Alpha Satu')
        ->assertDontSee('Bayar Kontrak')
        ->assertDontSee('<span>Input Setoran</span>', false);

    // PDF endpoints allowed (200) for Marketing A's dealt Unit A
    $this->actingAs($this->marketingA)->get(route('units.expenses-pdf', $this->unitA->id))->assertStatus(200);
    $this->actingAs($this->marketingA)->get(route('installments.unit-statement-pdf', $this->installmentA->id))->assertStatus(200);

    // Unit B (Marketing B's deal): Marketing A CANNOT see cicilan, booking buyer, commissions, or biaya
    Livewire::actingAs($this->marketingA)
        ->test(UnitsShow::class, ['id' => $this->unitB->id])
        ->assertDontSee('Skema Cicilan & Pembayaran Pembeli', false)
        ->assertDontSee('Cicilan B ke-1')
        ->assertDontSee('Total Setoran')
        ->assertDontSee('Realisasi Biaya')
        ->assertDontSee('Rincian Biaya Pengeluaran & Belanja Unit', false)
        ->assertDontSee('Besi Beton Rahasia Unit B')
        ->assertDontSee('Komisi Penjual Unit & Skema Cicilan', false)
        ->assertDontSee('Terbitkan Dokumen SPP & SPJB PDF', false)
        ->assertDontSee('Pembeli Beta Dua');

    // Unit C (warm prospect only, NOT a closed deal): Marketing A CANNOT see cicilan or biaya
    Livewire::actingAs($this->marketingA)
        ->test(UnitsShow::class, ['id' => $this->unitC->id])
        ->assertDontSee('Skema Cicilan & Pembayaran Pembeli', false)
        ->assertDontSee('Realisasi Biaya')
        ->assertDontSee('Rincian Biaya Pengeluaran & Belanja Unit', false);

    // PDF endpoints blocked (403) on Unit B for Marketing A
    $this->actingAs($this->marketingA)->get(route('units.expenses-pdf', $this->unitB->id))->assertStatus(403);
    $this->actingAs($this->marketingA)->get(route('installments.unit-statement-pdf', $this->installmentB->id))->assertStatus(403);
});

test('units index and project units tab mask cicilan and biaya of other marketing deals', function () {
    // Units Index: Unit A deal price 250.000.000 & Ada Biaya visible; Unit B deal price 260.000.000 & paid 53.000.000 hidden
    Livewire::actingAs($this->marketingA)
        ->test(UnitsIndex::class)
        ->assertSee('A-01')
        ->assertSee('Rp 250.000.000')
        ->assertSee('Ada Biaya')
        ->assertSee('B-02')
        ->assertDontSee('Rp 260.000.000');

    // Project Show: Marketing A sees Pembeli Alpha Satu on Unit A, does NOT see Pembeli Beta Dua or Rp 260.000.000
    Livewire::actingAs($this->marketingA)
        ->test(ProjectsShow::class, ['id' => $this->project->id])
        ->assertSee('Pembeli Alpha Satu')
        ->assertDontSee('Pembeli Beta Dua')
        ->assertDontSee('260.000.000');
});

test('marketing can only view own daily reports and cannot input data on behalf of other marketing', function () {
    // Marketing A sees own report, NOT Marketing B's report, and has no Sales dropdown
    Livewire::actingAs($this->marketingA)
        ->test(DailyActivityReportsIndex::class)
        ->assertSee('Pembeli Alpha Satu')
        ->assertDontSee('Pembeli Beta Dua')
        ->assertDontSee('Sales / Marketing Penanggung Jawab')
        ->assertDontSee('Semua Tim Sales');

    // Marketing A cannot view detail modal of Marketing B's report
    Livewire::actingAs($this->marketingA)
        ->test(DailyActivityReportsIndex::class)
        ->call('showReportDetail', $this->reportB->id)
        ->assertSet('showDetailModal', false)
        ->assertSet('selectedReport', null)
        ->assertDispatched('notify');

    // Marketing A cannot tamper user_id to submit a report for Marketing B
    Livewire::actingAs($this->marketingA)
        ->test(DailyActivityReportsIndex::class)
        ->call('openCreateModal')
        ->set('user_id', $this->marketingB->id)
        ->set('client_name', 'Prospek Tamper')
        ->set('client_phone', '0899111222')
        ->set('report_date', now()->toDateString())
        ->call('saveReport')
        ->assertDispatched('notify');

    expect(DailyActivityReport::where('client_name', 'Prospek Tamper')->exists())->toBeFalse();
});

test('admin utama (admin and founder) can view all marketing reports and input data for specific marketing', function () {
    foreach ([$this->admin, $this->founder] as $principalAdmin) {
        $clientName = 'Prospek Input ' . $principalAdmin->role;

        Livewire::actingAs($principalAdmin)
            ->test(DailyActivityReportsIndex::class)
            ->assertSee('Pembeli Alpha Satu')
            ->assertSee('Pembeli Beta Dua')
            ->call('openCreateModal')
            ->assertSee('Sales / Marketing Penanggung Jawab')
            ->set('user_id', $this->marketingA->id)
            ->set('client_name', $clientName)
            ->set('client_phone', '08771234567')
            ->set('report_date', now()->toDateString())
            ->set('lead_source', 'whatsapp')
            ->set('interaction_type', 'chat_wa')
            ->set('lead_stage', 'deal')
            ->call('saveReport')
            ->assertHasNoErrors();

        $created = DailyActivityReport::where('client_name', $clientName)->first();
        expect($created)->not->toBeNull();
        expect($created->user_id)->toBe($this->marketingA->id);
    }
});
