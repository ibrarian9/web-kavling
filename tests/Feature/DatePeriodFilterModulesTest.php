<?php

use App\Livewire\Bookings\Index as BookingsIndex;
use App\Livewire\DailyActivityReports\Index as DailyActivityReportsIndex;
use App\Livewire\Documents\Index as DocumentsIndex;
use App\Livewire\EmployeeSalaries\Index as EmployeeSalariesIndex;
use App\Livewire\ManualInvoices\Index as ManualInvoicesIndex;
use App\Livewire\Payables\Index as PayablesIndex;
use App\Models\Booking;
use App\Models\DailyActivityReport;
use App\Models\EmployeePayrollPayment;
use App\Models\EmployeeSalary;
use App\Models\ManualInvoice;
use App\Models\OfficialDocument;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use Carbon\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
});

test('bookings module filters by date period correctly', function () {
    $this->actingAs($this->founder);

    $project = Project::create([
        'name' => 'Kavling Harmoni 1',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'total_project_price' => 500000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $bToday = Booking::create([
        'project_id' => $project->id,
        'buyer_name' => 'Budi Hari Ini',
        'buyer_phone' => '081234567891',
        'booking_type' => 'project',
        'booking_amount' => 5000000,
        'booking_date' => Carbon::today()->toDateString(),
        'status' => 'active',
        'created_by' => $this->founder->id,
    ]);

    $bLastMonth = Booking::create([
        'project_id' => $project->id,
        'buyer_name' => 'Joko Bulan Lalu',
        'buyer_phone' => '081234567892',
        'booking_type' => 'project',
        'booking_amount' => 5000000,
        'booking_date' => Carbon::now()->subMonths(2)->toDateString(),
        'status' => 'active',
        'created_by' => $this->founder->id,
    ]);

    Livewire::test(BookingsIndex::class)
        ->assertSee('Budi Hari Ini')
        ->assertSee('Joko Bulan Lalu')
        ->set('datePeriod', 'today')
        ->assertSee('Budi Hari Ini')
        ->assertDontSee('Joko Bulan Lalu')
        ->set('datePeriod', 'all')
        ->assertSee('Budi Hari Ini')
        ->assertSee('Joko Bulan Lalu');
});

test('daily activity reports module filters by date period correctly', function () {
    $this->actingAs($this->founder);

    $repToday = DailyActivityReport::create([
        'user_id' => $this->founder->id,
        'report_date' => Carbon::today()->toDateString(),
        'client_name' => 'Klien Prospek Hari Ini',
        'client_phone' => '0811111111',
        'lead_source' => 'whatsapp',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'warm',
        'notes' => 'Follow up hari ini',
    ]);

    $repPast = DailyActivityReport::create([
        'user_id' => $this->founder->id,
        'report_date' => Carbon::now()->subMonths(2)->toDateString(),
        'client_name' => 'Klien Dua Bulan Lalu',
        'client_phone' => '0822222222',
        'lead_source' => 'instagram',
        'interaction_type' => 'chat_ig',
        'lead_stage' => 'cold',
        'notes' => 'Follow up masa lalu',
    ]);

    Livewire::test(DailyActivityReportsIndex::class)
        ->assertSee('Klien Prospek Hari Ini')
        ->assertSee('Klien Dua Bulan Lalu')
        ->set('datePeriod', 'today')
        ->assertSee('Klien Prospek Hari Ini')
        ->assertDontSee('Klien Dua Bulan Lalu')
        ->call('resetFilters')
        ->assertSet('datePeriod', 'all')
        ->assertSee('Klien Dua Bulan Lalu');
});

test('official documents module filters by date period correctly', function () {
    $this->actingAs($this->founder);

    $project = Project::create([
        'name' => 'Kavling Harmoni SPP',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'total_project_price' => 500000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'SPP-01',
        'land_area' => 100,
        'building_area' => 0,
        'category' => 'standar',
        'base_price' => 50000000,
        'final_selling_price' => 50000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    $proposalA = \App\Models\PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 40000000,
        'proposed_price' => 50000000,
        'margin' => 10000000,
        'proposed_by' => $this->founder->id,
        'status' => 'disetujui',
    ]);

    $docToday = OfficialDocument::create([
        'document_type' => 'spp',
        'document_number' => 'SPP/2026/001',
        'unit_id' => $unit->id,
        'price_proposal_id' => $proposalA->id,
        'issued_by' => $this->founder->id,
        'buyer_name' => 'Pembeli SPP Hari Ini',
        'buyer_contact' => '081234567890',
        'issued_at' => Carbon::today()->toDateString(),
    ]);

    $docPast = OfficialDocument::create([
        'document_type' => 'spp',
        'document_number' => 'SPP/2026/002',
        'unit_id' => $unit->id,
        'price_proposal_id' => $proposalA->id,
        'issued_by' => $this->founder->id,
        'buyer_name' => 'Pembeli SPP Lampau',
        'buyer_contact' => '081234567899',
        'issued_at' => Carbon::now()->subMonths(2)->toDateString(),
    ]);

    Livewire::test(DocumentsIndex::class)
        ->assertSee('Pembeli SPP Hari Ini')
        ->assertSee('Pembeli SPP Lampau')
        ->set('datePeriod', 'today')
        ->assertSee('Pembeli SPP Hari Ini')
        ->assertDontSee('Pembeli SPP Lampau')
        ->set('datePeriod', 'all')
        ->assertSee('Pembeli SPP Lampau');
});

test('manual invoices module filters by date period correctly', function () {
    $this->actingAs($this->founder);

    $invToday = ManualInvoice::create([
        'invoice_number' => 'INV/MAN/TODAY/01',
        'recipient_name' => 'Klien Manual Hari Ini',
        'recipient_phone' => '081234567890',
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 10000000,
        'invoice_date' => Carbon::today()->toDateString(),
        'status' => 'lunas',
        'created_by' => $this->founder->id,
    ]);

    $invPast = ManualInvoice::create([
        'invoice_number' => 'INV/MAN/PAST/01',
        'recipient_name' => 'Klien Manual Lampau',
        'recipient_phone' => '081234567899',
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 10000000,
        'invoice_date' => Carbon::now()->subMonths(2)->toDateString(),
        'status' => 'lunas',
        'created_by' => $this->founder->id,
    ]);

    Livewire::test(ManualInvoicesIndex::class)
        ->assertSee('Klien Manual Hari Ini')
        ->assertSee('Klien Manual Lampau')
        ->set('datePeriod', 'today')
        ->assertSee('Klien Manual Hari Ini')
        ->assertDontSee('Klien Manual Lampau')
        ->set('datePeriod', 'all')
        ->assertSee('Klien Manual Lampau');
});

test('payables module filters material bills by date period correctly', function () {
    $this->actingAs($this->founder);

    $matToday = WeeklyMaterialPurchase::create([
        'store_name' => 'Toko Semen Hari Ini',
        'item_name' => 'Semen Padang 50 Sak',
        'quantity' => 50,
        'unit_price' => 70000,
        'total_price' => 3500000,
        'purchase_date' => Carbon::today()->toDateString(),
        'payment_status' => 'belum_lunas',
        'pengawas_id' => $this->founder->id,
        'created_by' => $this->founder->id,
    ]);

    $matPast = WeeklyMaterialPurchase::create([
        'store_name' => 'Toko Pasir Lampau',
        'item_name' => 'Pasir 5 Truk',
        'quantity' => 5,
        'unit_price' => 1000000,
        'total_price' => 5000000,
        'purchase_date' => Carbon::now()->subMonths(2)->toDateString(),
        'payment_status' => 'belum_lunas',
        'pengawas_id' => $this->founder->id,
        'created_by' => $this->founder->id,
    ]);

    Livewire::test(PayablesIndex::class)
        ->set('activeTab', 'material_bills')
        ->assertSee('Toko Semen Hari Ini')
        ->assertSee('Toko Pasir Lampau')
        ->set('datePeriod', 'today')
        ->assertSee('Toko Semen Hari Ini')
        ->assertDontSee('Toko Pasir Lampau')
        ->set('datePeriod', 'all')
        ->assertSee('Toko Pasir Lampau');
});

test('employee salaries payments tab filters by date period correctly', function () {
    $this->actingAs($this->founder);

    $salary = EmployeeSalary::create([
        'employee_name' => 'Ahmad Teknisi',
        'position' => 'Teknisi Proyek',
        'basic_salary' => 4000000,
        'allowance' => 500000,
        'created_by' => $this->founder->id,
    ]);

    $payToday = EmployeePayrollPayment::create([
        'employee_salary_id' => $salary->id,
        'payroll_month' => (int) Carbon::today()->month,
        'payroll_year' => (int) Carbon::today()->year,
        'payment_date' => Carbon::today()->toDateString(),
        'basic_salary' => 4000000,
        'allowance' => 500000,
        'bonus' => 0,
        'deductions' => 0,
        'net_salary' => 4500000,
        'payment_method' => 'transfer',
        'created_by' => $this->founder->id,
    ]);

    $payPast = EmployeePayrollPayment::create([
        'employee_salary_id' => $salary->id,
        'payroll_month' => (int) Carbon::now()->subMonths(2)->month,
        'payroll_year' => (int) Carbon::now()->subMonths(2)->year,
        'payment_date' => Carbon::now()->subMonths(2)->toDateString(),
        'basic_salary' => 4000000,
        'allowance' => 500000,
        'bonus' => 0,
        'deductions' => 0,
        'net_salary' => 4500000,
        'payment_method' => 'transfer',
        'created_by' => $this->founder->id,
    ]);

    Livewire::test(EmployeeSalariesIndex::class)
        ->set('activeTab', 'payments')
        ->set('selected_month', '')
        ->set('selected_year', '')
        ->set('datePeriod', 'today')
        ->assertSee(format_id_date($payToday->payment_date))
        ->assertDontSee(format_id_date($payPast->payment_date));
});
