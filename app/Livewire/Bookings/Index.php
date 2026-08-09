<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public ?int $projectFilter = null;
    public string $typeFilter = '';
    public string $statusFilter = '';
    public ?int $editingBookingId = null;

    // Viewer Modal (PDF Viewer)
    public bool $showViewerModal = false;
    public string $viewerType = 'pdf';
    public string $viewerUrl = '';
    public string $viewerTitle = '';

    // Image Modal (Foto Resi Pembayaran Modal)
    public bool $showImageModal = false;
    public string $imageModalUrl = '';
    public string $imageModalTitle = '';

    public function openViewerModal(string $type, string $url, string $title = ''): void
    {
        $this->viewerType = $type;
        $this->viewerUrl = $url;
        $this->viewerTitle = $title ?: 'Pratinjau Resi PDF';
        $this->showViewerModal = true;
    }

    public function closeViewerModal(): void
    {
        $this->showViewerModal = false;
        $this->viewerType = '';
        $this->viewerUrl = '';
        $this->viewerTitle = '';
    }

    public function openImageModal(string $url, string $title = ''): void
    {
        $this->imageModalUrl = $url;
        $this->imageModalTitle = $title ?: 'Foto Resi Bukti Transfer / DP';
        $this->showImageModal = true;
    }

    public function closeImageModal(): void
    {
        $this->showImageModal = false;
        $this->imageModalUrl = '';
        $this->imageModalTitle = '';
    }

    // Modal state
    public bool $showModal = false;
    public ?int $project_id = null;
    public ?int $unit_id = null;
    public string $buyer_name = '';
    public string $buyer_phone = '';
    public string $booking_type = 'unit';
    public float $booking_amount = 0;
    public float $dp_amount = 0;
    public ?string $booking_date = null;
    public ?string $expiry_date = null;
    public string $notes = '';
    public $receipt_photo = null;

    protected function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:50',
            'booking_type' => 'required|in:project,unit',
            'booking_amount' => 'required|numeric|min:1000',
            'dp_amount' => 'nullable|numeric|min:0',
            'booking_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:booking_date',
            'notes' => 'nullable|string|max:500',
            'receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif|max:10240',
        ];
    }

    public function updatedReceiptPhoto(): void
    {
        $this->validateOnly('receipt_photo');
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->editingBookingId = null;
        $this->reset(['project_id', 'unit_id', 'buyer_name', 'buyer_phone', 'booking_amount', 'dp_amount', 'notes', 'receipt_photo']);
        $this->booking_amount = 5000000;
        $this->dp_amount = 0;
        $this->booking_type = 'unit';
        $this->booking_date = now()->toDateString();
        $this->expiry_date = now()->addDays(14)->toDateString();
        $this->showModal = true;
    }

    public function editBooking($id): void
    {
        $user = Auth::user();
        if (!$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak mengubah data booking.');
            return;
        }

        $booking = Booking::findOrFail((int) $id);
        $this->resetValidation();
        $this->editingBookingId = $booking->id;
        $this->project_id = $booking->project_id;
        $this->booking_type = $booking->booking_type;
        $this->unit_id = $booking->unit_id;
        $this->buyer_name = $booking->buyer_name;
        $this->buyer_phone = $booking->buyer_phone;
        $this->booking_amount = (float) $booking->booking_amount;
        $this->dp_amount = (float) $booking->dp_amount;
        $this->booking_date = $booking->booking_date ? $booking->booking_date->format('Y-m-d') : null;
        $this->expiry_date = $booking->expiry_date ? $booking->expiry_date->format('Y-m-d') : null;
        $this->notes = $booking->notes ?? '';
        $this->receipt_photo = null;
        $this->showModal = true;
    }

    public function save(): void
    {
        $user = Auth::user();

        if ($this->unit_id === '') {
            $this->unit_id = null;
        }

        if ($this->editingBookingId) {
            if (!$user->isFounder()) {
                session()->flash('error', 'Hanya Founder yang berhak mengedit data booking.');
                return;
            }

            $booking = Booking::findOrFail($this->editingBookingId);
            $oldUnitId = $booking->unit_id;

            $validated = $this->validate();
            $validated['dp_amount'] = (float)($this->dp_amount ?: 0);

            if ($this->receipt_photo) {
                $validated['receipt_photo_path'] = $this->receipt_photo->store('receipts/bookings', 'public');
            }
            unset($validated['receipt_photo']);

            $booking->update($validated);

            if ($oldUnitId && $oldUnitId != $this->unit_id) {
                $oldUnit = Unit::find($oldUnitId);
                if ($oldUnit && $oldUnit->status === 'booked') {
                    $oldUnit->update(['status' => 'tersedia']);
                }
            }

            if ($this->unit_id && $oldUnitId != $this->unit_id) {
                $newUnit = Unit::find($this->unit_id);
                if ($newUnit && in_array($newUnit->status, ['tersedia', 'disetujui'])) {
                    $newUnit->update(['status' => 'booked']);
                }
            }

            $cashflow = CashflowTransaction::where('reference_type', Booking::class)
                ->where('reference_id', $booking->id)
                ->first();
            if ($cashflow) {
                $cashflow->update([
                    'project_id' => $booking->project_id,
                    'amount' => $booking->booking_amount,
                    'transaction_date' => $booking->booking_date,
                    'description' => "Kas Masuk Tanda Jadi / Booking Fee dari {$booking->buyer_name}" . ($booking->unit_id ? " (Unit Kode ID {$booking->unit_id})" : ""),
                    'receipt_photo_path' => $booking->receipt_photo_path ?? $cashflow->receipt_photo_path,
                ]);
            }

            ActivityLogger::log('BOOKING_UPDATED', "Data booking atas nama {$booking->buyer_name} (ID #{$booking->id}) telah diperbarui oleh Founder.");

            $msg = 'Data booking atas nama ' . $booking->buyer_name . ' berhasil diperbarui oleh Founder.';
            session()->flash('success', $msg);
            $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
            $this->showModal = false;
            $this->editingBookingId = null;
            return;
        }

        if (!$user->isMarketing() && !$user->isFinance() && !$user->isFounder()) {
            $err = 'Hanya Marketing, Finance, dan Founder yang berhak mencatat booking baru.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $validated = $this->validate();
        $bookingData = array_merge($validated, [
            'unit_id' => $this->unit_id ? (int)$this->unit_id : null,
            'dp_amount' => (float)($this->dp_amount ?: 0),
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        if ($this->receipt_photo) {
            $bookingData['receipt_photo_path'] = $this->receipt_photo->store('receipts/bookings', 'public');
        }

        $booking = Booking::create($bookingData);

        if ($this->booking_amount > 0) {
            CashflowTransaction::create([
                'project_id' => $this->project_id,
                'type' => 'masuk',
                'category' => 'penjualan_unit',
                'amount' => $this->booking_amount,
                'transaction_date' => $this->booking_date,
                'description' => "Kas Masuk Tanda Jadi / Booking Fee dari {$this->buyer_name}" . ($this->unit_id ? " (Unit Kode ID {$this->unit_id})" : ""),
                'reference_type' => Booking::class,
                'reference_id' => $booking->id,
                'created_by' => Auth::id(),
            ]);
        }

        if ($this->unit_id) {
            $unit = Unit::find($this->unit_id);
            if ($unit && in_array($unit->status, ['tersedia', 'disetujui'])) {
                $unit->update(['status' => 'booked']);
            }
        }

        $msg = 'Tanda Jadi / Booking Fee berhasil dicatat dan arus kas diperbarui.';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showModal = false;
    }

    public function deleteBooking($id): void
    {
        $user = Auth::user();
        if (!$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak menghapus data booking.');
            return;
        }

        $booking = Booking::with('unit')->findOrFail((int) $id);
        $buyerName = $booking->buyer_name;

        if ($booking->unit && \App\Enums\UnitStatus::isBooked($booking->unit->status)) {
            $booking->unit->update(['status' => \App\Enums\UnitStatus::TERSEDIA->value]);
        }

        CashflowTransaction::where('reference_type', Booking::class)
            ->where('reference_id', $booking->id)
            ->delete();

        if ($booking->receipt_photo_path) {
            Storage::disk('public')->delete($booking->receipt_photo_path);
        }

        $booking->delete();

        ActivityLogger::log('BOOKING_DELETED', "Data booking atas nama {$buyerName} (ID #{$id}) telah dihapus oleh Founder.");

        session()->flash('success', 'Data booking atas nama ' . $buyerName . ' berhasil dihapus oleh Founder.');
    }

    // In-System DP Approval Workflow for Finance & Founder (Req #2)
    public function approveDp(int $bookingId): void
    {
        $user = Auth::user();
        if (!$user->isFinance() && !$user->isFounder()) {
            session()->flash('error', 'Hanya Finance dan Founder yang berhak menyetujui Tanda Jadi booking.');
            return;
        }

        $booking = Booking::with('unit')->findOrFail($bookingId);

        $totalDp = (float)($booking->dp_amount > 0 ? $booking->dp_amount : $booking->booking_amount);

        // 1. Record incoming Cashflow transaction
        CashflowTransaction::create([
            'project_id' => $booking->project_id,
            'type' => 'masuk',
            'category' => 'pembayaran_cicilan_pembeli',
            'amount' => $totalDp,
            'transaction_date' => now()->toDateString(),
            'description' => "Persetujuan DP Pembeli di Sistem: {$booking->buyer_name}" . ($booking->unit ? " (Unit {$booking->unit->code})" : ""),
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'created_by' => Auth::id(),
        ]);

        // 2. Update booking and unit statuses
        $booking->update([
            'status' => 'converted',
            'dp_amount' => $totalDp,
            'notes' => ($booking->notes ? $booking->notes . ' | ' : '') . 'DP disetujui oleh ' . Auth::user()->name . ' pada ' . now()->format('d/m/Y H:i'),
        ]);

        if ($booking->unit) {
            $booking->unit->update(['status' => 'booked']);
        }

        session()->flash('success', 'DP Booking atas nama ' . $booking->buyer_name . ' berhasil disetujui di sistem dan dicatat ke Arus Kas Masuk (Rp ' . number_format($totalDp, 0, ',', '.') . ')!');
    }

    public function rejectDp(int $bookingId): void
    {
        $user = Auth::user();
        if (!$user->isFinance() && !$user->isFounder()) {
            $err = 'Hanya Finance dan Founder yang berhak menolak Tanda Jadi booking.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $err]);
            return;
        }

        $booking = Booking::with('unit')->findOrFail($bookingId);

        // 1. Update booking status to cancelled
        $booking->update([
            'status' => 'cancelled',
            'notes' => ($booking->notes ? $booking->notes . ' | ' : '') . 'Booking ditolak oleh ' . $user->name . ' pada ' . now()->format('d/m/Y H:i'),
        ]);

        // 2. Revert unit status back to tersedia
        $targetUnitId = $booking->unit_id ?: ($booking->unit->id ?? null);
        if ($targetUnitId) {
            Unit::where('id', $targetUnitId)->update(['status' => 'tersedia']);
        }

        \App\Services\ActivityLogger::log('BOOKING_REJECTED', "Booking atas nama {$booking->buyer_name} ditolak oleh {$user->name} ({$user->role}). Status unit dikembalikan menjadi tersedia.");

        $msg = 'Booking atas nama ' . $booking->buyer_name . ' berhasil ditolak dan status unit dikembalikan menjadi tersedia.';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    public function cancelApprovedDp(int $bookingId): void
    {
        $user = Auth::user();
        if (!$user->isFinance() && !$user->isFounder()) {
            $err = 'Hanya Finance dan Founder yang berhak membatalkan atau merefund DP yang telah disetujui.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $err]);
            return;
        }

        $booking = Booking::with('unit')->findOrFail($bookingId);

        if ($booking->status !== 'converted') {
            session()->flash('error', 'Hanya booking dengan status DP ACC yang dapat dibatalkan/direfund.');
            return;
        }

        $refundAmount = (float)($booking->dp_amount > 0 ? $booking->dp_amount : $booking->booking_amount);

        // 1. Record Outgoing Cashflow transaction (Refund)
        if ($refundAmount > 0) {
            CashflowTransaction::create([
                'project_id' => $booking->project_id,
                'type' => 'keluar',
                'category' => 'lainnya',
                'amount' => $refundAmount,
                'transaction_date' => now()->toDateString(),
                'description' => "Pengembalian / Refund DP Booking Pembeli: {$booking->buyer_name}" . ($booking->unit ? " (Unit {$booking->unit->code})" : ""),
                'reference_type' => Booking::class,
                'reference_id' => $booking->id,
                'created_by' => Auth::id(),
            ]);
        }

        $targetUnitId = $booking->unit_id ?: ($booking->unit->id ?? null);

        // 2. Update booking and unit statuses
        $booking->update([
            'status' => 'refunded',
            'notes' => ($booking->notes ? $booking->notes . ' | ' : '') . 'DP dibatalkan/direfund oleh ' . $user->name . ' pada ' . now()->format('d/m/Y H:i'),
        ]);

        if ($targetUnitId) {
            Unit::where('id', $targetUnitId)->update(['status' => 'tersedia']);
        }

        \App\Services\ActivityLogger::log('BOOKING_REFUNDED', "DP Booking atas nama {$booking->buyer_name} dibatalkan/direfund oleh {$user->name} ({$user->role}). Pengeluaran kas refund dicatat dan unit kembali tersedia.");

        $msg = 'DP Booking atas nama ' . $booking->buyer_name . ' berhasil dibatalkan/direfund. Pengeluaran kas telah dicatat dan unit kembali tersedia.';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    public function render()
    {
        $query = Booking::query()
            ->with(['project', 'unit', 'creator']);

        if ($this->projectFilter) {
            $query->where('project_id', $this->projectFilter);
        }

        if ($this->typeFilter) {
            $query->where('booking_type', $this->typeFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $bookings = $query->latest('id')->paginate(10);
        $projects = Project::orderBy('name')->get();
        $units = $this->project_id ? Unit::where('project_id', $this->project_id)->orderBy('code')->get() : collect();

        $totalBookingAmount = Booking::whereIn('status', ['active', 'converted'])->sum('booking_amount');
        $totalDpAmount = Booking::where('status', 'converted')->sum('dp_amount');

        return view('livewire.bookings.index', [
            'bookings' => $bookings,
            'projects' => $projects,
            'availableUnits' => $units,
            'totalBookingAmount' => $totalBookingAmount,
            'totalDpAmount' => $totalDpAmount,
            'showImageModal' => $this->showImageModal,
            'imageModalUrl' => $this->imageModalUrl,
            'imageModalTitle' => $this->imageModalTitle,
            'receipt_photo' => $this->receipt_photo,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Booking & DP Pembeli']);
    }
}
