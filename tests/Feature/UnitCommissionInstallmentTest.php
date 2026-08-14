<?php

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitCommission;
use App\Models\UnitCommissionPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
    $this->finance = User::factory()->create(['role' => 'finance']);

    $this->project = Project::create([
        'name' => 'Kavling Unit Detail Commission Test',
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
        'code' => 'BLOK-COMM-01',
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
});

test('user can record unit commission from unit detail page and process installment payments', function () {
    $this->actingAs($this->founder);

    // 1. Record Unit Commission from Unit Detail Livewire Component
    Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
        ->call('openCommissionModal')
        ->set('unit_comm_seller_name', 'Agus Marketing Freelance')
        ->set('unit_comm_seller_phone', '08123456789')
        ->set('unit_comm_percentage', 3.0)
        ->set('unit_comm_amount', 4500000)
        ->set('unit_comm_notes', 'Komisi 3% Penjualan Unit BLOK-COMM-01')
        ->call('saveCommission')
        ->assertHasNoErrors();

    $comm = UnitCommission::where('seller_name', 'Agus Marketing Freelance')->first();
    expect($comm)->not->toBeNull();
    expect($comm->status)->toBe('belum_dibayar');
    expect((float)$comm->commission_amount)->toBe(4500000.0);
    expect((float)$comm->paid_amount)->toBe(0.0);
    expect((float)$comm->remaining_amount)->toBe(4500000.0);

    // 2. Pay Installment 1: Rp 2.000.000
    Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
        ->call('openCommissionPaymentModal', $comm->id)
        ->set('unit_pay_comm_amount', 2000000)
        ->set('unit_pay_comm_date', '2026-08-14')
        ->set('unit_pay_comm_method', 'Transfer Bank')
        ->call('processCommissionPayment')
        ->assertHasNoErrors();

    $comm->refresh();
    expect($comm->status)->toBe('berjalan');
    expect((float)$comm->paid_amount)->toBe(2000000.0);
    expect((float)$comm->remaining_amount)->toBe(2500000.0);

    // Verify Outgoing Cashflow (Kas Keluar)
    $cashflow1 = CashflowTransaction::where('reference_type', UnitCommissionPayment::class)
        ->where('amount', 2000000)
        ->first();
    expect($cashflow1)->not->toBeNull();
    expect($cashflow1->type)->toBe('keluar');

    // 3. Pay Installment 2: Pelunasan Sisa Rp 2.500.000
    Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
        ->call('openCommissionPaymentModal', $comm->id)
        ->set('unit_pay_comm_amount', 2500000)
        ->set('unit_pay_comm_date', '2026-08-20')
        ->set('unit_pay_comm_method', 'Transfer Bank')
        ->call('processCommissionPayment')
        ->assertHasNoErrors();

    $comm->refresh();
    expect($comm->status)->toBe('lunas');
    expect((float)$comm->paid_amount)->toBe(4500000.0);
    expect((float)$comm->remaining_amount)->toBe(0.0);
});

test('overpaying commission installment beyond remaining amount is rejected by validation', function () {
    $this->actingAs($this->finance);

    $comm = UnitCommission::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'seller_name' => 'Budi Marketing',
        'percentage' => 2.0,
        'commission_amount' => 3000000,
        'paid_amount' => 0,
        'status' => 'belum_dibayar',
        'created_by' => $this->founder->id,
    ]);

    Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
        ->call('openCommissionPaymentModal', $comm->id)
        ->set('unit_pay_comm_amount', 5000000) // Exceeds 3,000,000
        ->call('processCommissionPayment')
        ->assertHasErrors(['unit_pay_comm_amount' => 'max']);

    $comm->refresh();
    expect((float)$comm->paid_amount)->toBe(0.0);
});
