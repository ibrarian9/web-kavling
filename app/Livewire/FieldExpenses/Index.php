<?php

namespace App\Livewire\FieldExpenses;

use App\Models\Project;
use App\Models\Unit;
use App\Models\WeeklyMaterialPurchase;
use App\Models\WorkerSalaryPayment;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $project_id = '';
    public $unit_id = '';
    public string $category_filter = 'all'; // 'all', 'salary', 'material'
    public string $search = '';

    // Viewer Modal State
    public bool $showViewerModal = false;
    public string $viewerTitle = '';
    public string $viewerType = ''; // 'image' or 'pdf'
    public string $viewerUrl = '';

    public function openViewer(string $title, string $type, string $url): void
    {
        $this->viewerTitle = $title;
        $this->viewerType = $type;
        $this->viewerUrl = $url;
        $this->showViewerModal = true;
    }

    public function closeViewer(): void
    {
        $this->showViewerModal = false;
        $this->viewerTitle = '';
        $this->viewerType = '';
        $this->viewerUrl = '';
    }

    public function updatedProjectId(): void
    {
        $this->unit_id = '';
        $this->resetPage();
    }

    public function updatedUnitId(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // 1. Fetch Salary Payments
        $salaryQuery = WorkerSalaryPayment::with(['payroll.worker', 'payroll.unit', 'payroll.project']);
        if ($this->project_id) {
            $salaryQuery->whereHas('payroll', function ($q) {
                $q->where('project_id', $this->project_id);
            });
        }
        if ($this->unit_id) {
            $salaryQuery->whereHas('payroll', function ($q) {
                $q->where('unit_id', $this->unit_id);
            });
        }
        if ($this->search) {
            $salaryQuery->whereHas('payroll.worker', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Fetch Material Purchases
        $materialQuery = WeeklyMaterialPurchase::with(['unit', 'project', 'worker', 'pengawas']);
        if ($this->project_id) {
            $materialQuery->where('project_id', $this->project_id);
        }
        if ($this->unit_id) {
            $materialQuery->where('unit_id', $this->unit_id);
        }
        if ($this->search) {
            $materialQuery->where(function ($q) {
                $q->where('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }

        // Calculate Totals
        $totalSalary = (clone $salaryQuery)->sum('amount_paid');
        $totalMaterial = (clone $materialQuery)->sum('total_price');
        $totalExpenses = $totalSalary + $totalMaterial;

        // Build Combined List
        $combined = collect();

        if ($this->category_filter === 'all' || $this->category_filter === 'salary') {
            foreach ($salaryQuery->latest('payment_date')->latest('id')->get() as $sp) {
                $combined->push([
                    'type' => 'salary',
                    'date' => $sp->payment_date ? $sp->payment_date->format('Y-m-d') : $sp->created_at->format('Y-m-d'),
                    'project_name' => $sp->payroll->project->name ?? '-',
                    'unit_code' => $sp->payroll->unit->code ?? 'General Proyek',
                    'title' => 'Pembayaran Gaji Pekerja: ' . ($sp->payroll->worker->name ?? 'Worker'),
                    'quantity_label' => '1 Kali Bayar',
                    'unit_price' => (float)$sp->amount_paid,
                    'total_price' => (float)$sp->amount_paid,
                    'receipt_photo' => $sp->receipt_photo_path ? asset('storage/' . $sp->receipt_photo_path) : null,
                    'pdf_url' => route('payroll.receipt', ['uuid' => $sp->uuid]),
                    'qr_url' => route('verify.payroll', ['uuid' => $sp->uuid]),
                    'timestamp' => $sp->payment_date ? $sp->payment_date->timestamp : $sp->created_at->timestamp,
                ]);
            }
        }

        if ($this->category_filter === 'all' || $this->category_filter === 'material') {
            foreach ($materialQuery->latest('purchase_date')->latest('id')->get() as $mp) {
                $qtyStr = number_format((float)$mp->quantity, ($mp->quantity == floor($mp->quantity) ? 0 : 2), ',', '.') . ' ' . $mp->unit_measure;
                $priceStr = 'Rp ' . number_format((float)$mp->unit_price, 0, ',', '.');
                $combined->push([
                    'type' => 'material',
                    'date' => $mp->purchase_date ? $mp->purchase_date->format('Y-m-d') : $mp->created_at->format('Y-m-d'),
                    'project_name' => $mp->project->name ?? '-',
                    'unit_code' => $mp->unit->code ?? 'General Proyek',
                    'title' => $mp->item_name,
                    'quantity_label' => $qtyStr . ' @ ' . $priceStr,
                    'unit_price' => (float)$mp->unit_price,
                    'total_price' => (float)$mp->total_price,
                    'receipt_photo' => $mp->receipt_photo_path ? asset('storage/' . $mp->receipt_photo_path) : null,
                    'pdf_url' => route('material-purchases.receipt', ['id' => $mp->id]),
                    'qr_url' => route('verify.material-purchase', ['id' => $mp->id]),
                    'timestamp' => $mp->purchase_date ? $mp->purchase_date->timestamp : $mp->created_at->timestamp,
                ]);
            }
        }

        // Sort descending by timestamp
        $sortedCombined = $combined->sortByDesc('timestamp')->values();

        $projects = Project::orderBy('name')->get();
        $availableUnits = $this->project_id ? Unit::where('project_id', $this->project_id)->orderBy('code')->get() : Unit::orderBy('code')->get();

        return view('livewire.field-expenses.index', [
            'expenses' => $sortedCombined,
            'totalExpenses' => $totalExpenses,
            'totalSalary' => $totalSalary,
            'totalMaterial' => $totalMaterial,
            'projects' => $projects,
            'availableUnits' => $availableUnits,
            'showViewerModal' => $this->showViewerModal,
        ])->layout('components.layouts.app', ['title' => 'Laporan Belanja & Gaji Worker']);
    }
}
