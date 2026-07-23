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
use Livewire\Component;

class Show extends Component
{
    public $unitId;

    // Modal Worker Assignment
    public bool $showWorkerModal = false;
    public ?int $worker_id = null;
    public string $assigned_role = 'Mandor Lapangan';

    // Modal Unit Cost
    public bool $showCostModal = false;
    public string $cost_category = 'tukang';
    public string $cost_description = '';
    public float $cost_amount = 0;
    public string $cost_date = '';
    public string $vendor_name = '';
    public string $cost_status = 'dibayar';

    // Modal Booking
    public bool $showBookingModal = false;
    public string $buyer_name = '';
    public string $buyer_phone = '';
    public float $booking_amount = 5000000;
    public float $dp_amount = 25000000;
    public string $booking_notes = '';

    public function mount($id)
    {
        $this->unitId = $id;
        $this->cost_date = now()->toDateString();
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
            ->where('unit_id', $unit->id)
            ->orWhere(function($q) use ($unit) {
                $q->where('project_id', $unit->project_id)->whereNull('unit_id');
            })
            ->where('status', 'active')
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
        ])->layout('components.layouts.app', ['title' => 'Detail Unit ' . $unit->code . ' - ' . $unit->project->name]);
    }
}
