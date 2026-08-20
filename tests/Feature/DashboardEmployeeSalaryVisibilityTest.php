<?php

use App\Livewire\Dashboard;
use App\Models\EmployeePayrollPayment;
use App\Models\EmployeeSalary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('finance', 'web');
    Role::findOrCreate('marketing', 'web');
    Role::findOrCreate('supervisor', 'web');
    Role::findOrCreate('admin', 'web');
});

test('founder sees all executive modules and does not see personal employee salary card on dashboard', function () {
    $founder = User::create([
        'role' => 'founder',
        'email' => 'founder@test.com',
        'name' => 'Bpk. Founder Utama',
        'password' => bcrypt('password'),
    ]);
    $founder->assignRole('founder');

    Livewire::actingAs($founder)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertDontSee('Informasi Penetapan Gaji Anda')
        ->assertSee('Proyek Properti')
        ->assertSee('Stok Unit')
        ->assertSee('Saldo Kas Bersih Global')
        ->assertSee('Pintasan Modul Utama');
});

test('employee sees their salary details and their operational modules on dashboard', function () {
    $finance = User::create([
        'role' => 'finance',
        'email' => 'finance@test.com',
        'name' => 'Siti Finance',
        'position' => 'Staff Keuangan',
        'password' => bcrypt('password'),
    ]);
    $finance->assignRole('finance');

    $salary = EmployeeSalary::create([
        'user_id' => $finance->id,
        'employee_name' => $finance->name,
        'employee_type' => 'staf',
        'position' => 'Senior Financial Officer',
        'basic_salary' => 6000000,
        'allowance' => 1500000,
        'bonus' => 500000,
        'deductions' => 200000,
        'net_salary' => 7800000,
        'bank_name' => 'BCA',
        'bank_account_number' => '5210987654',
        'bank_account_holder' => 'Siti Finance',
        'notes' => 'SK Penetapan Gaji Staf Keuangan 2026',
        'created_by' => $finance->id,
    ]);

    $payment = EmployeePayrollPayment::create([
        'employee_salary_id' => $salary->id,
        'payroll_month' => 8,
        'payroll_year' => 2026,
        'basic_salary' => 6000000,
        'allowance' => 1500000,
        'bonus' => 500000,
        'deductions' => 200000,
        'net_salary' => 7800000,
        'payment_date' => now()->toDateString(),
        'payment_method' => 'transfer',
        'created_by' => $finance->id,
    ]);

    Livewire::actingAs($finance)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertSee('Informasi Penetapan Gaji Anda')
        ->assertSee('Ditetapkan Founder')
        ->assertSee('Senior Financial Officer')
        ->assertSee('Rp ' . number_format(6000000, 0, ',', '.'))
        ->assertSee('Rp ' . number_format(1500000, 0, ',', '.'))
        ->assertSee('Rp ' . number_format(7800000, 0, ',', '.'))
        ->assertSee('BCA - 5210987654')
        ->assertSee('SK Penetapan Gaji Staf Keuangan 2026')
        ->assertSee('Cetak Slip Gaji (Agustus 2026)')
        ->assertSee('Proyek Properti')
        ->assertSee('Stok Unit')
        ->assertSee('Pintasan Modul Utama')
        ->assertSee('Grafik Tren Keuangan Arus Kas');
});

test('employee without established salary sees helpful notice and operational modules on dashboard', function () {
    $marketing = User::create([
        'role' => 'marketing',
        'email' => 'sales@test.com',
        'name' => 'Rian Marketing',
        'password' => bcrypt('password'),
    ]);
    $marketing->assignRole('marketing');

    Livewire::actingAs($marketing)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertSee('Informasi Penetapan Gaji Anda')
        ->assertSee('Informasi Gaji Belum Ditetapkan')
        ->assertSee('Proyek Properti')
        ->assertSee('Pintasan Modul Utama')
        ->assertSee('Daily Activity & Hot Deals', false);
});

test('admin does not see financial stats or cashflow chart on dashboard and sees worker count instead', function () {
    $admin = User::create([
        'role' => 'admin',
        'email' => 'admin@test.com',
        'name' => 'Budi Admin',
        'password' => bcrypt('password'),
    ]);
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertSee('Informasi Penetapan Gaji Anda')
        ->assertSee('Proyek Properti')
        ->assertSee('Stok Unit')
        ->assertSee('Pemesanan & Booking', false)
        ->assertSee('Pekerja Mandor & Tukang', false)
        ->assertDontSee('Saldo Kas Bersih Global')
        ->assertDontSee('Grafik Tren Keuangan Arus Kas');
});

test('admin does not see land payments tab in installments page', function () {
    $admin = User::create([
        'role' => 'admin',
        'email' => 'admin2@test.com',
        'name' => 'Budi Admin 2',
        'password' => bcrypt('password'),
    ]);
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Installments\Index::class)
        ->assertStatus(200)
        ->assertSee('Cicilan & Piutang Pembeli', false)
        ->assertDontSee('Pembayaran Lahan Proyek');
});
