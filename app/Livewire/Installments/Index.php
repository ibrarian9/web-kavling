<?php

namespace App\Livewire\Installments;

use App\Actions\Installments\ConvertInstallmentToCashAction;
use App\Actions\Installments\DeleteInstallmentPaymentAction;
use App\Actions\Installments\DeleteInstallmentSchemeAction;
use App\Actions\Installments\DeleteLandPaymentAction;
use App\Actions\Installments\RecordInstallmentPaymentAction;
use App\Actions\Installments\RecordLandPaymentAction;
use App\Actions\Installments\SetupInstallmentSchemeAction;
use App\Livewire\Traits\WithMediaViewer;
use App\Models\InstallmentPayment;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Traits\WithDatePeriodFilter;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;
    use WithDatePeriodFilter;
    use WithMediaViewer;

    public string $activeTab = 'unit_installments'; // 'unit_installments' or 'land_payments'

    protected $queryString = [
        'activeTab' => ['except' => 'unit_installments'],
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'projectIdFilter' => ['except' => ''],
        'monthlyFilter' => ['except' => 'all'],
        'datePeriod' => ['except' => 'all'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'landSearch' => ['except' => ''],
        'landProjectIdFilter' => ['except' => ''],
        'landDatePeriod' => ['except' => 'all'],
        'landStartDate' => ['except' => ''],
        'landEndDate' => ['except' => ''],
    ];

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // ==========================================
    // SETUP SKEMA CICILAN STATE & MODAL
    // ==========================================
    public bool $showSetupModal = false;
    public ?int $editingInstallmentSchemeId = null;
    public $unit_id = '';
    public $official_document_id = null;
    public $total_price = 0;
    public $down_payment = 0;
    public $already_paid_booking = 0;
    public $installment_count = 12;
    public $installment_amount = 0;
    public $start_date = '';

    // ==========================================
    // PAYMENT FORM STATE & MODAL
    // ==========================================
    public bool $showPaymentModal = false;
    public ?int $selectedInstallmentId = null;
    public ?int $editingPaymentId = null;
    public $activeInstallment = null;
    public $payment_amount = 0;
    public $payment_date = '';
    public $payment_method = 'Transfer Bank';
    public $payment_notes = '';
    public $payment_receipt_photo = null;
    public ?string $existing_receipt_photo_path = null;

    // ==========================================
    // CONVERT TO CASH MODAL (FOUNDER/FINANCE)
    // ==========================================
    public bool $showConvertToCashModal = false;
    public ?int $convertToCashInstallmentId = null;
    public $activeConvertToCashInstallment = null;
    public $cash_payment_amount = 0;
    public string $cash_payment_date = '';
    public string $cash_payment_method = 'Transfer Bank';
    public string $cash_notes = '';

    // ==========================================
    // DETAIL SKEMA CICILAN MODAL
    // ==========================================
    public bool $showDetailModal = false;
    public $selectedDetailInstallment = null;

    // ==========================================
    // LAND PAYMENTS STATE & MODALS
    // ==========================================
    public bool $showLandPaymentModal = false;
    public ?int $editingLandPaymentId = null;
    public $land_project_id = '';
    public $land_payment_amount = 0;
    public $land_payment_date = '';
    public $land_payment_method = 'Transfer Bank';
    public $land_payment_notes = '';
    public $land_payment_receipt_photo = null;
    public ?string $existing_land_receipt_photo_path = null;

    public string $landSearch = '';
    public string $landProjectIdFilter = '';
    public string $landDatePeriod = 'all';
    public string $landStartDate = '';
    public string $landEndDate = '';

    // Land Payment Detail Modal
    public bool $showLandPaymentDetailModal = false;
    public ?ProjectPayment $selectedLandPayment = null;

    // Unit Installments Filter States
    public string $search = '';
    public string $statusFilter = '';
    public string $projectIdFilter = '';
    public string $monthlyFilter = 'all'; // 'all', 'unpaid_this_month', 'paid_this_month', 'lunas'

    public function mount(): void
    {
        $this->start_date = date('Y-m-d');
        $this->payment_date = date('Y-m-d');
        $this->land_payment_date = date('Y-m-d');
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['total_price', 'down_payment', 'installment_count'])) {
            $this->calculateMonthlyInstallment();
        }
    }

    public function calculateMonthlyInstallment(): void
    {
        $rem = max(0, (float)$this->total_price - (float)$this->down_payment);
        $count = max(1, (int)$this->installment_count);
        $this->installment_amount = $rem / $count;
    }

    public function selectUnitForInstallment($unitId): void
    {
        $this->unit_id = $unitId;
        $unit = Unit::with(['project', 'officialDocument', 'activeProposal', 'activeBooking', 'bookings'])->find($unitId);
        if ($unit) {
            $booking = $unit->activeBooking ?? $unit->bookings()->latest()->first();
            
            $basePrice = (float)($unit->final_selling_price ?: ($unit->activeProposal->proposed_price ?? 0));
            if ($basePrice <= 0 && $unit->project) {
                $basePrice = (float)($unit->project->base_price ?? 0) + ((float)($unit->excess_land_area ?? 0) * (float)($unit->project->excess_price_per_sqm ?? 0));
            }
            
            $this->total_price = $basePrice;
            $this->official_document_id = $unit->officialDocument->id ?? null;
            
            $this->already_paid_booking = $booking ? max((float)$booking->dp_amount, (float)$booking->booking_amount) : 0;
            $this->down_payment = (float)($this->already_paid_booking > 0 ? $this->already_paid_booking : ($this->total_price * 0.20));
            $this->calculateMonthlyInstallment();
        }
    }

    // ==========================================
    // SETUP SKEMA CICILAN HANDLERS
    // ==========================================
    public function openSetupModal($installmentId = null): void
    {
        $this->resetValidation();
        if ($installmentId) {
            $inst = UnitInstallment::with(['unit.project', 'payments'])->findOrFail($installmentId);
            $this->editingInstallmentSchemeId = $inst->id;
            $this->unit_id = $inst->unit_id;
            $this->official_document_id = $inst->official_document_id;
            $this->total_price = (float)$inst->total_price;
            $this->down_payment = (float)$inst->down_payment;
            $this->installment_count = (int)$inst->installment_count;
            $this->start_date = $inst->start_date ? $inst->start_date->format('Y-m-d') : date('Y-m-d');
            $this->calculateMonthlyInstallment();
        } else {
            $this->editingInstallmentSchemeId = null;
            $firstEligible = Unit::with(['project', 'activeBooking', 'bookings'])
                ->where(function ($q) {
                    $q->whereIn('status', ['booked', 'booking', 'dibooking', 'disetujui', 'terjual'])
                      ->orWhereHas('bookings', function ($bq) {
                          $bq->whereIn('status', ['active', 'converted']);
                      });
                })
                ->doesntHave('installment')
                ->first();
            if ($firstEligible) {
                $this->selectUnitForInstallment($firstEligible->id);
            }
        }
        $this->showSetupModal = true;
    }

    public function saveSetup(SetupInstallmentSchemeAction $action): void
    {
        $this->validate([
            'unit_id' => 'required|exists:units,id',
            'total_price' => 'required|numeric|min:1000',
            'installment_count' => 'required|integer|min:1',
            'start_date' => 'required|date',
        ]);

        $action->execute([
            'unit_id' => $this->unit_id,
            'official_document_id' => $this->official_document_id,
            'total_price' => $this->total_price,
            'down_payment' => $this->down_payment,
            'installment_count' => $this->installment_count,
            'installment_amount' => $this->installment_amount,
            'start_date' => $this->start_date,
        ], $this->editingInstallmentSchemeId);

        session()->flash('success', 'Skema cicilan/piutang pembeli berhasil dikonfigurasi!');

        if ($this->selectedDetailInstallment) {
            $this->selectedDetailInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments.creator'])->find($this->selectedDetailInstallment->id);
        }

        $this->showSetupModal = false;
    }

    // ==========================================
    // SETORAN CICILAN PEMBELI HANDLERS
    // ==========================================
    public function openPaymentModal($installmentId): void
    {
        $this->resetValidation();
        $this->editingPaymentId = null;
        $this->existing_receipt_photo_path = null;

        $this->selectedInstallmentId = $installmentId;
        $this->activeInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments'])->findOrFail($installmentId);
        $this->payment_amount = $this->activeInstallment->installment_amount;
        $this->payment_date = date('Y-m-d');
        $this->payment_notes = '';
        $this->payment_receipt_photo = null;
        $this->showPaymentModal = true;
    }

    public function editInstallmentPayment($paymentId): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isAdmin() && !$user->isFinance())) {
            $err = 'Akses ditolak. Hanya Tim Finance, Admin, dan Founder yang berhak mengedit setoran.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $pay = InstallmentPayment::with('installment.unit.project')->findOrFail($paymentId);
        $this->resetValidation();

        $this->editingPaymentId = $pay->id;
        $this->selectedInstallmentId = $pay->unit_installment_id;
        $this->activeInstallment = $pay->installment;
        $this->payment_amount = (float)$pay->amount_paid;
        $this->payment_date = $pay->payment_date ? $pay->payment_date->format('Y-m-d') : date('Y-m-d');
        $this->payment_method = $pay->payment_method ?: 'Transfer Bank';
        $this->payment_notes = $pay->notes ?? '';
        $this->payment_receipt_photo = null;
        $this->existing_receipt_photo_path = $pay->receipt_photo_path;

        $this->showPaymentModal = true;
    }

    public function submitPayment(RecordInstallmentPaymentAction $action): void
    {
        $this->validate([
            'payment_amount' => 'required|numeric|min:1000',
            'payment_date' => 'required|date',
            'payment_receipt_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp,heic,heif|max:2048',
        ], [
            'payment_receipt_photo.image' => 'File bukti setoran cicilan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
            'payment_receipt_photo.mimes' => 'File bukti setoran cicilan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
        ]);

        $result = $action->execute(
            $this->selectedInstallmentId,
            [
                'payment_date' => $this->payment_date,
                'payment_amount' => $this->payment_amount,
                'payment_method' => $this->payment_method,
                'payment_notes' => $this->payment_notes,
            ],
            $this->payment_receipt_photo,
            $this->editingPaymentId
        );

        if ($result['is_edit']) {
            $msg = 'Setoran cicilan Rp ' . number_format($this->payment_amount, 0, ',', '.') . ' berhasil diperbarui!';
            session()->flash('success', $msg);
            $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        } else {
            if ($result['is_settled']) {
                $msg = 'Pembayaran diterima! Status cicilan unit telah LUNAS!';
                session()->flash('success', $msg);
                $this->dispatch('notify', ['type' => 'success', 'title' => 'Lunas!', 'message' => $msg]);
            } else {
                $msg = 'Pembayaran cicilan Rp ' . number_format($this->payment_amount, 0, ',', '.') . ' berhasil dicatat di Arus Kas!';
                session()->flash('success', $msg);
                $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
            }
        }

        if ($this->selectedDetailInstallment) {
            $this->selectedDetailInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments.creator'])->find($this->selectedDetailInstallment->id);
        }

        $this->showPaymentModal = false;
        $this->payment_receipt_photo = null;
        $this->editingPaymentId = null;
    }

    // ==========================================
    // KONVERSI KE CASH (FOUNDER / FINANCE)
    // ==========================================
    public function openConvertToCashModal($installmentId): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            $err = 'Hanya Founder dan Tim Accounting/Finance yang berhak membatalkan cicilan dan menggantinya ke Cash.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $this->convertToCashInstallmentId = $installmentId;
        $this->activeConvertToCashInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments'])->findOrFail($installmentId);
        $this->cash_payment_amount = $this->activeConvertToCashInstallment->remaining_balance;
        $this->cash_payment_date = date('Y-m-d');
        $this->cash_payment_method = 'Transfer Bank';
        $this->cash_notes = 'Pembatalan skema cicilan & konversi pelunasan tunai/cash.';
        $this->showConvertToCashModal = true;
    }

    public function submitConvertToCash(ConvertInstallmentToCashAction $action): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            $err = 'Hanya Founder dan Tim Accounting/Finance yang berhak membatalkan cicilan dan menggantinya ke Cash.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $this->validate([
            'cash_payment_amount' => 'required|numeric|min:0',
            'cash_payment_date' => 'required|date',
            'cash_payment_method' => 'required|string',
        ]);

        $inst = $action->execute($this->convertToCashInstallmentId, [
            'cash_payment_amount' => $this->cash_payment_amount,
            'cash_payment_date' => $this->cash_payment_date,
            'cash_payment_method' => $this->cash_payment_method,
            'cash_notes' => $this->cash_notes,
        ]);

        $msg = 'Skema cicilan Unit ' . ($inst->unit->code ?? '') . ' berhasil dibatalkan dan dialihkan ke Pelunasan Cash Lunas!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showConvertToCashModal = false;
    }

    // ==========================================
    // DETAIL SKEMA CICILAN MODAL
    // ==========================================
    public function openDetailModal($installmentId): void
    {
        $this->selectedDetailInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments.creator'])->findOrFail($installmentId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedDetailInstallment = null;
    }

    // ==========================================
    // DELETE ACTIONS
    // ==========================================
    public function deleteInstallment($id, DeleteInstallmentSchemeAction $action): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isAdmin())) {
            session()->flash('error', 'Hanya Admin dan Founder yang berhak menghapus skema cicilan pembeli.');
            return;
        }

        $code = $action->execute($id);

        session()->flash('success', "Skema cicilan Unit {$code} berhasil dihapus!");

        if ($this->selectedDetailInstallment && $this->selectedDetailInstallment->id == $id) {
            $this->closeDetailModal();
        }
    }

    public function deleteInstallmentPayment($paymentId, DeleteInstallmentPaymentAction $action): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isAdmin() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Tim Finance, Admin, dan Founder yang berhak menghapus setoran cicilan pembeli.');
            return;
        }

        $result = $action->execute($paymentId);

        session()->flash('success', "Pencatatan setoran cicilan Rp " . number_format($result['amount'], 0, ',', '.') . " Unit {$result['unit_code']} berhasil dihapus!");

        if ($this->selectedDetailInstallment && $this->selectedDetailInstallment->id == $result['unit_installment_id']) {
            $this->selectedDetailInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments.creator'])->find($result['unit_installment_id']);
        }
    }

    // ==========================================
    // PEMBAYARAN LAHAN (LAND PAYMENTS)
    // ==========================================
    public function openLandPaymentModal($paymentId = null): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isAdmin() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder, Admin, dan Tim Finance yang berhak mencatat pembayaran lahan.');
            return;
        }

        $this->resetValidation();
        if ($paymentId) {
            $pay = ProjectPayment::with('project')->findOrFail($paymentId);
            $this->editingLandPaymentId = $pay->id;
            $this->land_project_id = $pay->project_id;
            $this->land_payment_amount = (float)$pay->amount_paid;
            $this->land_payment_date = $pay->payment_date ? $pay->payment_date->format('Y-m-d') : date('Y-m-d');
            $this->land_payment_method = $pay->payment_method ?: 'Transfer Bank';
            $this->land_payment_notes = $pay->notes ?? '';
            $this->land_payment_receipt_photo = null;
            $this->existing_land_receipt_photo_path = $pay->receipt_photo_path;
        } else {
            $this->editingLandPaymentId = null;
            $firstProj = Project::orderBy('name')->first();
            $this->land_project_id = $firstProj ? $firstProj->id : '';
            $this->land_payment_amount = 0;
            $this->land_payment_date = date('Y-m-d');
            $this->land_payment_method = 'Transfer Bank';
            $this->land_payment_notes = '';
            $this->land_payment_receipt_photo = null;
            $this->existing_land_receipt_photo_path = null;
        }
        $this->showLandPaymentModal = true;
    }

    public function closeLandPaymentModal(): void
    {
        $this->showLandPaymentModal = false;
        $this->editingLandPaymentId = null;
        $this->land_payment_receipt_photo = null;
        $this->existing_land_receipt_photo_path = null;
    }

    public function submitLandPayment(RecordLandPaymentAction $action): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isAdmin() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder, Admin, dan Tim Finance yang berhak mencatat pembayaran lahan.');
            return;
        }

        $this->validate([
            'land_project_id' => 'required|exists:projects,id',
            'land_payment_amount' => 'required|numeric|min:1000',
            'land_payment_date' => 'required|date',
            'land_payment_method' => 'required|string',
            'land_payment_receipt_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp,heic,heif|max:2048',
        ], [
            'land_payment_receipt_photo.image' => 'File bukti pembayaran lahan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
            'land_payment_receipt_photo.mimes' => 'File bukti pembayaran lahan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
        ]);

        $action->execute(
            [
                'project_id' => $this->land_project_id,
                'payment_date' => $this->land_payment_date,
                'amount_paid' => $this->land_payment_amount,
                'payment_method' => $this->land_payment_method,
                'notes' => $this->land_payment_notes,
            ],
            $this->land_payment_receipt_photo,
            $this->editingLandPaymentId
        );

        if ($this->editingLandPaymentId) {
            session()->flash('success', 'Data pembayaran lahan berhasil diperbarui! Arus Kas otomatis disesuaikan.');
        } else {
            session()->flash('success', 'Pembayaran pembelian lahan sebesar Rp ' . number_format($this->land_payment_amount, 0, ',', '.') . ' ke penjual tanah berhasil dicatat di Arus Kas (Kas Keluar)!');
        }

        $this->closeLandPaymentModal();
    }

    public function deleteLandPayment($paymentId, DeleteLandPaymentAction $action): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak menghapus catatan pembayaran lahan proyek.');
            return;
        }

        $action->execute($paymentId);

        session()->flash('success', 'Catatan pembayaran lahan proyek berhasil dihapus.');
    }

    public function showLandPaymentDetail($paymentId): void
    {
        $this->selectedLandPayment = ProjectPayment::with(['project.payments', 'creator'])->findOrFail($paymentId);
        $this->showLandPaymentDetailModal = true;
    }

    public function closeLandPaymentDetailModal(): void
    {
        $this->showLandPaymentDetailModal = false;
        $this->selectedLandPayment = null;
    }

    // ==========================================
    // FILTER HOOKS & PAGINATION RESETS
    // ==========================================
    public function updatedLandDatePeriod(): void
    {
        if ($this->landDatePeriod !== 'custom') {
            $this->landStartDate = '';
            $this->landEndDate = '';
        }
        $this->resetPage('land_page');
    }

    public function updatedLandStartDate(): void
    {
        $this->resetPage('land_page');
    }

    public function updatedLandEndDate(): void
    {
        $this->resetPage('land_page');
    }

    public function setMonthlyFilter(string $filter): void
    {
        $this->monthlyFilter = $filter;
        $this->resetPage();
    }

    public function updatingMonthlyFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingLandSearch(): void
    {
        $this->resetPage('land_page');
    }

    public function updatingLandProjectIdFilter(): void
    {
        $this->resetPage('land_page');
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingProjectIdFilter(): void
    {
        $this->resetPage();
    }

    // ==========================================
    // RENDER
    // ==========================================
    public function render()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Query Unit Installments
        $query = UnitInstallment::with(['unit.project', 'unit.activeBooking', 'unit.bookings', 'officialDocument', 'payments']);

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('unit', function ($uq) use ($search) {
                    $uq->where('code', 'like', "%{$search}%")
                       ->orWhereHas('project', function ($pq) use ($search) {
                           $pq->where('name', 'like', "%{$search}%");
                       })
                       ->orWhereHas('bookings', function ($bq) use ($search) {
                           $bq->where('buyer_name', 'like', "%{$search}%");
                       });
                })->orWhereHas('officialDocument', function ($dq) use ($search) {
                    $dq->where('buyer_name', 'like', "%{$search}%");
                });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->projectIdFilter) {
            $query->whereHas('unit', function ($uq) {
                $uq->where('project_id', $this->projectIdFilter);
            });
        }

        if ($this->monthlyFilter === 'unpaid_this_month') {
            $query->where('status', 'berjalan')
                ->whereDoesntHave('payments', function ($pq) use ($currentMonth, $currentYear) {
                    $pq->whereMonth('payment_date', $currentMonth)
                       ->whereYear('payment_date', $currentYear);
                });
        } elseif ($this->monthlyFilter === 'paid_this_month') {
            $query->whereHas('payments', function ($pq) use ($currentMonth, $currentYear) {
                $pq->whereMonth('payment_date', $currentMonth)
                   ->whereYear('payment_date', $currentYear);
            });
        } elseif ($this->monthlyFilter === 'lunas') {
            $query->whereIn('status', ['lunas', 'konversi_cash']);
        }

        // Apply Date Period Filter to Unit Installments
        if ($this->datePeriod !== 'all') {
            $this->applyDatePeriodFilter($query, 'start_date', $this->datePeriod, $this->startDate, $this->endDate);
        }

        $installments = $query->latest()->paginate(10);

        $projects = Project::with('payments')->orderBy('name')->get();

        $eligibleUnits = Unit::with(['project', 'activeBooking', 'bookings', 'officialDocument'])
            ->where(function ($q) {
                $q->whereIn('status', ['booked', 'booking', 'dibooking', 'disetujui', 'terjual'])
                  ->orWhereHas('bookings', function ($bq) {
                      $bq->whereIn('status', ['active', 'converted']);
                  });
            })
            ->doesntHave('installment')
            ->get();

        // Calculate Monthly Metrics for Unit Installment Cards
        $activeInstallments = UnitInstallment::with(['payments'])->where('status', 'berjalan')->get();
        $unpaidThisMonthCount = 0;
        $unpaidThisMonthAmount = 0;
        $paidThisMonthCount = 0;
        $paidThisMonthAmount = 0;

        foreach ($activeInstallments as $inst) {
            $hasPaid = $inst->payments->contains(function ($p) use ($currentMonth, $currentYear) {
                return $p->payment_date && $p->payment_date->month == $currentMonth && $p->payment_date->year == $currentYear;
            });

            if ($hasPaid) {
                $paidThisMonthCount++;
                $paidThisMonthAmount += (float)$inst->installment_amount;
            } else {
                $unpaidThisMonthCount++;
                $unpaidThisMonthAmount += (float)$inst->installment_amount;
            }
        }

        // Total Piutang Unit metrics
        $allActiveInstallments = UnitInstallment::with(['payments'])->whereIn('status', ['berjalan', 'lunas'])->get();
        $totalReceivableAmount = 0;
        $totalCollectedAmount = 0;
        $totalOutstandingAmount = 0;

        foreach ($allActiveInstallments as $inst) {
            $totalReceivableAmount += (float)$inst->total_price;
            $collected = (float)$inst->down_payment + (float)$inst->payments->sum('amount_paid');
            $totalCollectedAmount += $collected;
            $totalOutstandingAmount += max(0, (float)$inst->total_price - $collected);
        }

        // Query Land Payments (Pembayaran Lahan)
        $landQuery = ProjectPayment::with(['project.payments', 'creator']);

        if ($this->landSearch) {
            $ls = $this->landSearch;
            $landQuery->where(function ($lq) use ($ls) {
                $lq->whereHas('project', function ($pq) use ($ls) {
                    $pq->where('name', 'like', "%{$ls}%")
                       ->orWhere('location', 'like', "%{$ls}%");
                })->orWhere('notes', 'like', "%{$ls}%")
                  ->orWhere('payment_method', 'like', "%{$ls}%");
            });
        }

        if ($this->landProjectIdFilter) {
            $landQuery->where('project_id', $this->landProjectIdFilter);
        }

        // Apply Date Period Filter to Land Payments
        if ($this->landDatePeriod !== 'all') {
            $this->applyDatePeriodFilter($landQuery, 'payment_date', $this->landDatePeriod, $this->landStartDate, $this->landEndDate);
        }

        $filteredLandPaymentsTotal = (clone $landQuery)->sum('amount_paid');
        $landPayments = $landQuery->latest('payment_date')->paginate(10, ['*'], 'land_page');

        // Land Payment Summary Metrics
        if ($this->landProjectIdFilter) {
            $selectedProject = Project::find($this->landProjectIdFilter);
            $selectedProjectName = $selectedProject?->name;
            $totalLandCost = $selectedProject ? (float)$selectedProject->total_project_price : 0;
            $totalLandPaid = ProjectPayment::where('project_id', $this->landProjectIdFilter)->sum('amount_paid');
            $totalLandTransactions = ProjectPayment::where('project_id', $this->landProjectIdFilter)->count();
        } else {
            $selectedProjectName = null;
            $totalLandCost = Project::sum('total_project_price');
            $totalLandPaid = ProjectPayment::sum('amount_paid');
            $totalLandTransactions = ProjectPayment::count();
        }
        $totalLandProjectCost = $totalLandCost;
        $totalLandRemaining = max(0, $totalLandCost - $totalLandPaid);
        $currentMonthName = now()->locale('id')->isoFormat('MMMM YYYY');

        return view('livewire.installments.index', compact(
            'installments',
            'projects',
            'eligibleUnits',
            'unpaidThisMonthCount',
            'unpaidThisMonthAmount',
            'paidThisMonthCount',
            'paidThisMonthAmount',
            'totalReceivableAmount',
            'totalCollectedAmount',
            'totalOutstandingAmount',
            'landPayments',
            'filteredLandPaymentsTotal',
            'totalLandCost',
            'totalLandProjectCost',
            'totalLandPaid',
            'totalLandRemaining',
            'totalLandTransactions',
            'selectedProjectName',
            'currentMonthName'
        ))->layout('layouts.app', ['title' => 'Cicilan & Keuangan Properti']);
    }
}
