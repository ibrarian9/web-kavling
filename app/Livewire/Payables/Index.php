<?php

namespace App\Livewire\Payables;

use App\Models\CashflowTransaction;
use App\Models\CompanyReceivable;
use App\Models\Project;
use App\Models\ReceivablePayment;
use App\Models\Unit;
use App\Models\UnitCommission;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Livewire\Traits\WithFileUploadValidation;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;
    use WithFileUploadValidation;

    public string $activeTab = 'material_bills'; // 'material_bills', 'worker_payrolls', 'unit_commissions', 'company_receivables'

    // Filter properties
    public string $search = '';
    public string $filter_project_id = '';
    public string $filter_status = 'belum_lunas'; // 'belum_lunas', 'lunas', 'all'

    // Settlement Modal Properties for Material Bill (Tab 1)
    public bool $showSettleModal = false;
    public ?int $settlingMaterialId = null;
    public string $settle_payment_date = '';
    public string $settle_payment_method = 'Transfer Bank';
    public string $settle_notes = '';
    public $settle_receipt_photo = null;

    // Settlement Modal Properties for Worker Wage (Tab 2)
    public bool $showWorkerPaymentModal = false;
    public ?int $settlingPayrollId = null;
    public float $worker_payment_amount = 0;
    public string $worker_payment_date = '';
    public string $worker_payment_method = 'Transfer Bank';
    public string $worker_payment_notes = '';

    // Create New Payable Bill Modal Properties (General Operational Debt)
    public bool $showCreateBillModal = false;
    public string $new_project_id = '';
    public string $new_unit_id = '';
    public string $new_store_name = '';
    public string $new_item_name = '';
    public $new_quantity = 1;
    public string $new_unit_measure = 'paket';
    public $new_unit_price = 0;
    public $new_total_price = 0;
    public string $new_purchase_date = '';
    public string $new_payment_status = 'belum_lunas';
    public string $new_notes = '';
    public $new_receipt_photo = null;

    // Tab 3: Unit Commission Modal Properties (Hutang Komisi Penjual)
    public bool $showCreateCommissionModal = false;
    public string $comm_project_id = '';
    public string $comm_unit_id = '';
    public string $comm_marketing_id = '';
    public string $comm_seller_name = '';
    public string $comm_seller_phone = '';
    public $comm_percentage = 0;
    public $comm_amount = 0;
    public string $comm_notes = '';

    public bool $showSettleCommissionModal = false;
    public ?int $settlingCommissionId = null;
    public float $settle_comm_amount = 0;
    public string $settle_comm_date = '';
    public string $settle_comm_method = 'Transfer Bank';
    public string $settle_comm_notes = '';
    public $settle_comm_photo = null;

    // Tab 4: Company Receivables / Kasbon Modal Properties (Piutang Perusahaan)
    public bool $showCreateReceivableModal = false;
    public string $rec_debtor_type = 'other'; // 'worker', 'user', 'other'
    public string $rec_debtor_name = '';
    public string $rec_worker_id = '';
    public string $rec_user_id = '';
    public $rec_amount = 0;
    public string $rec_loan_date = '';
    public string $rec_notes = '';

    public bool $showPayReceivableModal = false;
    public ?int $settlingReceivableId = null;
    public $pay_rec_amount = 0;
    public string $pay_rec_date = '';
    public string $pay_rec_method = 'Cash / Tunai';
    public string $pay_rec_notes = '';
    public $pay_rec_photo = null;

    protected $queryString = [
        'activeTab' => ['except' => 'material_bills'],
        'search' => ['except' => ''],
        'filter_project_id' => ['except' => ''],
        'filter_status' => ['except' => 'belum_lunas'],
    ];

    public function mount()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $this->settle_payment_date = now()->toDateString();
        $this->worker_payment_date = now()->toDateString();
        $this->new_purchase_date = now()->toDateString();
        $this->settle_comm_date = now()->toDateString();
        $this->rec_loan_date = now()->toDateString();
        $this->pay_rec_date = now()->toDateString();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterProjectId(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // --- TAB 1 ACTIONS (Belanja Toko & Operational Debt) ---
    public function openCreateBillModal(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat tagihan operasional.');
            return;
        }

        $this->resetValidation();
        $this->new_project_id = '';
        $this->new_unit_id = '';
        $this->new_store_name = '';
        $this->new_item_name = '';
        $this->new_quantity = 1;
        $this->new_unit_measure = 'paket';
        $this->new_unit_price = 0;
        $this->new_total_price = 0;
        $this->new_purchase_date = now()->toDateString();
        $this->new_payment_status = 'belum_lunas';
        $this->new_notes = '';
        $this->new_receipt_photo = null;
        $this->showCreateBillModal = true;
    }

    public function updatedNewQuantity(): void
    {
        $qty = is_numeric($this->new_quantity) ? (float)$this->new_quantity : 0;
        $price = is_numeric($this->new_unit_price) ? (float)$this->new_unit_price : 0;
        $this->new_total_price = $qty * $price;
    }

    public function updatedNewUnitPrice(): void
    {
        $qty = is_numeric($this->new_quantity) ? (float)$this->new_quantity : 0;
        $price = is_numeric($this->new_unit_price) ? (float)$this->new_unit_price : 0;
        $this->new_total_price = $qty * $price;
    }

    public function saveNewBill(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat tagihan operasional.');
            return;
        }

        $this->validate([
            'new_purchase_date' => 'required|date',
            'new_item_name' => 'required|string|max:255',
            'new_store_name' => 'nullable|string|max:255',
            'new_payment_status' => 'required|in:lunas,belum_lunas',
            'new_quantity' => 'required|numeric|min:0.01',
            'new_unit_measure' => 'required|string|max:50',
            'new_unit_price' => 'required|numeric|min:0',
            'new_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
            'new_notes' => 'nullable|string',
        ]);

        $totalPrice = (float)$this->new_quantity * (float)$this->new_unit_price;
        $projectId = $this->new_project_id ? (int)$this->new_project_id : null;
        $unitId = $this->new_unit_id ? (int)$this->new_unit_id : null;

        $photoPath = null;
        if ($this->new_receipt_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->new_receipt_photo, 'material-receipts');
        }

        DB::transaction(function () use ($projectId, $unitId, $totalPrice, $photoPath, $user) {
            $purchase = WeeklyMaterialPurchase::create([
                'project_id' => $projectId,
                'unit_id' => $unitId,
                'worker_id' => null,
                'pengawas_id' => $user->id,
                'purchase_date' => $this->new_purchase_date,
                'item_name' => $this->new_item_name,
                'store_name' => $this->new_store_name,
                'quantity' => $this->new_quantity,
                'unit_measure' => $this->new_unit_measure,
                'unit_price' => $this->new_unit_price,
                'total_price' => $totalPrice,
                'payment_status' => $this->new_payment_status,
                'paid_at' => $this->new_payment_status === 'lunas' ? $this->new_purchase_date : null,
                'paid_by' => $this->new_payment_status === 'lunas' ? $user->id : null,
                'receipt_photo_path' => $photoPath,
                'notes' => $this->new_notes,
            ]);

            if ($this->new_payment_status === 'lunas') {
                $storeInfo = $this->new_store_name ? " (Toko/Vendor: {$this->new_store_name})" : '';
                $scopeInfo = $projectId ? " (Proyek #{$projectId})" : ' (Operasional Umum / Non-Proyek)';
                $description = "Pelunasan Tagihan Operasional{$scopeInfo}{$storeInfo}: {$this->new_item_name}";

                CashflowTransaction::create([
                    'project_id' => $projectId,
                    'type' => 'keluar',
                    'category' => 'operasional',
                    'amount' => $totalPrice,
                    'transaction_date' => $this->new_purchase_date,
                    'description' => $description,
                    'reference_type' => WeeklyMaterialPurchase::class,
                    'reference_id' => $purchase->id,
                    'receipt_photo_path' => $photoPath,
                    'created_by' => $user->id,
                ]);
            }
        });

        \App\Services\ActivityLogger::log(
            'OPERATIONAL_BILL_CREATED',
            "User {$user->name} mencatat tagihan operasional baru '{$this->new_item_name}' (Rp " . number_format($totalPrice, 0, ',', '.') . ") status " . strtoupper($this->new_payment_status)
        );

        $this->showCreateBillModal = false;
        $msg = "Catatan Tagihan Operasional baru berhasil disimpan!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil Dicatat!', 'message' => $msg]);
    }

    public function openSettleModal(int $materialId): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mengonfirmasi pelunasan tagihan.');
            return;
        }

        $mat = WeeklyMaterialPurchase::with(['unit', 'project'])->findOrFail($materialId);
        $this->settlingMaterialId = $mat->id;
        $this->settle_payment_date = now()->toDateString();
        $this->settle_payment_method = 'Transfer Bank';
        $this->settle_notes = "Pelunasan Tagihan Material: {$mat->item_name} (" . ($mat->store_name ?: 'Toko Material') . ")";
        $this->settle_receipt_photo = null;
        $this->showSettleModal = true;
    }

    public function processMaterialSettlement(): void
    {
        if (!$this->settlingMaterialId) {
            return;
        }

        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mengonfirmasi pelunasan tagihan.');
            return;
        }

        $this->validate([
            'settle_payment_date' => 'required|date',
            'settle_payment_method' => 'required|string',
            'settle_notes' => 'nullable|string',
            'settle_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
        ]);

        $mat = WeeklyMaterialPurchase::with(['unit', 'project'])->findOrFail($this->settlingMaterialId);

        $photoPath = $mat->receipt_photo_path;
        if ($this->settle_receipt_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->settle_receipt_photo, 'material-receipts');
        }

        DB::transaction(function () use ($mat, $photoPath, $user) {
            $mat->update([
                'payment_status' => 'lunas',
                'paid_at' => $this->settle_payment_date,
                'paid_by' => $user->id,
                'receipt_photo_path' => $photoPath,
                'notes' => $this->settle_notes ?: $mat->notes,
            ]);

            $storeInfo = $mat->store_name ? " (Toko: {$mat->store_name})" : '';
            $unitCode = $mat->unit ? $mat->unit->code : '-';
            $description = "Pelunasan Tagihan Material Unit {$unitCode}{$storeInfo}: {$mat->item_name} ({$mat->quantity} {$mat->unit_measure})";

            CashflowTransaction::updateOrCreate(
                [
                    'reference_type' => WeeklyMaterialPurchase::class,
                    'reference_id' => $mat->id,
                ],
                [
                    'project_id' => $mat->project_id,
                    'type' => 'keluar',
                    'category' => 'material',
                    'amount' => $mat->total_price,
                    'transaction_date' => $this->settle_payment_date,
                    'description' => $description,
                    'receipt_photo_path' => $photoPath,
                    'created_by' => $user->id,
                ]
            );
        });

        \App\Services\ActivityLogger::log(
            'MATERIAL_BILL_SETTLED',
            "User {$user->name} melunasi tagihan material toko #MAT-{$mat->id} ({$mat->item_name}) sebesar Rp " . number_format($mat->total_price, 0, ',', '.') . " & tercatat di Arus Kas Global."
        );

        $this->showSettleModal = false;
        $this->settlingMaterialId = null;

        $msg = "Tagihan material '{$mat->item_name}' berhasil dilunasi & dicatat ke Kas Keluar!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil Lunas!', 'message' => $msg]);
    }

    // --- TAB 2 ACTIONS (Sisa Upah Worker) ---
    public function openWorkerPaymentModal(int $payrollId): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat pembayaran upah tukang.');
            return;
        }

        $payroll = WorkerUnitPayroll::with(['worker', 'unit', 'project', 'payments'])->findOrFail($payrollId);
        $remaining = max(0, (float)$payroll->agreed_salary - (float)$payroll->paid_amount);

        $this->settlingPayrollId = $payroll->id;
        $this->worker_payment_amount = $remaining;
        $this->worker_payment_date = now()->toDateString();
        $this->worker_payment_method = 'Transfer Bank';
        $this->worker_payment_notes = "Pembayaran sisa upah: " . ($payroll->notes ?: 'Upah Kontrak Worker') . " (" . ($payroll->worker->name ?? 'Tukang') . ")";
        $this->showWorkerPaymentModal = true;
    }

    public function processWorkerPayment(): void
    {
        if (!$this->settlingPayrollId) {
            return;
        }

        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat pembayaran upah tukang.');
            return;
        }

        $this->validate([
            'worker_payment_amount' => 'required|numeric|min:1000',
            'worker_payment_date' => 'required|date',
            'worker_payment_method' => 'required|string',
            'worker_payment_notes' => 'nullable|string',
        ]);

        $payroll = WorkerUnitPayroll::with(['worker', 'unit', 'project'])->findOrFail($this->settlingPayrollId);

        DB::transaction(function () use ($payroll, $user) {
            $salaryPayment = WorkerSalaryPayment::create([
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'worker_unit_payroll_id' => $payroll->id,
                'payment_date' => $this->worker_payment_date,
                'amount_gross' => $this->worker_payment_amount,
                'loan_deduction' => 0,
                'amount_paid' => $this->worker_payment_amount,
                'payment_method' => $this->worker_payment_method,
                'notes' => $this->worker_payment_notes,
                'created_by' => $user->id,
            ]);

            $newPaidAmount = (float)$payroll->paid_amount + $this->worker_payment_amount;
            $newStatus = ($newPaidAmount >= (float)$payroll->agreed_salary) ? 'lunas' : 'berjalan';

            $payroll->update([
                'paid_amount' => $newPaidAmount,
                'status' => $newStatus,
            ]);

            $unitCode = $payroll->unit ? $payroll->unit->code : '-';
            $workerName = $payroll->worker ? $payroll->worker->name : 'Tukang';
            $workDesc = $payroll->notes ?: 'Upah Kontrak Worker';
            $description = "Pembayaran Upah Pekerja ({$workerName}) Unit {$unitCode}: {$workDesc}";

            CashflowTransaction::create([
                'project_id' => $payroll->project_id,
                'type' => 'keluar',
                'category' => 'upah_tukang',
                'amount' => $this->worker_payment_amount,
                'transaction_date' => $this->worker_payment_date,
                'description' => $description,
                'reference_type' => WorkerSalaryPayment::class,
                'reference_id' => $salaryPayment->id,
                'created_by' => $user->id,
            ]);
        });

        $this->showWorkerPaymentModal = false;
        $this->settlingPayrollId = null;

        $msg = "Pembayaran upah tukang '" . ($payroll->worker->name ?? '') . "' sebesar Rp " . number_format($this->worker_payment_amount, 0, ',', '.') . " berhasil dicatat!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Pembayaran Berhasil!', 'message' => $msg]);
    }

    // --- TAB 3 ACTIONS (Hutang Komisi / Persenan Penjual per Unit) ---
    public function openCreateCommissionModal(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat hutang komisi penjual.');
            return;
        }

        $this->resetValidation();
        $this->comm_project_id = '';
        $this->comm_unit_id = '';
        $this->comm_marketing_id = '';
        $this->comm_seller_name = '';
        $this->comm_seller_phone = '';
        $this->comm_percentage = 2.5;
        $this->comm_amount = 0;
        $this->comm_notes = '';
        $this->showCreateCommissionModal = true;
    }

    public function updatedCommUnitId(): void
    {
        if ($this->comm_unit_id && (float)$this->comm_percentage > 0) {
            $unit = Unit::find($this->comm_unit_id);
            if ($unit) {
                $this->comm_amount = round(((float)$unit->price * (float)$this->comm_percentage) / 100);
            }
        }
    }

    public function updatedCommPercentage(): void
    {
        if ($this->comm_unit_id && (float)$this->comm_percentage > 0) {
            $unit = Unit::find($this->comm_unit_id);
            if ($unit) {
                $this->comm_amount = round(((float)$unit->price * (float)$this->comm_percentage) / 100);
            }
        }
    }

    public function updatedCommMarketingId(): void
    {
        if ($this->comm_marketing_id) {
            $user = User::find($this->comm_marketing_id);
            if ($user && empty($this->comm_seller_name)) {
                $this->comm_seller_name = $user->name;
                $this->comm_seller_phone = $user->phone ?? '';
            }
        }
    }

    public function saveCommission(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat komisi penjual.');
            return;
        }

        if ($this->comm_amount) {
            $this->comm_amount = (float) preg_replace('/[^0-9]/', '', (string)$this->comm_amount);
        }

        $this->validate([
            'comm_seller_name' => 'required|string|max:255',
            'comm_amount' => 'required|numeric|min:1000',
            'comm_project_id' => 'nullable|exists:projects,id',
            'comm_unit_id' => 'nullable|exists:units,id',
            'comm_marketing_id' => 'nullable|exists:users,id',
            'comm_percentage' => 'nullable|numeric|min:0|max:100',
            'comm_notes' => 'nullable|string',
        ]);

        $comm = UnitCommission::create([
            'project_id' => $this->comm_project_id ? (int)$this->comm_project_id : null,
            'unit_id' => $this->comm_unit_id ? (int)$this->comm_unit_id : null,
            'marketing_id' => $this->comm_marketing_id ? (int)$this->comm_marketing_id : null,
            'seller_name' => $this->comm_seller_name,
            'seller_phone' => $this->comm_seller_phone,
            'percentage' => $this->comm_percentage ?: 0,
            'commission_amount' => $this->comm_amount,
            'status' => 'belum_dibayar',
            'notes' => $this->comm_notes,
            'created_by' => $user->id,
        ]);

        \App\Services\ActivityLogger::log(
            'COMMISSION_DEBT_CREATED',
            "User {$user->name} mencatat hutang komisi penjual '{$comm->seller_name}' sebesar Rp " . number_format($comm->commission_amount, 0, ',', '.')
        );

        $this->showCreateCommissionModal = false;
        $msg = "Catatan Hutang Komisi Penjual '{$comm->seller_name}' berhasil disimpan!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Komisi Dicatat!', 'message' => $msg]);
    }

    public function openSettleCommissionModal(int $commissionId): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mengonfirmasi pembayaran komisi.');
            return;
        }

        $comm = UnitCommission::with(['unit', 'project'])->findOrFail($commissionId);
        $this->settlingCommissionId = $comm->id;
        $this->settle_comm_amount = $comm->remaining_amount;
        $this->settle_comm_date = now()->toDateString();
        $this->settle_comm_method = 'Transfer Bank';
        $this->settle_comm_notes = "Pembayaran Cicilan Komisi Penjual: {$comm->seller_name} (" . ($comm->unit ? $comm->unit->code : 'Unit') . ")";
        $this->settle_comm_photo = null;
        $this->showSettleCommissionModal = true;
    }

    public function processCommissionSettlement(): void
    {
        if (!$this->settlingCommissionId) {
            return;
        }

        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mengonfirmasi pembayaran komisi.');
            return;
        }

        $comm = UnitCommission::with(['unit', 'project'])->findOrFail($this->settlingCommissionId);

        if ($this->settle_comm_amount) {
            $this->settle_comm_amount = (float) preg_replace('/[^0-9]/', '', (string)$this->settle_comm_amount);
        }

        $this->validate([
            'settle_comm_amount' => 'required|numeric|min:1000|max:' . $comm->remaining_amount,
            'settle_comm_date' => 'required|date',
            'settle_comm_method' => 'required|string',
            'settle_comm_notes' => 'nullable|string',
            'settle_comm_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
        ], [
            'settle_comm_amount.max' => 'Nominal pembayaran cicilan komisi tidak boleh melebihi sisa hutang komisi (Rp ' . number_format($comm->remaining_amount, 0, ',', '.') . ').',
        ]);

        $photoPath = null;
        if ($this->settle_comm_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->settle_comm_photo, 'commission-receipts');
        }

        DB::transaction(function () use ($comm, $photoPath, $user) {
            $payment = \App\Models\UnitCommissionPayment::create([
                'unit_commission_id' => $comm->id,
                'payment_date' => $this->settle_comm_date,
                'amount' => $this->settle_comm_amount,
                'payment_method' => $this->settle_comm_method,
                'notes' => $this->settle_comm_notes,
                'receipt_photo_path' => $photoPath,
                'created_by' => $user->id,
            ]);

            $unitCode = $comm->unit ? " Unit {$comm->unit->code}" : '';
            $description = "Pembayaran Cicilan Komisi Penjual ({$comm->seller_name}){$unitCode}: Rp " . number_format($this->settle_comm_amount, 0, ',', '.');

            CashflowTransaction::create([
                'project_id' => $comm->project_id,
                'type' => 'keluar',
                'category' => 'operasional',
                'amount' => $this->settle_comm_amount,
                'transaction_date' => $this->settle_comm_date,
                'description' => $description,
                'reference_type' => \App\Models\UnitCommissionPayment::class,
                'reference_id' => $payment->id,
                'receipt_photo_path' => $photoPath,
                'created_by' => $user->id,
            ]);

            $comm->recalculateStatus();
        });

        \App\Services\ActivityLogger::log(
            'COMMISSION_SETTLED',
            "User {$user->name} mencatat pembayaran cicilan komisi '{$comm->seller_name}' sebesar Rp " . number_format($this->settle_comm_amount, 0, ',', '.') . " & tercatat di Arus Kas Global."
        );

        $this->showSettleCommissionModal = false;
        $this->settlingCommissionId = null;

        $msg = "Pembayaran cicilan komisi '{$comm->seller_name}' sebesar Rp " . number_format($this->settle_comm_amount, 0, ',', '.') . " berhasil dicatat ke Kas Keluar!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Pembayaran Berhasil!', 'message' => $msg]);
    }

    // --- TAB 4 ACTIONS (Piutang & Kasbon Staf / Worker) ---
    public function openCreateReceivableModal(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat piutang / kasbon.');
            return;
        }

        $this->resetValidation();
        $this->rec_debtor_type = 'other';
        $this->rec_debtor_name = '';
        $this->rec_worker_id = '';
        $this->rec_user_id = '';
        $this->rec_amount = 0;
        $this->rec_loan_date = now()->toDateString();
        $this->rec_notes = '';
        $this->showCreateReceivableModal = true;
    }

    public function updatedRecWorkerId(): void
    {
        if ($this->rec_worker_id) {
            $w = Worker::find($this->rec_worker_id);
            if ($w) {
                $this->rec_debtor_type = 'worker';
                $this->rec_debtor_name = $w->name . ' (' . ucfirst($w->type) . ')';
            }
        }
    }

    public function updatedRecUserId(): void
    {
        if ($this->rec_user_id) {
            $u = User::find($this->rec_user_id);
            if ($u) {
                $this->rec_debtor_type = 'user';
                $this->rec_debtor_name = $u->name . ' (' . ucfirst($u->role) . ')';
            }
        }
    }

    public function saveReceivable(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat piutang.');
            return;
        }

        $this->validate([
            'rec_debtor_name' => 'required|string|max:255',
            'rec_amount' => 'required|numeric|min:1000',
            'rec_loan_date' => 'required|date',
            'rec_notes' => 'nullable|string',
        ]);

        $rec = CompanyReceivable::create([
            'debtor_type' => $this->rec_debtor_type,
            'debtor_name' => $this->rec_debtor_name,
            'worker_id' => $this->rec_worker_id ? (int)$this->rec_worker_id : null,
            'user_id' => $this->rec_user_id ? (int)$this->rec_user_id : null,
            'amount' => $this->rec_amount,
            'paid_amount' => 0,
            'loan_date' => $this->rec_loan_date,
            'status' => 'belum_lunas',
            'notes' => $this->rec_notes,
            'created_by' => $user->id,
        ]);

        \App\Services\ActivityLogger::log(
            'COMPANY_RECEIVABLE_CREATED',
            "User {$user->name} mencatat piutang/kasbon baru '{$rec->debtor_name}' sebesar Rp " . number_format($rec->amount, 0, ',', '.')
        );

        $this->showCreateReceivableModal = false;
        $msg = "Catatan piutang/kasbon untuk '{$rec->debtor_name}' berhasil disimpan!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Piutang Dicatat!', 'message' => $msg]);
    }

    public function openPayReceivableModal(int $receivableId): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat pengembalian piutang.');
            return;
        }

        $rec = CompanyReceivable::with(['payments'])->findOrFail($receivableId);
        $remaining = max(0, (float)$rec->amount - (float)$rec->paid_amount);

        $this->settlingReceivableId = $rec->id;
        $this->pay_rec_amount = $remaining;
        $this->pay_rec_date = now()->toDateString();
        $this->pay_rec_method = 'Cash / Tunai';
        $this->pay_rec_notes = "Pengembalian piutang / kasbon: {$rec->debtor_name}";
        $this->pay_rec_photo = null;
        $this->showPayReceivableModal = true;
    }

    public function processReceivablePayment(): void
    {
        if (!$this->settlingReceivableId) {
            return;
        }

        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat pengembalian piutang.');
            return;
        }

        $this->validate([
            'pay_rec_amount' => 'required|numeric|min:1000',
            'pay_rec_date' => 'required|date',
            'pay_rec_method' => 'required|string',
            'pay_rec_notes' => 'nullable|string',
            'pay_rec_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
        ]);

        $rec = CompanyReceivable::findOrFail($this->settlingReceivableId);

        $photoPath = null;
        if ($this->pay_rec_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->pay_rec_photo, 'receivable-receipts');
        }

        DB::transaction(function () use ($rec, $photoPath, $user) {
            $payment = ReceivablePayment::create([
                'company_receivable_id' => $rec->id,
                'payment_date' => $this->pay_rec_date,
                'amount' => $this->pay_rec_amount,
                'payment_method' => $this->pay_rec_method,
                'notes' => $this->pay_rec_notes,
                'receipt_photo_path' => $photoPath,
                'created_by' => $user->id,
            ]);

            $newPaid = (float)$rec->paid_amount + (float)$this->pay_rec_amount;
            $newStatus = ($newPaid >= (float)$rec->amount) ? 'lunas' : 'belum_lunas';

            $rec->update([
                'paid_amount' => $newPaid,
                'status' => $newStatus,
            ]);

            // KAS MASUK ke Arus Kas Global saat piutang dikembalikan!
            $description = "Pengembalian Piutang / Kasbon ({$rec->debtor_name}): Rp " . number_format($this->pay_rec_amount, 0, ',', '.');

            CashflowTransaction::create([
                'project_id' => null, // Kas Masuk Global
                'type' => 'masuk',
                'category' => 'lain_lain',
                'amount' => $this->pay_rec_amount,
                'transaction_date' => $this->pay_rec_date,
                'description' => $description,
                'reference_type' => ReceivablePayment::class,
                'reference_id' => $payment->id,
                'receipt_photo_path' => $photoPath,
                'created_by' => $user->id,
            ]);
        });

        \App\Services\ActivityLogger::log(
            'RECEIVABLE_PAYMENT_RECORDED',
            "User {$user->name} mencatat pengembalian piutang '{$rec->debtor_name}' sebesar Rp " . number_format($this->pay_rec_amount, 0, ',', '.') . " & masuk Kas Masuk Global."
        );

        $this->showPayReceivableModal = false;
        $this->settlingReceivableId = null;

        $msg = "Pengembalian piutang dari '{$rec->debtor_name}' sebesar Rp " . number_format($this->pay_rec_amount, 0, ',', '.') . " berhasil dicatat ke Kas Masuk Global!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Kas Masuk Diterima!', 'message' => $msg]);
    }

    // --- FOUNDER DELETION METHODS ---
    public function deleteMaterialPurchase(int $id): void
    {
        $user = auth()->user();
        if (!$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak menghapus catatan tagihan material.');
            return;
        }

        $mat = WeeklyMaterialPurchase::findOrFail($id);
        DB::transaction(function () use ($mat) {
            CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
                ->where('reference_id', $mat->id)
                ->delete();
            $mat->delete();
        });

        session()->flash('success', 'Catatan tagihan material berhasil dihapus oleh Founder.');
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Dihapus!', 'message' => 'Catatan tagihan material berhasil dihapus.']);
    }

    public function deleteWorkerPayroll(int $id): void
    {
        $user = auth()->user();
        if (!$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak menghapus kontrak upah worker.');
            return;
        }

        $payroll = WorkerUnitPayroll::findOrFail($id);
        DB::transaction(function () use ($payroll) {
            foreach ($payroll->salaryPayments as $sp) {
                CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                    ->where('reference_id', $sp->id)
                    ->delete();
                $sp->delete();
            }
            $payroll->delete();
        });

        session()->flash('success', 'Kontrak upah worker berhasil dihapus oleh Founder.');
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Dihapus!', 'message' => 'Kontrak upah worker berhasil dihapus.']);
    }

    public function deleteCommission(int $id): void
    {
        $user = auth()->user();
        if (!$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak menghapus catatan komisi.');
            return;
        }

        $comm = UnitCommission::findOrFail($id);
        DB::transaction(function () use ($comm) {
            foreach ($comm->payments as $p) {
                CashflowTransaction::where('reference_type', \App\Models\UnitCommissionPayment::class)
                    ->where('reference_id', $p->id)
                    ->delete();
                $p->delete();
            }
            $comm->delete();
        });

        session()->flash('success', 'Catatan hutang komisi penjual berhasil dihapus oleh Founder.');
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Dihapus!', 'message' => 'Catatan hutang komisi penjual berhasil dihapus.']);
    }

    public function deleteReceivable(int $id): void
    {
        $user = auth()->user();
        if (!$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak menghapus catatan piutang/kasbon.');
            return;
        }

        $rec = CompanyReceivable::findOrFail($id);
        DB::transaction(function () use ($rec) {
            foreach ($rec->payments as $p) {
                CashflowTransaction::where('reference_type', ReceivablePayment::class)
                    ->where('reference_id', $p->id)
                    ->delete();
                $p->delete();
            }
            $rec->delete();
        });

        session()->flash('success', 'Catatan piutang / kasbon berhasil dihapus oleh Founder.');
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Dihapus!', 'message' => 'Catatan piutang / kasbon berhasil dihapus.']);
    }

    public function render()
    {
        $projects = Project::orderBy('name')->get();
        $allUsers = User::orderBy('name')->get();
        $allWorkers = Worker::orderBy('name')->get();

        // 1. Material & Vendor Bills Query (Tab 1)
        $materialQuery = WeeklyMaterialPurchase::with(['project', 'unit', 'worker', 'pengawas', 'payer']);

        if ($this->filter_status === 'belum_lunas') {
            $materialQuery->where('payment_status', 'belum_lunas');
        } elseif ($this->filter_status === 'lunas') {
            $materialQuery->where('payment_status', 'lunas');
        }

        if ($this->filter_project_id !== '') {
            $materialQuery->where('project_id', $this->filter_project_id);
        }

        if (trim($this->search) !== '') {
            $s = '%' . trim($this->search) . '%';
            $materialQuery->where(function ($q) use ($s) {
                $q->where('item_name', 'like', $s)
                    ->orWhere('store_name', 'like', $s)
                    ->orWhereHas('unit', function ($uq) use ($s) {
                        $uq->where('code', 'like', $s);
                    })
                    ->orWhereHas('worker', function ($wq) use ($s) {
                        $wq->where('name', 'like', $s);
                    });
            });
        }

        $materialBills = (clone $materialQuery)->latest('purchase_date')->latest('id')->paginate(10, ['*'], 'mat_page');

        // Total Unpaid Material Bills KPI
        $totalUnpaidMaterialBills = WeeklyMaterialPurchase::where('payment_status', 'belum_lunas')
            ->when($this->filter_project_id, fn($q) => $q->where('project_id', $this->filter_project_id))
            ->sum('total_price');

        // 2. Worker Salary Payables Query (Tab 2)
        $workerPayrollQuery = WorkerUnitPayroll::with(['project', 'unit', 'worker', 'salaryPayments'])
            ->whereRaw('agreed_salary > paid_amount');

        if ($this->filter_project_id !== '') {
            $workerPayrollQuery->where('project_id', $this->filter_project_id);
        }

        if (trim($this->search) !== '') {
            $s = '%' . trim($this->search) . '%';
            $workerPayrollQuery->where(function ($q) use ($s) {
                $q->where('work_description', 'like', $s)
                    ->orWhereHas('unit', function ($uq) use ($s) {
                        $uq->where('code', 'like', $s);
                    })
                    ->orWhereHas('worker', function ($wq) use ($s) {
                        $wq->where('name', 'like', $s);
                    });
            });
        }

        $workerPayrolls = (clone $workerPayrollQuery)->latest('created_at')->paginate(10, ['*'], 'wrk_page');

        // Total Unpaid Worker Wages KPI
        $totalUnpaidWorkerWages = WorkerUnitPayroll::whereRaw('agreed_salary > paid_amount')
            ->when($this->filter_project_id, fn($q) => $q->where('project_id', $this->filter_project_id))
            ->selectRaw('SUM(agreed_salary - paid_amount) as total_unpaid')
            ->value('total_unpaid') ?? 0;

        // 3. Unit Commissions Query (Tab 3)
        $commissionQuery = UnitCommission::with(['project', 'unit', 'marketing', 'payer']);

        if ($this->filter_status === 'belum_lunas') {
            $commissionQuery->where('status', 'belum_dibayar');
        } elseif ($this->filter_status === 'lunas') {
            $commissionQuery->where('status', 'lunas');
        }

        if ($this->filter_project_id !== '') {
            $commissionQuery->where('project_id', $this->filter_project_id);
        }

        if (trim($this->search) !== '') {
            $s = '%' . trim($this->search) . '%';
            $commissionQuery->where(function ($q) use ($s) {
                $q->where('seller_name', 'like', $s)
                    ->orWhereHas('unit', function ($uq) use ($s) {
                        $uq->where('code', 'like', $s);
                    });
            });
        }

        $unitCommissions = (clone $commissionQuery)->latest('created_at')->paginate(10, ['*'], 'com_page');

        // Total Unpaid Commissions KPI
        $totalUnpaidCommissions = UnitCommission::where('status', 'belum_dibayar')
            ->when($this->filter_project_id, fn($q) => $q->where('project_id', $this->filter_project_id))
            ->sum('commission_amount');

        // 4. Company Receivables Query (Tab 4 - Piutang / Kasbon)
        $receivableQuery = CompanyReceivable::with(['worker', 'user', 'payments']);

        if ($this->filter_status === 'belum_lunas') {
            $receivableQuery->where('status', 'belum_lunas');
        } elseif ($this->filter_status === 'lunas') {
            $receivableQuery->where('status', 'lunas');
        }

        if (trim($this->search) !== '') {
            $s = '%' . trim($this->search) . '%';
            $receivableQuery->where('debtor_name', 'like', $s);
        }

        $companyReceivables = (clone $receivableQuery)->latest('loan_date')->paginate(10, ['*'], 'rec_page');

        // Total Unpaid Company Receivables KPI
        $totalCompanyReceivables = CompanyReceivable::where('status', 'belum_lunas')
            ->selectRaw('SUM(amount - paid_amount) as total_unpaid')
            ->value('total_unpaid') ?? 0;

        // TOTAL HUTANG PERUSAHAAN = Toko + Upah Worker + Komisi Penjual
        $totalCompanyPayables = $totalUnpaidMaterialBills + $totalUnpaidWorkerWages + $totalUnpaidCommissions;

        // 5. Global Settled History Collection (Tab 5: Riwayat Lunas Global)
        $settledHistory = collect();

        // Material Purchases Lunas
        $mats = WeeklyMaterialPurchase::with(['project', 'unit', 'worker', 'pengawas', 'payer'])
            ->where('payment_status', 'lunas')
            ->when($this->filter_project_id, fn($q) => $q->where('project_id', $this->filter_project_id))
            ->get();

        foreach ($mats as $m) {
            $settledHistory->push((object)[
                'id' => $m->id,
                'category_type' => 'material',
                'category_name' => 'Hutang Toko Material',
                'badge_class' => 'bg-amber-100 text-amber-800 border-amber-200',
                'title' => $m->item_name . ' (' . ($m->store_name ?: 'Toko Material') . ')',
                'sub_info' => 'Unit ' . ($m->unit->code ?? '-') . ' • Proyek ' . ($m->project->name ?? 'Operasional Umum'),
                'date' => $m->paid_at ?? $m->purchase_date,
                'amount' => (float)$m->total_price,
                'status_label' => 'LUNAS TOKO',
                'notes' => $m->notes,
                'model' => $m,
            ]);
        }

        // Worker Salary Payments (Upah Terbayar)
        $spays = WorkerSalaryPayment::with(['payroll.project', 'payroll.unit', 'payroll.worker', 'creator'])
            ->when($this->filter_project_id, fn($q) => $q->whereHas('payroll', fn($pq) => $pq->where('project_id', $this->filter_project_id)))
            ->get();

        foreach ($spays as $sp) {
            $settledHistory->push((object)[
                'id' => $sp->id,
                'category_type' => 'worker_wage',
                'category_name' => 'Upah Worker / Tukang',
                'badge_class' => 'bg-blue-100 text-blue-800 border-blue-200',
                'title' => 'Pembayaran Upah ' . ($sp->payroll->worker->name ?? 'Pekerja'),
                'sub_info' => 'Unit ' . ($sp->payroll->unit->code ?? '-') . ' • Method: ' . $sp->payment_method,
                'date' => $sp->payment_date,
                'amount' => (float)$sp->amount_paid,
                'status_label' => 'TERBAYAR',
                'notes' => $sp->notes,
                'model' => $sp,
            ]);
        }

        // Commission Payments (Cicilan Komisi Terbayar)
        $commPayments = \App\Models\UnitCommissionPayment::with(['commission.project', 'commission.unit', 'creator'])
            ->when($this->filter_project_id, fn($q) => $q->whereHas('commission', fn($cq) => $cq->where('project_id', $this->filter_project_id)))
            ->get();

        foreach ($commPayments as $cp) {
            $settledHistory->push((object)[
                'id' => $cp->id,
                'category_type' => 'commission',
                'category_name' => 'Komisi Penjual Unit',
                'badge_class' => 'bg-purple-100 text-purple-800 border-purple-200',
                'title' => 'Cicilan Komisi ' . ($cp->commission->seller_name ?? 'Penjual'),
                'sub_info' => 'Unit ' . ($cp->commission->unit->code ?? '-') . ' • Method: ' . $cp->payment_method,
                'date' => $cp->payment_date,
                'amount' => (float)$cp->amount,
                'status_label' => 'TERBAYAR',
                'notes' => $cp->notes,
                'model' => $cp,
            ]);
        }

        // Receivable Payments (Pengembalian Kasbon Diterima)
        $recPayments = ReceivablePayment::with(['receivable', 'creator'])->get();

        foreach ($recPayments as $rp) {
            $settledHistory->push((object)[
                'id' => $rp->id,
                'category_type' => 'receivable',
                'category_name' => 'Pengembalian Piutang Staf',
                'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'title' => 'Pengembalian Kasbon ' . ($rp->receivable->debtor_name ?? 'Peminjam'),
                'sub_info' => 'Metode: ' . $rp->payment_method,
                'date' => $rp->payment_date,
                'amount' => (float)$rp->amount,
                'status_label' => 'KAS MASUK DITERIMA',
                'notes' => $rp->notes,
                'model' => $rp,
            ]);
        }

        // Search filter for Settled History
        if (trim($this->search) !== '') {
            $term = strtolower(trim($this->search));
            $settledHistory = $settledHistory->filter(function ($item) use ($term) {
                return str_contains(strtolower($item->title), $term)
                    || str_contains(strtolower($item->sub_info), $term)
                    || str_contains(strtolower($item->category_name), $term)
                    || str_contains(strtolower($item->notes ?? ''), $term);
            });
        }

        // Sort by date descending
        $settledHistory = $settledHistory->sortByDesc(function ($item) {
            $d = $item->date ? (is_string($item->date) ? $item->date : $item->date->format('Y-m-d')) : '0000-00-00';
            return $d . '_' . $item->id;
        })->values();

        $availableUnits = $this->new_project_id ? Unit::where('project_id', $this->new_project_id)->orderBy('code')->get() : collect();
        $commAvailableUnits = $this->comm_project_id ? Unit::where('project_id', $this->comm_project_id)->orderBy('code')->get() : collect();

        return view('livewire.payables.index', [
            'materialBills' => $materialBills,
            'workerPayrolls' => $workerPayrolls,
            'unitCommissions' => $unitCommissions,
            'companyReceivables' => $companyReceivables,
            'settledHistory' => $settledHistory,
            'projects' => $projects,
            'allUsers' => $allUsers,
            'allWorkers' => $allWorkers,
            'availableUnits' => $availableUnits,
            'commAvailableUnits' => $commAvailableUnits,
            'totalUnpaidMaterialBills' => $totalUnpaidMaterialBills,
            'totalUnpaidWorkerWages' => $totalUnpaidWorkerWages,
            'totalUnpaidCommissions' => $totalUnpaidCommissions,
            'totalCompanyPayables' => $totalCompanyPayables,
            'totalCompanyReceivables' => $totalCompanyReceivables,
            'showCreateBillModal' => $this->showCreateBillModal,
        ])->layout('components.layouts.app', ['title' => 'Hutang & Piutang Perusahaan']);
    }
}
