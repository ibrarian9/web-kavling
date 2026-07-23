<?php

namespace App\Livewire\Costs;

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitCost;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $showModal = false;

    public $project_id = '';
    public $unit_id = null;
    public $category = 'tukang';
    public $description = '';
    public $amount = 0;
    public $cost_date = '';
    public $vendor_name = '';
    public $status = 'belum_dibayar';

    public function mount()
    {
        $this->cost_date = date('Y-m-d');
        if (Project::count() > 0) {
            $this->project_id = Project::first()->id;
        }
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->category = 'tukang';
        $this->description = '';
        $this->amount = 0;
        $this->cost_date = date('Y-m-d');
        $this->vendor_name = '';
        $this->status = 'belum_dibayar';
    }

    public function saveCost()
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance() && !$user->isPengawasProject()) {
            session()->flash('error', 'Hanya Pengawas Project, Finance, dan Founder yang berhak mencatat biaya proyek.');
            return;
        }

        $this->validate([
            'project_id' => 'required|exists:projects,id',
            'category' => 'required|in:tukang,material,perizinan,lainnya',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'cost_date' => 'required|date',
        ]);

        $cost = UnitCost::create([
            'project_id' => $this->project_id,
            'unit_id' => $this->unit_id ?: null,
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'cost_date' => $this->cost_date,
            'vendor_name' => $this->vendor_name,
            'status' => $this->status,
            'created_by' => auth()->id(),
        ]);

        // If status is paid, auto record in Cashflow Transaction
        if ($this->status === 'dibayar') {
            CashflowTransaction::create([
                'project_id' => $this->project_id,
                'type' => 'keluar',
                'category' => 'pembayaran_tukang',
                'amount' => $this->amount,
                'transaction_date' => $this->cost_date,
                'description' => 'Pembayaran Biaya ' . ucfirst($this->category) . ': ' . $this->description,
                'reference_type' => UnitCost::class,
                'reference_id' => $cost->id,
                'created_by' => auth()->id(),
            ]);
        }

        session()->flash('success', 'Biaya ' . $this->description . ' sebesar Rp ' . number_format($this->amount, 0, ',', '.') . ' berhasil dicatat!');
        $this->showModal = false;
    }

    public function markAsPaid($id)
    {
        $user = auth()->user();
        if (!$user->isFinance() && !$user->isFounder()) {
            session()->flash('error', 'Hanya Finance dan Founder yang berhak menandai biaya sebagai Dibayar/Lunas.');
            return;
        }

        $cost = UnitCost::findOrFail($id);
        $cost->update(['status' => 'dibayar']);


        // Record in cashflow
        CashflowTransaction::create([
            'project_id' => $cost->project_id,
            'type' => 'keluar',
            'category' => 'pembayaran_tukang',
            'amount' => $cost->amount,
            'transaction_date' => date('Y-m-d'),
            'description' => 'Pembayaran Biaya ' . ucfirst($cost->category) . ': ' . $cost->description,
            'reference_type' => UnitCost::class,
            'reference_id' => $cost->id,
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'Status biaya telah diperbarui menjadi Dibayar & dicatat di Arus Kas!');
    }

    public function render()
    {
        $costs = UnitCost::with(['project', 'unit', 'creator'])
            ->latest()
            ->paginate(10);

        $projects = Project::where('status', 'aktif')->get();
        $units = $this->project_id ? Unit::where('project_id', $this->project_id)->get() : collect();

        return view('livewire.costs.index', [
            'costs' => $costs,
            'projects' => $projects,
            'units' => $units,
        ])->layout('components.layouts.app', ['title' => 'Biaya Tukang & Material']);
    }
}
