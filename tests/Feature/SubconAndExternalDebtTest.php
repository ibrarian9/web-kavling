<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CashflowTransaction;
use App\Models\CompanyDebt;
use App\Models\CompanyDebtPayment;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
    $this->finance = User::factory()->create(['role' => 'finance']);
    $this->pengawas = User::factory()->create(['role' => 'pengawas_project']);
    $this->marketing = User::factory()->create(['role' => 'marketing']);

    $this->project = Project::create([
        'name' => 'Kavling Harmoni Subcon Test',
        'location' => 'Malang',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'total_project_price' => 400000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);
});

test('founder or finance can record subcon and outside-project debt', function () {
    $this->actingAs($this->founder);

    // 1. Utang Sub-kon dengan Proyek
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openCreateDebtModal')
        ->set('debt_creditor_type', 'subkon')
        ->set('debt_creditor_name', 'CV Maju Jaya Sub-kon')
        ->set('debt_creditor_phone', '08123456789')
        ->set('debt_project_id', $this->project->id)
        ->set('debt_title', 'Borongan Pasang Saluran & Paving')
        ->set('debt_amount', 20000000)
        ->set('debt_date', '2026-09-01')
        ->set('debt_due_date', '2026-09-30')
        ->set('debt_notes', 'Termin 3 tahap')
        ->call('saveDebt')
        ->assertHasNoErrors();

    $debt1 = CompanyDebt::where('creditor_name', 'CV Maju Jaya Sub-kon')->first();
    expect($debt1)->not->toBeNull();
    expect($debt1->project_id)->toBe($this->project->id);
    expect($debt1->creditor_type)->toBe('subkon');
    expect((float)$debt1->amount)->toBe(20000000.0);
    expect((float)$debt1->paid_amount)->toBe(0.0);
    expect($debt1->status)->toBe('belum_lunas');

    // 2. Utang Vendor di Luar Proyek (project_id null)
    $this->actingAs($this->finance);
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openCreateDebtModal')
        ->set('debt_creditor_type', 'vendor')
        ->set('debt_creditor_name', 'PT Sewa Genset Nusantara')
        ->set('debt_project_id', '') // Di Luar Proyek
        ->set('debt_title', 'Pengadaan Genset Cadangan Luar Proyek')
        ->set('debt_amount', 8500000)
        ->set('debt_date', '2026-09-02')
        ->call('saveDebt')
        ->assertHasNoErrors();

    $debt2 = CompanyDebt::where('creditor_name', 'PT Sewa Genset Nusantara')->first();
    expect($debt2)->not->toBeNull();
    expect($debt2->project_id)->toBeNull();
    expect($debt2->creditor_type)->toBe('vendor');
    expect((float)$debt2->amount)->toBe(8500000.0);
    expect($debt2->status)->toBe('belum_lunas');
});

test('paying installment on debt creates cashflow keluar and updates balance correctly', function () {
    $this->actingAs($this->founder);

    $debt = CompanyDebt::create([
        'creditor_type' => 'subkon',
        'creditor_name' => 'Pak Joko Sub-kon Atap Baja',
        'project_id' => $this->project->id,
        'title' => 'Rangka Baja Ringan Rumah Contoh',
        'amount' => 15000000,
        'paid_amount' => 0,
        'debt_date' => '2026-09-01',
        'status' => 'belum_lunas',
        'created_by' => $this->founder->id,
    ]);

    // Cicilan Termin 1: Rp 5.000.000
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openPayDebtModal', $debt->id)
        ->set('pay_debt_amount', 5000000)
        ->set('pay_debt_date', '2026-09-03')
        ->set('pay_debt_method', 'Transfer Bank')
        ->set('pay_debt_notes', 'Pembayaran Termin 1')
        ->call('processDebtPayment')
        ->assertHasNoErrors();

    $debt->refresh();
    expect((float)$debt->paid_amount)->toBe(5000000.0);
    expect($debt->remaining_amount)->toBe(10000000.0);
    expect($debt->status)->toBe('belum_lunas');

    // Verifikasi Kas Keluar Global
    $cashflow1 = CashflowTransaction::where('reference_type', CompanyDebtPayment::class)->first();
    expect($cashflow1)->not->toBeNull();
    expect($cashflow1->type)->toBe('keluar');
    expect((float)$cashflow1->amount)->toBe(5000000.0);
    expect($cashflow1->category)->toBe('subkon');
    expect($cashflow1->project_id)->toBe($this->project->id);

    // Pelunasan Sisa: Rp 10.000.000
    $this->actingAs($this->finance);
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openPayDebtModal', $debt->id)
        ->set('pay_debt_amount', 10000000)
        ->set('pay_debt_date', '2026-09-05')
        ->set('pay_debt_method', 'Cash / Tunai')
        ->call('processDebtPayment')
        ->assertHasNoErrors();

    $debt->refresh();
    expect((float)$debt->paid_amount)->toBe(15000000.0);
    expect($debt->remaining_amount)->toBe(0.0);
    expect($debt->status)->toBe('lunas');

    expect(CashflowTransaction::where('reference_type', CompanyDebtPayment::class)->count())->toBe(2);
});

test('paying external non-project debt creates cashflow transaction with null project_id', function () {
    $this->actingAs($this->finance);

    $externalDebt = CompanyDebt::create([
        'creditor_type' => 'operasional_luar',
        'creditor_name' => 'Bapak Hendra (Talangan Alat Luar)',
        'project_id' => null, // Di Luar Proyek
        'title' => 'Talangan Sewa Excavator Tambahan',
        'amount' => 6000000,
        'paid_amount' => 0,
        'debt_date' => '2026-09-02',
        'status' => 'belum_lunas',
        'created_by' => $this->finance->id,
    ]);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openPayDebtModal', $externalDebt->id)
        ->set('pay_debt_amount', 6000000)
        ->set('pay_debt_date', '2026-09-04')
        ->call('processDebtPayment');

    $externalDebt->refresh();
    expect($externalDebt->status)->toBe('lunas');

    $cf = CashflowTransaction::where('reference_type', CompanyDebtPayment::class)->latest('id')->first();
    expect($cf)->not->toBeNull();
    expect($cf->project_id)->toBeNull(); // Kas Keluar Global Non-Proyek
    expect($cf->type)->toBe('keluar');
    expect((float)$cf->amount)->toBe(6000000.0);
});

test('unauthorized users cannot create or pay debt', function () {
    $this->actingAs($this->pengawas);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openCreateDebtModal')
        ->set('debt_creditor_name', 'Hacker Sub-kon')
        ->set('debt_amount', 50000000)
        ->call('saveDebt');

    expect(CompanyDebt::where('creditor_name', 'Hacker Sub-kon')->first())->toBeNull();
});

test('super admin can delete debt and related cashflows are removed', function () {
    $this->actingAs($this->founder);

    $debt = CompanyDebt::create([
        'creditor_type' => 'vendor',
        'creditor_name' => 'Toko Alat Bangunan',
        'title' => 'Pembelian Mesin',
        'amount' => 3000000,
        'paid_amount' => 3000000,
        'debt_date' => '2026-09-01',
        'status' => 'lunas',
        'created_by' => $this->founder->id,
    ]);

    $payment = CompanyDebtPayment::create([
        'company_debt_id' => $debt->id,
        'payment_date' => '2026-09-02',
        'amount' => 3000000,
        'payment_method' => 'Cash / Tunai',
        'created_by' => $this->founder->id,
    ]);

    CashflowTransaction::create([
        'type' => 'keluar',
        'category' => 'operasional',
        'amount' => 3000000,
        'transaction_date' => '2026-09-02',
        'description' => 'Test bayar',
        'reference_type' => CompanyDebtPayment::class,
        'reference_id' => $payment->id,
        'created_by' => $this->founder->id,
    ]);

    expect(CompanyDebt::count())->toBe(1);
    expect(CashflowTransaction::where('reference_type', CompanyDebtPayment::class)->count())->toBe(1);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('deleteDebt', $debt->id);

    expect(CompanyDebt::count())->toBe(0);
    expect(CompanyDebtPayment::count())->toBe(0);
    expect(CashflowTransaction::where('reference_type', CompanyDebtPayment::class)->count())->toBe(0);
});
