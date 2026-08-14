<?php

use App\Models\CashflowTransaction;
use App\Models\CompanyReceivable;
use App\Models\Project;
use App\Models\ReceivablePayment;
use App\Models\Unit;
use App\Models\UnitCommission;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerUnitPayroll;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
    $this->finance = User::factory()->create(['role' => 'finance']);
    $this->pengawas = User::factory()->create(['role' => 'pengawas_project']);

    $this->project = Project::create([
        'name' => 'Kavling Settled History Test',
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
        'code' => 'BLOK-HIS-01',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 80000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    $this->worker = Worker::create([
        'name' => 'Pak Wagiman',
        'type' => 'tukang',
        'daily_rate' => 150000,
        'status' => 'active',
    ]);
});

test('tab 5 renders global settled history for material, wages, commissions and receivables', function () {
    $this->actingAs($this->founder);

    // Create a settled material purchase
    WeeklyMaterialPurchase::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'pengawas_id' => $this->founder->id,
        'purchase_date' => '2026-08-10',
        'item_name' => 'Batu Bata Merah 5000 Pcs',
        'store_name' => 'Toko Bata Maju',
        'quantity' => 5000,
        'unit_measure' => 'pcs',
        'unit_price' => 800,
        'total_price' => 4000000,
        'payment_status' => 'lunas',
        'paid_at' => '2026-08-10',
    ]);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->set('activeTab', 'settled_history')
        ->assertStatus(200)
        ->assertSee('Batu Bata Merah 5000 Pcs')
        ->assertSee('LUNAS TOKO');
});

test('founder can delete material purchase and cleanup cashflow transaction', function () {
    $this->actingAs($this->founder);

    $mat = WeeklyMaterialPurchase::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'pengawas_id' => $this->founder->id,
        'purchase_date' => '2026-08-12',
        'item_name' => 'Semen Padang 20 Sak',
        'store_name' => 'TB Subur',
        'quantity' => 20,
        'unit_measure' => 'sak',
        'unit_price' => 65000,
        'total_price' => 1300000,
        'payment_status' => 'belum_lunas',
    ]);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('deleteMaterialPurchase', $mat->id)
        ->assertHasNoErrors();

    expect(WeeklyMaterialPurchase::find($mat->id))->toBeNull();
});

test('founder can delete receivable kasbon and cleanup payment records', function () {
    $this->actingAs($this->founder);

    $rec = CompanyReceivable::create([
        'debtor_type' => 'worker',
        'debtor_name' => 'Wagiman Kasbon Motor',
        'worker_id' => $this->worker->id,
        'amount' => 1000000,
        'paid_amount' => 0,
        'loan_date' => '2026-08-01',
        'status' => 'belum_lunas',
        'created_by' => $this->founder->id,
    ]);

    Livewire::test(\App\Livewire\Payables\Index::class)
        ->call('deleteReceivable', $rec->id)
        ->assertHasNoErrors();

    expect(CompanyReceivable::find($rec->id))->toBeNull();
});
