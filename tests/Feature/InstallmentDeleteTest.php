<?php

use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use App\Livewire\Installments\Index as InstallmentsIndex;
use Livewire\Livewire;

test('founder can delete installment scheme and individual payment in installments menu', function () {
    /** @var User $founder */
    $founder = User::create([
        'name' => 'Founder Installment Delete',
        'email' => 'founder_inst_del@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    /** @var Project $project */
    $project = Project::create([
        'name' => 'Proyek Test Cicilan Hapus',
        'location' => 'Jalan Permata',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    /** @var Unit $unit */
    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'HAPUS-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'terjual',
        'created_by' => $founder->id,
    ]);

    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'total_price' => 150000000,
        'down_payment' => 30000000,
        'installment_count' => 12,
        'installment_amount' => 10000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    $payment = InstallmentPayment::create([
        'unit_installment_id' => $installment->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 10000000,
        'payment_method' => 'Transfer Bank',
        'created_by' => $founder->id,
    ]);

    CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'masuk',
        'category' => 'pembayaran_cicilan_pembeli',
        'amount' => 10000000,
        'transaction_date' => now()->toDateString(),
        'description' => 'Setoran Cicilan Unit HAPUS-01',
        'reference_type' => InstallmentPayment::class,
        'reference_id' => $payment->id,
        'created_by' => $founder->id,
    ]);

    // Test 1: Founder can delete individual payment
    Livewire::actingAs($founder)
        ->test(InstallmentsIndex::class)
        ->call('deleteInstallmentPayment', $payment->id)
        ->assertHasNoErrors();

    expect(InstallmentPayment::find($payment->id))->toBeNull();

    // Test 2: Founder can delete entire installment scheme
    Livewire::actingAs($founder)
        ->test(InstallmentsIndex::class)
        ->call('deleteInstallment', $installment->id)
        ->assertHasNoErrors();

    expect(UnitInstallment::find($installment->id))->toBeNull();
});

test('founder can delete installment scheme on Units Show component', function () {
    /** @var User $founder */
    $founder = User::create([
        'name' => 'Founder Unit Show Delete',
        'email' => 'founder_unit_show_del@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    /** @var Project $project */
    $project = Project::create([
        'name' => 'Proyek Test Show Hapus',
        'location' => 'Jalan Permata',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    /** @var Unit $unit */
    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'SHOW-DEL-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'terjual',
        'created_by' => $founder->id,
    ]);

    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'total_price' => 150000000,
        'down_payment' => 30000000,
        'installment_count' => 12,
        'installment_amount' => 10000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->call('deleteInstallmentScheme')
        ->assertHasNoErrors();

    expect(UnitInstallment::find($installment->id))->toBeNull();
});
