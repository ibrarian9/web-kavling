<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use App\Models\WeeklyMaterialPurchase;
use App\Models\WorkerUnitPayroll;
use App\Models\WorkerSalaryPayment;
use App\Models\CashflowTransaction;
use App\Livewire\Workers\Index as WorkerIndex;
use App\Livewire\FieldExpenses\Index as FieldExpenseIndex;
use App\Livewire\Units\Show as UnitShow;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'pengawas']);
    Role::firstOrCreate(['name' => 'finance']);
});

test('end to end construction workflow: worker registration -> project assignment -> material purchase -> borongan payroll -> receipt pdf generation', function () {
    // 1. Setup Users
    $founder = User::create([
        'name' => 'Founder Construction E2E',
        'email' => 'founder_const_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $pengawasUser = User::create([
        'name' => 'Pengawas Lapangan E2E',
        'email' => 'pengawas_e2e_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'pengawas_project',
        'is_active' => true,
    ]);
    $pengawasUser->assignRole('pengawas');

    $finance = User::create([
        'name' => 'Finance Construction E2E',
        'email' => 'finance_const_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    // 2. Create Project & Unit
    $project = Project::create([
        'name' => 'Kavling Graha Konstruksi',
        'location' => 'Jl. Pembangunan No. 10',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'GK-01',
        'category' => 'rumah',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 36,
        'hpp' => 100000000,
        'final_selling_price' => 180000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    // Assign Pengawas to Project
    WorkerAssignment::create([
        'project_id' => $project->id,
        'user_id' => $pengawasUser->id,
        'assigned_role' => 'Pengawas Lapangan',
        'status' => 'active',
    ]);

    // 3. Register Worker (Mandor)
    Livewire::actingAs($founder)
        ->test(WorkerIndex::class)
        ->set('name', 'Mandor Suparno')
        ->set('phone', '081344556677')
        ->set('type', 'mandor')
        ->set('specialty', 'Konstruksi Beton & Dinding')
        ->set('status', 'active')
        ->call('save')
        ->assertHasNoErrors();

    $worker = Worker::where('name', 'Mandor Suparno')->first();
    expect($worker)->not->toBeNull();

    // 4. Assign Worker to Unit
    Livewire::actingAs($founder)
        ->test(WorkerIndex::class)
        ->set('assignWorkerId', $worker->id)
        ->set('assignProjectId', $project->id)
        ->set('assignUnitId', $unit->id)
        ->set('assignedRole', 'Mandor Penanggung Jawab Unit')
        ->call('saveAssignment')
        ->assertHasNoErrors();

    expect(WorkerAssignment::where('worker_id', $worker->id)->where('unit_id', $unit->id)->exists())->toBeTrue();

    // 5. Pengawas Records Material Purchase (Semen Tiga Roda 50 sak @ Rp 65,000)
    Livewire::actingAs($pengawasUser)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('openMaterialModal')
        ->set('material_worker_id', $worker->id)
        ->set('material_item_name', 'Semen Tiga Roda')
        ->set('material_quantity', 50)
        ->set('material_unit_measure', 'sak')
        ->set('material_unit_price', 65000)
        ->set('material_purchase_date', now()->toDateString())
        ->set('material_notes', 'Pembelian semen pondasi unit GK-01')
        ->call('saveMaterialPurchase')
        ->assertHasNoErrors();

    $material = WeeklyMaterialPurchase::where('item_name', 'Semen Tiga Roda')->first();
    expect($material)->not->toBeNull();
    expect((float)$material->total_price)->toBe(3250000.0);

    // Verify Outgoing Cashflow for Material
    $matCashflow = CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
        ->where('reference_id', $material->id)
        ->first();
    expect($matCashflow)->not->toBeNull();
    expect((float)$matCashflow->amount)->toBe(3250000.0);

    // 6. Setup Borongan Payroll (Agreed Salary 20M) & Record Payment (10M)
    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->set('payroll_worker_id', $worker->id)
        ->set('payroll_agreed_salary', 20000000)
        ->set('payroll_payment_frequency', 'mingguan')
        ->call('savePayrollSetup')
        ->assertHasNoErrors();

    $payroll = WorkerUnitPayroll::where('unit_id', $unit->id)->first();
    expect($payroll)->not->toBeNull();

    Livewire::actingAs($finance)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('openPayrollPaymentModal', $payroll->id)
        ->set('payroll_amount_gross', 10000000)
        ->set('payroll_loan_deduction', 0)
        ->set('payroll_payment_method', 'transfer_bank')
        ->call('savePayrollPayment')
        ->assertHasNoErrors();

    $salaryPayment = WorkerSalaryPayment::where('worker_unit_payroll_id', $payroll->id)->first();
    expect($salaryPayment)->not->toBeNull();
    expect((float)$salaryPayment->amount_paid)->toBe(10000000.0);

    // Verify PDF Receipt Route for Salary Payment
    $pdfResponse = $this->actingAs($finance)->get(route('payroll.receipt', $salaryPayment->uuid));
    $pdfResponse->assertStatus(200);

    // Verify FieldExpenses List
    Livewire::actingAs($founder)
        ->test(FieldExpenseIndex::class)
        ->set('project_id', $project->id)
        ->assertSee('Semen Tiga Roda')
        ->assertSee('Mandor Suparno');
});
