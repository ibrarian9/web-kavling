<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\CashflowTransaction;
use App\Livewire\Cashflow\Index as CashflowIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
});

test('end to end cashflow reporting workflow: aggregate income & expense transactions -> filter cashflow -> create manual transaction -> export report', function () {
    // 1. Setup Users
    $founder = User::create([
        'name' => 'Founder Cashflow E2E',
        'email' => 'founder_cf_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $finance = User::create([
        'name' => 'Finance Cashflow E2E',
        'email' => 'finance_cf_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    $project = Project::create([
        'name' => 'Proyek Arus Kas Utama',
        'location' => 'Jl. Keuangan No. 88',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    // 2. Pre-populate Income & Expense Transactions
    CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 50000000.00,
        'transaction_date' => now()->toDateString(),
        'description' => 'Penjualan Unit A1',
        'created_by' => $finance->id,
    ]);

    CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'keluar',
        'category' => 'pembelian_material',
        'amount' => 12000000.00,
        'transaction_date' => now()->toDateString(),
        'description' => 'Pembelian Besi & Semen',
        'created_by' => $finance->id,
    ]);

    // 3. Finance Creates Manual Cashflow Inflow Transaction (Rp 5,000,000)
    Livewire::actingAs($finance)
        ->test(CashflowIndex::class)
        ->call('openManualModal')
        ->set('project_id', $project->id)
        ->set('type', 'masuk')
        ->set('category', 'lainnya')
        ->set('amount', 5000000)
        ->set('transaction_date', now()->toDateString())
        ->set('description', 'Sisa dana operasional dikembalikan')
        ->call('saveTransaction')
        ->assertHasNoErrors();

    $manualTx = CashflowTransaction::where('description', 'Sisa dana operasional dikembalikan')->first();
    expect($manualTx)->not->toBeNull();
    expect((float)$manualTx->amount)->toBe(5000000.0);

    // 4. Test Cashflow Index Filter & Detail Modal
    Livewire::actingAs($founder)
        ->test(CashflowIndex::class)
        ->set('filter_project_id', $project->id)
        ->set('filter_month', now()->format('Y-m'))
        ->call('openDetailModal', $manualTx->id)
        ->assertSet('selectedTransactionId', $manualTx->id)
        ->assertSet('showDetailModal', true);

    // 5. Test Export PDF & Excel Routes
    $pdfUrl = route('cashflow.export-pdf', ['project_id' => $project->id, 'month' => now()->format('Y-m')]);
    $pdfResponse = $this->actingAs($founder)->get($pdfUrl);
    $pdfResponse->assertStatus(200);

    $excelUrl = route('cashflow.export-excel', ['project_id' => $project->id, 'month' => now()->format('Y-m')]);
    $excelResponse = $this->actingAs($founder)->get($excelUrl);
    $excelResponse->assertStatus(200);
});
