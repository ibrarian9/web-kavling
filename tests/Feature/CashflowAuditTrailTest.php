<?php

use App\Models\CashflowTransaction;
use App\Models\ManualInvoice;
use App\Models\Project;
use App\Models\User;
use App\Livewire\Cashflow\Index as CashflowIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('finance', 'web');
});

test('finance and founder can view audit trail details of cashflow transactions', function () {
    $founder = User::create([
        'name' => 'Founder Audit',
        'email' => 'founder_audit@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Audit Test',
        'location' => 'Malang',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    $invoice = ManualInvoice::create([
        'recipient_name' => 'Klien Audit Test',
        'amount' => 15000000,
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'invoice_date' => now()->toDateString(),
        'payment_method' => 'Transfer Bank',
        'status' => 'lunas',
        'record_cashflow' => true,
        'created_by' => $founder->id,
    ]);

    $trx = CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 15000000,
        'transaction_date' => now()->toDateString(),
        'description' => 'Invoice Manual [' . $invoice->invoice_number . ']: Klien Audit Test',
        'reference_type' => ManualInvoice::class,
        'reference_id' => $invoice->id,
        'created_by' => $founder->id,
    ]);

    Livewire::actingAs($founder)
        ->test(CashflowIndex::class)
        ->call('openDetailModal', $trx->id)
        ->assertSet('showDetailModal', true)
        ->assertSet('selectedTransactionId', $trx->id)
        ->assertSee('Detail Alur Keuangan')
        ->assertSee('Founder Audit')
        ->assertSee('Klien Audit Test')
        ->assertSee('Menu Invoice Manual')
        ->assertSee('Waktu System')
        ->call('closeDetailModal')
        ->assertSet('showDetailModal', false);
});
