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
                    'id' => $sp->id,
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
                    'id' => $mp->id,
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
            'showEditModal' => $this->showEditModal,
        ])->layout('components.layouts.app', ['title' => 'Laporan Belanja & Gaji Worker']);
    }

    // Modal Edit Transaksi Operasional State
    public bool $showEditModal = false;
    public string $editingType = ''; // 'material' or 'salary'
    public ?int $editingId = null;

    // Fields for Material Edit
    public string $edit_item_name = '';
    public $edit_quantity = 1;
    public string $edit_unit_measure = 'pcs';
    public $edit_unit_price = 0;
    public string $edit_purchase_date = '';
    public string $edit_notes = '';

    // Fields for Salary Edit
    public string $edit_payment_date = '';
    public $edit_amount_gross = 0;
    public string $edit_payment_method = 'transfer_bank';

    public function openEditModal(string $type, int $id): void
    {
        $this->resetValidation();
        $this->editingType = $type;
        $this->editingId = $id;

        if ($type === 'material') {
            $mat = WeeklyMaterialPurchase::findOrFail($id);
            $this->edit_item_name = $mat->item_name;
            $this->edit_quantity = $mat->quantity;
            $this->edit_unit_measure = $mat->unit_measure;
            $this->edit_unit_price = $mat->unit_price;
            $this->edit_purchase_date = $mat->purchase_date ? $mat->purchase_date->format('Y-m-d') : date('Y-m-d');
            $this->edit_notes = $mat->notes ?? '';
        } elseif ($type === 'salary') {
            $sp = WorkerSalaryPayment::findOrFail($id);
            $this->edit_payment_date = $sp->payment_date ? $sp->payment_date->format('Y-m-d') : date('Y-m-d');
            $this->edit_amount_gross = $sp->amount_gross;
            $this->edit_payment_method = $sp->payment_method ?? 'transfer_bank';
            $this->edit_notes = $sp->notes ?? '';
        }

        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingType = '';
        $this->editingId = null;
    }

    public function saveEdit(): void
    {
        if (!$this->editingId) return;

        if ($this->editingType === 'material') {
            $this->validate([
                'edit_purchase_date' => 'required|date',
                'edit_item_name' => 'required|string|max:255',
                'edit_quantity' => 'required|numeric|min:0.01',
                'edit_unit_measure' => 'required|string|max:50',
                'edit_unit_price' => 'required|numeric|min:0',
                'edit_notes' => 'nullable|string',
            ]);

            $mat = WeeklyMaterialPurchase::with('unit')->findOrFail($this->editingId);
            $totalPrice = (float)$this->edit_quantity * (float)$this->edit_unit_price;

            \Illuminate\Support\Facades\DB::transaction(function () use ($mat, $totalPrice) {
                $mat->update([
                    'item_name' => $this->edit_item_name,
                    'quantity' => $this->edit_quantity,
                    'unit_measure' => $this->edit_unit_measure,
                    'unit_price' => $this->edit_unit_price,
                    'total_price' => $totalPrice,
                    'purchase_date' => $this->edit_purchase_date,
                    'notes' => $this->edit_notes,
                ]);

                \App\Models\CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
                    ->where('reference_id', $mat->id)
                    ->update([
                        'amount' => $totalPrice,
                        'transaction_date' => $this->edit_purchase_date,
                        'description' => "Pembelian Material Unit " . ($mat->unit->code ?? 'General') . ": {$this->edit_item_name} ({$this->edit_quantity} {$this->edit_unit_measure})",
                    ]);
            });

            session()->flash('success', 'Data belanja material berhasil diperbarui!');
        } elseif ($this->editingType === 'salary') {
            $this->validate([
                'edit_payment_date' => 'required|date',
                'edit_amount_gross' => 'required|numeric|min:1000',
                'edit_payment_method' => 'required|string',
                'edit_notes' => 'nullable|string',
            ]);

            $sp = WorkerSalaryPayment::with(['payroll.worker', 'payroll.unit'])->findOrFail($this->editingId);
            $amountGross = (float)$this->edit_amount_gross;

            \Illuminate\Support\Facades\DB::transaction(function () use ($sp, $amountGross) {
                $oldGross = (float)$sp->amount_gross;
                $sp->update([
                    'payment_date' => $this->edit_payment_date,
                    'amount_gross' => $amountGross,
                    'amount_paid' => $amountGross,
                    'payment_method' => $this->edit_payment_method,
                    'notes' => $this->edit_notes,
                ]);

                if ($sp->payroll) {
                    $newPaidTotal = (float)$sp->payroll->paid_amount - $oldGross + $amountGross;
                    $status = $newPaidTotal >= (float)$sp->payroll->agreed_salary ? 'lunas' : 'berjalan';
                    $sp->payroll->update([
                        'paid_amount' => $newPaidTotal,
                        'status' => $status,
                    ]);
                }

                \App\Models\CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                    ->where('reference_id', $sp->id)
                    ->update([
                        'amount' => $amountGross,
                        'transaction_date' => $this->edit_payment_date,
                        'description' => "Gaji Worker: " . ($sp->payroll->worker->name ?? 'Worker') . " (Unit " . ($sp->payroll->unit->code ?? 'General') . ") - Rp " . number_format($amountGross, 0, ',', '.'),
                    ]);
            });

            session()->flash('success', 'Data pembayaran gaji worker berhasil diperbarui!');
        }

        $this->closeEditModal();
    }

    public function deleteExpense(string $type, int $id): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance() && !$user->isSupervisor() && !$user->isPengawasProject())) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        if ($type === 'material') {
            $mat = WeeklyMaterialPurchase::find($id);
            if ($mat) {
                \Illuminate\Support\Facades\DB::transaction(function () use ($mat) {
                    \App\Models\CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
                        ->where('reference_id', $mat->id)
                        ->delete();
                    $mat->delete();
                });
                session()->flash('success', 'Data belanja material berhasil dihapus!');
            }
        } elseif ($type === 'salary') {
            $sp = WorkerSalaryPayment::with('payroll')->find($id);
            if ($sp) {
                $payroll = $sp->payroll;
                \Illuminate\Support\Facades\DB::transaction(function () use ($sp, $payroll) {
                    \App\Models\CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                        ->where('reference_id', $sp->id)
                        ->delete();

                    $oldGross = (float)$sp->amount_gross;
                    $sp->delete();

                    if ($payroll) {
                        $newPaidTotal = max(0, (float)$payroll->paid_amount - $oldGross);
                        $status = $newPaidTotal >= (float)$payroll->agreed_salary ? 'lunas' : 'berjalan';
                        $payroll->update([
                            'paid_amount' => $newPaidTotal,
                            'status' => $status,
                        ]);
                    }
                });
                session()->flash('success', 'Data pembayaran gaji worker berhasil dihapus!');
            }
        }
    }
}
