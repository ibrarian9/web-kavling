<?php

namespace App\Livewire\Units;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\Unit;
use App\Models\UnitCost;
use App\Models\UnitInstallment;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use App\Models\WorkerLoan;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkerLoanPayment;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public $unitId;

    // Modal Worker Assignment
    public bool $showWorkerModal = false;
    public ?int $worker_id = null;
    public string $assigned_role = 'Mandor Lapangan';

    // Modal Unit Cost
    public bool $showCostModal = false;
    public string $cost_category = 'tukang';
    public string $cost_description = '';
    public $cost_amount = 0;
    public string $cost_date = '';
    public string $vendor_name = '';
    public string $cost_status = 'dibayar';

    // Modal Booking
    public bool $showBookingModal = false;
    public string $buyer_name = '';
    public string $buyer_phone = '';
    public $booking_amount = 5000000;
    public $dp_amount = 25000000;
    public string $booking_notes = '';

    // Modal Worker Payroll Setup
    public bool $showPayrollSetupModal = false;
    public ?int $payroll_worker_id = null;
    public $payroll_agreed_salary = 0;
    public string $payroll_payment_frequency = 'fleksibel';
    public string $payroll_notes = '';

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

    // Modal Material Purchase (Catat Belanja Barang Unit)
    public bool $showMaterialModal = false;
    public ?int $material_worker_id = null;
    public string $material_purchase_date = '';
    public string $material_item_name = '';
    public $material_quantity = 1;
    public string $material_unit_measure = 'pcs';
    public $material_unit_price = 0;
    public $material_total_price = 0;
    public $material_receipt_photo = null;
    public string $material_notes = '';
    public bool $material_is_deducted_from_loan = false;

    // Viewer Modal (Jendela Melayang untuk Foto Struk, PDF Resi, & Barcode QR)
    public bool $showViewerModal = false;
    public string $viewerType = ''; // 'image', 'pdf', 'qr'
    public string $viewerUrl = '';
    public string $viewerTitle = '';

    public function openViewerModal(string $type, string $url, string $title = ''): void
    {
        $this->viewerType = $type;
        $this->viewerUrl = $url;
        $this->viewerTitle = $title ?: 'Pratinjau Dokumen / Resi';
        $this->showViewerModal = true;
    }

    public function closeViewerModal(): void
    {
        $this->showViewerModal = false;
        $this->viewerType = '';
        $this->viewerUrl = '';
        $this->viewerTitle = '';
    }

    public function mount($id)
    {
        $this->unitId = $id;
        $this->cost_date = now()->toDateString();
        $this->material_purchase_date = now()->toDateString();
    }

    public function openMaterialModal(): void
    {
        $this->resetValidation();
        $this->reset(['material_worker_id', 'material_item_name', 'material_unit_price', 'material_total_price', 'material_receipt_photo', 'material_notes']);
        $this->material_purchase_date = now()->toDateString();
        $this->material_quantity = 1;
        $this->material_unit_measure = 'pcs';
        $this->material_is_deducted_from_loan = false;
        $this->material_worker_id = Worker::where('status', 'active')->first()?->id;
        $this->showMaterialModal = true;
    }

    public function updatedMaterialQuantity(): void
    {
        $qty = is_numeric($this->material_quantity) ? (float)$this->material_quantity : 0;
        $price = is_numeric($this->material_unit_price) ? (float)$this->material_unit_price : 0;
        $this->material_total_price = $qty * $price;
    }

    public function updatedMaterialUnitPrice(): void
    {
        $qty = is_numeric($this->material_quantity) ? (float)$this->material_quantity : 0;
        $price = is_numeric($this->material_unit_price) ? (float)$this->material_unit_price : 0;
        $this->material_total_price = $qty * $price;
    }

    public function saveMaterialPurchase(): void
    {
        $this->validate([
            'material_purchase_date' => 'required|date',
            'material_item_name' => 'required|string|max:255',
            'material_quantity' => 'required|numeric|min:0.01',
            'material_unit_measure' => 'required|string|max:50',
            'material_unit_price' => 'required|numeric|min:0',
            'material_receipt_photo' => 'nullable|image|max:4096',
            'material_notes' => 'nullable|string',
        ]);

        $unit = Unit::findOrFail($this->unitId);
        $totalPrice = (float)$this->material_quantity * (float)$this->material_unit_price;

        $photoPath = null;
        if ($this->material_receipt_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->material_receipt_photo, 'material-receipts');
        }

        DB::transaction(function () use ($unit, $totalPrice, $photoPath) {
            $purchase = WeeklyMaterialPurchase::create([
                'project_id' => $unit->project_id,
                'unit_id' => $unit->id,
                'worker_id' => $this->material_worker_id ?: Worker::where('status', 'active')->first()?->id,
                'pengawas_id' => Auth::id(),
                'purchase_date' => $this->material_purchase_date,
                'item_name' => $this->material_item_name,
                'quantity' => $this->material_quantity,
                'unit_measure' => $this->material_unit_measure,
                'unit_price' => $this->material_unit_price,
                'total_price' => $totalPrice,
                'is_deducted_from_loan' => $this->material_is_deducted_from_loan,
                'receipt_photo_path' => $photoPath,
                'notes' => $this->material_notes,
            ]);

            UnitCost::create([
                'unit_id' => $unit->id,
                'project_id' => $unit->project_id,
                'category' => 'material',
                'description' => "Belanja Barang: {$this->material_item_name} ({$this->material_quantity} {$this->material_unit_measure})",
                'amount' => $totalPrice,
                'cost_date' => $this->material_purchase_date,
                'status' => 'dibayar',
                'created_by' => Auth::id(),
            ]);

            CashflowTransaction::create([
                'project_id' => $unit->project_id,
                'type' => 'keluar',
                'category' => 'operasional',
                'amount' => $totalPrice,
                'transaction_date' => $this->material_purchase_date,
                'description' => "Pembelian Material Unit {$unit->code}: {$this->material_item_name} ({$this->material_quantity} {$this->material_unit_measure})",
                'reference_type' => WeeklyMaterialPurchase::class,
                'reference_id' => $purchase->id,
                'created_by' => Auth::id(),
            ]);
        });

        session()->flash('success', 'Pembelian barang/material unit ' . $unit->code . ' berhasil dicatat!');
        $this->showMaterialModal = false;
    }

    public function openPayrollSetupModal(): void
    {
        $this->resetValidation();
        $this->reset(['payroll_worker_id', 'payroll_agreed_salary', 'payroll_notes']);
        $this->payroll_payment_frequency = 'fleksibel';
        $this->payroll_worker_id = Worker::where('status', 'active')->first()?->id;
        $this->showPayrollSetupModal = true;
    }

    public function savePayrollSetup(): void
    {
        $this->validate([
            'payroll_worker_id' => 'required|exists:workers,id',
            'payroll_agreed_salary' => 'required|numeric|min:10000',
            'payroll_payment_frequency' => 'required|in:harian,mingguan,bulanan,fleksibel',
        ]);

        $unit = Unit::findOrFail($this->unitId);

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

        session()->flash('success', 'Penetapan gaji unit ' . $unit->code . ' berhasil disimpan!');
        $this->showPayrollSetupModal = false;
    }

    public function openPayrollPaymentModal(int $payrollId): void
    {
        $this->resetValidation();
        $this->reset(['payroll_amount_gross', 'payroll_receipt_photo', 'payroll_payment_notes']);
        
        $this->selectedPayroll = WorkerUnitPayroll::with(['worker', 'project', 'unit'])->findOrFail($payrollId);
        $this->payroll_payment_date = now()->toDateString();
        $this->payroll_payment_method = 'transfer_bank';

        $this->showPayrollPaymentModal = true;
    }

    public function savePayrollPayment(): void
    {
        if (!$this->selectedPayroll) {
            return;
        }

        $remainingSalary = $this->selectedPayroll->remaining_salary;

        $this->validate([
            'payroll_payment_date' => 'required|date',
            'payroll_amount_gross' => 'required|numeric|min:1000|max:' . max(1000, $remainingSalary),
            'payroll_payment_method' => 'required|in:transfer_bank,tunai',
            'payroll_receipt_photo' => 'nullable|image|max:4096',
            'payroll_payment_notes' => 'nullable|string',
        ]);

        $amountGross = (float) $this->payroll_amount_gross;
        $amountPaid = $amountGross;

        $photoPath = null;
        if ($this->payroll_receipt_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->payroll_receipt_photo, 'payroll-receipts');
        }

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

            UnitCost::create([
                'unit_id' => $this->selectedPayroll->unit_id,
                'project_id' => $this->selectedPayroll->project_id,
                'category' => 'tukang',
                'description' => "Upah Borongan Gaji: {$this->selectedPayroll->worker->name}",
                'amount' => $amountGross,
                'cost_date' => $this->payroll_payment_date,
                'status' => 'dibayar',
                'created_by' => Auth::id(),
            ]);
        });

        session()->flash('success', 'Pembayaran gaji unit ' . $this->selectedPayroll->unit->code . ' berhasil disimpan!');
        $this->showPayrollPaymentModal = false;
        $this->selectedPayroll = null;
    }

    // 1. Worker Assignment Handler (Req #3)
    public function openWorkerModal(): void
    {
        $this->resetValidation();
        $this->worker_id = Worker::where('status', 'active')->first()?->id;
        $this->assigned_role = 'Mandor Unit';
        $this->showWorkerModal = true;
    }

    public function saveWorkerAssignment(): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isSupervisor() && !$user->isPengawasProject()) {
            session()->flash('error', 'Hanya tim operasional lapangan yang berhak menugaskan pekerja.');
            return;
        }

        $this->validate([
            'worker_id' => 'required|exists:workers,id',
            'assigned_role' => 'required|string|max:255',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        WorkerAssignment::create([
            'worker_id' => $this->worker_id,
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'assigned_role' => $this->assigned_role,
            'start_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        session()->flash('success', 'Pekerja berhasil ditugaskan langsung pada unit ' . $unit->code . '!');
        $this->showWorkerModal = false;
    }

    // 2. Unit Cost Handler (Req #4)
    public function openCostModal(): void
    {
        $this->resetValidation();
        $this->cost_category = 'tukang';
        $this->cost_description = '';
        $this->cost_amount = 0;
        $this->cost_date = now()->toDateString();
        $this->vendor_name = '';
        $this->cost_status = 'dibayar';
        $this->showCostModal = true;
    }

    public function saveUnitCost(): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance() && !$user->isPengawasProject()) {
            session()->flash('error', 'Hanya Pengawas Project, Finance, dan Founder yang berhak mencatat biaya unit.');
            return;
        }

        $this->validate([
            'cost_category' => 'required|in:tukang,material,perizinan,lainnya',
            'cost_description' => 'required|string|max:255',
            'cost_amount' => 'required|numeric|min:1000',
            'cost_date' => 'required|date',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        $cost = UnitCost::create([
            'unit_id' => $unit->id,
            'project_id' => $unit->project_id,
            'category' => $this->cost_category,
            'description' => $this->cost_description,
            'amount' => $this->cost_amount,
            'cost_date' => $this->cost_date,
            'vendor_name' => $this->vendor_name,
            'status' => $this->cost_status,
            'created_by' => Auth::id(),
        ]);

        if ($this->cost_status === 'dibayar') {
            $categoryMapping = [
                'tukang' => 'pembayaran_tukang',
                'material' => 'operasional',
                'perizinan' => 'operasional',
                'lainnya' => 'lainnya',
            ];

            CashflowTransaction::create([
                'project_id' => $unit->project_id,
                'type' => 'keluar',
                'category' => $categoryMapping[$this->cost_category] ?? 'operasional',
                'amount' => $this->cost_amount,
                'transaction_date' => $this->cost_date,
                'description' => 'Biaya Unit ' . $unit->code . ' (' . ucfirst($this->cost_category) . '): ' . $this->cost_description,
                'reference_type' => UnitCost::class,
                'reference_id' => $cost->id,
                'created_by' => Auth::id(),
            ]);
        }

        session()->flash('success', 'Biaya pengeluaran unit ' . $unit->code . ' berhasil dicatat!');
        $this->showCostModal = false;
    }

    // 3. Direct Booking Handler (Req #2)
    public function openBookingModal(): void
    {
        $unit = Unit::findOrFail($this->unitId);
        $this->buyer_name = '';
        $this->buyer_phone = '';
        $this->booking_amount = 5000000;
        $this->dp_amount = 0;
        $this->booking_notes = 'Booking unit ' . $unit->code . ' via Halaman Detail Unit.';
        $this->showBookingModal = true;
    }

    public function saveBooking(): void
    {
        $user = auth()->user();
        if (!$user->isMarketing() && !$user->isFinance() && !$user->isFounder()) {
            session()->flash('error', 'Hanya tim Sales Marketing, Finance, dan Founder yang berhak mendaftarkan booking unit.');
            return;
        }

        $this->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:50',
            'booking_amount' => 'required|numeric|min:1000',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        Booking::create([
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'buyer_name' => $this->buyer_name,
            'buyer_phone' => $this->buyer_phone,
            'booking_type' => 'unit',
            'booking_amount' => $this->booking_amount,
            'dp_amount' => 0,
            'booking_date' => now()->toDateString(),
            'expiry_date' => now()->addDays(14)->toDateString(),
            'status' => 'active',
            'notes' => $this->booking_notes,
            'created_by' => Auth::id(),
        ]);


        $unit->update(['status' => 'booked']);

        session()->flash('success', 'Booking unit ' . $unit->code . ' atas nama ' . $this->buyer_name . ' berhasil dicatat!');
        $this->showBookingModal = false;
    }

    public function render()
    {
        $unit = Unit::with([
            'project',
            'creator',
            'proposals.proposer',
            'proposals.approvals.approver',
            'officialDocument.issuer',
            'installment.payments.creator',
            'costs',
        ])->findOrFail($this->unitId);

        $unitAssignments = WorkerAssignment::with('worker')
            ->where('status', 'active')
            ->where(function($q) use ($unit) {
                $q->where('unit_id', $unit->id)
                  ->orWhere(function($subQ) use ($unit) {
                      $subQ->where('project_id', $unit->project_id)->whereNull('unit_id');
                  });
            })
            ->get();

        $materialPurchases = WeeklyMaterialPurchase::with(['worker', 'pengawas', 'workerLoan'])
            ->where('unit_id', $unit->id)
            ->latest('purchase_date')
            ->get();

        $workerLoans = WorkerLoan::with(['worker', 'approver', 'payments'])
            ->where('unit_id', $unit->id)
            ->latest('loan_date')
            ->get();

        $unitCosts = UnitCost::where('unit_id', $unit->id)->get();
        $totalCosts = $unitCosts->sum('amount');
        $paidCosts = $unitCosts->where('status', 'dibayar')->sum('amount');
        $unpaidCosts = $unitCosts->where('status', 'belum_dibayar')->sum('amount');

        $totalCashIn = 0;
        if ($unit->installment) {
            $totalCashIn += $unit->installment->down_payment;
            $totalCashIn += $unit->installment->payments->sum('amount_paid');
        }

        $unitPayrolls = WorkerUnitPayroll::with(['worker', 'payments'])
            ->where('unit_id', $unit->id)
            ->latest('id')
            ->get();

        $salaryPayments = WorkerSalaryPayment::whereHas('payroll', function($q) use ($unit) {
            $q->where('unit_id', $unit->id);
        })->with(['payroll.worker'])->get();

        $materialPurchases = WeeklyMaterialPurchase::with(['worker', 'pengawas'])
            ->where('unit_id', $unit->id)
            ->get();

        $otherCosts = UnitCost::where('unit_id', $unit->id)
            ->whereNotIn('category', ['tukang', 'material'])
            ->get();

        $combinedExpenses = collect();

        foreach ($salaryPayments as $sp) {
            $combinedExpenses->push((object)[
                'id' => 'sp_' . $sp->id,
                'date' => $sp->payment_date,
                'category_badge' => 'Gaji Worker',
                'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'description' => 'Gaji ' . $sp->payroll->worker->name . ' (' . str_replace('_', ' ', $sp->payment_method) . ')',
                'amount' => $sp->amount_paid,
                'gross_amount' => $sp->amount_gross,
                'loan_deduction' => $sp->loan_deduction,
                'receipt_photo_path' => $sp->receipt_photo_path,
                'pdf_url' => route('payroll.receipt', $sp->uuid),
                'qr_url' => route('verify.payroll', $sp->uuid),
                'created_at' => $sp->created_at,
            ]);
        }

        foreach ($materialPurchases as $mp) {
            $combinedExpenses->push((object)[
                'id' => 'mp_' . $mp->id,
                'date' => $mp->purchase_date,
                'category_badge' => 'Barang / Material',
                'badge_class' => 'bg-amber-100 text-amber-800 border-amber-200',
                'description' => $mp->item_name . ' (' . number_format($mp->quantity, 0, ',', '.') . ' ' . $mp->unit_measure . ' @ Rp ' . number_format($mp->unit_price, 0, ',', '.') . ')' . ($mp->worker ? ' oleh ' . $mp->worker->name : ''),
                'amount' => $mp->total_price,
                'gross_amount' => $mp->total_price,
                'loan_deduction' => 0,
                'receipt_photo_path' => $mp->receipt_photo_path,
                'pdf_url' => null,
                'qr_url' => null,
                'created_at' => $mp->created_at,
            ]);
        }

        foreach ($otherCosts as $uc) {
            $combinedExpenses->push((object)[
                'id' => 'uc_' . $uc->id,
                'date' => $uc->cost_date,
                'category_badge' => ucfirst($uc->category),
                'badge_class' => 'bg-slate-100 text-slate-800 border-slate-200',
                'description' => $uc->description . ($uc->vendor_name ? ' (' . $uc->vendor_name . ')' : ''),
                'amount' => $uc->amount,
                'gross_amount' => $uc->amount,
                'loan_deduction' => 0,
                'receipt_photo_path' => null,
                'pdf_url' => null,
                'qr_url' => null,
                'created_at' => $uc->created_at,
            ]);
        }

        $combinedExpenses = $combinedExpenses->sortByDesc(function ($item) {
            return ($item->date ? $item->date->format('Y-m-d') : '0000-00-00') . '_' . $item->id;
        })->values();

        $allWorkers = Worker::where('status', 'active')->orderBy('name')->get();

        return view('livewire.units.show', [
            'unit' => $unit,
            'unitAssignments' => $unitAssignments,
            'materialPurchases' => $materialPurchases,
            'workerLoans' => $workerLoans,
            'unitCosts' => $unitCosts,
            'totalCosts' => $totalCosts,
            'paidCosts' => $paidCosts,
            'unpaidCosts' => $unpaidCosts,
            'totalCashIn' => $totalCashIn,
            'allWorkers' => $allWorkers,
            'unitPayrolls' => $unitPayrolls,
            'combinedExpenses' => $combinedExpenses,
            'showWorkerModal' => $this->showWorkerModal,
            'showCostModal' => $this->showCostModal,
            'showBookingModal' => $this->showBookingModal,
            'showPayrollSetupModal' => $this->showPayrollSetupModal,
            'showPayrollPaymentModal' => $this->showPayrollPaymentModal,
            'showMaterialModal' => $this->showMaterialModal,
            'showViewerModal' => $this->showViewerModal,
        ])->layout('components.layouts.app', ['title' => 'Detail Unit ' . $unit->code . ' - ' . $unit->project->name]);
    }
}
