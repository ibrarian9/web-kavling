<?php

use App\Models\User;
use App\Models\EmployeeSalary;
use App\Models\EmployeePayrollPayment;
use App\Models\CashflowTransaction;
use App\Livewire\EmployeeSalaries\Index as EmployeeSalaryIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
});

test('end to end employee payroll workflow: setup standard salary -> process monthly payment -> cashflow logging -> stream slip pdf', function () {
    // 1. Setup Users
    $founder = User::create([
        'name' => 'Founder Executive HR',
        'email' => 'founder_hr_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $financeStaff = User::create([
        'name' => 'Citra Finance',
        'email' => 'citra_finance_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $financeStaff->assignRole('finance');

    // 2. Founder Sets Up Employee Standard Salary for Finance Staff
    Livewire::actingAs($founder)
        ->test(EmployeeSalaryIndex::class)
        ->call('openSalaryModal')
        ->set('target_type', 'user')
        ->set('user_id', $financeStaff->id)
        ->set('employee_name', 'Citra Finance')
        ->set('position', 'Finance Admin Officer')
        ->set('basic_salary', 7500000)
        ->set('allowance', 1500000)
        ->set('bonus', 500000)
        ->set('deductions', 250000)
        ->set('bank_name', 'Bank Mandiri')
        ->set('bank_account_number', '1400012345678')
        ->call('saveSalaryStandard')
        ->assertHasNoErrors();

    $employeeSalary = EmployeeSalary::where('employee_name', 'Citra Finance')->first();
    expect($employeeSalary)->not->toBeNull();
    expect((float)$employeeSalary->basic_salary)->toBe(7500000.0);
    expect((float)$employeeSalary->net_salary)->toBe(9250000.0); // (7.5M + 1.5M + 0.5M - 0.25M)

    // 3. Founder Processes Monthly Payroll for August 2026
    Livewire::actingAs($founder)
        ->test(EmployeeSalaryIndex::class)
        ->call('openPaymentModal', $employeeSalary->id)
        ->set('payroll_month', 8)
        ->set('payroll_year', 2026)
        ->set('payment_date', now()->toDateString())
        ->set('pay_basic_salary', 7500000)
        ->set('pay_allowance', 1500000)
        ->set('pay_bonus', 500000)
        ->set('pay_deductions', 250000)
        ->set('payment_method', 'transfer')
        ->set('pay_bank_name', 'Bank Mandiri 1400012345678')
        ->call('processPayment')
        ->assertHasNoErrors();

    $payrollPayment = EmployeePayrollPayment::where('employee_salary_id', $employeeSalary->id)->first();
    expect($payrollPayment)->not->toBeNull();
    expect((float)$payrollPayment->net_salary)->toBe(9250000.0);

    // 4. Verify Outgoing Cashflow Record
    $cashflow = CashflowTransaction::find($payrollPayment->cashflow_transaction_id);
    expect($cashflow)->not->toBeNull();
    expect((float)$cashflow->amount)->toBe(9250000.0);
    expect($cashflow->type)->toBe('keluar');

    // 5. Verify PDF Slip Stream Route
    $pdfResponse = $this->actingAs($founder)->get(route('employee-salary.slip-pdf', $payrollPayment->uuid));
    $pdfResponse->assertStatus(200);
});
