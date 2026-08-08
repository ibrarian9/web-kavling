<?php

use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use App\Models\Worker;
use App\Models\Unit;
use App\Models\Project;
use App\Models\User;

test('worker salary payment auto-generates UUID on creation', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Salary UUID',
        'location' => 'Jl. Salary',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'SAL-A1',
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
        'name' => 'Pak Joko Salary',
        'type' => 'mandor',
        'phone' => '081234567890',
        'status' => 'active',
    ]);

    $payroll = WorkerUnitPayroll::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'agreed_salary' => 5000000,
        'paid_amount' => 0,
        'status' => 'berjalan',
        'created_by' => $founder->id,
    ]);

    $payment = WorkerSalaryPayment::create([
        'worker_unit_payroll_id' => $payroll->id,
        'payment_date' => now()->toDateString(),
        'amount_gross' => 2000000,
        'loan_deduction' => 500000,
        'amount_paid' => 1500000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Gaji minggu ke-1',
        'created_by' => $founder->id,
    ]);

    expect($payment->uuid)->not->toBeNull();
    expect(strlen($payment->uuid))->toBe(36);
});

test('worker salary payment has correct casts and attributes', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Cast Salary',
        'location' => 'Jl. Cast Sal',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'CST-B1',
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
        'name' => 'Pak Budi Cast',
        'type' => 'tukang',
        'phone' => '081',
        'status' => 'active',
    ]);

    $payroll = WorkerUnitPayroll::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'agreed_salary' => 3000000,
        'paid_amount' => 0,
        'status' => 'berjalan',
        'created_by' => $founder->id,
    ]);

    $payment = WorkerSalaryPayment::create([
        'worker_unit_payroll_id' => $payroll->id,
        'payment_date' => '2026-08-01',
        'amount_gross' => 1500000,
        'loan_deduction' => 200000,
        'amount_paid' => 1300000,
        'payment_method' => 'Cash',
        'created_by' => $founder->id,
    ]);

    expect((float)$payment->amount_gross)->toBe(1500000.00);
    expect((float)$payment->loan_deduction)->toBe(200000.00);
    expect((float)$payment->amount_paid)->toBe(1300000.00);
    expect($payment->payment_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('worker salary payment has correct relationships: payroll, creator', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Rel Salary',
        'location' => 'Jl. Rel Sal',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'RLS-C1',
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
        'name' => 'Pak Ahmad Rel',
        'type' => 'mandor',
        'phone' => '082',
        'status' => 'active',
    ]);

    $payroll = WorkerUnitPayroll::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'agreed_salary' => 4000000,
        'paid_amount' => 0,
        'status' => 'berjalan',
        'created_by' => $founder->id,
    ]);

    $payment = WorkerSalaryPayment::create([
        'worker_unit_payroll_id' => $payroll->id,
        'payment_date' => now()->toDateString(),
        'amount_gross' => 2000000,
        'loan_deduction' => 0,
        'amount_paid' => 2000000,
        'payment_method' => 'Transfer',
        'created_by' => $founder->id,
    ]);

    expect($payment->payroll->id)->toBe($payroll->id);
    expect($payment->creator->id)->toBe($founder->id);
});
