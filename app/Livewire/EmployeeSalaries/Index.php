<?php

namespace App\Livewire\EmployeeSalaries;

use App\Models\CashflowTransaction;
use App\Models\EmployeePayrollPayment;
use App\Models\EmployeeSalary;
use App\Models\User;
use App\Models\Worker;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public string $activeTab = 'salaries'; // 'salaries', 'payments'
    public string $search = '';
    public string $selected_month = '';
    public string $selected_year = '';

    // Modal Penetapan Gaji Standard
    public bool $showSalaryModal = false;
    public ?int $editingSalaryId = null;
    public string $target_type = 'user'; // 'user', 'worker', 'custom'
    public ?int $user_id = null;
    public ?int $worker_id = null;
    public string $employee_name = '';
    public string $position = '';
    public $basic_salary = 0;
    public $allowance = 0;
    public $bonus = 0;
    public $deductions = 0;
    public string $bank_name = '';
    public string $bank_account_number = '';
    public string $bank_account_holder = '';
    public string $notes = '';

    // Modal Pembayaran Gaji Bulanan
    public bool $showPaymentModal = false;
    public ?int $paymentSalaryId = null;
    public string $payment_employee_name = '';
    public int $payroll_month = 8;
    public int $payroll_year = 2026;
    public string $payment_date = '';
    public $pay_basic_salary = 0;
    public $pay_allowance = 0;
    public $pay_bonus = 0;
    public $pay_deductions = 0;
    public string $payment_method = 'transfer';
    public string $pay_bank_name = '';
    public string $pay_account_number = '';
    public string $payment_notes = '';
    public $receipt_photo = null;

    protected $queryString = ['activeTab', 'search', 'selected_month', 'selected_year'];

    public function mount(): void
    {
        if (!auth()->user()->isFounder()) {
            abort(403, 'Akses menu penggajian karyawan khusus untuk Founder.');
        }

        $this->payroll_month = (int) date('n');
        $this->payroll_year = (int) date('Y');
        $this->payment_date = date('Y-m-d');
        $this->selected_month = (string) date('n');
        $this->selected_year = (string) date('Y');
    }

    public function updatedTargetType(): void
    {
        $this->user_id = null;
        $this->worker_id = null;
        $this->employee_name = '';
        $this->position = '';
    }

    public function updatedUserId($value): void
    {
        if ($value) {
            $user = User::find($value);
            if ($user) {
                $this->employee_name = $user->name;
                $this->position = ucfirst($user->role);
                $this->phone = $user->phone ?? '';
            }
        }
    }

    public function updatedWorkerId($value): void
    {
        if ($value) {
            $worker = Worker::find($value);
            if ($worker) {
                $this->employee_name = $worker->name;
                $this->position = 'Pekerja ' . ucfirst($worker->type);
                $this->bank_name = $worker->bank_name ?? '';
                $this->bank_account_number = $worker->account_number ?? '';
            }
        }
    }

    public function openSalaryModal(): void
    {
        $this->resetSalaryFields();
        $this->showSalaryModal = true;
    }

    public function closeSalaryModal(): void
    {
        $this->showSalaryModal = false;
    }

    public function resetSalaryFields(): void
    {
        $this->editingSalaryId = null;
        $this->target_type = 'user';
        $this->user_id = null;
        $this->worker_id = null;
        $this->employee_name = '';
        $this->position = '';
        $this->basic_salary = 0;
        $this->allowance = 0;
        $this->bonus = 0;
        $this->deductions = 0;
        $this->bank_name = '';
        $this->bank_account_number = '';
        $this->bank_account_holder = '';
        $this->notes = '';
    }

    public function saveSalaryStandard(): void
    {
        $this->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
        ]);

        $basic = (float) $this->basic_salary;
        $allowance = (float) $this->allowance;
        $bonus = (float) $this->bonus;
        $deductions = (float) $this->deductions;
        $netSalary = max(0, $basic + $allowance + $bonus - $deductions);

        $employeeType = ($this->target_type === 'worker') ? 'lapangan' : 'staf';

        EmployeeSalary::updateOrCreate(
            ['id' => $this->editingSalaryId],
            [
                'user_id' => $this->target_type === 'user' ? $this->user_id : null,
                'worker_id' => $this->target_type === 'worker' ? $this->worker_id : null,
                'employee_name' => $this->employee_name,
                'employee_type' => $employeeType,
                'position' => $this->position,
                'basic_salary' => $basic,
                'allowance' => $allowance,
                'bonus' => $bonus,
                'deductions' => $deductions,
                'net_salary' => $netSalary,
                'bank_name' => $this->bank_name ?: null,
                'bank_account_number' => $this->bank_account_number ?: null,
                'bank_account_holder' => $this->bank_account_holder ?: null,
                'notes' => $this->notes ?: null,
                'created_by' => auth()->id(),
            ]
        );

        ActivityLogger::log('SALARY_STANDARD_SET', "Penetapan gaji pokok & tunjangan untuk {$this->employee_name} ({$this->position}) disimpan oleh Founder.");

        session()->flash('success', 'Standar gaji karyawan ' . $this->employee_name . ' berhasil ditetapkan!');
        $this->closeSalaryModal();
    }

    public function editSalaryStandard($id): void
    {
        $sal = EmployeeSalary::findOrFail($id);
        $this->editingSalaryId = $sal->id;
        $this->target_type = $sal->user_id ? 'user' : ($sal->worker_id ? 'worker' : 'custom');
        $this->user_id = $sal->user_id;
        $this->worker_id = $sal->worker_id;
        $this->employee_name = $sal->employee_name;
        $this->position = $sal->position ?? '';
        $this->basic_salary = $sal->basic_salary;
        $this->allowance = $sal->allowance;
        $this->bonus = $sal->bonus;
        $this->deductions = $sal->deductions;
        $this->bank_name = $sal->bank_name ?? '';
        $this->bank_account_number = $sal->bank_account_number ?? '';
        $this->bank_account_holder = $sal->bank_account_holder ?? '';
        $this->notes = $sal->notes ?? '';

        $this->showSalaryModal = true;
    }

    public function deleteSalaryStandard($id): void
    {
        $sal = EmployeeSalary::findOrFail($id);
        $name = $sal->employee_name;
        $pos = $sal->position ?? '-';
        $sal->delete();

        ActivityLogger::log('SALARY_STANDARD_DELETED', "Standar gaji karyawan {$name} ({$pos}) dihapus dari sistem oleh Founder.");

        session()->flash('success', 'Standar gaji ' . $name . ' berhasil dihapus.');
    }

    // Process Salary Payment & Generate Slip Gaji
    public function openPaymentModal($salaryId): void
    {
        $sal = EmployeeSalary::findOrFail($salaryId);
        $this->paymentSalaryId = $sal->id;
        $this->payment_employee_name = $sal->employee_name;
        $this->pay_basic_salary = $sal->basic_salary;
        $this->pay_allowance = $sal->allowance;
        $this->pay_bonus = $sal->bonus;
        $this->pay_deductions = $sal->deductions;
        $this->pay_bank_name = $sal->bank_name ?? 'BCA';
        $this->pay_account_number = $sal->bank_account_number ?? '';
        $this->payment_notes = 'Gaji bulan ' . $this->getIndonesianMonth($this->payroll_month) . ' ' . $this->payroll_year;
        $this->payment_date = date('Y-m-d');
        $this->receipt_photo = null;

        $this->showPaymentModal = true;
    }

    public function processPayment(): void
    {
        $this->validate([
            'payroll_month' => 'required|integer|between:1,12',
            'payroll_year' => 'required|integer|min:2020',
            'payment_date' => 'required|date',
            'pay_basic_salary' => 'required|numeric|min:0',
            'pay_allowance' => 'nullable|numeric|min:0',
            'pay_bonus' => 'nullable|numeric|min:0',
            'pay_deductions' => 'nullable|numeric|min:0',
            'receipt_photo' => 'nullable|image|max:2048',
        ]);

        $sal = EmployeeSalary::findOrFail($this->paymentSalaryId);

        $basic = (float) $this->pay_basic_salary;
        $allowance = (float) $this->pay_allowance;
        $bonus = (float) $this->pay_bonus;
        $deductions = (float) $this->pay_deductions;
        $netSalary = max(0, $basic + $allowance + $bonus - $deductions);

        $receiptPath = null;
        if ($this->receipt_photo) {
            $receiptPath = $this->receipt_photo->store('receipts/employee_salaries', 'public');
        }

        DB::transaction(function () use ($sal, $basic, $allowance, $bonus, $deductions, $netSalary, $receiptPath) {
            // Auto Record into Cashflow Transaction
            $cashflow = CashflowTransaction::create([
                'project_id' => null, // Global Office Expense
                'type' => 'keluar',
                'category' => 'operasional',
                'amount' => $netSalary,
                'transaction_date' => $this->payment_date,
                'description' => "Beban Gaji Karyawan: {$sal->employee_name} ({$sal->position}) - Periode " . $this->getIndonesianMonth($this->payroll_month) . " {$this->payroll_year}",
                'payment_method' => $this->payment_method,
                'receipt_photo_path' => $receiptPath,
                'created_by' => auth()->id(),
            ]);

            $payment = EmployeePayrollPayment::create([
                'employee_salary_id' => $sal->id,
                'payroll_month' => $this->payroll_month,
                'payroll_year' => $this->payroll_year,
                'payment_date' => $this->payment_date,
                'basic_salary' => $basic,
                'allowance' => $allowance,
                'bonus' => $bonus,
                'deductions' => $deductions,
                'net_salary' => $netSalary,
                'payment_method' => $this->payment_method,
                'bank_name' => $this->pay_bank_name ?: null,
                'account_number' => $this->pay_account_number ?: null,
                'receipt_photo_path' => $receiptPath,
                'notes' => $this->payment_notes ?: null,
                'cashflow_transaction_id' => $cashflow->id,
                'status' => 'dibayar',
                'paid_at' => now(),
                'created_by' => auth()->id(),
            ]);

            ActivityLogger::log('SALARY_PAID', "Pembayaran gaji karyawan {$sal->employee_name} sebesar Rp " . number_format($netSalary, 0, ',', '.') . " berhasil diproses.");
        });

        session()->flash('success', 'Pembayaran gaji ' . $sal->employee_name . ' berhasil diproses & Slip Gaji PDF terbit!');
        $this->showPaymentModal = false;
    }

    public function deletePaymentRecord($id): void
    {
        $payment = EmployeePayrollPayment::with('employeeSalary')->findOrFail($id);
        $empName = $payment->employeeSalary->employee_name ?? 'Karyawan';
        $period = $this->getIndonesianMonth($payment->payroll_month) . ' ' . $payment->payroll_year;

        if ($payment->cashflowTransaction) {
            $payment->cashflowTransaction->delete();
        }
        $payment->delete();

        ActivityLogger::log('EMPLOYEE_PAYROLL_DELETED', "Berkas histori penggajian karyawan {$empName} (Periode {$period}) dihapus dari sistem.");

        session()->flash('success', 'Histori penggajian berhasil dihapus.');
    }

    public function getIndonesianMonth(int $m): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$m] ?? 'Bulan ' . $m;
    }

    public function render()
    {
        $salariesQuery = EmployeeSalary::with(['user', 'worker', 'payrollPayments']);

        if ($this->search) {
            $s = '%' . trim($this->search) . '%';
            $salariesQuery->where(function ($q) use ($s) {
                $q->where('employee_name', 'like', $s)
                  ->orWhere('position', 'like', $s)
                  ->orWhere('bank_name', 'like', $s);
            });
        }

        $salaries = $salariesQuery->latest()->get();

        $paymentsQuery = EmployeePayrollPayment::with(['employeeSalary.user', 'creator', 'cashflowTransaction']);

        if ($this->search) {
            $s = '%' . trim($this->search) . '%';
            $paymentsQuery->whereHas('employeeSalary', function ($q) use ($s) {
                $q->where('employee_name', 'like', $s)
                  ->orWhere('position', 'like', $s);
            });
        }

        if ($this->selected_month) {
            $paymentsQuery->where('payroll_month', (int) $this->selected_month);
        }

        if ($this->selected_year) {
            $paymentsQuery->where('payroll_year', (int) $this->selected_year);
        }

        $payments = $paymentsQuery->latest()->paginate(15);

        // KPI Summary Metrics
        $totalMonthlySalaryPaid = EmployeePayrollPayment::where('payroll_month', (int) date('n'))
            ->where('payroll_year', (int) date('Y'))
            ->sum('net_salary');

        $totalEmployeesCount = EmployeeSalary::count();
        $totalPaymentsCount = EmployeePayrollPayment::count();

        $users = User::orderBy('name')->get();
        $workers = Worker::orderBy('name')->get();

        return view('livewire.employee-salaries.index', [
            'salaries' => $salaries,
            'payments' => $payments,
            'users' => $users,
            'workers' => $workers,
            'totalMonthlySalaryPaid' => $totalMonthlySalaryPaid,
            'totalEmployeesCount' => $totalEmployeesCount,
            'totalPaymentsCount' => $totalPaymentsCount,
        ])->layout('components.layouts.app', ['title' => 'Penetapan & Penggajian Karyawan']);
    }
}
