<?php

use App\Models\CashflowTransaction;
use App\Models\ManualInvoice;
use App\Models\Project;
use App\Models\User;
use App\Livewire\ManualInvoices\Index as ManualInvoicesIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

test('founder and finance can create manual invoice and sync automatically with cashflow transactions', function () {
    Role::findOrCreate('founder', 'web');

    $founder = User::create([
        'name' => 'Founder Invoice',
        'email' => 'founder_manual_inv@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Kavling Utama',
        'location' => 'Malang',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    Livewire::actingAs($founder)
        ->test(ManualInvoicesIndex::class)
        ->call('openCreateModal')
        ->set('project_id', $project->id)
        ->set('recipient_name', 'PT Citra Perkasa')
        ->set('amount', 25000000)
        ->set('type', 'masuk')
        ->set('category', 'penjualan_unit')
        ->set('invoice_date', now()->toDateString())
        ->set('payment_method', 'Transfer Bank')
        ->set('status', 'lunas')
        ->set('record_cashflow', true)
        ->call('saveInvoice')
        ->assertHasNoErrors();

    $invoice = ManualInvoice::where('recipient_name', 'PT Citra Perkasa')->first();
    expect($invoice)->not->toBeNull();
    expect((float)$invoice->amount)->toBe(25000000.0);
    expect($invoice->project_id)->toBe($project->id);

    // Verify CashflowTransaction automatically recorded
    $cashflow = CashflowTransaction::where('reference_type', ManualInvoice::class)
        ->where('reference_id', $invoice->id)
        ->first();

    expect($cashflow)->not->toBeNull();
    expect((float)$cashflow->amount)->toBe(25000000.0);
    expect($cashflow->type)->toBe('masuk');

    // Verify PDF Stream
    $pdfResponse = $this->actingAs($founder)->get(route('manual-invoices.pdf', $invoice->uuid));
    $pdfResponse->assertStatus(200);
    $pdfResponse->assertHeader('content-type', 'application/pdf');

    // Verify Guest Public Verification Page
    $guestResponse = $this->get(route('verify.manual-invoice', $invoice->uuid));
    $guestResponse->assertStatus(200);
    $guestResponse->assertSee('Invoice Manual Terverifikasi Resmi');
    $guestResponse->assertSee('PT Citra Perkasa');
});
