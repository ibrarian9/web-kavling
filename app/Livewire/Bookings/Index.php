<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $projectFilter = null;
    public string $typeFilter = '';
    public string $statusFilter = '';

    // Viewer Modal (PDF Viewer)
    public bool $showViewerModal = false;
    public string $viewerType = 'pdf';
    public string $viewerUrl = '';
    public string $viewerTitle = '';

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

    protected function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:50',
            'booking_type' => 'required|in:project,unit',
            'booking_amount' => 'required|numeric|min:1000',
            'booking_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:booking_date',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['project_id', 'unit_id', 'buyer_name', 'buyer_phone', 'booking_amount', 'dp_amount', 'notes']);
        $this->booking_amount = 5000000;
        $this->dp_amount = 0;
        $this->booking_type = 'unit';
        $this->booking_date = now()->toDateString();
        $this->expiry_date = now()->addDays(14)->toDateString();
        $this->showModal = true;
    }

    public function save(): void
    {
        $user = Auth::user();
        if (!$user->isMarketing() && !$user->isFinance() && !$user->isFounder()) {
            session()->flash('error', 'Hanya Marketing, Finance, dan Founder yang berhak mencatat booking baru.');
            return;
        }

        $validated = $this->validate();
        $validated['dp_amount'] = 0;
        $validated['status'] = 'active';
        $validated['created_by'] = Auth::id();

        $booking = Booking::create($validated);

        // Record automatically to cashflow if booking_amount > 0
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

        session()->flash('success', 'Tanda Jadi / Booking Fee berhasil dicatat dan arus kas diperbarui.');
        $this->showModal = false;
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


        
        $totalDp = $booking->dp_amount > 0 ? $booking->dp_amount : $booking->booking_amount;

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
            'notes' => ($booking->notes ? $booking->notes . ' | ' : '') . 'DP disetujui oleh ' . Auth::user()->name . ' pada ' . now()->format('d/m/Y H:i'),
        ]);

        if ($booking->unit) {
            $booking->unit->update(['status' => 'booked']);
        }

        session()->flash('success', 'DP Booking atas nama ' . $booking->buyer_name . ' berhasil disetujui di sistem dan dicatat ke Arus Kas Masuk (Rp ' . number_format($totalDp, 0, ',', '.') . ')!');
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
        $totalDpAmount = Booking::whereIn('status', ['active', 'converted'])->sum('dp_amount');

        return view('livewire.bookings.index', [
            'bookings' => $bookings,
            'projects' => $projects,
            'availableUnits' => $units,
            'totalBookingAmount' => $totalBookingAmount,
            'totalDpAmount' => $totalDpAmount,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Booking & DP Pembeli']);
    }
}
