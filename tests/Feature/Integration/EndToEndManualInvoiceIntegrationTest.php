<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\ManualInvoice;
use App\Models\CashflowTransaction;
use App\Livewire\ManualInvoices\Index as ManualInvoiceIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
});

test('end to end manual invoice workflow: create draft invoice -> update to paid -> verify automatic cashflow sync -> stream invoice pdf', function () {
    // 1. Setup Users
    $founder = User::create([
        'name' => 'Founder Invoice E2E',
        'email' => 'founder_inv_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $finance = User::create([
        'name' => 'Finance Invoice E2E',
        'email' => 'finance_inv_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    $project = Project::create([
        'name' => 'Proyek Invoice Properti',
        'location' => 'Jl. Tagihan No 1',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 100000000,
        'created_by' => $founder->id,
    ]);

    // 2. Finance Creates Pending Invoice (Rp 15,000,000)
    Livewire::actingAs($finance)
        ->test(ManualInvoiceIndex::class)
        ->call('openCreateModal')
        ->set('project_id', $project->id)
        ->set('recipient_name', 'PT. Konstruksi Jaya Utama')
        ->set('recipient_phone', '081199887766')
        ->set('type', 'masuk')
        ->set('category', 'penjualan_unit')
        ->set('amount', 15000000)
        ->set('invoice_date', now()->toDateString())
        ->set('payment_method', 'Transfer Bank')
        ->set('status', 'pending')
        ->set('record_cashflow', true)
        ->call('saveInvoice')
        ->assertHasNoErrors();

    $invoice = ManualInvoice::where('recipient_name', 'PT. Konstruksi Jaya Utama')->first();
    expect($invoice)->not->toBeNull();
    expect($invoice->status)->toBe('pending');

    // Pending status MUST NOT create CashflowTransaction
    $cashflowPending = CashflowTransaction::where('reference_type', ManualInvoice::class)
        ->where('reference_id', $invoice->id)
        ->first();
    expect($cashflowPending)->toBeNull();

    // 3. Finance Updates Invoice Status to Paid (Lunas)
    Livewire::actingAs($finance)
        ->test(ManualInvoiceIndex::class)
        ->call('editInvoice', $invoice->id)
        ->set('status', 'lunas')
        ->call('saveInvoice')
        ->assertHasNoErrors();

    expect($invoice->fresh()->status)->toBe('lunas');

    // Paid status MUST automatically sync CashflowTransaction
    $cashflowPaid = CashflowTransaction::where('reference_type', ManualInvoice::class)
        ->where('reference_id', $invoice->id)
        ->first();
    expect($cashflowPaid)->not->toBeNull();
    expect((float)$cashflowPaid->amount)->toBe(15000000.0);
    expect($cashflowPaid->type)->toBe('masuk');

    // 4. Stream PDF Invoice Route
    $pdfResponse = $this->actingAs($finance)->get(route('manual-invoices.pdf', $invoice->uuid));
    $pdfResponse->assertStatus(200);

    // 5. Delete Invoice & Verify Cashflow Cleanup
    Livewire::actingAs($founder)
        ->test(ManualInvoiceIndex::class)
        ->call('deleteInvoice', $invoice->id)
        ->assertHasNoErrors();

    expect(ManualInvoice::find($invoice->id))->toBeNull();
    expect(CashflowTransaction::where('reference_type', ManualInvoice::class)->where('reference_id', $invoice->id)->exists())->toBeFalse();
});
