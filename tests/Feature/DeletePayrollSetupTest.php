<?php

use App\Livewire\Units\Show;
use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use Livewire\Livewire;

test('founder can delete payroll setup and associated salary payments without foreach null error', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Proyek Payroll Test',
        'location' => 'Jl. Borongan',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'PAY-01',
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
        'name' => 'Mandor Budi Borongan',
        'phone' => '089911223344',
        'specialty' => 'Pembangunan Rumah',
    ]);

    $payroll = WorkerUnitPayroll::create([
        'worker_id' => $worker->id,
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'agreed_salary' => 50000000,
        'paid_amount' => 10000000,
        'payment_frequency' => 'mingguan',
        'status' => 'berjalan',
        'created_by' => $founder->id,
    ]);

    $payment = WorkerSalaryPayment::create([
        'uuid' => (string) \Illuminate\Support\Str::uuid(),
        'worker_unit_payroll_id' => $payroll->id,
        'payment_date' => now()->toDateString(),
        'amount_gross' => 10000000,
        'loan_deduction' => 0,
        'amount_paid' => 10000000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Termin 1 Borongan',
        'created_by' => $founder->id,
    ]);

    $cashflow = CashflowTransaction::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'type' => 'keluar',
        'category' => 'upah_pekerja',
        'amount' => 10000000,
        'transaction_date' => now()->toDateString(),
        'description' => 'Upah Pekerja',
        'reference_type' => WorkerSalaryPayment::class,
        'reference_id' => $payment->id,
        'created_by' => $founder->id,
    ]);

    $this->actingAs($founder);

    // Call deletePayrollSetup
    Livewire::test(Show::class, ['id' => $unit->id])
        ->call('deletePayrollSetup', $payroll->id)
        ->assertHasNoErrors();

    // Verify deletion
    $this->assertDatabaseMissing('worker_unit_payrolls', ['id' => $payroll->id]);
    $this->assertDatabaseMissing('worker_salary_payments', ['id' => $payment->id]);
    $this->assertDatabaseMissing('cashflow_transactions', ['id' => $cashflow->id]);
});
