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
    public $filter_month = ''; // format YYYY-MM
    public $view_mode = 'global'; // 'global', 'project', atau 'unit'
    public $showManualModal = false;

    protected $queryString = [
        'view_mode' => ['except' => 'global'],
        'filter_project_id' => ['except' => ''],
        'filter_unit_id' => ['except' => ''],
        'filter_month' => ['except' => ''],
    ];

    // Manual Transaction Form
    public $project_id = '';
    public $type = 'masuk';
    public $category = 'operasional';
    public $amount = 0;
    public $transaction_date = '';
    public $description = '';

    public function boot()
    {
        if (empty($this->filter_month)) {
            $this->filter_month = date('Y-m');
        }
    }

    public function mount()
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Akses ditolak. Modul Laporan Arus Kas & Global bersifat rahasia (Hanya untuk Founder dan Finance).');
            return redirect()->route('dashboard');
        }

        $this->filter_month = date('Y-m');
        $this->transaction_date = date('Y-m-d');
        if (Project::count() > 0) {
            $this->project_id = Project::first()->id;
        }
    }

    public function updatedFilterProjectId()
    {
        $this->filter_unit_id = '';
        $this->resetPage();
    }

    public function updatedFilterUnitId()
    {
        $this->resetPage();
    }

    public function updatedFilterMonth()
    {
        $this->resetPage();
    }

    public function updatedViewMode()
    {
        $this->resetPage();
    }

    public function setAllTime()
    {
        $this->filter_month = '';
        $this->resetPage();
    }

    public function setCurrentMonth()
    {
        $this->filter_month = date('Y-m');
        $this->resetPage();
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

        if ($this->view_mode === 'unit' || $this->filter_unit_id) {
            if ($this->filter_unit_id) {
                $selectedUnit = Unit::find($this->filter_unit_id);
                if ($selectedUnit) {
                    $query->where(function ($q) use ($selectedUnit) {
                        $q->where('description', 'like', '%' . $selectedUnit->code . '%');
                    });
                }
            }
        }

        if ($this->filter_month) {
            $parts = explode('-', $this->filter_month);
            if (count($parts) === 2) {
                $query->whereYear('transaction_date', $parts[0])
                      ->whereMonth('transaction_date', $parts[1]);
            }
        }

        $transactions = (clone $query)->latest('transaction_date')->latest('id')->paginate(12);
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
        $monthFilter = $this->filter_month;
        $projectBreakdown = Project::withCount('units')
            ->get()
            ->map(function ($p) use ($monthFilter) {
                $masukQ = CashflowTransaction::where('project_id', $p->id)->where('type', 'masuk');
                $keluarQ = CashflowTransaction::where('project_id', $p->id)->where('type', 'keluar');

                if ($monthFilter) {
                    $parts = explode('-', $monthFilter);
                    if (count($parts) === 2) {
                        $masukQ->whereYear('transaction_date', $parts[0])->whereMonth('transaction_date', $parts[1]);
                        $keluarQ->whereYear('transaction_date', $parts[0])->whereMonth('transaction_date', $parts[1]);
                    }
                }

                $masuk = $masukQ->sum('amount');
                $keluar = $keluarQ->sum('amount');
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'masuk' => $masuk,
                    'keluar' => $keluar,
                    'net' => $masuk - $keluar,
                ];
            });

        // 1. Historical Trend Data (6 Months Trend)
        $trendMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $trendMonths->push(now()->subMonths($i)->format('Y-m'));
        }

        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];

        foreach ($trendMonths as $m) {
            $parts = explode('-', $m);
            $year = $parts[0];
            $monthNum = $parts[1];

            $qM = CashflowTransaction::query();
            if ($this->view_mode === 'project' && $this->filter_project_id) {
                $qM->where('project_id', $this->filter_project_id);
            }
            $qM->whereYear('transaction_date', $year)->whereMonth('transaction_date', $monthNum);

            $mMasuk = (clone $qM)->where('type', 'masuk')->sum('amount');
            $mKeluar = (clone $qM)->where('type', 'keluar')->sum('amount');

            $chartLabels[] = \Carbon\Carbon::createFromFormat('Y-m', $m)->translatedFormat('M Y');
            $chartMasuk[] = (float)$mMasuk;
            $chartKeluar[] = (float)$mKeluar;
        }

        // 2. Category Breakdown Kas Masuk (Sales & Income Breakdown)
        $masukCategories = (clone $query)->where('type', 'masuk')
            ->selectRaw('category, sum(amount) as total_amount')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        // 3. Category Breakdown Kas Keluar (Costs & Expenses Breakdown)
        $keluarCategories = (clone $query)->where('type', 'keluar')
            ->selectRaw('category, sum(amount) as total_amount')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

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
            'chartLabels' => $chartLabels,
            'chartMasuk' => $chartMasuk,
            'chartKeluar' => $chartKeluar,
            'masukCategories' => $masukCategories,
            'keluarCategories' => $keluarCategories,
            'view_mode' => $this->view_mode,
            'filter_project_id' => $this->filter_project_id,
            'filter_unit_id' => $this->filter_unit_id,
            'filter_month' => $this->filter_month,
            'showManualModal' => $this->showManualModal,
        ])->layout('components.layouts.app', ['title' => 'Arus Kas Per-Proyek, Per-Unit & Konsolidasi Global']);
    }
}
