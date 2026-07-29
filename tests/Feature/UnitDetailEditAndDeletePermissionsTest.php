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
use App\Models\UnitInstallment;
use App\Models\InstallmentPayment;
use App\Livewire\Units\Show as UnitShow;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
    Role::firstOrCreate(['name' => 'pengawas']);
});

test('founder and finance can edit and delete all items on unit detail page', function () {
    $founder = User::create([
        'name' => 'Founder User',
        'email' => 'founder_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Permata Land',
        'location' => 'Jalan Permata',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'A1_' . Str::random(3),
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $worker = Worker::create([
        'name' => 'Pak Budi',
        'type' => 'mandor',
        'phone' => '08123456789',
        'status' => 'active',
        'created_by' => $founder->id,
    ]);

    // 1. Edit Unit Specs
    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('openEditUnitModal')
        ->set('edit_unit_code', 'A1-UPDATED')
        ->set('edit_unit_category', 'kavling')
        ->set('edit_unit_status', 'tersedia')
        ->set('edit_land_area', 120)
        ->call('saveEditUnit')
        ->assertHasNoErrors();

    expect($unit->fresh()->code)->toBe('A1-UPDATED');
    expect((float)$unit->fresh()->land_area)->toBe(120.0);

    // 2. Worker Assignment Edit & Delete
    $assignment = WorkerAssignment::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'assigned_role' => 'Mandor Awal',
        'status' => 'active',
    ]);

    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('editWorkerAssignment', $assignment->id)
        ->set('assigned_role', 'Mandor Utama')
        ->call('saveWorkerAssignment')
        ->assertHasNoErrors();

    expect($assignment->fresh()->assigned_role)->toBe('Mandor Utama');

    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('deleteWorkerAssignment', $assignment->id)
        ->assertHasNoErrors();

    expect(WorkerAssignment::find($assignment->id))->toBeNull();

    // 3. Material Purchase Edit & Delete
    $material = WeeklyMaterialPurchase::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'pengawas_id' => $founder->id,
        'purchase_date' => now()->toDateString(),
        'item_name' => 'Semen Tiga Roda',
        'quantity' => 10,
        'unit_measure' => 'sak',
        'unit_price' => 65000,
        'total_price' => 650000,
    ]);

    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('editMaterialPurchase', $material->id)
        ->set('material_item_name', 'Semen Gresik Super')
        ->set('material_unit_price', 70000)
        ->set('material_quantity', 15)
        ->call('saveMaterialPurchase')
        ->assertHasNoErrors();

    expect($material->fresh()->item_name)->toBe('Semen Gresik Super');
    expect((float)$material->fresh()->total_price)->toBe(1050000.0);

    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('deleteMaterialPurchase', $material->id)
        ->assertHasNoErrors();

    expect(WeeklyMaterialPurchase::find($material->id))->toBeNull();
});

test('pengawas can edit and delete operational data on assigned project but blocked on financial data', function () {
    $pengawas = User::create([
        'name' => 'Pengawas User',
        'email' => 'pengawas_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'pengawas_project',
        'is_active' => true,
    ]);
    $pengawas->assignRole('pengawas');

    $project = Project::create([
        'name' => 'Graha Asri',
        'location' => 'Jalan Graha',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $pengawas->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'B2_' . Str::random(3),
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $pengawas->id,
    ]);

    $worker = Worker::create([
        'name' => 'Pak Slamet',
        'type' => 'tukang',
        'phone' => '08123456788',
        'status' => 'active',
        'created_by' => $pengawas->id,
    ]);

    // Assign pengawas to project
    WorkerAssignment::create([
        'project_id' => $project->id,
        'user_id' => $pengawas->id,
        'assigned_role' => 'Pengawas Lapangan',
        'status' => 'active',
    ]);

    $material = WeeklyMaterialPurchase::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'pengawas_id' => $pengawas->id,
        'purchase_date' => now()->toDateString(),
        'item_name' => 'Bata Merah',
        'quantity' => 1000,
        'unit_measure' => 'pcs',
        'unit_price' => 800,
        'total_price' => 800000,
    ]);

    // Pengawas CAN edit material purchase on supervised project
    Livewire::actingAs($pengawas)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('editMaterialPurchase', $material->id)
        ->set('material_unit_price', 800)
        ->set('material_quantity', 1200)
        ->call('saveMaterialPurchase')
        ->assertHasNoErrors();

    expect((float)$material->fresh()->total_price)->toBe(960000.0);

    // Pengawas CANNOT edit financial specs
    Livewire::actingAs($pengawas)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('openEditUnitModal')
        ->assertSee('Akses ditolak');
});

test('editing worker salary payment updates pdf receipt and qr verification route data', function () {
    $finance = User::create([
        'name' => 'Finance Accounting User',
        'email' => 'finance_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    $project = Project::create([
        'name' => 'Kavling Harmoni',
        'location' => 'Jalan Harmoni',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $finance->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'C3_' . Str::random(3),
        'category' => 'rumah',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 36,
        'hpp' => 100000000,
        'final_selling_price' => 180000000,
        'status' => 'tersedia',
        'created_by' => $finance->id,
    ]);

    $worker = Worker::create([
        'name' => 'Mandor Joko',
        'type' => 'mandor',
        'phone' => '08123456799',
        'status' => 'active',
        'created_by' => $finance->id,
    ]);

    $payroll = WorkerUnitPayroll::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'agreed_salary' => 5000000,
        'paid_amount' => 1000000,
        'status' => 'berjalan',
        'created_by' => $finance->id,
    ]);

    $sp = WorkerSalaryPayment::create([
        'worker_unit_payroll_id' => $payroll->id,
        'payment_date' => now()->toDateString(),
        'amount_gross' => 1000000,
        'loan_deduction' => 0,
        'amount_paid' => 1000000,
        'payment_method' => 'transfer_bank',
        'uuid' => (string) Str::uuid(),
        'created_by' => $finance->id,
    ]);

    CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'keluar',
        'category' => 'pembayaran_tukang',
        'amount' => 1000000,
        'transaction_date' => now()->toDateString(),
        'description' => "Gaji Worker: {$worker->name} (Unit {$unit->code})",
        'reference_type' => WorkerSalaryPayment::class,
        'reference_id' => $sp->id,
        'created_by' => $finance->id,
    ]);

    // Finance edits the salary payment from 1,000,000 to 2,500,000
    Livewire::actingAs($finance)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('editPayrollPayment', $sp->id)
        ->set('payroll_amount_gross', 2500000)
        ->call('savePayrollPayment')
        ->assertHasNoErrors();

    expect((float)$sp->fresh()->amount_gross)->toBe(2500000.0);
    expect((float)$payroll->fresh()->paid_amount)->toBe(2500000.0);

    $cashflow = CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
        ->where('reference_id', $sp->id)
        ->first();
    expect((float)$cashflow->amount)->toBe(2500000.0);

    // Verify PDF route loads updated 2,500,000 payment details seamlessly
    $response = $this->actingAs($finance)->get(route('payroll.receipt', $sp->uuid));
    $response->assertStatus(200);
});
