<?php

namespace App\Livewire\Installments;

use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\OfficialDocument;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Unit;
use App\Models\UnitInstallment;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public string $activeTab = 'unit_installments'; // 'unit_installments' or 'land_payments'

    protected $queryString = [
        'activeTab' => ['except' => 'unit_installments'],
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'projectIdFilter' => ['except' => ''],
        'monthlyFilter' => ['except' => 'all'],
        'landSearch' => ['except' => ''],
        'landProjectIdFilter' => ['except' => ''],
    ];

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public $showSetupModal = false;
    public $showPaymentModal = false;

    // Viewer Modal (PDF Viewer)
    public bool $showViewerModal = false;
    public string $viewerType = 'pdf';
    public string $viewerUrl = '';
    public string $viewerTitle = '';

    public function openViewerModal(string $type, string $url, string $title = ''): void
    {
        $this->viewerType = $type;
        $this->viewerUrl = $url;
        $this->viewerTitle = $title ?: 'Pratinjau PDF Laporan';
        $this->showViewerModal = true;
    }

    public function closeViewerModal(): void
    {
        $this->showViewerModal = false;
        $this->viewerType = '';
        $this->viewerUrl = '';
        $this->viewerTitle = '';
    }

    // Setup Skema Cicilan
    public $unit_id = '';
    public $official_document_id = null;
    public $total_price = 0;
    public $down_payment = 0;
    public $already_paid_booking = 0;
    public $installment_count = 12;
    public $installment_amount = 0;
    public $start_date = '';

    // Payment Form
    public $selectedInstallmentId = null;
    public $activeInstallment = null;
    public $payment_amount = 0;
    public $payment_date = '';
    public $payment_method = 'Transfer Bank';
    public $payment_notes = '';
    public $payment_receipt_photo = null;

    // Land Payment Form
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

    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->payment_date = date('Y-m-d');
        $this->land_payment_date = date('Y-m-d');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['total_price', 'down_payment', 'installment_count'])) {
            $this->calculateMonthlyInstallment();
        }
    }

    public function calculateMonthlyInstallment()
    {
        $rem = max(0, (float)$this->total_price - (float)$this->down_payment);
        $count = max(1, (int)$this->installment_count);
        $this->installment_amount = $rem / $count;
    }

    public function selectUnitForInstallment($unitId)
    {
        $this->unit_id = $unitId;
        $unit = Unit::with(['officialDocument', 'activeProposal', 'activeBooking'])->find($unitId);
        if ($unit) {
            $this->total_price = $unit->final_selling_price ?: ($unit->activeProposal->proposed_price ?? 0);
            $this->official_document_id = $unit->officialDocument->id ?? null;
            
            $booking = $unit->activeBooking;
            $this->already_paid_booking = $booking ? max((float)$booking->dp_amount, (float)$booking->booking_amount) : 0;
            
            $this->down_payment = $this->already_paid_booking > 0 ? $this->already_paid_booking : ($this->total_price * 0.20);
            $this->calculateMonthlyInstallment();
        }
    }

    // Editing state
    public ?int $editingInstallmentSchemeId = null;
    public ?int $editingPaymentId = null;
    public ?string $existing_receipt_photo_path = null;

    public function openSetupModal($installmentId = null)
    {
        $this->resetValidation();
        if ($installmentId) {
            $inst = UnitInstallment::with(['unit', 'payments'])->findOrFail($installmentId);
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
            $firstSold = Unit::whereIn('status', ['terjual', 'disetujui'])->doesntHave('installment')->first();
            if ($firstSold) {
                $this->selectUnitForInstallment($firstSold->id);
            }
        }
        $this->showSetupModal = true;
    }

    public function saveSetup()
    {
        $this->validate([
            'unit_id' => 'required|exists:units,id',
            'total_price' => 'required|numeric|min:1000',
            'installment_count' => 'required|integer|min:1',
            'start_date' => 'required|date',
        ]);

        if ($this->editingInstallmentSchemeId) {
            $installment = UnitInstallment::with('payments')->findOrFail($this->editingInstallmentSchemeId);
            $totalPaidSoFar = (float)$this->down_payment + (float)$installment->payments->sum('amount_paid');
            $status = ($totalPaidSoFar >= (float)$this->total_price) ? 'lunas' : 'berjalan';

            $installment->update([
                'total_price' => $this->total_price,
                'down_payment' => $this->down_payment,
                'installment_count' => $this->installment_count,
                'installment_amount' => $this->installment_amount,
                'start_date' => $this->start_date,
                'status' => $status,
            ]);

            session()->flash('success', 'Skema cicilan pembeli berhasil diperbarui!');
            \App\Services\ActivityLogger::log('INSTALLMENT_SCHEME_UPDATED', "Skema cicilan Unit {$installment->unit->code} diperbarui.");
        } else {
            $installment = UnitInstallment::create([
                'unit_id' => $this->unit_id,
                'official_document_id' => $this->official_document_id,
                'total_price' => $this->total_price,
                'down_payment' => $this->down_payment,
                'installment_count' => $this->installment_count,
                'installment_amount' => $this->installment_amount,
                'start_date' => $this->start_date,
                'status' => 'berjalan',
            ]);

            $unit = Unit::with('activeBooking')->find($this->unit_id);
            $booking = $unit ? $unit->activeBooking : null;
            $alreadyPaid = $booking ? max((float)$booking->dp_amount, (float)$booking->booking_amount) : 0;
            $netDpCashflow = max(0, (float)$this->down_payment - $alreadyPaid);

            // Record net DP addition to cashflow to prevent double counting booking fee
            if ($netDpCashflow > 0) {
                CashflowTransaction::create([
                    'project_id' => $unit->project_id,
                    'type' => 'masuk',
                    'category' => 'pembayaran_cicilan_pembeli',
                    'amount' => $netDpCashflow,
                    'transaction_date' => $this->start_date,
                    'description' => 'Pembayaran Uang Muka (DP) Unit ' . $unit->code . ($alreadyPaid > 0 ? ' (Net Tambahan DP, memperhitungkan Booking Fee Rp ' . number_format($alreadyPaid, 0, ',', '.') . ' yang sudah tercatat)' : ''),
                    'reference_type' => UnitInstallment::class,
                    'reference_id' => $installment->id,
                    'created_by' => auth()->id(),
                ]);
            }

            session()->flash('success', 'Skema cicilan/piutang pembeli berhasil dikonfigurasi!');
            \App\Services\ActivityLogger::log('INSTALLMENT_SCHEME_CREATED', "Skema cicilan Unit {$unit->code} dikonfigurasi sebesar Rp " . number_format($this->total_price, 0, ',', '.'));
        }

        if ($this->selectedDetailInstallment) {
            $this->selectedDetailInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments.creator'])->find($this->selectedDetailInstallment->id);
        }

        $this->showSetupModal = false;
    }

    public function openPaymentModal($installmentId)
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

    public function editInstallmentPayment($paymentId)
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

    public function submitPayment()
    {
        $this->validate([
            'payment_amount' => 'required|numeric|min:1000',
            'payment_date' => 'required|date',
            'payment_receipt_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp,heic,heif|max:2048',
        ], [
            'payment_receipt_photo.image' => 'File bukti setoran cicilan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
            'payment_receipt_photo.mimes' => 'File bukti setoran cicilan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
        ]);

        $inst = UnitInstallment::with('unit')->findOrFail($this->selectedInstallmentId);

        $receiptPhotoPath = null;
        if ($this->payment_receipt_photo) {
            $receiptPhotoPath = \App\Services\ImageCompressor::compressAndStore($this->payment_receipt_photo, 'installment-receipts');
        }

        $hasReceiptColPayment = \Illuminate\Support\Facades\Schema::hasColumn('installment_payments', 'receipt_photo_path');
        $hasReceiptColCashflow = \Illuminate\Support\Facades\Schema::hasColumn('cashflow_transactions', 'receipt_photo_path');

        if ($this->editingPaymentId) {
            $pay = InstallmentPayment::findOrFail($this->editingPaymentId);
            \Illuminate\Support\Facades\DB::transaction(function () use ($inst, $pay, $receiptPhotoPath, $hasReceiptColPayment, $hasReceiptColCashflow) {
                $payData = [
                    'payment_date' => $this->payment_date,
                    'amount_paid' => $this->payment_amount,
                    'payment_method' => $this->payment_method,
                    'notes' => $this->payment_notes,
                ];

                if ($receiptPhotoPath && $hasReceiptColPayment) {
                    $payData['receipt_photo_path'] = $receiptPhotoPath;
                }

                $pay->update($payData);

                $cashflow = CashflowTransaction::where('reference_type', InstallmentPayment::class)
                    ->where('reference_id', $pay->id)
                    ->first();

                if ($cashflow) {
                    $cashData = [
                        'amount' => $this->payment_amount,
                        'transaction_date' => $this->payment_date,
                        'description' => 'Setoran Cicilan Pembeli Unit ' . $inst->unit->code . ' (' . $this->payment_method . ')',
                    ];
                    if ($receiptPhotoPath && $hasReceiptColCashflow) {
                        $cashData['receipt_photo_path'] = $receiptPhotoPath;
                    }
                    $cashflow->update($cashData);
                }

                $totalPaid = (float)$inst->down_payment + (float)$inst->payments()->sum('amount_paid');
                $status = ($totalPaid >= (float)$inst->total_price) ? 'lunas' : 'berjalan';
                $inst->update(['status' => $status]);
            });

            $msg = 'Setoran cicilan Rp ' . number_format($this->payment_amount, 0, ',', '.') . ' berhasil diperbarui!';
            session()->flash('success', $msg);
            $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
            \App\Services\ActivityLogger::log('INSTALLMENT_PAYMENT_UPDATED', "Setoran cicilan Unit {$inst->unit->code} diperbarui.");
        } else {
            $createData = [
                'unit_installment_id' => $inst->id,
                'payment_date' => $this->payment_date,
                'amount_paid' => $this->payment_amount,
                'payment_method' => $this->payment_method,
                'notes' => $this->payment_notes,
                'created_by' => auth()->id(),
            ];
            if ($receiptPhotoPath && $hasReceiptColPayment) {
                $createData['receipt_photo_path'] = $receiptPhotoPath;
            }

            $payment = InstallmentPayment::create($createData);

            $cashData = [
                'project_id' => $inst->unit->project_id,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => $this->payment_amount,
                'transaction_date' => $this->payment_date,
                'description' => 'Setoran Cicilan Pembeli Unit ' . $inst->unit->code . ' (' . $this->payment_method . ')',
                'reference_type' => InstallmentPayment::class,
                'reference_id' => $payment->id,
                'created_by' => auth()->id(),
            ];
            if ($receiptPhotoPath && $hasReceiptColCashflow) {
                $cashData['receipt_photo_path'] = $receiptPhotoPath;
            }
            CashflowTransaction::create($cashData);

            \App\Services\ActivityLogger::log('INSTALLMENT_PAYMENT_RECORDED', "Setoran cicilan Unit {$inst->unit->code} dicatat sebesar Rp " . number_format($this->payment_amount, 0, ',', '.'));

            if ($inst->remaining_balance <= 0) {
                $inst->update(['status' => 'lunas']);
                $msg = 'Pembayaran diterima! Status cicilan Unit ' . $inst->unit->code . ' telah LUNAS!';
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

    // Modal Batalkan Skema Cicilan & Dialihkan ke Cash (Founder & Finance Only)
    public bool $showConvertToCashModal = false;
    public ?int $convertToCashInstallmentId = null;
    public $activeConvertToCashInstallment = null;
    public $cash_payment_amount = 0;
    public string $cash_payment_date = '';
    public string $cash_payment_method = 'Transfer Bank';
    public string $cash_notes = '';

    public function openConvertToCashModal($installmentId)
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

    public function submitConvertToCash()
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

        $inst = UnitInstallment::with('unit')->findOrFail($this->convertToCashInstallmentId);

        \Illuminate\Support\Facades\DB::transaction(function () use ($inst) {
            if ($this->cash_payment_amount > 0) {
                InstallmentPayment::create([
                    'unit_installment_id' => $inst->id,
                    'payment_date' => $this->cash_payment_date,
                    'amount_paid' => $this->cash_payment_amount,
                    'payment_method' => $this->cash_payment_method,
                    'notes' => '[Pelunasan Cash - Pembatalan Skema Cicilan] ' . $this->cash_notes,
                    'created_by' => auth()->id(),
                ]);

                CashflowTransaction::create([
                    'project_id' => $inst->unit->project_id,
                    'type' => 'masuk',
                    'category' => 'pembayaran_cicilan_pembeli',
                    'amount' => $this->cash_payment_amount,
                    'transaction_date' => $this->cash_payment_date,
                    'description' => 'Pelunasan Cash (Pembatalan Skema Cicilan) Unit ' . $inst->unit->code . ' (' . $this->cash_payment_method . ')',
                    'reference_type' => UnitInstallment::class,
                    'reference_id' => $inst->id,
                    'created_by' => auth()->id(),
                ]);
            }

            $inst->update(['status' => 'konversi_cash']);

            \App\Services\ActivityLogger::log('CANCEL_INSTALLMENT_TO_CASH', "Founder/Accounting membatalkan skema cicilan Unit {$inst->unit->code} dan menggantinya ke Pelunasan Cash Lunas sebesar Rp " . number_format($this->cash_payment_amount, 0, ',', '.'));
        });

        $msg = 'Skema cicilan Unit ' . $inst->unit->code . ' berhasil dibatalkan dan dialihkan ke Pelunasan Cash Lunas!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showConvertToCashModal = false;
    }

    // Modal Detail Skema & Riwayat Setoran
    public bool $showDetailModal = false;
    public $selectedDetailInstallment = null;

    public function openDetailModal($installmentId)
    {
        $this->selectedDetailInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments.creator'])->findOrFail($installmentId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedDetailInstallment = null;
    }

    public function deleteInstallment($id)
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isAdmin())) {
            session()->flash('error', 'Hanya Admin dan Founder yang berhak menghapus skema cicilan pembeli.');
            return;
        }

        $inst = UnitInstallment::with('unit')->findOrFail($id);
        $code = $inst->unit->code ?? '-';

        \Illuminate\Support\Facades\DB::transaction(function () use ($inst, $code) {
            $paymentIds = InstallmentPayment::where('unit_installment_id', $inst->id)->pluck('id');

            // Delete associated cashflow transactions
            CashflowTransaction::where('reference_type', UnitInstallment::class)
                ->where('reference_id', $inst->id)
                ->delete();

            if ($paymentIds->count() > 0) {
                CashflowTransaction::where('reference_type', InstallmentPayment::class)
                    ->whereIn('reference_id', $paymentIds)
                    ->delete();
            }

            // Delete payments
            InstallmentPayment::where('unit_installment_id', $inst->id)->delete();

            // Delete unit installment scheme
            $inst->delete();

            \App\Services\ActivityLogger::log(
                'DELETE_INSTALLMENT_SCHEME',
                "Founder menghapus skema cicilan & piutang pembeli untuk Unit {$code}"
            );
        });

        session()->flash('success', "Skema cicilan Unit {$code} berhasil dihapus!");

        if ($this->selectedDetailInstallment && $this->selectedDetailInstallment->id == $id) {
            $this->closeDetailModal();
        }
    }

    public function deleteInstallmentPayment($paymentId)
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isAdmin() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Tim Finance, Admin, dan Founder yang berhak menghapus setoran cicilan pembeli.');
            return;
        }

        $pay = InstallmentPayment::with('installment.unit')->findOrFail($paymentId);
        $instId = $pay->unit_installment_id;
        $unitCode = $pay->installment->unit->code ?? '-';
        $amount = $pay->amount_paid;

        \Illuminate\Support\Facades\DB::transaction(function () use ($pay, $instId) {
            CashflowTransaction::where('reference_type', InstallmentPayment::class)
                ->where('reference_id', $pay->id)
                ->delete();

            $pay->delete();

            $inst = UnitInstallment::find($instId);
            if ($inst) {
                $totalPaid = (float)$inst->down_payment + (float)$inst->payments()->sum('amount_paid');
                $status = ($totalPaid >= (float)$inst->total_price) ? 'lunas' : 'berjalan';
                $inst->update(['status' => $status]);
            }
        });

        session()->flash('success', "Pencatatan setoran cicilan Rp " . number_format($amount, 0, ',', '.') . " Unit {$unitCode} berhasil dihapus!");

        if ($this->selectedDetailInstallment && $this->selectedDetailInstallment->id == $instId) {
            $this->selectedDetailInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments.creator'])->find($instId);
        }
    }

    // ==========================================
    // LAND PAYMENTS MANAGEMENT (PEMBAYARAN LAHAN)
    // ==========================================

    public function openLandPaymentModal($paymentId = null)
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

    public function closeLandPaymentModal()
    {
        $this->showLandPaymentModal = false;
        $this->editingLandPaymentId = null;
        $this->land_payment_receipt_photo = null;
        $this->existing_land_receipt_photo_path = null;
    }

    public function submitLandPayment()
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

        $project = Project::findOrFail($this->land_project_id);

        $photoPath = null;
        if ($this->land_payment_receipt_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->land_payment_receipt_photo, 'project-payment-receipts');
        }

        if ($this->editingLandPaymentId) {
            $payment = ProjectPayment::findOrFail($this->editingLandPaymentId);
            $updateData = [
                'project_id' => $this->land_project_id,
                'payment_date' => $this->land_payment_date,
                'amount_paid' => $this->land_payment_amount,
                'payment_method' => $this->land_payment_method,
                'notes' => $this->land_payment_notes,
            ];
            if ($photoPath) {
                $updateData['receipt_photo_path'] = $photoPath;
            }
            $payment->update($updateData);

            // Sync related CashflowTransaction
            CashflowTransaction::where('reference_type', ProjectPayment::class)
                ->where('reference_id', $payment->id)
                ->update([
                    'project_id' => $project->id,
                    'amount' => $this->land_payment_amount,
                    'transaction_date' => $this->land_payment_date,
                    'description' => 'Pembayaran Lahan Proyek ' . $project->name . ' ke Penjual Tanah (' . $this->land_payment_method . ')',
                ]);

            \App\Services\ActivityLogger::log('PROJECT_PAYMENT_UPDATED', "Pembayaran lahan Proyek {$project->name} sebesar Rp " . number_format($this->land_payment_amount, 0, ',', '.') . " diperbarui.");
            session()->flash('success', 'Data pembayaran lahan berhasil diperbarui! Arus Kas otomatis disesuaikan.');
            $this->closeLandPaymentModal();
            return;
        }

        // CREATE MODE
        $payment = ProjectPayment::create([
            'project_id' => $project->id,
            'payment_date' => $this->land_payment_date,
            'amount_paid' => $this->land_payment_amount,
            'payment_method' => $this->land_payment_method,
            'notes' => $this->land_payment_notes,
            'receipt_photo_path' => $photoPath,
            'created_by' => auth()->id(),
        ]);

        // Auto record in Cashflow Transactions as Kas Keluar (Pengeluaran Pembelian Lahan)
        CashflowTransaction::create([
            'project_id' => $project->id,
            'type' => 'keluar',
            'category' => 'operasional',
            'amount' => $this->land_payment_amount,
            'transaction_date' => $this->land_payment_date,
            'description' => 'Pembayaran Lahan Proyek ' . $project->name . ' ke Penjual Tanah (' . $this->land_payment_method . ')',
            'reference_type' => ProjectPayment::class,
            'reference_id' => $payment->id,
            'created_by' => auth()->id(),
        ]);

        \App\Services\ActivityLogger::log('PROJECT_PAYMENT_CREATED', "Pembayaran lahan Proyek {$project->name} sebesar Rp " . number_format($this->land_payment_amount, 0, ',', '.') . " dicatat di Arus Kas.");

        session()->flash('success', 'Pembayaran pembelian lahan sebesar Rp ' . number_format($this->land_payment_amount, 0, ',', '.') . ' ke penjual tanah berhasil dicatat di Arus Kas (Kas Keluar)!');
        $this->closeLandPaymentModal();
    }

    public function deleteLandPayment($paymentId)
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak menghapus catatan pembayaran lahan proyek.');
            return;
        }

        $payment = ProjectPayment::with('project')->findOrFail($paymentId);
        $projectName = $payment->project->name ?? '-';

        // Remove related CashflowTransaction if present
        CashflowTransaction::where('reference_type', ProjectPayment::class)
            ->where('reference_id', $payment->id)
            ->delete();

        $payment->delete();

        \App\Services\ActivityLogger::log('PROJECT_PAYMENT_DELETED', "Catatan pembayaran lahan proyek {$projectName} (ID #{$paymentId}) dihapus.");

        session()->flash('success', 'Catatan pembayaran lahan proyek berhasil dihapus.');
    }

    public string $search = '';
    public string $statusFilter = '';
    public string $projectIdFilter = '';
    public string $monthlyFilter = 'all'; // 'all', 'unpaid_this_month', 'paid_this_month', 'lunas'

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

    public function render()
    {
        // 1. QUERY FOR UNIT INSTALLMENTS
        $query = UnitInstallment::with(['unit.project', 'unit.activeBooking', 'unit.bookings', 'officialDocument', 'payments']);

        if (trim($this->search) !== '') {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->whereHas('unit', function ($uq) use ($s) {
                    $uq->where('code', 'like', $s)
                        ->orWhereHas('project', function ($pq) use ($s) {
                            $pq->where('name', 'like', $s);
                        })
                        ->orWhereHas('bookings', function ($bq) use ($s) {
                            $bq->where('buyer_name', 'like', $s);
                        });
                })->orWhereHas('officialDocument', function ($dq) use ($s) {
                    $dq->where('buyer_name', 'like', $s);
                });
            });
        }

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->projectIdFilter !== '') {
            $query->whereHas('unit', function ($uq) {
                $uq->where('project_id', $this->projectIdFilter);
            });
        }

        // Apply Monthly Filter
        $currentMonth = now()->month;
        $currentYear = now()->year;

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

        $installments = $query->latest()->paginate(10);

        $projects = Project::with('payments')->orderBy('name')->get();

        $eligibleUnits = Unit::whereIn('status', ['terjual', 'disetujui'])
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
                $paidThisMonthAmount += $inst->payments->filter(function ($p) use ($currentMonth, $currentYear) {
                    return $p->payment_date && $p->payment_date->month == $currentMonth && $p->payment_date->year == $currentYear;
                })->sum('amount_paid');
            } else {
                $unpaidThisMonthCount++;
                $unpaidThisMonthAmount += (float)$inst->installment_amount;
            }
        }

        // 2. QUERY FOR LAND PAYMENTS (PEMBAYARAN LAHAN)
        $landQuery = ProjectPayment::with(['project', 'creator', 'cashflowTransaction']);
        if (trim($this->landSearch) !== '') {
            $ls = '%' . trim($this->landSearch) . '%';
            $landQuery->where(function ($q) use ($ls) {
                $q->whereHas('project', function ($pq) use ($ls) {
                    $pq->where('name', 'like', $ls)->orWhere('location', 'like', $ls);
                })->orWhere('notes', 'like', $ls)
                  ->orWhere('payment_method', 'like', $ls);
            });
        }
        if ($this->landProjectIdFilter !== '') {
            $landQuery->where('project_id', $this->landProjectIdFilter);
        }

        $landPayments = $landQuery->latest('payment_date')->paginate(10, ['*'], 'land_page');

        // Land Payment KPI Metrics
        $totalLandCost = (float)Project::sum('total_project_price');
        $totalLandPaid = (float)ProjectPayment::sum('amount_paid');
        $totalLandRemaining = max(0, $totalLandCost - $totalLandPaid);
        $totalLandTransactions = ProjectPayment::count();

        return view('livewire.installments.index', [
            'installments' => $installments,
            'landPayments' => $landPayments,
            'projects' => $projects,
            'eligibleUnits' => $eligibleUnits,
            'showConvertToCashModal' => $this->showConvertToCashModal,
            'showDetailModal' => $this->showDetailModal,
            'selectedDetailInstallment' => $this->selectedDetailInstallment,
            'unpaidThisMonthCount' => $unpaidThisMonthCount,
            'unpaidThisMonthAmount' => $unpaidThisMonthAmount,
            'paidThisMonthCount' => $paidThisMonthCount,
            'paidThisMonthAmount' => $paidThisMonthAmount,
            'currentMonthName' => now()->locale('id')->isoFormat('MMMM YYYY'),
            'showViewerModal' => $this->showViewerModal,
            'viewerType' => $this->viewerType,
            'viewerUrl' => $this->viewerUrl,
            'viewerTitle' => $this->viewerTitle,
            'totalLandCost' => $totalLandCost,
            'totalLandPaid' => $totalLandPaid,
            'totalLandRemaining' => $totalLandRemaining,
            'totalLandTransactions' => $totalLandTransactions,
        ])->layout('components.layouts.app', ['title' => 'Cicilan & Piutang']);
    }
}
