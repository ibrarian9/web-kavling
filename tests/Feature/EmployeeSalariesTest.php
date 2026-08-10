<?php

use App\Models\CashflowTransaction;
use App\Models\EmployeePayrollPayment;
use App\Models\EmployeeSalary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('supervisor', 'web');
});

test('non founder is denied access to employee salaries page', function () {
    $supervisor = User::create([
        'name' => 'Supervisor User',
        'email' => 'supervisor_sal@example.com',
        'password' => bcrypt('password'),
        'role' => 'supervisor',
        'is_active' => true,
    ]);
    $supervisor->assignRole('supervisor');

    $this->actingAs($supervisor)
        ->get(route('employee-salaries.index'))
        ->assertStatus(403);
});

test('founder can access employee salaries page and manage salary standards', function () {
    $founder = User::create([
        'name' => 'Founder Utama',
        'email' => 'founder_sal@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    // Access Page
    $this->actingAs($founder)
        ->get(route('employee-salaries.index'))
        ->assertStatus(200)
        ->assertSee('Penetapan & Penggajian Karyawan');

    // Test setting salary standard
    Livewire::actingAs($founder)
        ->test(\App\Livewire\EmployeeSalaries\Index::class)
        ->set('employee_name', 'Budi Santoso')
        ->set('position', 'Supervisor Teknik')
        ->set('basic_salary', 6000000)
        ->set('allowance', 1000000)
        ->set('bonus', 500000)
        ->set('deductions', 200000)
        ->call('saveSalaryStandard')
        ->assertHasNoErrors();

    $salary = EmployeeSalary::where('employee_name', 'Budi Santoso')->first();
    expect($salary)->not->toBeNull();
    expect((float) $salary->net_salary)->toBe(7300000.0);

    // Test processing monthly payroll payment
    Livewire::actingAs($founder)
        ->test(\App\Livewire\EmployeeSalaries\Index::class)
        ->set('paymentSalaryId', $salary->id)
        ->set('payroll_month', 8)
        ->set('payroll_year', 2026)
        ->set('payment_date', '2026-08-06')
        ->set('pay_basic_salary', 6000000)
        ->set('pay_allowance', 1000000)
        ->set('pay_bonus', 500000)
        ->set('pay_deductions', 200000)
        ->set('payment_method', 'transfer')
        ->call('processPayment')
        ->assertHasNoErrors();

    $payment = EmployeePayrollPayment::where('employee_salary_id', $salary->id)->first();
    expect($payment)->not->toBeNull();
    expect((float) $payment->net_salary)->toBe(7300000.0);

    // Verify Cashflow Transaction logged
    $cashflow = CashflowTransaction::where('id', $payment->cashflow_transaction_id)->first();
    expect($cashflow)->not->toBeNull();
    expect($cashflow->type)->toBe('keluar');
    expect($cashflow->category)->toBe('operasional');
    expect((float) $cashflow->amount)->toBe(7300000.0);

    // Test PDF Slip Gaji Streaming
    $this->actingAs($founder)
        ->get(route('employee-salary.slip-pdf', $payment->uuid))
        ->assertStatus(200);

    // Test Deleting Payroll Payment Record -> EMPLOYEE_PAYROLL_DELETED log
    Livewire::actingAs($founder)
        ->test(\App\Livewire\EmployeeSalaries\Index::class)
        ->call('deletePaymentRecord', $payment->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $founder->id,
        'action' => 'EMPLOYEE_PAYROLL_DELETED',
    ]);

    // Test Deleting Salary Standard -> SALARY_STANDARD_DELETED log
    Livewire::actingAs($founder)
        ->test(\App\Livewire\EmployeeSalaries\Index::class)
        ->call('deleteSalaryStandard', $salary->id)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $founder->id,
        'action' => 'SALARY_STANDARD_DELETED',
    ]);
});
