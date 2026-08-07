<?php

namespace Database\Seeders;

use App\Models\CashflowTransaction;
use App\Models\EmployeePayrollPayment;
use App\Models\EmployeeSalary;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeeSalarySeeder extends Seeder
{
    public function run(): void
    {
        $founder = User::where('role', 'founder')->first() ?? User::first();
        if (!$founder) return;

        $users = User::all()->keyBy('email');
        $workers = Worker::all()->keyBy('id');

        // 1. Data Penetapan Standar Gaji Karyawan (Staf Kantor & Pekerja Lapangan)
        $salariesData = [
            // --- STAF MANAJEMEN & KANTOR ---
            [
                'user_email' => 'marwansyah@simatlantik.my.id',
                'worker_id' => null,
                'employee_name' => 'Marwansyah',
                'employee_type' => 'staf',
                'position' => 'Founder & Direktur Utama',
                'basic_salary' => 15000000,
                'allowance' => 3000000,
                'bonus' => 2000000,
                'deductions' => 500000,
                'bank_name' => 'Bank Mandiri',
                'bank_account_number' => '1080012345678',
                'bank_account_holder' => 'Marwansyah',
                'notes' => 'Gaji Pokok & Tunjangan Direksi Utama PT. Atlantik Perkasa Abadi',
            ],
            [
                'user_email' => 'finance@kavling.com',
                'worker_id' => null,
                'employee_name' => 'Ibu Wan Rahmah, S.E., M.Si.',
                'employee_type' => 'staf',
                'position' => 'Chief Finance Officer (Finance)',
                'basic_salary' => 8500000,
                'allowance' => 1500000,
                'bonus' => 1000000,
                'deductions' => 300000,
                'bank_name' => 'BCA',
                'bank_account_number' => '8420192831',
                'bank_account_holder' => 'Wan Rahmah',
                'notes' => 'Gaji Kepala Divisi Keuangan & Arus Kas Kantor Pusat',
            ],
            [
                'user_email' => 'supervisor@kavling.com',
                'worker_id' => null,
                'employee_name' => 'Ir. H. Ahmad Syafrizal',
                'employee_type' => 'staf',
                'position' => 'Field Supervisor Utama',
                'basic_salary' => 7500000,
                'allowance' => 1200000,
                'bonus' => 800000,
                'deductions' => 250000,
                'bank_name' => 'Bank Riau Kepri Syariah',
                'bank_account_number' => '10108928172',
                'bank_account_holder' => 'Ahmad Syafrizal',
                'notes' => 'Supervisi Lapangan Seluruh Proyek Kavling Pekanbaru',
            ],
            [
                'user_email' => 'Yannoki12@gmail.com',
                'worker_id' => null,
                'employee_name' => 'Yannoki',
                'employee_type' => 'staf',
                'position' => 'Pengawas Project Utama',
                'basic_salary' => 6000000,
                'allowance' => 1000000,
                'bonus' => 500000,
                'deductions' => 200000,
                'bank_name' => 'BRI',
                'bank_account_number' => '001201092837501',
                'bank_account_holder' => 'Yannoki',
                'notes' => 'Pengawas Lapangan Proyek Panam & Marpoyan',
            ],
            [
                'user_email' => 'marketing@kavling.com',
                'worker_id' => null,
                'employee_name' => 'Rian Gunawan, S.Kom.',
                'employee_type' => 'staf',
                'position' => 'Marketing Executive',
                'basic_salary' => 5000000,
                'allowance' => 1000000,
                'bonus' => 2500000,
                'deductions' => 150000,
                'bank_name' => 'BCA',
                'bank_account_number' => '8420991122',
                'bank_account_holder' => 'Rian Gunawan',
                'notes' => 'Gaji Pokok & Komisi Penjualan Unit Kavling',
            ],
            [
                'user_email' => 'pengawas2@kavling.com',
                'worker_id' => null,
                'employee_name' => 'Bambang Syafruddin, A.Md.',
                'employee_type' => 'staf',
                'position' => 'Pengawas Lapangan 2',
                'basic_salary' => 5500000,
                'allowance' => 800000,
                'bonus' => 400000,
                'deductions' => 180000,
                'bank_name' => 'Bank Mandiri',
                'bank_account_number' => '1080099887766',
                'bank_account_holder' => 'Bambang Syafruddin',
                'notes' => 'Pengawas Proyek Tenayan Raya & Rimbo Panjang',
            ],

            // --- PEKERJA LAPANGAN (MANDOR & TUKANG) ---
            [
                'user_email' => null,
                'worker_id' => 1,
                'employee_name' => 'Mandor Sugeng Riau',
                'employee_type' => 'lapangan',
                'position' => 'Mandor Utama Infrastruktur',
                'basic_salary' => 5500000,
                'allowance' => 500000,
                'bonus' => 500000,
                'deductions' => 100000,
                'bank_name' => 'BRI',
                'bank_account_number' => '543201928374509',
                'bank_account_holder' => 'Sugeng Riau',
                'notes' => 'Honor Tetap Mandor Pembangunan Jalan & Drainase Panam',
            ],
            [
                'user_email' => null,
                'worker_id' => 2,
                'employee_name' => 'Mandor Tengku Supri',
                'employee_type' => 'lapangan',
                'position' => 'Mandor Finishing & Granit',
                'basic_salary' => 5200000,
                'allowance' => 500000,
                'bonus' => 300000,
                'deductions' => 100000,
                'bank_name' => 'BCA',
                'bank_account_number' => '8420776655',
                'bank_account_holder' => 'Tengku Supri',
                'notes' => 'Honor Mandor Subkon Finishing Rumah Contoh',
            ],
            [
                'user_email' => null,
                'worker_id' => 3,
                'employee_name' => 'Tukang Syahrul',
                'employee_type' => 'lapangan',
                'position' => 'Tukang Batu & Granit',
                'basic_salary' => 4200000,
                'allowance' => 300000,
                'bonus' => 200000,
                'deductions' => 50000,
                'bank_name' => 'Bank Riau Kepri Syariah',
                'bank_account_number' => '10107766554',
                'bank_account_holder' => 'Syahrul',
                'notes' => 'Honor Bulanan Tukang Senior Pemasangan Keramik',
            ],
        ];

        foreach ($salariesData as $item) {
            $user = $item['user_email'] && isset($users[$item['user_email']]) ? $users[$item['user_email']] : null;
            $worker = $item['worker_id'] && isset($workers[$item['worker_id']]) ? $workers[$item['worker_id']] : null;

            $basic = (float) $item['basic_salary'];
            $allowance = (float) $item['allowance'];
            $bonus = (float) $item['bonus'];
            $deductions = (float) $item['deductions'];
            $netSalary = max(0, $basic + $allowance + $bonus - $deductions);

            $salary = EmployeeSalary::updateOrCreate(
                [
                    'employee_name' => $item['employee_name'],
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'user_id' => $user?->id,
                    'worker_id' => $worker?->id,
                    'employee_name' => $item['employee_name'],
                    'employee_type' => $item['employee_type'],
                    'position' => $item['position'],
                    'basic_salary' => $basic,
                    'allowance' => $allowance,
                    'bonus' => $bonus,
                    'deductions' => $deductions,
                    'net_salary' => $netSalary,
                    'bank_name' => $item['bank_name'],
                    'bank_account_number' => $item['bank_account_number'],
                    'bank_account_holder' => $item['bank_account_holder'],
                    'notes' => $item['notes'],
                    'created_by' => $founder->id,
                ]
            );

            // 2. Buat Histori Pembayaran Gaji (Payroll Payments Bulan 7 dan Bulan 8 TAHUN 2026)
            $payMonths = [
                ['month' => 7, 'year' => 2026, 'date' => '2026-07-28', 'notes' => 'Penggajian Karyawan Periode Juli 2026'],
                ['month' => 8, 'year' => 2026, 'date' => '2026-08-01', 'notes' => 'Penggajian Karyawan Periode Agustus 2026'],
            ];

            foreach ($payMonths as $pm) {
                // Create EmployeePayrollPayment first
                $payment = EmployeePayrollPayment::updateOrCreate(
                    [
                        'employee_salary_id' => $salary->id,
                        'payroll_month' => $pm['month'],
                        'payroll_year' => $pm['year'],
                    ],
                    [
                        'uuid' => (string) Str::uuid(),
                        'employee_salary_id' => $salary->id,
                        'payroll_month' => $pm['month'],
                        'payroll_year' => $pm['year'],
                        'payment_date' => $pm['date'],
                        'basic_salary' => $basic,
                        'allowance' => $allowance,
                        'bonus' => $bonus,
                        'deductions' => $deductions,
                        'net_salary' => $netSalary,
                        'payment_method' => 'transfer',
                        'bank_name' => $salary->bank_name,
                        'account_number' => $salary->bank_account_number,
                        'receipt_photo_path' => null,
                        'notes' => $pm['notes'],
                        'status' => 'dibayar',
                        'paid_at' => $pm['date'] . ' 10:00:00',
                        'created_by' => $founder->id,
                    ]
                );

                // Create Cashflow Transaction and link reference
                $cashflow = CashflowTransaction::create([
                    'project_id' => null, // Global Office Expense
                    'type' => 'keluar',
                    'category' => 'operasional',
                    'amount' => $netSalary,
                    'transaction_date' => $pm['date'],
                    'description' => "Beban Gaji Karyawan: {$salary->employee_name} ({$salary->position}) - Periode " . $this->getMonthName($pm['month']) . " {$pm['year']}",
                    'receipt_photo_path' => null,
                    'reference_type' => EmployeePayrollPayment::class,
                    'reference_id' => $payment->id,
                    'created_by' => $founder->id,
                ]);

                $payment->update(['cashflow_transaction_id' => $cashflow->id]);
            }
        }
    }

    private function getMonthName(int $m): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$m] ?? 'Bulan ' . $m;
    }
}
