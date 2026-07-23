<?php

namespace App\Livewire\Materials;

use App\Models\Project;
use App\Models\Unit;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerLoan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class WeeklyLog extends Component
{
    use WithPagination;

    public ?int $projectFilter = null;
    public ?int $workerFilter = null;

    // Modal state
    public bool $showModal = false;
    public ?int $project_id = null;
    public ?int $unit_id = null;
    public ?int $worker_id = null;
    public ?string $purchase_date = null;
    public string $item_name = '';
    public float $quantity = 1;
    public string $unit_measure = 'sak';
    public float $unit_price = 0;
    public bool $is_deducted_from_loan = true; // Option 1 confirmed by user
    public string $notes = '';

    protected function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'worker_id' => 'required|exists:workers,id',
            'purchase_date' => 'required|date',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_measure' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'is_deducted_from_loan' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['project_id', 'unit_id', 'worker_id', 'item_name', 'quantity', 'unit_price', 'notes']);
        $this->purchase_date = now()->toDateString();
        $this->unit_measure = 'sak';
        $this->quantity = 1;
        $this->is_deducted_from_loan = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $totalPrice = $this->quantity * $this->unit_price;
        $validated['total_price'] = $totalPrice;
        $validated['pengawas_id'] = Auth::id();

        // Save Weekly Purchase Record
        $purchase = WeeklyMaterialPurchase::create($validated);

        // Opsi 1: Otomatis memotong/menjadikan piutang worker
        if ($this->is_deducted_from_loan) {
            $worker = Worker::find($this->worker_id);
            $loan = WorkerLoan::create([
                'worker_id' => $this->worker_id,
                'project_id' => $this->project_id,
                'unit_id' => $this->unit_id,
                'loan_date' => $this->purchase_date,
                'amount' => $totalPrice,
                'paid_amount' => 0,
                'purpose' => "Pengambilan barang mingguan: {$this->item_name} ({$this->quantity} {$this->unit_measure})",
                'status' => 'approved',
                'approved_by' => Auth::id(),
            ]);

            $purchase->update(['worker_loan_id' => $loan->id]);
        }

        session()->flash('success', 'Log barang mingguan berhasil dicatat dan ditambahkan ke piutang worker.');
        $this->showModal = false;
    }

    public function render()
    {
        $query = WeeklyMaterialPurchase::query()
            ->with(['project', 'unit', 'worker', 'pengawas']);

        if ($this->projectFilter) {
            $query->where('project_id', $this->projectFilter);
        }

        if ($this->workerFilter) {
            $query->where('worker_id', $this->workerFilter);
        }

        $purchases = $query->latest('id')->paginate(10);
        $projects = Project::orderBy('name')->get();
        $workers = Worker::where('status', 'active')->orderBy('name')->get();
        $units = $this->project_id ? Unit::where('project_id', $this->project_id)->orderBy('code')->get() : collect();

        $totalWeeklyPurchases = WeeklyMaterialPurchase::sum('total_price');

        return view('livewire.materials.weekly-log', [
            'purchases' => $purchases,
            'projects' => $projects,
            'workers' => $workers,
            'availableUnits' => $units,
            'totalWeeklyPurchases' => $totalWeeklyPurchases,
        ])->layout('components.layouts.app', ['title' => 'Log Pembelian Material & Alat Mingguan Tukang']);
    }
}

