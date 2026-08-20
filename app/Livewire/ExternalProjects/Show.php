<?php

namespace App\Livewire\ExternalProjects;

use App\Livewire\Traits\WithMediaViewer;
use App\Models\ExternalProject;
use App\Models\ExternalProjectMaterial;
use App\Models\ExternalProjectWorkerWage;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination, WithFileUploads, WithMediaViewer;

    public int $projectId;
    public string $activeTab = 'materials'; // 'materials' or 'wages'

    // Material filter & multi-row modal
    public string $materialSearch = '';
    public bool $showMaterialModal = false;
    public ?int $editingMaterialId = null;
    public string $material_supplier_global = '';
    public ?string $material_purchase_date = null;
    public $material_receipt_photo = null;
    public ?string $existingMaterialReceipt = null;
    public array $material_rows = [];

    // Wage filter & modal
    public string $wageSearch = '';
    public bool $showWageModal = false;
    public ?int $editingWageId = null;
    public string $wage_worker_name = '';
    public string $wage_role_type = 'tukang';
    public string $wage_type = 'harian';
    public $wage_amount = 0;
    public ?string $wage_payment_date = null;
    public $wage_receipt_photo = null;
    public ?string $existingWageReceipt = null;
    public string $wage_notes = '';

    protected $queryString = [
        'activeTab' => ['except' => 'materials'],
    ];

    public function mount(int $id): void
    {
        if (!auth()->user() || !auth()->user()->isFounder()) {
            abort(403, 'Akses khusus Founder untuk modul Proyek Luar.');
        }

        $this->projectId = $id;
        ExternalProject::findOrFail($id);
    }

    public function updatingMaterialSearch(): void
    {
        $this->resetPage('materialsPage');
    }

    public function updatingWageSearch(): void
    {
        $this->resetPage('wagesPage');
    }

    // Dynamic Multi-Row Calculation
    public function updatedMaterialRows($value, $key): void
    {
        // Example key: '0.quantity' or '1.unit_price' or '0.total_price'
        $parts = explode('.', $key);
        if (count($parts) >= 2) {
            $index = (int) $parts[0];
            $field = $parts[1];

            if (isset($this->material_rows[$index])) {
                $qty = (float) ($this->material_rows[$index]['quantity'] ?? 0);
                $unitPrice = (float) ($this->material_rows[$index]['unit_price'] ?? 0);

                if ($field === 'quantity' || $field === 'unit_price') {
                    if ($qty > 0 && $unitPrice > 0) {
                        $this->material_rows[$index]['total_price'] = $qty * $unitPrice;
                    }
                } elseif ($field === 'total_price') {
                    $total = (float) ($this->material_rows[$index]['total_price'] ?? 0);
                    if ($qty > 0 && $total > 0 && $unitPrice == 0) {
                        $this->material_rows[$index]['unit_price'] = $total / $qty;
                    }
                }
            }
        }
    }

    public function addMaterialRow(): void
    {
        $this->material_rows[] = [
            'item_name' => '',
            'supplier' => $this->material_supplier_global,
            'quantity' => 1,
            'unit' => 'pcs',
            'unit_price' => 0,
            'total_price' => 0,
            'notes' => '',
        ];
    }

    public function removeMaterialRow(int $index): void
    {
        if (count($this->material_rows) > 1) {
            unset($this->material_rows[$index]);
            $this->material_rows = array_values($this->material_rows);
        }
    }

    // Modal Material Open
    public function openMaterialModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->editingMaterialId = $id;
        $this->material_receipt_photo = null;

        if ($id) {
            $mat = ExternalProjectMaterial::where('external_project_id', $this->projectId)->findOrFail($id);
            $this->material_supplier_global = $mat->supplier ?? '';
            $this->material_purchase_date = $mat->purchase_date ? $mat->purchase_date->format('Y-m-d') : null;
            $this->existingMaterialReceipt = $mat->receipt_photo;
            $this->material_rows = [
                [
                    'item_name' => $mat->item_name,
                    'supplier' => $mat->supplier ?? '',
                    'quantity' => (float) $mat->quantity,
                    'unit' => $mat->unit,
                    'unit_price' => (float) $mat->unit_price,
                    'total_price' => (float) $mat->total_price,
                    'notes' => $mat->notes ?? '',
                ]
            ];
        } else {
            $this->material_supplier_global = '';
            $this->material_purchase_date = now()->format('Y-m-d');
            $this->existingMaterialReceipt = null;
            $this->material_rows = [
                [
                    'item_name' => '',
                    'supplier' => '',
                    'quantity' => 1,
                    'unit' => 'pcs',
                    'unit_price' => 0,
                    'total_price' => 0,
                    'notes' => '',
                ]
            ];
        }

        $this->showMaterialModal = true;
    }

    public function saveMaterial(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isFounder()) {
            abort(403, 'Akses khusus Founder.');
        }

        $this->validate([
            'material_purchase_date' => 'required|date',
            'material_supplier_global' => 'nullable|string|max:255',
            'material_receipt_photo' => 'nullable|image|max:10240',
            'material_rows' => 'required|array|min:1',
            'material_rows.*.item_name' => 'required|string|max:255',
            'material_rows.*.quantity' => 'required|numeric|min:0.01',
            'material_rows.*.unit' => 'required|string|max:50',
            'material_rows.*.unit_price' => 'nullable|numeric|min:0',
            'material_rows.*.total_price' => 'required|numeric|min:0',
            'material_rows.*.supplier' => 'nullable|string|max:255',
            'material_rows.*.notes' => 'nullable|string',
        ], [
            'material_rows.*.item_name.required' => 'Nama barang harus diisi di setiap baris.',
            'material_rows.*.quantity.required' => 'Jumlah Qty harus diisi di setiap baris.',
            'material_rows.*.total_price.required' => 'Total biaya harus diisi di setiap baris.',
        ]);

        $project = ExternalProject::findOrFail($this->projectId);

        $photoPath = $this->existingMaterialReceipt;
        if ($this->material_receipt_photo) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $this->material_receipt_photo->store('external_projects/materials', 'public');
        }

        DB::transaction(function () use ($project, $photoPath, $user) {
            if ($this->editingMaterialId) {
                // Update Single Existing Material
                $row = $this->material_rows[0];
                $mat = ExternalProjectMaterial::where('external_project_id', $project->id)->findOrFail($this->editingMaterialId);
                
                $unitPrice = (float) ($row['unit_price'] ?: 0);
                $totalPrice = (float) $row['total_price'];
                if ($unitPrice == 0 && (float)$row['quantity'] > 0) {
                    $unitPrice = $totalPrice / (float)$row['quantity'];
                }

                $mat->update([
                    'item_name' => $row['item_name'],
                    'supplier' => $row['supplier'] ?: ($this->material_supplier_global ?: null),
                    'quantity' => (float) $row['quantity'],
                    'unit' => $row['unit'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'purchase_date' => $this->material_purchase_date,
                    'receipt_photo' => $photoPath,
                    'notes' => $row['notes'] ?: null,
                ]);

                ActivityLogger::log('EXTERNAL_MATERIAL_UPDATED', "Founder ({$user->name}) memperbarui catatan material {$mat->item_name} pada Proyek Luar: {$project->name}");
            } else {
                // Bulk Insert Multi-Row Materials
                $createdCount = 0;
                foreach ($this->material_rows as $row) {
                    $unitPrice = (float) ($row['unit_price'] ?: 0);
                    $totalPrice = (float) $row['total_price'];
                    if ($unitPrice == 0 && (float)$row['quantity'] > 0) {
                        $unitPrice = $totalPrice / (float)$row['quantity'];
                    }

                    ExternalProjectMaterial::create([
                        'external_project_id' => $project->id,
                        'item_name' => $row['item_name'],
                        'supplier' => $row['supplier'] ?: ($this->material_supplier_global ?: null),
                        'quantity' => (float) $row['quantity'],
                        'unit' => $row['unit'],
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'purchase_date' => $this->material_purchase_date,
                        'receipt_photo' => $photoPath,
                        'notes' => $row['notes'] ?: null,
                        'created_by' => $user->id,
                    ]);
                    $createdCount++;
                }

                ActivityLogger::log('EXTERNAL_MATERIAL_BULK_CREATED', "Founder ({$user->name}) menambahkan {$createdCount} item material pada Proyek Luar: {$project->name}");
            }
        });

        $msg = $this->editingMaterialId 
            ? "Catatan material berhasil diperbarui!" 
            : "Berhasil mencatat " . count($this->material_rows) . " item pembelian material!";

        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showMaterialModal = false;
    }

    public function deleteMaterial(int $id): void
    {
        $user = auth()->user();
        if (!$user || !$user->isFounder()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $mat = ExternalProjectMaterial::where('external_project_id', $this->projectId)->findOrFail($id);
        $itemName = $mat->item_name;

        if ($mat->receipt_photo && Storage::disk('public')->exists($mat->receipt_photo)) {
            // Check if other materials share the same receipt photo
            $otherCount = ExternalProjectMaterial::where('receipt_photo', $mat->receipt_photo)->where('id', '!=', $id)->count();
            if ($otherCount === 0) {
                Storage::disk('public')->delete($mat->receipt_photo);
            }
        }

        $mat->delete();

        ActivityLogger::log('EXTERNAL_MATERIAL_DELETED', "Founder ({$user->name}) menghapus catatan material {$itemName} dari Proyek Luar.");
        $msg = "Catatan material {$itemName} berhasil dihapus!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Dihapus!', 'message' => $msg]);
    }

    // Modal Wage
    public function openWageModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->editingWageId = $id;
        $this->wage_receipt_photo = null;

        if ($id) {
            $wage = ExternalProjectWorkerWage::where('external_project_id', $this->projectId)->findOrFail($id);
            $this->wage_worker_name = $wage->worker_name;
            $this->wage_role_type = $wage->role_type;
            $this->wage_type = $wage->wage_type;
            $this->wage_amount = (float) $wage->amount;
            $this->wage_payment_date = $wage->payment_date ? $wage->payment_date->format('Y-m-d') : null;
            $this->existingWageReceipt = $wage->receipt_photo;
            $this->wage_notes = $wage->notes ?? '';
        } else {
            $this->wage_worker_name = '';
            $this->wage_role_type = 'tukang';
            $this->wage_type = 'harian';
            $this->wage_amount = 0;
            $this->wage_payment_date = now()->format('Y-m-d');
            $this->existingWageReceipt = null;
            $this->wage_notes = '';
        }

        $this->showWageModal = true;
    }

    public function saveWage(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isFounder()) {
            abort(403, 'Akses khusus Founder.');
        }

        $this->validate([
            'wage_worker_name' => 'required|string|max:255',
            'wage_role_type' => 'required|string|max:100',
            'wage_type' => 'required|string|max:50',
            'wage_amount' => 'required|numeric|min:1',
            'wage_payment_date' => 'required|date',
            'wage_receipt_photo' => 'nullable|image|max:10240',
            'wage_notes' => 'nullable|string',
        ]);

        $project = ExternalProject::findOrFail($this->projectId);

        $photoPath = $this->existingWageReceipt;
        if ($this->wage_receipt_photo) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $this->wage_receipt_photo->store('external_projects/wages', 'public');
        }

        $data = [
            'external_project_id' => $project->id,
            'worker_name' => $this->wage_worker_name,
            'role_type' => $this->wage_role_type,
            'wage_type' => $this->wage_type,
            'amount' => (float) $this->wage_amount,
            'payment_date' => $this->wage_payment_date,
            'receipt_photo' => $photoPath,
            'notes' => $this->wage_notes ?: null,
        ];

        if ($this->editingWageId) {
            $wage = ExternalProjectWorkerWage::where('external_project_id', $project->id)->findOrFail($this->editingWageId);
            $wage->update($data);
            ActivityLogger::log('EXTERNAL_WAGE_UPDATED', "Founder ({$user->name}) memperbarui pembayaran upah {$wage->worker_name} pada Proyek Luar: {$project->name}");
            $msg = "Catatan upah {$wage->worker_name} berhasil diperbarui!";
        } else {
            $data['created_by'] = $user->id;
            $wage = ExternalProjectWorkerWage::create($data);
            ActivityLogger::log('EXTERNAL_WAGE_CREATED', "Founder ({$user->name}) mencatat pembayaran upah {$wage->worker_name} (Rp " . number_format($wage->amount, 0, ',', '.') . ") pada Proyek Luar: {$project->name}");
            $msg = "Pembayaran upah {$wage->worker_name} berhasil dicatat!";
        }

        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showWageModal = false;
    }

    public function deleteWage(int $id): void
    {
        $user = auth()->user();
        if (!$user || !$user->isFounder()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $wage = ExternalProjectWorkerWage::where('external_project_id', $this->projectId)->findOrFail($id);
        $workerName = $wage->worker_name;

        if ($wage->receipt_photo && Storage::disk('public')->exists($wage->receipt_photo)) {
            Storage::disk('public')->delete($wage->receipt_photo);
        }

        $wage->delete();

        ActivityLogger::log('EXTERNAL_WAGE_DELETED', "Founder ({$user->name}) menghapus catatan upah {$workerName} dari Proyek Luar.");
        $msg = "Catatan upah {$workerName} berhasil dihapus!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Dihapus!', 'message' => $msg]);
    }

    public function render()
    {
        $project = ExternalProject::with(['creator'])->findOrFail($this->projectId);

        // Materials Query
        $materialsQuery = ExternalProjectMaterial::where('external_project_id', $project->id);
        if ($this->materialSearch) {
            $search = '%' . trim($this->materialSearch) . '%';
            $materialsQuery->where(function ($q) use ($search) {
                $q->where('item_name', 'like', $search)
                    ->orWhere('supplier', 'like', $search)
                    ->orWhere('notes', 'like', $search);
            });
        }
        $materials = $materialsQuery->latest('purchase_date')->latest('id')->paginate(15, ['*'], 'materialsPage');

        // Wages Query
        $wagesQuery = ExternalProjectWorkerWage::where('external_project_id', $project->id);
        if ($this->wageSearch) {
            $search = '%' . trim($this->wageSearch) . '%';
            $wagesQuery->where(function ($q) use ($search) {
                $q->where('worker_name', 'like', $search)
                    ->orWhere('role_type', 'like', $search)
                    ->orWhere('notes', 'like', $search);
            });
        }
        $wages = $wagesQuery->latest('payment_date')->latest('id')->paginate(15, ['*'], 'wagesPage');

        // Totals
        $totalMaterialCost = (float) ExternalProjectMaterial::where('external_project_id', $project->id)->sum('total_price');
        $totalWageCost = (float) ExternalProjectWorkerWage::where('external_project_id', $project->id)->sum('amount');
        $totalExpenses = $totalMaterialCost + $totalWageCost;
        $contractValue = (float) $project->contract_value;
        $marginBalance = $contractValue > 0 ? ($contractValue - $totalExpenses) : null;

        return view('livewire.external-projects.show', [
            'project' => $project,
            'materials' => $materials,
            'wages' => $wages,
            'totalMaterialCost' => $totalMaterialCost,
            'totalWageCost' => $totalWageCost,
            'totalExpenses' => $totalExpenses,
            'contractValue' => $contractValue,
            'marginBalance' => $marginBalance,
        ])->layout('components.layouts.app', ['title' => 'Detail Proyek Luar - ' . $project->name]);
    }
}
