<?php

namespace App\Livewire\Workers;

use App\Models\Project;
use App\Models\Unit;
use App\Models\Worker;
use App\Models\WorkerLoan;
use App\Models\WorkerLoanPayment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Loans extends Component
{
    use WithPagination;

    public ?int $workerFilter = null;
    public string $statusFilter = '';

    // Form Modal Loan
    public bool $showLoanModal = false;
    public ?int $worker_id = null;
    public ?int $project_id = null;
    public ?int $unit_id = null;
    public ?string $loan_date = null;
    public float $amount = 0;
    public string $purpose = '';

    // Form Modal Payment
    public bool $showPaymentModal = false;
    public ?WorkerLoan $selectedLoan = null;
    public float $amount_paid = 0;
    public string $payment_method = 'potong_opname';
    public string $payment_notes = '';

    protected function rulesForLoan(): array
    {
        return [
            'worker_id' => 'required|exists:workers,id',
            'project_id' => 'nullable|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'loan_date' => 'required|date',
            'amount' => 'required|numeric|min:1000',
            'purpose' => 'nullable|string|max:500',
        ];
    }

    public function createLoan(): void
    {
        $this->resetValidation();
        $this->reset(['worker_id', 'project_id', 'unit_id', 'amount', 'purpose']);
        $this->loan_date = now()->toDateString();
        $this->showLoanModal = true;
    }

    public function saveLoan(): void
    {
        $user = Auth::user();
        if (!$user->isFinance() && !$user->isFounder()) {
            session()->flash('error', 'Hanya Finance dan Founder yang berhak mencatat pinjaman worker.');
            return;
        }

        $validated = $this->validate($this->rulesForLoan());
        $validated['paid_amount'] = 0;
        $validated['status'] = 'approved';
        $validated['approved_by'] = Auth::id();

        WorkerLoan::create($validated);

        session()->flash('success', 'Pinjaman/Piutang worker berhasil dicatat.');
        $this->showLoanModal = false;
    }

    public function openPaymentModal(WorkerLoan $loan): void
    {
        $this->resetValidation();
        $this->selectedLoan = $loan;
        $this->amount_paid = $loan->remaining_balance;
        $this->payment_method = 'potong_opname';
        $this->payment_notes = '';
        $this->showPaymentModal = true;
    }

    public function savePayment(): void
    {
        $user = Auth::user();
        if (!$user->isFinance() && !$user->isFounder()) {
            session()->flash('error', 'Hanya Finance dan Founder yang berhak mencatat pelunasan/potongan piutang worker.');
            return;
        }

        $this->validate([
            'amount_paid' => 'required|numeric|min:1000|max:' . ($this->selectedLoan ? $this->selectedLoan->remaining_balance : 9999999999),
            'payment_method' => 'required|string',
            'payment_notes' => 'nullable|string',
        ]);


        if (!$this->selectedLoan) return;

        WorkerLoanPayment::create([
            'worker_loan_id' => $this->selectedLoan->id,
            'payment_date' => now()->toDateString(),
            'amount_paid' => $this->amount_paid,
            'payment_method' => $this->payment_method,
            'notes' => $this->payment_notes,
            'created_by' => Auth::id(),
        ]);

        $newPaidAmount = $this->selectedLoan->paid_amount + $this->amount_paid;
        $newStatus = $newPaidAmount >= $this->selectedLoan->amount ? 'paid' : 'partially_paid';

        $this->selectedLoan->update([
            'paid_amount' => $newPaidAmount,
            'status' => $newStatus,
        ]);

        session()->flash('success', 'Pembayaran/Potongan piutang berhasil dicatat.');
        $this->showPaymentModal = false;
    }

    public function render()
    {
        $query = WorkerLoan::query()
            ->with(['worker', 'project', 'unit', 'payments']);

        if ($this->workerFilter) {
            $query->where('worker_id', $this->workerFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $loans = $query->latest('id')->paginate(10);
        $workers = Worker::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $units = $this->project_id ? Unit::where('project_id', $this->project_id)->orderBy('code')->get() : collect();

        // Ringkasan Piutang
        $totalAllLoans = WorkerLoan::sum('amount');
        $totalPaidLoans = WorkerLoan::sum('paid_amount');
        $totalRemainingLoans = max(0, $totalAllLoans - $totalPaidLoans);

        return view('livewire.workers.loans', [
            'loans' => $loans,
            'workers' => $workers,
            'projects' => $projects,
            'availableUnits' => $units,
            'totalAllLoans' => $totalAllLoans,
            'totalPaidLoans' => $totalPaidLoans,
            'totalRemainingLoans' => $totalRemainingLoans,
        ])->layout('components.layouts.app', ['title' => 'Kasbon & Piutang Pekerja Lapangan']);
    }
}

