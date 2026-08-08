<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use App\Models\WorkerUnitPayroll;
use App\Models\WorkerSalaryPayment;

test('worker model handles worker types, assignments, and borongan salary payments', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Lapangan',
        'location' => 'Jl. Lapangan',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 100000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'L-01',
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

    $worker = Worker::create([
        'name' => 'Mandor Sugeng',
        'type' => 'mandor',
        'phone' => '081299887766',
        'status' => 'active',
        'created_by' => $founder->id,
    ]);

    // Assignment
    $assignment = WorkerAssignment::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'assigned_role' => 'Mandor Utama Struktur',
        'status' => 'active',
    ]);

    // Borongan Payroll setup: 15,000,000 agreed salary
    $payroll = WorkerUnitPayroll::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'agreed_salary' => 15000000.00,
        'paid_amount' => 5000000.00,
        'status' => 'berjalan',
        'created_by' => $founder->id,
    ]);

    expect($worker->name)->toBe('Mandor Sugeng');
    expect($worker->type)->toBe('mandor');

    expect($assignment->worker->id)->toBe($worker->id);
    expect($assignment->unit->id)->toBe($unit->id);

    expect((float)$payroll->agreed_salary)->toBe(15000000.00);
    expect((float)$payroll->paid_amount)->toBe(5000000.00);

    $remainingSalary = (float)$payroll->agreed_salary - (float)$payroll->paid_amount;
    expect($remainingSalary)->toBe(10000000.00);

    // Record Salary Payment: 10,000,000
    $payment = WorkerSalaryPayment::create([
        'worker_unit_payroll_id' => $payroll->id,
        'payment_date' => now()->toDateString(),
        'amount_gross' => 10000000.00,
        'loan_deduction' => 0.00,
        'amount_paid' => 10000000.00,
        'payment_method' => 'transfer_bank',
        'uuid' => (string)\Illuminate\Support\Str::uuid(),
        'created_by' => $founder->id,
    ]);

    $payroll->update([
        'paid_amount' => (float)$payroll->paid_amount + (float)$payment->amount_paid,
        'status' => 'lunas',
    ]);

    expect((float)$payroll->fresh()->paid_amount)->toBe(15000000.00);
    expect($payroll->fresh()->status)->toBe('lunas');
});
