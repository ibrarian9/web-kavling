<?php

use App\Livewire\DailyActivityReports\Index as DailyActivityReportsIndex;
use App\Livewire\Dashboard;
use App\Livewire\GlobalSearch;
use App\Livewire\Units\Index as UnitsIndex;
use App\Livewire\Units\Show as UnitsShow;
use App\Models\Booking;
use App\Models\DailyActivityReport;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'finance', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'marketing', 'guard_name' => 'web']);

    $this->founder = User::create([
        'name' => 'Founder Test',
        'email' => 'founder_mkt_test@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $this->founder->assignRole('founder');

    $this->finance = User::create([
        'name' => 'Finance Test',
        'email' => 'finance_mkt_test@example.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $this->finance->assignRole('finance');

    $this->marketing = User::create([
        'name' => 'Marketing Test',
        'email' => 'marketing_mkt_test@example.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $this->marketing->assignRole('marketing');

    $this->project = Project::create([
        'name' => 'Perumahan Harmoni Test',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 150000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'MKT-01',
        'category' => 'kavling',
        'type' => 'kavling',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);
});

test('marketing sidebar and dashboard hide keuangan arus kas, booking, and ajukan harga while keeping daily report and spp', function () {
    $response = $this->actingAs($this->marketing)->get(route('dashboard'));
    $response->assertStatus(200);

    // Sidebar & dashboard do NOT show Keuangan & Arus Kas, Booking, or Proposals
    $response->assertDontSee('Keuangan & Arus Kas', false)
        ->assertDontSee('Booking Fee & DP', false)
        ->assertDontSee('Cicilan & Piutang', false)
        ->assertDontSee('Arus Kas Global', false)
        ->assertDontSee('Hutang & Piutang', false)
        ->assertDontSee('Pengajuan & Approval', false)
        ->assertDontSee('Booking Unit', false)
        ->assertDontSee('Cicilan Pembeli', false)
        ->assertDontSee('Pengajuan Harga Terbaru', false)
        ->assertDontSee(route('bookings.index'))
        ->assertDontSee(route('proposals.index'))
        ->assertDontSee(route('installments.index'))
        ->assertDontSee(route('cashflow.index'))
        ->assertDontSee(route('payables.index'));

    // Sidebar & dashboard STILL show Daily Activity Report and Surat Resmi SPP
    $response->assertSee('Daily Activity Report', false)
        ->assertSee('Surat Resmi SPP (PDF)', false)
        ->assertSee(route('daily-activity-reports.index'))
        ->assertSee(route('documents.index'))
        ->assertSee('Prospek Hot Deal', false);

    // Founder STILL sees Keuangan & Arus Kas and Booking/Proposal menus
    $founderResponse = $this->actingAs($this->founder)->get(route('dashboard'));
    $founderResponse->assertStatus(200)
        ->assertSee('Keuangan & Arus Kas', false)
        ->assertSee('Booking Fee & DP', false)
        ->assertSee('Pengajuan & Approval', false);
});

test('marketing global search hides booking, proposal, and installment menus and booking records', function () {
    Booking::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'buyer_name' => 'Pembeli Rahasia Booking',
        'buyer_phone' => '081234567890',
        'booking_type' => 'unit',
        'booking_amount' => 5000000,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $this->finance->id,
    ]);

    Livewire::actingAs($this->marketing)
        ->test(GlobalSearch::class)
        ->set('isOpen', true)
        ->set('query', 'Booking')
        ->assertDontSee('Booking Fee & DP')
        ->assertDontSee('Pembeli Rahasia Booking')
        ->set('query', 'Proposal')
        ->assertDontSee('Pengajuan & Approval Proposal')
        ->set('query', 'Cicilan')
        ->assertDontSee('Cicilan Pembeli & Skema')
        ->set('query', 'Daily')
        ->assertSee('Daily Activity Report');
});

test('marketing cannot see or execute booking and ajukan harga actions on units index and detail', function () {
    // Units Index view & action guard
    Livewire::actingAs($this->marketing)
        ->test(UnitsIndex::class)
        ->assertStatus(200)
        ->assertSee('MKT-01')
        ->assertDontSee('Ajukan Harga')
        ->assertDontSee('Opsi Tindakan Unit')
        ->assertDontSee('wire:click="openBookingModal', false)
        ->call('openBookingModal', $this->unit->id)
        ->assertSet('showBookingModal', false)
        ->set('buyer_name', 'Percobaan Marketing')
        ->set('buyer_phone', '081234567890')
        ->set('booking_amount', 5000000)
        ->set('booking_date', now()->toDateString())
        ->call('saveBooking');

    expect(Booking::count())->toBe(0);
    expect($this->unit->fresh()->status)->toBe('tersedia');

    // Units Show view & action guard
    Livewire::actingAs($this->marketing)
        ->test(UnitsShow::class, ['id' => $this->unit->id])
        ->assertStatus(200)
        ->assertDontSee('Booking Unit Ini')
        ->assertDontSee('Ajukan Penawaran Harga')
        ->assertDontSee('Ajukan Proposal Baru')
        ->call('openBookingModal')
        ->assertSet('showBookingModal', false)
        ->call('openDirectProposalModal')
        ->assertSet('showDirectProposalModal', false);
});

test('marketing receives 403 on booking, proposal, installment, payables, and booking receipt endpoints', function () {
    $booking = Booking::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'buyer_name' => 'Pembeli Finance',
        'buyer_phone' => '081234567890',
        'booking_type' => 'unit',
        'booking_amount' => 5000000,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $this->finance->id,
    ]);

    $this->actingAs($this->marketing);

    $this->get(route('bookings.index'))->assertStatus(403);
    $this->get(route('proposals.index'))->assertStatus(403);
    $this->get(route('installments.index'))->assertStatus(403);
    $this->get(route('payables.index'))->assertStatus(403);
    $this->get(route('bookings.receipt', $booking->id))->assertStatus(403);
    $this->get(route('installments.unpaid-pdf'))->assertStatus(403);
});

test('daily report normalizes whatsapp urls and renders enlarged clickable wa links in table and detail modal', function () {
    $reportLocal = DailyActivityReport::create([
        'user_id' => $this->marketing->id,
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Bpk. Budi WhatsApp',
        'client_phone' => '0812-3456-7890',
        'lead_source' => 'whatsapp',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'hot_deal',
        'payment_type' => 'tanpa_dp',
        'deal_amount' => 0,
    ]);

    $reportPlus62 = DailyActivityReport::make(['client_phone' => '+62 813 9988 7766']);
    $reportNoZero = DailyActivityReport::make(['client_phone' => '81511223344']);
    $reportEmpty = DailyActivityReport::make(['client_phone' => '-']);

    expect($reportLocal->whatsapp_url)->toBe('https://wa.me/6281234567890');
    expect($reportPlus62->whatsapp_url)->toBe('https://wa.me/6281399887766');
    expect($reportNoZero->whatsapp_url)->toBe('https://wa.me/6281511223344');
    expect($reportEmpty->whatsapp_url)->toBeNull();

    Livewire::actingAs($this->marketing)
        ->test(DailyActivityReportsIndex::class)
        ->assertStatus(200)
        ->assertSee('https://wa.me/6281234567890', false)
        ->assertSee('0812-3456-7890')
        ->assertSee('text-sm sm:text-[15px]', false)
        ->call('showReportDetail', $reportLocal->id)
        ->assertSet('showDetailModal', true)
        ->assertSee('https://wa.me/6281234567890', false)
        ->assertSee('text-sm sm:text-base', false);
});

test('admin utama and founder dashboards display compact responsive daily activity report table and links', function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    $admin = User::create([
        'name' => 'Admin Utama Test',
        'email' => 'admin_utama_dar@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_active' => true,
    ]);
    $admin->assignRole('admin');

    $otherMarketing = User::create([
        'name' => 'Marketing Kedua',
        'email' => 'marketing_dua_dar@example.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $otherMarketing->assignRole('marketing');

    DailyActivityReport::create([
        'user_id' => $this->marketing->id,
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Ibu Sari Admin Feed',
        'client_phone' => '0812-1111-2222',
        'lead_source' => 'instagram',
        'interaction_type' => 'survey_lokasi',
        'lead_stage' => 'hot_deal',
        'payment_type' => 'tanpa_dp',
        'deal_amount' => 150000000,
        'notes' => 'Catatan panjang tidak perlu muncul di dashboard',
    ]);

    DailyActivityReport::create([
        'user_id' => $otherMarketing->id,
        'project_id' => $this->project->id,
        'unit_id' => null,
        'report_date' => now()->subDay()->toDateString(),
        'client_name' => 'Bpk. Doni Admin Feed',
        'client_phone' => '0813-3333-4444',
        'lead_source' => 'tiktok',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'warm',
        'payment_type' => 'tanpa_dp',
        'deal_amount' => 0,
    ]);

    foreach ([$admin, $this->founder] as $executive) {
        Livewire::actingAs($executive)
            ->test(Dashboard::class)
            ->assertStatus(200)
            ->assertSee('Daily Activity Report Terbaru')
            ->assertSee(route('daily-activity-reports.index'), false)
            ->assertSee('table-responsive-card', false)
            ->assertSee('table-responsive-container', false)
            ->assertSee('data-label="Klien & Kontak"', false)
            ->assertSee('min-h-[44px]', false)
            ->assertSee('bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200', false)
            ->assertSee('rounded-2xl sm:rounded-3xl', false)
            ->assertDontSee('text-xs text-emerald-700 font-bold hover:underline', false)
            ->assertSee('Tanggal / Sales')
            ->assertSee('Klien & Kontak', false)
            ->assertSee('Proyek / Unit')
            ->assertSee('Tahap Prospek')
            ->assertSee('Ibu Sari Admin Feed')
            ->assertSee('https://wa.me/6281211112222', false)
            ->assertSee('Hot Deal')
            ->assertSee('Bpk. Doni Admin Feed')
            ->assertSee('Warm Prospect')
            ->assertSee('Marketing Test')
            ->assertSee('Marketing Kedua')
            ->assertDontSee('Sumber & Interaksi', false)
            ->assertDontSee('Nominal Closing')
            ->assertDontSee('Catatan / Next Follow Up')
            ->assertDontSee('Catatan panjang tidak perlu muncul di dashboard');
    }

    Livewire::actingAs($admin)
        ->test(Dashboard::class)
        ->assertSee('Status Stok & Unit Properti', false)
        ->assertSee('Harga Total Jual Unit & Kavling', false);
});

test('marketing dashboard displays compact own daily activity report table while finance does not', function () {
    $otherMarketing = User::create([
        'name' => 'Marketing Lain',
        'email' => 'marketing_lain_dar@example.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $otherMarketing->assignRole('marketing');

    DailyActivityReport::create([
        'user_id' => $this->marketing->id,
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Klien Saya Sendiri',
        'client_phone' => '0812-7777-8888',
        'lead_source' => 'whatsapp',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'hot_deal',
        'payment_type' => 'tanpa_dp',
        'deal_amount' => 0,
        'notes' => 'Detail catatan internal sales',
    ]);

    DailyActivityReport::create([
        'user_id' => $otherMarketing->id,
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Klien Sales Lain',
        'client_phone' => '0819-0000-1111',
        'lead_source' => 'instagram',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'warm',
        'payment_type' => 'tanpa_dp',
        'deal_amount' => 0,
    ]);

    Livewire::actingAs($this->marketing)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertSee('Daily Activity Report Terbaru')
        ->assertSee('table-responsive-card', false)
        ->assertSee('Status Availability Unit Kavling')
        ->assertSee('bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200', false)
        ->assertDontSee('text-xs text-emerald-700 font-bold hover:underline', false)
        ->assertSee('Klien Saya Sendiri')
        ->assertSee('https://wa.me/6281277778888', false)
        ->assertSee('Unit MKT-01')
        ->assertSee('Hot Deal')
        ->assertDontSee('Klien Sales Lain')
        ->assertDontSee('Tanggal / Sales')
        ->assertDontSee('Sumber & Interaksi', false)
        ->assertDontSee('Nominal Closing')
        ->assertDontSee('Detail catatan internal sales');

    Livewire::actingAs($this->finance)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertDontSee('Daily Activity Report Terbaru');
});

