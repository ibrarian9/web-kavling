<?php

use App\Livewire\Cashflow\Index as CashflowIndex;
use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'finance', 'guard_name' => 'web']);

    $this->founder = User::create([
        'name' => 'Marwansyah',
        'email' => 'marwan@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $this->founder->assignRole('founder');

    $this->project = Project::create([
        'name' => 'Barokah Village Stadium',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 120000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->transaction = CashflowTransaction::create([
        'project_id' => $this->project->id,
        'created_by' => $this->founder->id,
        'type' => 'keluar',
        'category' => 'operasional',
        'amount' => 300000,
        'transaction_date' => now()->toDateString(),
        'description' => 'Pembelian Material Unit POS SEKURITY (Toko: Wahyu tiang listrik): Pengecoran tiang listrik (1 pcs)',
        'reference_type' => 'App\Models\WeeklyMaterialPurchase',
        'reference_id' => 205,
    ]);
});

test('cashflow index renders mobile card list and desktop table correctly', function () {
    $this->actingAs($this->founder);

    Livewire::test(CashflowIndex::class)
        ->assertStatus(200)
        ->assertSeeHtml('block md:hidden')
        ->assertSeeHtml('hidden md:block')
        ->assertSee('Barokah Village Stadium')
        ->assertSee('Pembelian Material Unit POS SEKURITY')
        ->assertSee('Ref: WeeklyMaterialPurchase #205')
        ->assertSee('Rp 300.000')
        ->assertSee('Marwansyah');
});

test('cashflow index modal detail opens and displays transaction audit trail', function () {
    $this->actingAs($this->founder);

    Livewire::test(CashflowIndex::class)
        ->call('openDetailModal', $this->transaction->id)
        ->assertSet('showDetailModal', true)
        ->assertSee('Detail Alur Keuangan & Audit Trail')
        ->assertSee('Nomor Mutasi: #TRX-' . $this->transaction->id);
});
