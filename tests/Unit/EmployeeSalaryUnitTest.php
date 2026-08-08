<?php

use App\Models\User;
use App\Models\EmployeeSalary;
use App\Models\EmployeePayrollPayment;

test('employee salary model calculates net salary and handles payroll payment records', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $employee = EmployeeSalary::create([
        'user_id' => $founder->id,
        'employee_name' => 'Dwi Cahyono',
        'employee_type' => 'staf',
        'position' => 'Supervisor Keuangan',
        'basic_salary' => 8000000.00,
        'allowance' => 2000000.00,
        'bonus' => 1000000.00,
        'deductions' => 500000.00,
        'net_salary' => 10500000.00, // (8M + 2M + 1M - 0.5M)
        'bank_name' => 'BCA',
        'bank_account_number' => '1234567890',
        'created_by' => $founder->id,
    ]);

    expect((float)$employee->basic_salary)->toBe(8000000.00);
    expect((float)$employee->allowance)->toBe(2000000.00);
    expect((float)$employee->bonus)->toBe(1000000.00);
    expect((float)$employee->deductions)->toBe(500000.00);

    $calculatedNet = (float)$employee->basic_salary + (float)$employee->allowance + (float)$employee->bonus - (float)$employee->deductions;
    expect($calculatedNet)->toBe(10500000.00);

    // Record monthly payment for August 2026
    $payment = EmployeePayrollPayment::create([
        'employee_salary_id' => $employee->id,
        'payroll_month' => 8,
        'payroll_year' => 2026,
        'payment_date' => now()->toDateString(),
        'basic_salary' => 8000000.00,
        'allowance' => 2000000.00,
        'bonus' => 1000000.00,
        'deductions' => 500000.00,
        'net_salary' => 10500000.00,
        'payment_method' => 'transfer',
        'uuid' => (string)\Illuminate\Support\Str::uuid(),
        'created_by' => $founder->id,
    ]);

    expect($payment->employeeSalary->id)->toBe($employee->id);
    expect((float)$payment->net_salary)->toBe(10500000.00);
    expect($payment->payroll_month)->toBe(8);
    expect($payment->payroll_year)->toBe(2026);
});
