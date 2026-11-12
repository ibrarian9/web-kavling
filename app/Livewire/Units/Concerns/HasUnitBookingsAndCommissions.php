<?php

namespace App\Livewire\Units\Concerns;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\Unit;
use App\Models\UnitCommission;
use App\Models\UnitCommissionPayment;
use App\Services\ActivityLogger;
use App\Services\ImageCompressor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HasUnitBookingsAndCommissions
{
    // Modal Booking
    public bool $showBookingModal = false;
    public string $buyer_name = '';
    public string $buyer_phone = '';
    public $booking_amount = 5000000;
    public $dp_amount = 25000000;
    public ?string $booking_date = null;
    public ?string $expiry_date = null;
    public string $booking_notes = '';
    public $receipt_photo = null;

    // Modal Catat Komisi Penjual Unit
    public bool $showCommissionModal = false;
    public string $unit_comm_seller_name = '';
    public string $unit_comm_seller_phone = '';
    public ?int $unit_comm_marketing_id = null;
    public $unit_comm_percentage = 2.5;
    public $unit_comm_amount = 0;
    public string $unit_comm_notes = '';

    // Modal Bayar Cicilan Komisi
    public bool $showCommissionPaymentModal = false;
    public ?int $unit_settling_commission_id = null;
    public $unit_pay_comm_amount = 0;
    public string $unit_pay_comm_date = '';
    public string $unit_pay_comm_method = 'Transfer Bank';
    public string $unit_pay_comm_notes = '';
    public $unit_pay_comm_photo = null;

    public function openBookingModal(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFinance() && !$user->isFounder())) {
            session()->flash('error', 'Hanya tim Finance dan Founder yang berhak mendaftarkan booking unit.');
            return;
        }

        $unit = Unit::findOrFail($this->unitId);
        $this->buyer_name = '';
        $this->buyer_phone = '';
        $this->booking_amount = 5000000;
        $this->dp_amount = 0;
        $this->booking_date = now()->toDateString();
        $this->expiry_date = now()->addDays(14)->toDateString();
        $this->booking_notes = 'Booking unit ' . $unit->code . ' via Halaman Detail Unit.';
        $this->receipt_photo = null;
        $this->showBookingModal = true;
    }

    public function updatedReceiptPhoto(): void
    {
        $this->validate([
            'receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
        ]);
    }

    public function saveBooking(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFinance() && !$user->isFounder())) {
            session()->flash('error', 'Hanya tim Finance dan Founder yang berhak mendaftarkan booking unit.');
            return;
        }

        if ($this->booking_amount) {
            $this->booking_amount = (float) preg_replace('/[^0-9]/', '', (string)$this->booking_amount);
        }

        $this->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:50',
            'booking_amount' => 'required|numeric|min:1000',
            'booking_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:booking_date',
            'receipt_photo' => 'nullable|image|max:2048',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        $receiptPath = null;
        if ($this->receipt_photo) {
            $receiptPath = $this->receipt_photo->store('receipts/bookings', 'public');
        }

        $booking = Booking::create([
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'buyer_name' => $this->buyer_name,
            'buyer_phone' => $this->buyer_phone,
            'booking_type' => 'unit',
            'booking_amount' => $this->booking_amount,
            'dp_amount' => 0,
            'booking_date' => $this->booking_date,
            'expiry_date' => $this->expiry_date ?: Carbon::parse($this->booking_date)->addDays(14)->toDateString(),
            'status' => 'active',
            'notes' => $this->booking_notes,
            'receipt_photo_path' => $receiptPath,
            'created_by' => Auth::id(),
        ]);

        $unit->update(['status' => 'booked']);

        session()->flash('success', 'Booking unit ' . $unit->code . ' atas nama ' . $this->buyer_name . ' berhasil dicatat!');
        $this->showBookingModal = false;

        $this->openViewerModal('pdf', route('bookings.receipt', $booking->id) . '?v=' . now()->timestamp, 'Kwitansi Tanda Jadi Booking - ' . $booking->buyer_name . ' (' . $unit->code . ')');
    }

    public function openCommissionModal(): void
    {
        $user = Auth::user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat hutang komisi penjual.');
            return;
        }

        $unit = Unit::with('installment')->findOrFail($this->unitId);
        $this->resetValidation();
        $this->unit_comm_seller_name = '';
        $this->unit_comm_seller_phone = '';
        $this->unit_comm_marketing_id = null;
        $this->unit_comm_percentage = 2.5;

        $basePrice = $unit->installment ? (float)$unit->installment->total_price : (float)($unit->final_selling_price ?: $unit->price);
        $this->unit_comm_amount = round(($this->unit_comm_percentage / 100) * $basePrice);
        $this->unit_comm_notes = "Komisi Penjualan Unit {$unit->code}";
        $this->showCommissionModal = true;
    }

    public function updatedUnitCommPercentage(): void
    {
        $unit = Unit::with('installment')->find($this->unitId);
        if ($unit) {
            $basePrice = $unit->installment ? (float)$unit->installment->total_price : (float)($unit->final_selling_price ?: $unit->price);
            $this->unit_comm_amount = round(((float)$this->unit_comm_percentage / 100) * $basePrice);
        }
    }

    public function saveCommission(): void
    {
        $user = Auth::user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat hutang komisi penjual.');
            return;
        }

        if ($this->unit_comm_amount) {
            $this->unit_comm_amount = (float) preg_replace('/[^0-9]/', '', (string)$this->unit_comm_amount);
        }

        $this->validate([
            'unit_comm_seller_name' => 'required|string|max:255',
            'unit_comm_seller_phone' => 'nullable|string|max:50',
            'unit_comm_marketing_id' => 'nullable|exists:users,id',
            'unit_comm_percentage' => 'nullable|numeric|min:0|max:100',
            'unit_comm_amount' => 'required|numeric|min:1000',
            'unit_comm_notes' => 'nullable|string|max:500',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        $comm = UnitCommission::create([
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'marketing_id' => $this->unit_comm_marketing_id ?: null,
            'seller_name' => trim($this->unit_comm_seller_name),
            'seller_phone' => trim($this->unit_comm_seller_phone) ?: null,
            'percentage' => (float)($this->unit_comm_percentage ?: 0),
            'commission_amount' => (float)$this->unit_comm_amount,
            'paid_amount' => 0,
            'status' => 'belum_dibayar',
            'notes' => $this->unit_comm_notes,
            'created_by' => $user->id,
        ]);

        ActivityLogger::log(
            'COMMISSION_DEBT_CREATED',
            "User {$user->name} mencatat hutang komisi penjual '{$comm->seller_name}' untuk Unit {$unit->code} sebesar Rp " . number_format($comm->commission_amount, 0, ',', '.')
        );

        $this->showCommissionModal = false;
        $msg = "Catatan Hutang Komisi Penjual '{$comm->seller_name}' berhasil disimpan!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Komisi Dicatat!', 'message' => $msg]);
    }

    public function openCommissionPaymentModal(int $commissionId): void
    {
        $user = Auth::user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat pembayaran cicilan komisi.');
            return;
        }

        $comm = UnitCommission::with('unit')->findOrFail($commissionId);
        $this->unit_settling_commission_id = $comm->id;
        $this->unit_pay_comm_amount = $comm->remaining_amount;
        $this->unit_pay_comm_date = now()->toDateString();
        $this->unit_pay_comm_method = 'Transfer Bank';
        $this->unit_pay_comm_notes = "Pembayaran Cicilan Komisi: {$comm->seller_name} (Unit " . ($comm->unit->code ?? '-') . ")";
        $this->unit_pay_comm_photo = null;
        $this->showCommissionPaymentModal = true;
    }

    public function processCommissionPayment(): void
    {
        if (!$this->unit_settling_commission_id) {
            return;
        }

        $user = Auth::user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat pembayaran cicilan komisi.');
            return;
        }

        $comm = UnitCommission::with('unit')->findOrFail($this->unit_settling_commission_id);

        if ($this->unit_pay_comm_amount) {
            $this->unit_pay_comm_amount = (float) preg_replace('/[^0-9]/', '', (string)$this->unit_pay_comm_amount);
        }

        $this->validate([
            'unit_pay_comm_amount' => 'required|numeric|min:1000|max:' . $comm->remaining_amount,
            'unit_pay_comm_date' => 'required|date',
            'unit_pay_comm_method' => 'required|string',
            'unit_pay_comm_notes' => 'nullable|string',
            'unit_pay_comm_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
        ], [
            'unit_pay_comm_amount.max' => 'Nominal pembayaran cicilan komisi tidak boleh melebihi sisa hutang komisi (Rp ' . number_format($comm->remaining_amount, 0, ',', '.') . ').',
        ]);

        $photoPath = null;
        if ($this->unit_pay_comm_photo) {
            $photoPath = ImageCompressor::compressAndStore($this->unit_pay_comm_photo, 'commission-receipts');
        }

        DB::transaction(function () use ($comm, $photoPath, $user) {
            $payment = UnitCommissionPayment::create([
                'unit_commission_id' => $comm->id,
                'payment_date' => $this->unit_pay_comm_date,
                'amount' => $this->unit_pay_comm_amount,
                'payment_method' => $this->unit_pay_comm_method,
                'notes' => $this->unit_pay_comm_notes,
                'receipt_photo_path' => $photoPath,
                'created_by' => $user->id,
            ]);

            $unitCode = $comm->unit ? " Unit {$comm->unit->code}" : '';
            $description = "Pembayaran Cicilan Komisi Penjual ({$comm->seller_name}){$unitCode}: Rp " . number_format($this->unit_pay_comm_amount, 0, ',', '.');

            CashflowTransaction::create([
                'project_id' => $comm->project_id,
                'type' => 'keluar',
                'category' => 'operasional',
                'amount' => $this->unit_pay_comm_amount,
                'transaction_date' => $this->unit_pay_comm_date,
                'description' => $description,
                'reference_type' => UnitCommissionPayment::class,
                'reference_id' => $payment->id,
                'receipt_photo_path' => $photoPath,
                'created_by' => $user->id,
            ]);

            $comm->recalculateStatus();
        });

        ActivityLogger::log(
            'COMMISSION_PAYMENT_RECORDED',
            "User {$user->name} mencatat pembayaran cicilan komisi '{$comm->seller_name}' sebesar Rp " . number_format($this->unit_pay_comm_amount, 0, ',', '.') . " & tercatat di Arus Kas."
        );

        $this->showCommissionPaymentModal = false;
        $this->unit_settling_commission_id = null;

        $msg = "Pembayaran cicilan komisi '{$comm->seller_name}' sebesar Rp " . number_format($this->unit_pay_comm_amount, 0, ',', '.') . " berhasil dicatat ke Kas Keluar!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Pembayaran Berhasil!', 'message' => $msg]);
    }

    public function deleteCommission(int $commissionId): void
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            session()->flash('error', 'Hanya Founder dan Supervisor yang berhak menghapus catatan komisi.');
            return;
        }

        $comm = UnitCommission::with('unit')->where('unit_id', $this->unitId)->findOrFail($commissionId);
        $sellerName = $comm->seller_name;
        $unitCode = $comm->unit->code ?? $this->unitId;
        $totalCommission = (float)$comm->total_commission;
        $userName = $user->name ?? 'User';

        DB::transaction(function () use ($comm) {
            foreach ($comm->payments as $p) {
                CashflowTransaction::where('reference_type', UnitCommissionPayment::class)
                    ->where('reference_id', $p->id)
                    ->delete();
            }
            $comm->delete();
        });

        ActivityLogger::log(
            'COMMISSION_DELETED',
            "Catatan komisi marketing/penjual '{$sellerName}' (Rp " . number_format($totalCommission, 0, ',', '.') . ") pada Unit {$unitCode} dihapus oleh {$userName}."
        );

        session()->flash('success', 'Catatan komisi penjual berhasil dihapus.');
    }
}
