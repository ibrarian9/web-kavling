<?php

namespace App\Livewire\Units\Concerns;

use App\Models\CashflowTransaction;
use App\Models\Unit;
use App\Models\Worker;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use App\Services\ActivityLogger;
use App\Services\ImageCompressor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HasUnitPayroll
{
    // Modal Worker Payroll Setup
    public bool $showPayrollSetupModal = false;
    public ?int $payroll_worker_id = null;
    public $payroll_agreed_salary = 0;
    public string $payroll_payment_frequency = 'fleksibel';
    public string $payroll_notes = '';
    public ?int $editingPayrollId = null;

    // Modal Worker Payroll Payment
    public bool $showPayrollPaymentModal = false;
    public ?WorkerUnitPayroll $selectedPayroll = null;
    public string $payroll_payment_date = '';
    public $payroll_amount_gross = 0;
    public $payroll_loan_deduction = 0;
    public string $payroll_payment_method = 'transfer_bank';
    public string $payroll_bank_name = '';
    public string $payroll_account_number = '';
    public $payroll_receipt_photo = null;
    public string $payroll_payment_notes = '';
    public $payroll_active_worker_loan = 0;
    public ?int $editingSalaryPaymentId = null;

    public function openPayrollSetupModal(): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $this->resetValidation();
        $this->editingPayrollId = null;
        $this->reset(['payroll_worker_id', 'payroll_agreed_salary', 'payroll_notes']);
        $this->payroll_payment_frequency = 'fleksibel';
        $this->payroll_worker_id = Worker::where('status', 'active')->first()?->id;
        $this->showPayrollSetupModal = true;
    }

    public function editPayrollSetup(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $up = WorkerUnitPayroll::findOrFail($id);
        $this->resetValidation();
        $this->editingPayrollId = $up->id;
        $this->payroll_worker_id = $up->worker_id;
        $this->payroll_agreed_salary = $up->agreed_salary;
        $this->payroll_payment_frequency = $up->payment_frequency;
        $this->payroll_notes = $up->notes ?? '';
        $this->showPayrollSetupModal = true;
    }

    public function deletePayrollSetup(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $up = WorkerUnitPayroll::with(['worker', 'unit'])->findOrFail($id);
        $workerName = $up->worker->name ?? 'Worker';
        $unitCode = $up->unit->code ?? $this->unitId;
        $userName = auth()->user()->name ?? 'User';

        DB::transaction(function () use ($up) {
            foreach ($up->payments as $sp) {
                CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                    ->where('reference_id', $sp->id)
                    ->delete();
                $sp->delete();
            }
            $up->delete();
        });

        ActivityLogger::log(
            'PAYROLL_SETUP_DELETED',
            "Penetapan gaji borongan Unit {$unitCode} untuk {$workerName} dihapus oleh {$userName}."
        );

        session()->flash('success', 'Penetapan gaji borongan unit berhasil dihapus!');
    }

    public function savePayrollSetup(): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $this->validate([
            'payroll_worker_id' => 'required|exists:workers,id',
            'payroll_agreed_salary' => 'required|numeric|min:10000',
            'payroll_payment_frequency' => 'required|in:harian,mingguan,bulanan,fleksibel',
        ]);

        $unit = Unit::findOrFail($this->unitId);
        $worker = Worker::find($this->payroll_worker_id);
        $workerName = $worker->name ?? 'Worker';
        $userName = auth()->user()->name ?? 'User';
        $salaryFormatted = number_format($this->payroll_agreed_salary, 0, ',', '.');

        if ($this->editingPayrollId) {
            $up = WorkerUnitPayroll::findOrFail($this->editingPayrollId);
            $up->update([
                'worker_id' => $this->payroll_worker_id,
                'agreed_salary' => $this->payroll_agreed_salary,
                'payment_frequency' => $this->payroll_payment_frequency,
                'notes' => $this->payroll_notes,
            ]);

            ActivityLogger::log(
                'PAYROLL_SETUP_UPDATED',
                "Penetapan gaji borongan Unit {$unit->code} untuk {$workerName} (Rp {$salaryFormatted}) diperbarui oleh {$userName}."
            );

            session()->flash('success', 'Penetapan gaji borongan unit berhasil diperbarui!');
        } else {
            WorkerUnitPayroll::create([
                'worker_id' => $this->payroll_worker_id,
                'project_id' => $unit->project_id,
                'unit_id' => $unit->id,
                'agreed_salary' => $this->payroll_agreed_salary,
                'payment_frequency' => $this->payroll_payment_frequency,
                'status' => 'berjalan',
                'notes' => $this->payroll_notes,
                'created_by' => Auth::id(),
            ]);

            ActivityLogger::log(
                'PAYROLL_SETUP_CREATED',
                "Penetapan gaji borongan Unit {$unit->code} untuk {$workerName} (Rp {$salaryFormatted}) dibuat oleh {$userName}."
            );

            session()->flash('success', 'Penetapan gaji unit ' . $unit->code . ' berhasil disimpan!');
        }

        $this->showPayrollSetupModal = false;
        $this->editingPayrollId = null;
    }

    public function openPayrollPaymentModal(int $payrollId): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $this->resetValidation();
        $this->editingSalaryPaymentId = null;
        $this->reset(['payroll_amount_gross', 'payroll_receipt_photo', 'payroll_payment_notes']);

        $this->selectedPayroll = WorkerUnitPayroll::with(['worker', 'project', 'unit'])->findOrFail($payrollId);
        $this->payroll_payment_date = now()->toDateString();
        $this->payroll_payment_method = 'transfer_bank';

        $this->showPayrollPaymentModal = true;
    }

    public function editPayrollPayment(int $salaryPaymentId): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $sp = WorkerSalaryPayment::with(['payroll.worker', 'payroll.project', 'payroll.unit'])->findOrFail($salaryPaymentId);
        $this->resetValidation();
        $this->editingSalaryPaymentId = $sp->id;
        $this->selectedPayroll = $sp->payroll;
        $this->payroll_payment_date = $sp->payment_date ? $sp->payment_date->format('Y-m-d') : date('Y-m-d');
        $this->payroll_amount_gross = $sp->amount_gross;
        $this->payroll_payment_method = $sp->payment_method ?? 'transfer_bank';
        $this->payroll_payment_notes = $sp->notes ?? '';
        $this->payroll_receipt_photo = null;

        $this->showPayrollPaymentModal = true;
    }

    public function deletePayrollPayment(int $salaryPaymentId): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $sp = WorkerSalaryPayment::with(['payroll.worker', 'payroll.unit'])->findOrFail($salaryPaymentId);
        $payroll = $sp->payroll;
        $workerName = $payroll->worker->name ?? 'Worker';
        $unitCode = $payroll->unit->code ?? $this->unitId;
        $oldGross = (float)$sp->amount_gross;
        $userName = auth()->user()->name ?? 'User';

        DB::transaction(function () use ($sp, $payroll, $oldGross) {
            CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                ->where('reference_id', $sp->id)
                ->delete();

            $sp->delete();

            if ($payroll) {
                $newPaidTotal = max(0, (float)$payroll->paid_amount - $oldGross);
                $status = $newPaidTotal >= (float)$payroll->agreed_salary ? 'lunas' : 'berjalan';
                $payroll->update([
                    'paid_amount' => $newPaidTotal,
                    'status' => $status,
                ]);
            }
        });

        ActivityLogger::log(
            'PAYROLL_PAYMENT_DELETED',
            "Pencatatan pembayaran gaji worker {$workerName} (Rp " . number_format($oldGross, 0, ',', '.') . ") pada Unit {$unitCode} dihapus oleh {$userName}."
        );

        session()->flash('success', 'Pencatatan pembayaran gaji worker berhasil dihapus!');
    }

    public function savePayrollPayment(): void
    {
        if (!$this->selectedPayroll) {
            return;
        }

        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $sp = $this->editingSalaryPaymentId ? WorkerSalaryPayment::find($this->editingSalaryPaymentId) : null;
        $maxAllowed = (float)$this->selectedPayroll->remaining_salary + ($sp ? (float)$sp->amount_gross : 0);

        $this->validate([
            'payroll_payment_date' => 'required|date',
            'payroll_amount_gross' => 'required|numeric|min:1000|max:' . max(1000, $maxAllowed),
            'payroll_payment_method' => 'required|in:transfer_bank,tunai',
            'payroll_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
            'payroll_payment_notes' => 'nullable|string',
        ]);

        $amountGross = (float) $this->payroll_amount_gross;
        $amountPaid = $amountGross;

        $photoPath = null;
        if ($this->payroll_receipt_photo) {
            $photoPath = ImageCompressor::compressAndStore($this->payroll_receipt_photo, 'payroll-receipts');
        }

        $workerName = $this->selectedPayroll->worker->name ?? 'Worker';
        $unitCode = $this->selectedPayroll->unit->code ?? $this->unitId;
        $userName = auth()->user()->name ?? 'User';
        $paidFormatted = number_format($amountPaid, 0, ',', '.');

        if ($this->editingSalaryPaymentId && $sp) {
            $oldGross = (float)$sp->amount_gross;
            $finalPhoto = $photoPath ?: $sp->receipt_photo_path;

            DB::transaction(function () use ($sp, $amountGross, $amountPaid, $oldGross, $finalPhoto) {
                $sp->update([
                    'payment_date' => $this->payroll_payment_date,
                    'amount_gross' => $amountGross,
                    'amount_paid' => $amountPaid,
                    'payment_method' => $this->payroll_payment_method,
                    'receipt_photo_path' => $finalPhoto,
                    'notes' => $this->payroll_payment_notes,
                ]);

                $newPaidTotal = (float)$this->selectedPayroll->paid_amount - $oldGross + $amountGross;
                $status = $newPaidTotal >= (float)$this->selectedPayroll->agreed_salary ? 'lunas' : 'berjalan';

                $this->selectedPayroll->update([
                    'paid_amount' => $newPaidTotal,
                    'status' => $status,
                ]);

                $cashflow = CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                    ->where('reference_id', $sp->id)
                    ->first();

                if ($cashflow) {
                    $cashflow->update([
                        'amount' => $amountPaid,
                        'transaction_date' => $this->payroll_payment_date,
                        'description' => "Gaji Worker: {$this->selectedPayroll->worker->name} (Unit {$this->selectedPayroll->unit->code}) - Rp " . number_format($amountPaid, 0, ',', '.'),
                    ]);
                }
            });

            ActivityLogger::log(
                'PAYROLL_PAYMENT_UPDATED',
                "Pembayaran gaji worker {$workerName} pada Unit {$unitCode} diperbarui menjadi Rp {$paidFormatted} oleh {$userName}."
            );

            session()->flash('success', 'Pembayaran gaji unit ' . $this->selectedPayroll->unit->code . ' berhasil diperbarui! Perubahan sudah otomatis memperbarui PDF Resi & QR Code.');
        } else {
            DB::transaction(function () use ($amountGross, $amountPaid, $photoPath) {
                $payment = WorkerSalaryPayment::create([
                    'worker_unit_payroll_id' => $this->selectedPayroll->id,
                    'payment_date' => $this->payroll_payment_date,
                    'amount_gross' => $amountGross,
                    'loan_deduction' => 0,
                    'amount_paid' => $amountPaid,
                    'payment_method' => $this->payroll_payment_method,
                    'bank_name' => null,
                    'account_number' => null,
                    'receipt_photo_path' => $photoPath,
                    'notes' => $this->payroll_payment_notes,
                    'created_by' => Auth::id(),
                ]);

                $newPaidTotal = (float)$this->selectedPayroll->paid_amount + $amountGross;
                $status = $newPaidTotal >= (float)$this->selectedPayroll->agreed_salary ? 'lunas' : 'berjalan';

                $this->selectedPayroll->update([
                    'paid_amount' => $newPaidTotal,
                    'status' => $status,
                ]);

                CashflowTransaction::create([
                    'project_id' => $this->selectedPayroll->project_id,
                    'type' => 'keluar',
                    'category' => 'pembayaran_tukang',
                    'amount' => $amountPaid,
                    'transaction_date' => $this->payroll_payment_date,
                    'description' => "Gaji Worker: {$this->selectedPayroll->worker->name} (Unit {$this->selectedPayroll->unit->code}) - Rp " . number_format($amountPaid, 0, ',', '.'),
                    'reference_type' => WorkerSalaryPayment::class,
                    'reference_id' => $payment->id,
                    'created_by' => Auth::id(),
                ]);
            });

            ActivityLogger::log(
                'PAYROLL_PAYMENT_RECORDED',
                "Pembayaran gaji worker {$workerName} pada Unit {$unitCode} dicatat sebesar Rp {$paidFormatted} oleh {$userName}."
            );

            session()->flash('success', 'Pembayaran gaji unit ' . $this->selectedPayroll->unit->code . ' berhasil disimpan!');
        }

        $this->showPayrollPaymentModal = false;
        $this->selectedPayroll = null;
        $this->editingSalaryPaymentId = null;
    }
}
