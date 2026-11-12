<?php

namespace App\Livewire\Units\Concerns;

use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Services\ActivityLogger;
use App\Services\ImageCompressor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait HasUnitInstallments
{
    // Modal Buyer Installment Payment (Setoran Cicilan Pembeli)
    public bool $showInstallmentPaymentModal = false;
    public $installment_payment_amount = 0;
    public string $installment_payment_date = '';
    public string $installment_payment_method = 'Transfer Bank';
    public string $installment_payment_notes = '';
    public $installment_payment_receipt_photo = null;
    public ?string $existing_installment_receipt_photo_path = null;
    public ?int $editingInstallmentPaymentId = null;

    // Modal Batalkan Skema Cicilan & Dialihkan ke Cash
    public bool $showConvertToCashModal = false;
    public $cash_payment_amount = 0;
    public string $cash_payment_date = '';
    public string $cash_payment_method = 'Transfer Bank';
    public string $cash_notes = '';

    // Modal Setup Skema Cicilan Baru
    public bool $showSetupInstallmentModal = false;
    public $setup_total_price = 0;
    public $setup_down_payment = 0;
    public $setup_installment_count = 12;
    public $setup_installment_amount = 0;
    public string $setup_start_date = '';

    public function openInstallmentPaymentModal(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Akses ditolak. Hanya Founder dan Finance.');
            return;
        }

        $unit = Unit::with('installment')->findOrFail($this->unitId);
        if (!$unit->installment) {
            session()->flash('error', 'Unit ini belum memiliki skema cicilan aktif.');
            return;
        }

        $this->resetValidation();
        $this->editingInstallmentPaymentId = null;
        $this->installment_payment_amount = $unit->installment->installment_amount;
        $this->installment_payment_date = now()->toDateString();
        $this->installment_payment_method = 'Transfer Bank';
        $this->installment_payment_notes = '';
        $this->installment_payment_receipt_photo = null;
        $this->existing_installment_receipt_photo_path = null;
        $this->showInstallmentPaymentModal = true;
    }

    public function editInstallmentPayment(int $id): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Akses ditolak. Hanya Founder dan Finance.');
            return;
        }
        $pay = InstallmentPayment::findOrFail($id);
        $this->resetValidation();
        $this->editingInstallmentPaymentId = $pay->id;
        $this->installment_payment_amount = $pay->amount_paid;
        $this->installment_payment_date = $pay->payment_date ? $pay->payment_date->format('Y-m-d') : date('Y-m-d');
        $this->installment_payment_method = $pay->payment_method;
        $this->installment_payment_notes = $pay->notes ?? '';
        $this->installment_payment_receipt_photo = null;
        $this->existing_installment_receipt_photo_path = $pay->receipt_photo_path;
        $this->showInstallmentPaymentModal = true;
    }

    public function deleteInstallmentPayment(int $id): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Akses ditolak. Hanya Founder dan Finance.');
            return;
        }
        $pay = InstallmentPayment::with('installment.unit')->findOrFail($id);
        $inst = $pay->installment;
        $unitCode = $inst->unit->code ?? $this->unitId;
        $amountPaid = (float)$pay->amount_paid;
        $userName = auth()->user()->name ?? 'User';

        DB::transaction(function () use ($pay, $inst) {
            CashflowTransaction::where('reference_type', InstallmentPayment::class)
                ->where('reference_id', $pay->id)
                ->delete();
            $pay->delete();

            if ($inst) {
                $totalPaid = $inst->down_payment + $inst->payments()->sum('amount_paid');
                $status = ($totalPaid >= $inst->total_price) ? 'lunas' : 'berjalan';
                $inst->update(['status' => $status]);
            }
        });

        ActivityLogger::log(
            'INSTALLMENT_PAYMENT_DELETED',
            "Setoran cicilan pembeli Unit {$unitCode} sebesar Rp " . number_format($amountPaid, 0, ',', '.') . " dihapus oleh {$userName}."
        );

        session()->flash('success', 'Setoran cicilan pembeli berhasil dihapus!');
    }

    public function saveInstallmentPayment(): void
    {
        $user = auth()->user();
        if (!$user->isFinance() && !$user->isAdminOrFounder()) {
            session()->flash('error', 'Hanya tim Finance dan Founder yang berhak mencatat setoran cicilan pembeli.');
            return;
        }

        $this->validate([
            'installment_payment_amount' => 'required|numeric|min:1000',
            'installment_payment_date' => 'required|date',
            'installment_payment_method' => 'required|string',
            'installment_payment_notes' => 'nullable|string',
            'installment_payment_receipt_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp,heic,heif|max:2048',
        ], [
            'installment_payment_receipt_photo.image' => 'File bukti setoran cicilan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
            'installment_payment_receipt_photo.mimes' => 'File bukti setoran cicilan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
        ]);

        $unit = Unit::with('installment')->findOrFail($this->unitId);
        $inst = $unit->installment;

        if (!$inst) {
            session()->flash('error', 'Skema cicilan tidak ditemukan.');
            return;
        }

        $receiptPhotoPath = null;
        if ($this->installment_payment_receipt_photo) {
            $receiptPhotoPath = ImageCompressor::compressAndStore($this->installment_payment_receipt_photo, 'installment-receipts');
        }

        $hasReceiptColPayment = Schema::hasColumn('installment_payments', 'receipt_photo_path');
        $hasReceiptColCashflow = Schema::hasColumn('cashflow_transactions', 'receipt_photo_path');
        $amountFormatted = number_format($this->installment_payment_amount, 0, ',', '.');
        $userName = $user->name ?? 'User';

        if ($this->editingInstallmentPaymentId) {
            $pay = InstallmentPayment::findOrFail($this->editingInstallmentPaymentId);
            DB::transaction(function () use ($unit, $inst, $pay, $receiptPhotoPath, $hasReceiptColPayment, $hasReceiptColCashflow) {
                $payData = [
                    'payment_date' => $this->installment_payment_date,
                    'amount_paid' => $this->installment_payment_amount,
                    'payment_method' => $this->installment_payment_method,
                    'notes' => $this->installment_payment_notes,
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
                        'amount' => $this->installment_payment_amount,
                        'transaction_date' => $this->installment_payment_date,
                        'description' => 'Setoran Cicilan Pembeli Unit ' . $unit->code . ' (' . $this->installment_payment_method . ')',
                    ];
                    if ($receiptPhotoPath && $hasReceiptColCashflow) {
                        $cashData['receipt_photo_path'] = $receiptPhotoPath;
                    }
                    $cashflow->update($cashData);
                }

                $totalPaid = $inst->down_payment + $inst->payments()->sum('amount_paid');
                $status = ($totalPaid >= $inst->total_price) ? 'lunas' : 'berjalan';
                $inst->update(['status' => $status]);
            });

            ActivityLogger::log(
                'INSTALLMENT_PAYMENT_UPDATED',
                "Setoran cicilan pembeli Unit {$unit->code} diperbarui menjadi Rp {$amountFormatted} oleh {$userName}."
            );

            session()->flash('success', 'Setoran cicilan pembeli berhasil diperbarui!');
        } else {
            DB::transaction(function () use ($unit, $inst, $receiptPhotoPath, $hasReceiptColPayment, $hasReceiptColCashflow) {
                $createData = [
                    'unit_installment_id' => $inst->id,
                    'payment_date' => $this->installment_payment_date,
                    'amount_paid' => $this->installment_payment_amount,
                    'payment_method' => $this->installment_payment_method,
                    'notes' => $this->installment_payment_notes,
                    'created_by' => Auth::id(),
                ];
                if ($receiptPhotoPath && $hasReceiptColPayment) {
                    $createData['receipt_photo_path'] = $receiptPhotoPath;
                }

                $payment = InstallmentPayment::create($createData);

                $cashData = [
                    'project_id' => $unit->project_id,
                    'type' => 'masuk',
                    'category' => 'pembayaran_cicilan_pembeli',
                    'amount' => $this->installment_payment_amount,
                    'transaction_date' => $this->installment_payment_date,
                    'description' => 'Setoran Cicilan Pembeli Unit ' . $unit->code . ' (' . $this->installment_payment_method . ')',
                    'reference_type' => InstallmentPayment::class,
                    'reference_id' => $payment->id,
                    'created_by' => Auth::id(),
                ];
                if ($receiptPhotoPath && $hasReceiptColCashflow) {
                    $cashData['receipt_photo_path'] = $receiptPhotoPath;
                }
                CashflowTransaction::create($cashData);

                $totalPaid = $inst->down_payment + $inst->payments()->sum('amount_paid');
                if ($totalPaid >= $inst->total_price) {
                    $inst->update(['status' => 'lunas']);
                }
            });

            ActivityLogger::log(
                'INSTALLMENT_PAYMENT_RECORDED',
                "Setoran cicilan pembeli Unit {$unit->code} dicatat sebesar Rp {$amountFormatted} oleh {$userName}."
            );

            session()->flash('success', 'Setoran cicilan pembeli Rp ' . number_format($this->installment_payment_amount, 0, ',', '.') . ' berhasil dicatat!');
        }

        $this->showInstallmentPaymentModal = false;
        $this->editingInstallmentPaymentId = null;
        $this->installment_payment_receipt_photo = null;
    }

    public function openConvertToCashModal(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Hanya tim Finance, Founder, dan Admin yang berhak membatalkan cicilan dan menggantinya ke Cash.');
            return;
        }

        $unit = Unit::with('installment.payments')->findOrFail($this->unitId);
        if (!$unit->installment) {
            session()->flash('error', 'Unit ini belum memiliki skema cicilan aktif.');
            return;
        }

        $this->resetValidation();
        $this->cash_payment_amount = $unit->installment->remaining_balance;
        $this->cash_payment_date = now()->toDateString();
        $this->cash_payment_method = 'Transfer Bank';
        $this->cash_notes = 'Pembatalan skema cicilan unit ' . $unit->code . ' dan konversi pelunasan tunai/cash.';
        $this->showConvertToCashModal = true;
    }

    public function saveConvertToCash(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Hanya tim Finance, Founder, dan Admin yang berhak membatalkan skema cicilan.');
            return;
        }

        $this->validate([
            'cash_payment_amount' => 'required|numeric|min:0',
            'cash_payment_date' => 'required|date',
            'cash_payment_method' => 'required|string',
            'cash_notes' => 'nullable|string',
        ]);

        $unit = Unit::with('installment')->findOrFail($this->unitId);
        $inst = $unit->installment;

        if (!$inst) {
            session()->flash('error', 'Skema cicilan tidak ditemukan.');
            return;
        }

        DB::transaction(function () use ($unit, $inst) {
            if ($this->cash_payment_amount > 0) {
                InstallmentPayment::create([
                    'unit_installment_id' => $inst->id,
                    'payment_date' => $this->cash_payment_date,
                    'amount_paid' => $this->cash_payment_amount,
                    'payment_method' => $this->cash_payment_method,
                    'notes' => '[Pelunasan Cash - Pembatalan Skema Cicilan] ' . $this->cash_notes,
                    'created_by' => Auth::id(),
                ]);

                CashflowTransaction::create([
                    'project_id' => $unit->project_id,
                    'type' => 'masuk',
                    'category' => 'pembayaran_cicilan_pembeli',
                    'amount' => $this->cash_payment_amount,
                    'transaction_date' => $this->cash_payment_date,
                    'description' => 'Pelunasan Cash (Pembatalan Skema Cicilan) Unit ' . $unit->code . ' (' . $this->cash_payment_method . ')',
                    'reference_type' => UnitInstallment::class,
                    'reference_id' => $inst->id,
                    'created_by' => Auth::id(),
                ]);
            }

            $inst->update(['status' => 'konversi_cash']);
            $unit->update(['status' => 'lunas']);

            ActivityLogger::log('CANCEL_INSTALLMENT_TO_CASH', "Founder/Accounting membatalkan skema cicilan Unit {$unit->code} dan menggantinya ke Pelunasan Cash Lunas sebesar Rp " . number_format($this->cash_payment_amount, 0, ',', '.'));
        });

        session()->flash('success', 'Skema cicilan unit ' . $unit->code . ' berhasil dibatalkan dan dialihkan ke Pelunasan Cash Lunas!');
        $this->showConvertToCashModal = false;
    }

    public function openSetupInstallmentModal(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Akses ditolak. Hanya Founder dan Finance yang dapat mengonfigurasi skema cicilan.');
            return;
        }

        $unit = Unit::with(['installment', 'activeBooking'])->findOrFail($this->unitId);
        $this->resetValidation();

        $booking = $unit->activeBooking;
        $alreadyPaid = $booking ? max((float)$booking->dp_amount, (float)$booking->booking_amount) : 0;

        if ($unit->installment) {
            $this->setup_total_price = (float)$unit->installment->total_price;
            $this->setup_down_payment = (float)$unit->installment->down_payment;
            $this->setup_installment_count = (int)$unit->installment->installment_count;
            $this->setup_start_date = $unit->installment->start_date ? $unit->installment->start_date->format('Y-m-d') : now()->toDateString();
        } else {
            $approvedProp = $unit->proposals->where('status', 'disetujui')->first();
            $defaultPrice = $unit->final_selling_price ?: ($unit->officialDocument?->proposal?->proposed_price ?? ($approvedProp?->proposed_price ?? ($unit->proposals->first()?->proposed_price ?? $unit->total_price)));
            $this->setup_total_price = (float)$defaultPrice;
            $this->setup_down_payment = $alreadyPaid > 0 ? $alreadyPaid : ($this->setup_total_price * 0.20);
            $this->setup_installment_count = 12;
            $this->setup_start_date = now()->toDateString();
        }

        $this->calculateMonthlyInstallment();
        $this->showSetupInstallmentModal = true;
    }

    public function calculateMonthlyInstallment(): void
    {
        $rem = max(0, (float)$this->setup_total_price - (float)$this->setup_down_payment);
        $count = max(1, (int)$this->setup_installment_count);
        $this->setup_installment_amount = $rem / $count;
    }

    public function saveSetupInstallment(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Hanya tim Finance, Founder, dan Admin yang berhak mengonfigurasi skema cicilan.');
            return;
        }

        $this->validate([
            'setup_total_price' => 'required|numeric|min:1000',
            'setup_installment_count' => 'required|integer|min:1',
            'setup_start_date' => 'required|date',
        ]);

        $unit = Unit::with(['officialDocument', 'installment.payments', 'activeBooking'])->findOrFail($this->unitId);

        DB::transaction(function () use ($unit) {
            if ($unit->installment) {
                // Update existing installment scheme
                $paidSoFar = (float)$this->setup_down_payment + (float)$unit->installment->payments->sum('amount_paid');
                $remUnpaid = max(0, (float)$this->setup_total_price - $paidSoFar);
                $newStatus = $remUnpaid <= 0 ? 'lunas' : 'berjalan';

                $unit->installment->update([
                    'total_price' => $this->setup_total_price,
                    'down_payment' => $this->setup_down_payment,
                    'installment_count' => $this->setup_installment_count,
                    'installment_amount' => $this->setup_installment_amount,
                    'start_date' => $this->setup_start_date,
                    'status' => $newStatus,
                ]);

                // Sync Cashflow DP transaction if present
                $dpCashflow = CashflowTransaction::where('reference_type', UnitInstallment::class)
                    ->where('reference_id', $unit->installment->id)
                    ->first();
                if ($dpCashflow) {
                    $dpCashflow->update([
                        'amount' => $this->setup_down_payment,
                        'transaction_date' => $this->setup_start_date,
                    ]);
                }
            } else {
                // Create new installment scheme
                $installment = UnitInstallment::create([
                    'unit_id' => $unit->id,
                    'official_document_id' => $unit->officialDocument->id ?? null,
                    'total_price' => $this->setup_total_price,
                    'down_payment' => $this->setup_down_payment,
                    'installment_count' => $this->setup_installment_count,
                    'installment_amount' => $this->setup_installment_amount,
                    'start_date' => $this->setup_start_date,
                    'status' => 'berjalan',
                ]);

                $booking = $unit->activeBooking;
                $alreadyPaid = $booking ? max((float)$booking->dp_amount, (float)$booking->booking_amount) : 0;
                $netDpCashflow = max(0, (float)$this->setup_down_payment - $alreadyPaid);

                if ($netDpCashflow > 0) {
                    CashflowTransaction::create([
                        'project_id' => $unit->project_id,
                        'type' => 'masuk',
                        'category' => 'pembayaran_cicilan_pembeli',
                        'amount' => $netDpCashflow,
                        'transaction_date' => $this->setup_start_date,
                        'description' => 'Pembayaran Uang Muka (DP) Unit ' . $unit->code . ($alreadyPaid > 0 ? ' (Net Tambahan DP, memperhitungkan Booking Fee Rp ' . number_format($alreadyPaid, 0, ',', '.') . ' yang sudah tercatat)' : ''),
                        'reference_type' => UnitInstallment::class,
                        'reference_id' => $installment->id,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        });

        session()->flash('success', 'Skema cicilan & piutang unit ' . $unit->code . ' berhasil diperbarui!');
        $this->showSetupInstallmentModal = false;
    }

    public function deleteInstallmentScheme(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isSuperAdmin()) {
            session()->flash('error', 'Hanya Founder dan Supervisor yang berhak menghapus skema cicilan pembeli.');
            return;
        }

        $unit = Unit::with('installment')->findOrFail($this->unitId);
        if (!$unit->installment) {
            session()->flash('error', 'Unit ini tidak memiliki skema cicilan aktif.');
            return;
        }

        $instId = $unit->installment->id;
        $code = $unit->code;

        DB::transaction(function () use ($unit, $instId, $code) {
            $paymentIds = InstallmentPayment::where('unit_installment_id', $instId)->pluck('id');

            // 1. Delete associated cashflow transactions
            CashflowTransaction::where('reference_type', UnitInstallment::class)
                ->where('reference_id', $instId)
                ->delete();

            if ($paymentIds->count() > 0) {
                CashflowTransaction::where('reference_type', InstallmentPayment::class)
                    ->whereIn('reference_id', $paymentIds)
                    ->delete();
            }

            // 2. Delete payments
            InstallmentPayment::where('unit_installment_id', $instId)->delete();

            // 3. Delete unit installment scheme
            $unit->installment->delete();

            ActivityLogger::log(
                'DELETE_INSTALLMENT_SCHEME',
                "Founder menghapus skema cicilan & piutang pembeli untuk Unit {$code}"
            );
        });

        session()->flash('success', "Skema cicilan Unit {$code} berhasil dihapus dari sistem!");
    }
}
