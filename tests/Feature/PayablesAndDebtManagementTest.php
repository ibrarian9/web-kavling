<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerUnitPayroll;
use Livewire\Livewire;

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
    $this->finance = User::factory()->create(['role' => 'finance']);
    $this->pengawas = User::factory()->create(['role' => 'pengawas_project']);

    $this->project = Project::create([
        'name' => 'Kavling Harmoni Payables Test',
        'location' => 'Malang',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'total_project_price' => 400000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'A-01-PAYABLE',
        'category' => 'kavling',
        'surface_area' => 100,
        'price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    $this->worker = Worker::create([
        'name' => 'Pak Slamet Tukang',
        'type' => 'tukang',
        'specialty' => 'Pondasi',
        'daily_rate' => 150000,
        'status' => 'active',
    ]);
});

test('material purchase marked as HUTANG TOKO does not create direct cashflow transaction', function () {
    $this->actingAs($this->pengawas);

    $purchase = WeeklyMaterialPurchase::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'worker_id' => $this->worker->id,
        'pengawas_id' => $this->pengawas->id,
        'purchase_date' => '2026-08-13',
        'item_name' => 'Semen Gresik 50 Sak',
        'store_name' => 'TB Harapan Jaya',
        'quantity' => 50,
        'unit_measure' => 'sak',
        'unit_price' => 65000,
        'total_price' => 3250000,
        'payment_status' => 'belum_lunas',
    ]);

    expect($purchase)->not->toBeNull();
    expect($purchase->payment_status)->toBe('belum_lunas');
    expect($purchase->store_name)->toBe('TB Harapan Jaya');
    expect((float)$purchase->total_price)->toBe(3250000.0);

    // Verify Cashflow Transaction was NOT created for unpaid debt
    $cashflow = CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
        ->where('reference_id', $purchase->id)
        ->first();
    expect($cashflow)->toBeNull();
});

test('founder or finance can settle material debt in payables menu and create cashflow transaction', function () {
    $purchase = WeeklyMaterialPurchase::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'worker_id' => $this->worker->id,
        'pengawas_id' => $this->pengawas->id,
        'purchase_date' => '2026-08-13',
        'item_name' => 'Besi Beton 10mm 100 Btg',
        'store_name' => 'TB Maju Jaya',
        'quantity' => 100,
        'unit_measure' => 'btg',
        'unit_price' => 85000,
        'total_price' => 8500000,
        'payment_status' => 'belum_lunas',
    ]);

    $this->actingAs($this->finance);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openSettleModal', $purchase->id)
        ->set('settle_payment_date', '2026-08-14')
        ->set('settle_payment_method', 'Transfer Bank')
        ->set('settle_notes', 'Pelunasan via Transfer Mandiri')
        ->call('processMaterialSettlement');

    $purchase->refresh();
    expect($purchase->payment_status)->toBe('lunas');
    expect($purchase->paid_by)->toBe($this->finance->id);

    // Verify Cashflow Transaction WAS created automatically for settled debt
    $cashflow = CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
        ->where('reference_id', $purchase->id)
        ->first();

    expect($cashflow)->not->toBeNull();
    expect($cashflow->type)->toBe('keluar');
    expect((float)$cashflow->amount)->toBe(8500000.0);
    expect($cashflow->description)->toContain('Pelunasan Tagihan Material');
    expect($cashflow->description)->toContain('TB Maju Jaya');
});

test('paying worker wages in payables menu updates unpaid balance and creates cashflow transaction', function () {
    $payroll = WorkerUnitPayroll::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'worker_id' => $this->worker->id,
        'notes' => 'Pengecoran Pondasi & Sloof',
        'agreed_salary' => 5000000,
        'paid_amount' => 2000000,
        'status' => 'berjalan',
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->set('activeTab', 'worker_payrolls')
        ->call('openWorkerPaymentModal', $payroll->id)
        ->set('worker_payment_amount', 3000000)
        ->set('worker_payment_date', '2026-08-14')
        ->set('worker_payment_method', 'Transfer Bank')
        ->call('processWorkerPayment');

    $payroll->refresh();
    expect((float)$payroll->paid_amount)->toBe(5000000.0);
    expect($payroll->status)->toBe('lunas');

    // Verify Cashflow Transaction WAS created for worker wage payment
    $cashflow = CashflowTransaction::where('category', 'upah_tukang')
        ->where('amount', 3000000)
        ->first();

    expect($cashflow)->not->toBeNull();
    expect($cashflow->type)->toBe('keluar');
    expect($cashflow->description)->toContain('Pengecoran Pondasi & Sloof');
});

test('can record and settle non-project general operational debt', function () {
    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openCreateBillModal')
        ->set('new_project_id', '')
        ->set('new_unit_id', '')
        ->set('new_store_name', 'PLN / Telkom Corporate')
        ->set('new_item_name', 'Tagihan Internet & Listrik Kantor Utama')
        ->set('new_quantity', 1)
        ->set('new_unit_measure', 'paket')
        ->set('new_unit_price', 1250000)
        ->set('new_purchase_date', '2026-08-13')
        ->set('new_payment_status', 'belum_lunas')
        ->call('saveNewBill');

    $purchase = WeeklyMaterialPurchase::where('item_name', 'Tagihan Internet & Listrik Kantor Utama')->first();
    expect($purchase)->not->toBeNull();
    expect($purchase->project_id)->toBeNull();
    expect($purchase->payment_status)->toBe('belum_lunas');

    // Settle non-project debt
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openSettleModal', $purchase->id)
        ->set('settle_payment_date', '2026-08-14')
        ->set('settle_payment_method', 'Transfer Bank')
        ->call('processMaterialSettlement');

    $purchase->refresh();
    expect($purchase->payment_status)->toBe('lunas');

    // Verify Cashflow Transaction created with project_id = null (Global Cashflow)
    $cashflow = CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
        ->where('reference_id', $purchase->id)
        ->first();

    expect($cashflow)->not->toBeNull();
    expect($cashflow->project_id)->toBeNull();
    expect((float)$cashflow->amount)->toBe(1250000.0);
});

test('can record and settle unit seller commission creating cashflow expense', function () {
    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openCreateCommissionModal')
        ->set('comm_project_id', $this->project->id)
        ->set('comm_unit_id', $this->unit->id)
        ->set('comm_seller_name', 'Budi Marketing Partner')
        ->set('comm_seller_phone', '08123456789')
        ->set('comm_percentage', 2.5)
        ->set('comm_amount', 3750000)
        ->set('comm_notes', 'Komisi Penjualan Unit A-01-PAYABLE')
        ->call('saveCommission');

    $comm = \App\Models\UnitCommission::where('seller_name', 'Budi Marketing Partner')->first();
    expect($comm)->not->toBeNull();
    expect($comm->status)->toBe('belum_dibayar');
    expect((float)$comm->commission_amount)->toBe(3750000.0);

    // Settle Commission
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openSettleCommissionModal', $comm->id)
        ->set('settle_comm_date', '2026-08-14')
        ->set('settle_comm_method', 'Transfer Bank')
        ->call('processCommissionSettlement');

    $comm->refresh();
    expect($comm->status)->toBe('lunas');

    // Verify Cashflow Transaction (Kas Keluar)
    $cashflow = CashflowTransaction::where('reference_type', \App\Models\UnitCommissionPayment::class)
        ->first();

    expect($cashflow)->not->toBeNull();
    expect($cashflow->type)->toBe('keluar');
    expect((float)$cashflow->amount)->toBe(3750000.0);
    expect($cashflow->description)->toContain('Pembayaran Cicilan Komisi Penjual');
});

test('can record company receivable kasbon and process repayment creating cashflow income', function () {
    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openCreateReceivableModal')
        ->set('rec_debtor_type', 'worker')
        ->set('rec_worker_id', $this->worker->id)
        ->set('rec_debtor_name', 'Pak Slamet Tukang (Kasbon Operasional)')
        ->set('rec_amount', 1500000)
        ->set('rec_loan_date', '2026-08-10')
        ->set('rec_notes', 'Kasbon perbaikan motor mandor')
        ->call('saveReceivable');

    $rec = \App\Models\CompanyReceivable::where('debtor_name', 'Pak Slamet Tukang (Kasbon Operasional)')->first();
    expect($rec)->not->toBeNull();
    expect($rec->status)->toBe('belum_lunas');
    expect((float)$rec->amount)->toBe(1500000.0);

    // Process Repayment / Pengembalian Kasbon
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openPayReceivableModal', $rec->id)
        ->set('pay_rec_amount', 1500000)
        ->set('pay_rec_date', '2026-08-14')
        ->set('pay_rec_method', 'Cash / Tunai')
        ->call('processReceivablePayment');

    $rec->refresh();
    expect($rec->status)->toBe('lunas');
    expect((float)$rec->paid_amount)->toBe(1500000.0);

    // Verify Cashflow Transaction (Kas Masuk Global)
    $cashflow = CashflowTransaction::where('reference_type', \App\Models\ReceivablePayment::class)
        ->first();

    expect($cashflow)->not->toBeNull();
    expect($cashflow->type)->toBe('masuk');
    expect((float)$cashflow->amount)->toBe(1500000.0);
    expect($cashflow->description)->toContain('Pengembalian Piutang / Kasbon');
});

test('FATAL SCENARIO: unauthorized project supervisor cannot settle material debt or create commission', function () {
    $purchase = WeeklyMaterialPurchase::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'pengawas_id' => $this->pengawas->id,
        'purchase_date' => '2026-08-13',
        'item_name' => 'Batu Kali 1 Ret',
        'store_name' => 'Toko Batu Subur',
        'quantity' => 1,
        'unit_measure' => 'ret',
        'unit_price' => 750000,
        'total_price' => 750000,
        'payment_status' => 'belum_lunas',
    ]);

    // Acting as regular supervisor (not Founder or Finance)
    $this->actingAs($this->pengawas);

    // Attempt to settle material debt
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openSettleModal', $purchase->id)
        ->set('settle_payment_date', '2026-08-14')
        ->call('processMaterialSettlement');

    $purchase->refresh();
    expect($purchase->payment_status)->toBe('belum_lunas');

    // Attempt to create commission
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openCreateCommissionModal')
        ->set('comm_seller_name', 'Hacker Marketing')
        ->set('comm_amount', 10000000)
        ->call('saveCommission');

    $comm = \App\Models\UnitCommission::where('seller_name', 'Hacker Marketing')->first();
    expect($comm)->toBeNull();
});

test('FATAL SCENARIO: double settlement repeat invocation does not duplicate cashflow transactions', function () {
    $purchase = WeeklyMaterialPurchase::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'pengawas_id' => $this->pengawas->id,
        'purchase_date' => '2026-08-13',
        'item_name' => 'Pasir Pasang 2 Ret',
        'store_name' => 'TB Pasir Jaya',
        'quantity' => 2,
        'unit_measure' => 'ret',
        'unit_price' => 600000,
        'total_price' => 1200000,
        'payment_status' => 'belum_lunas',
    ]);

    $this->actingAs($this->founder);

    $component = Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openSettleModal', $purchase->id)
        ->set('settle_payment_date', '2026-08-14')
        ->call('processMaterialSettlement');

    // Repeat invocation simulating double click / race condition
    $component->call('processMaterialSettlement');

    $count = CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
        ->where('reference_id', $purchase->id)
        ->count();

    expect($count)->toBe(1);
});

test('STRANGE SCENARIO: overpaying receivable kasbon caps remaining debt and updates status to lunas', function () {
    $this->actingAs($this->founder);

    $rec = \App\Models\CompanyReceivable::create([
        'debtor_type' => 'other',
        'debtor_name' => 'Budi Marketing Overpay',
        'amount' => 500000,
        'paid_amount' => 0,
        'loan_date' => '2026-08-01',
        'status' => 'belum_lunas',
        'created_by' => $this->founder->id,
    ]);

    // Pay 1,000,000 for a 500,000 loan
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('openPayReceivableModal', $rec->id)
        ->set('pay_rec_amount', 1000000)
        ->set('pay_rec_date', '2026-08-14')
        ->call('processReceivablePayment');

    $rec->refresh();
    expect($rec->status)->toBe('lunas');
    expect((float)$rec->paid_amount)->toBe(1000000.0);
    expect($rec->remaining_amount)->toBe(0.0);
});

test('STRANGE SCENARIO: orphan commission or receivable with null project/unit gracefully renders without crashing', function () {
    $this->actingAs($this->founder);

    $comm = \App\Models\UnitCommission::create([
        'project_id' => null,
        'unit_id' => null,
        'seller_name' => 'Agen Tanpa Proyek Terikat',
        'commission_amount' => 2000000,
        'status' => 'belum_dibayar',
        'created_by' => $this->founder->id,
    ]);

    // Verify component renders smoothly without throwing Trying to get property of non-object
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->set('activeTab', 'unit_commissions')
        ->assertStatus(200)
        ->assertSee('Agen Tanpa Proyek Terikat');
});

test('FATAL SCENARIO: invalid negative or zero payment amount is rejected by validation', function () {
    $payroll = WorkerUnitPayroll::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'worker_id' => $this->worker->id,
        'agreed_salary' => 2000000,
        'paid_amount' => 0,
        'status' => 'berjalan',
    ]);

    $this->actingAs($this->finance);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->set('activeTab', 'worker_payrolls')
        ->call('openWorkerPaymentModal', $payroll->id)
        ->set('worker_payment_amount', -500000)
        ->call('processWorkerPayment')
        ->assertHasErrors(['worker_payment_amount' => 'min']);

    $payroll->refresh();
    expect((float)$payroll->paid_amount)->toBe(0.0);
});
