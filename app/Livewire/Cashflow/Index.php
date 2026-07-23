<?php

namespace App\Livewire\Cashflow;

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filter_project_id = '';
    public $filter_unit_id = '';
    public $view_mode = 'global'; // 'global', 'project', atau 'unit'
    public $showManualModal = false;

    // Manual Transaction Form
    public $project_id = '';
    public $type = 'masuk';
    public $category = 'operasional';
    public $amount = 0;
    public $transaction_date = '';
    public $description = '';

    public function mount()
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Akses ditolak. Modul Laporan Arus Kas & Global bersifat rahasia (Hanya untuk Founder dan Finance).');
            return redirect()->route('dashboard');
        }

        $this->transaction_date = date('Y-m-d');
        if (Project::count() > 0) {
            $this->project_id = Project::first()->id;
        }
    }


    public function updatedFilterProjectId()
    {
        $this->filter_unit_id = '';
    }

    public function openManualModal()
    {
        $this->resetForm();
        $this->showManualModal = true;
    }

    public function resetForm()
    {
        $this->type = 'masuk';
        $this->category = 'operasional';
        $this->amount = 0;
        $this->transaction_date = date('Y-m-d');
        $this->description = '';
    }

    public function saveTransaction()
    {
        $this->validate([
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:masuk,keluar',
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        CashflowTransaction::create([
            'project_id' => $this->project_id,
            'type' => $this->type,
            'category' => $this->category,
            'amount' => $this->amount,
            'transaction_date' => $this->transaction_date,
            'description' => $this->description,
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'Transaksi Arus Kas berhasil dicatat!');
        $this->showManualModal = false;
    }

    public function render()
    {
        $query = CashflowTransaction::with(['project', 'creator']);

        if ($this->view_mode === 'project' && $this->filter_project_id) {
            $query->where('project_id', $this->filter_project_id);
        }

        if ($this->filter_unit_id) {
            $selectedUnit = Unit::find($this->filter_unit_id);
            if ($selectedUnit) {
                $query->where(function($q) use ($selectedUnit) {
                    $q->where('description', 'like', '%' . $selectedUnit->code . '%');
                });
            }
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(12);
        $projects = Project::orderBy('name')->get();
        $availableUnits = $this->filter_project_id ? Unit::where('project_id', $this->filter_project_id)->get() : Unit::all();

        // Global Statistics
        $globalMasuk = CashflowTransaction::where('type', 'masuk')->sum('amount');
        $globalKeluar = CashflowTransaction::where('type', 'keluar')->sum('amount');
        $globalNet = $globalMasuk - $globalKeluar;

        // Current Filter Statistics
        $filteredMasuk = (clone $query)->where('type', 'masuk')->sum('amount');
        $filteredKeluar = (clone $query)->where('type', 'keluar')->sum('amount');
        $filteredNet = $filteredMasuk - $filteredKeluar;

        // Breakdown per project
        $projectBreakdown = Project::withCount('units')
            ->get()
            ->map(function ($p) {
                $masuk = CashflowTransaction::where('project_id', $p->id)->where('type', 'masuk')->sum('amount');
                $keluar = CashflowTransaction::where('project_id', $p->id)->where('type', 'keluar')->sum('amount');
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'masuk' => $masuk,
                    'keluar' => $keluar,
                    'net' => $masuk - $keluar,
                ];
            });

        return view('livewire.cashflow.index', [
            'transactions' => $transactions,
            'projects' => $projects,
            'availableUnits' => $availableUnits,
            'totalMasuk' => $filteredMasuk,
            'totalKeluar' => $filteredKeluar,
            'netCashflow' => $filteredNet,
            'globalMasuk' => $globalMasuk,
            'globalKeluar' => $globalKeluar,
            'globalNet' => $globalNet,
            'projectBreakdown' => $projectBreakdown,
        ])->layout('components.layouts.app', ['title' => 'Arus Kas Per-Proyek & Konsolidasi Global']);
    }
}
