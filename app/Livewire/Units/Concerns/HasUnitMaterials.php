<?php

namespace App\Livewire\Units\Concerns;

use App\Models\CashflowTransaction;
use App\Models\Unit;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Services\ActivityLogger;
use App\Services\ImageCompressor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HasUnitMaterials
{
    // Modal Material Purchase (Catat Belanja Barang Unit) — Bulk Input Support
    public bool $showMaterialModal = false;
    public ?int $material_worker_id = null;
    public string $material_purchase_date = '';
    public string $material_store_name = '';
    public string $material_payment_status = 'lunas';
    public $material_receipt_photo = null;
    public string $material_notes = '';
    public bool $material_is_deducted_from_loan = false;
    public array $materialRows = [];
    public $material_grand_total = 0;
    public ?int $editingMaterialId = null;

    // Legacy single-item properties kept for edit mode compatibility
    public string $material_item_name = '';
    public $material_quantity = 1;
    public string $material_unit_measure = 'pcs';
    public $material_unit_price = 0;
    public $material_total_price = 0;

    protected function emptyMaterialRow(): array
    {
        return ['item_name' => '', 'quantity' => 1, 'unit_measure' => 'pcs', 'unit_price' => 0];
    }

    public function addMaterialRow(): void
    {
        $this->materialRows[] = $this->emptyMaterialRow();
        $this->recalcMaterialGrandTotal();
    }

    public function removeMaterialRow(int $index): void
    {
        if (count($this->materialRows) <= 1) return;
        unset($this->materialRows[$index]);
        $this->materialRows = array_values($this->materialRows);
        $this->recalcMaterialGrandTotal();
    }

    public function updatedMaterialRows(): void
    {
        $this->recalcMaterialGrandTotal();
        if (isset($this->materialRows[0])) {
            $this->material_item_name = $this->materialRows[0]['item_name'] ?? null;
            $this->material_quantity = $this->materialRows[0]['quantity'] ?? null;
            $this->material_unit_measure = $this->materialRows[0]['unit_measure'] ?? null;
            $this->material_unit_price = $this->materialRows[0]['unit_price'] ?? null;
            $qty = is_numeric($this->material_quantity) ? (float)$this->material_quantity : 0;
            $price = is_numeric($this->material_unit_price) ? (float)$this->material_unit_price : 0;
            $this->material_total_price = $qty * $price;
        }
    }

    protected function recalcMaterialGrandTotal(): void
    {
        $total = 0;
        foreach ($this->materialRows as $row) {
            $qty = is_numeric($row['quantity'] ?? 0) ? (float)$row['quantity'] : 0;
            $price = is_numeric($row['unit_price'] ?? 0) ? (float)$row['unit_price'] : 0;
            $total += $qty * $price;
        }
        $this->material_grand_total = $total;
    }

    public function openMaterialModal(): void
    {
        $this->resetValidation();
        $this->editingMaterialId = null;
        $this->reset(['material_worker_id', 'material_item_name', 'material_store_name', 'material_unit_price', 'material_total_price', 'material_receipt_photo', 'material_notes']);
        $this->material_purchase_date = now()->toDateString();
        $this->material_payment_status = 'lunas';
        $this->material_quantity = 1;
        $this->material_unit_measure = 'pcs';
        $this->material_is_deducted_from_loan = false;
        $this->material_worker_id = Worker::where('status', 'active')->first()?->id;
        $this->materialRows = [$this->emptyMaterialRow()];
        $this->material_grand_total = 0;
        $this->showMaterialModal = true;
    }

    public function editMaterialPurchase(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $mat = WeeklyMaterialPurchase::findOrFail($id);
        $this->resetValidation();
        $this->editingMaterialId = $mat->id;
        $this->material_worker_id = $mat->worker_id;
        $this->material_purchase_date = $mat->purchase_date ? $mat->purchase_date->format('Y-m-d') : date('Y-m-d');
        $this->material_item_name = $mat->item_name;
        $this->material_store_name = $mat->store_name ?? '';
        $this->material_payment_status = $mat->payment_status ?? 'lunas';
        $this->material_quantity = $mat->quantity;
        $this->material_unit_measure = $mat->unit_measure;
        $this->material_unit_price = $mat->unit_price;
        $this->material_total_price = $mat->total_price;
        $this->material_receipt_photo = null;
        $this->material_notes = $mat->notes ?? '';
        // Edit mode: populate materialRows with the single existing item
        $this->materialRows = [[
            'item_name' => $mat->item_name,
            'quantity' => $mat->quantity,
            'unit_measure' => $mat->unit_measure,
            'unit_price' => $mat->unit_price,
        ]];
        $this->material_grand_total = (float)$mat->total_price;
        $this->showMaterialModal = true;
    }

    public function deleteMaterialPurchase(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $mat = WeeklyMaterialPurchase::with('unit')->findOrFail($id);
        $itemName = $mat->item_name;
        $totalPrice = (float)$mat->total_price;
        $unitCode = $mat->unit->code ?? $this->unitId;
        $userName = auth()->user()->name ?? 'User';

        DB::transaction(function () use ($mat) {
            CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
                ->where('reference_id', $mat->id)
                ->delete();
            $mat->delete();
        });

        ActivityLogger::log(
            'MATERIAL_PURCHASE_DELETED',
            "Catatan belanja material {$itemName} (Rp " . number_format($totalPrice, 0, ',', '.') . ") pada Unit {$unitCode} dihapus oleh {$userName}."
        );

        session()->flash('success', 'Pencatatan belanja material berhasil dihapus!');
    }

    public function updatedMaterialItemName($value): void
    {
        if (isset($this->materialRows[0])) {
            $this->materialRows[0]['item_name'] = $value;
        }
    }

    public function updatedMaterialQuantity($value = null): void
    {
        $qty = is_numeric($this->material_quantity) ? (float)$this->material_quantity : 0;
        $price = is_numeric($this->material_unit_price) ? (float)$this->material_unit_price : 0;
        $this->material_total_price = $qty * $price;
        if (isset($this->materialRows[0])) {
            $this->materialRows[0]['quantity'] = $qty;
        }
        $this->recalcMaterialGrandTotal();
    }

    public function updatedMaterialUnitPrice($value = null): void
    {
        $qty = is_numeric($this->material_quantity) ? (float)$this->material_quantity : 0;
        $price = is_numeric($this->material_unit_price) ? (float)$this->material_unit_price : 0;
        $this->material_total_price = $qty * $price;
        if (isset($this->materialRows[0])) {
            $this->materialRows[0]['unit_price'] = $price;
        }
        $this->recalcMaterialGrandTotal();
    }

    public function updatedMaterialUnitMeasure($value): void
    {
        if (isset($this->materialRows[0])) {
            $this->materialRows[0]['unit_measure'] = $value;
        }
    }

    public function saveMaterialPurchase(): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        if (empty($this->materialRows)) {
            $this->materialRows = [[
                'item_name' => $this->material_item_name ?? '',
                'quantity' => $this->material_quantity ?? 1,
                'unit_measure' => $this->material_unit_measure ?? 'pcs',
                'unit_price' => $this->material_unit_price ?? 0,
            ]];
        }

        // Shared metadata validation
        $this->validate([
            'material_purchase_date' => 'required|date',
            'material_store_name' => 'nullable|string|max:255',
            'material_payment_status' => 'required|in:lunas,belum_lunas',
            'material_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
            'material_notes' => 'nullable|string',
            'materialRows' => 'required|array|min:1',
            'materialRows.*.item_name' => 'required|string|max:255',
            'materialRows.*.quantity' => 'required|numeric|min:0.01',
            'materialRows.*.unit_measure' => 'required|string|max:50',
            'materialRows.*.unit_price' => 'required|numeric|min:0',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        $photoPath = null;
        if ($this->material_receipt_photo) {
            $photoPath = ImageCompressor::compressAndStore($this->material_receipt_photo, 'material-receipts');
        }

        if ($this->editingMaterialId) {
            // EDIT MODE: Update single existing item from first row
            $row = $this->materialRows[0];
            $totalPrice = (float)$row['quantity'] * (float)$row['unit_price'];
            $mat = WeeklyMaterialPurchase::findOrFail($this->editingMaterialId);
            $finalPhoto = $photoPath ?: $mat->receipt_photo_path;

            DB::transaction(function () use ($unit, $mat, $row, $totalPrice, $finalPhoto) {
                $mat->update([
                    'worker_id' => $this->material_worker_id ?: $mat->worker_id,
                    'purchase_date' => $this->material_purchase_date,
                    'item_name' => $row['item_name'],
                    'store_name' => $this->material_store_name,
                    'quantity' => $row['quantity'],
                    'unit_measure' => $row['unit_measure'],
                    'unit_price' => $row['unit_price'],
                    'total_price' => $totalPrice,
                    'payment_status' => $this->material_payment_status,
                    'receipt_photo_path' => $finalPhoto,
                    'notes' => $this->material_notes,
                ]);

                $cashflow = CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
                    ->where('reference_id', $mat->id)
                    ->first();

                if ($this->material_payment_status === 'lunas') {
                    $storeInfo = $this->material_store_name ? " (Toko: {$this->material_store_name})" : '';
                    if ($cashflow) {
                        $cashflow->update([
                            'amount' => $totalPrice,
                            'transaction_date' => $this->material_purchase_date,
                            'description' => "Pembelian Material Unit {$unit->code}{$storeInfo}: {$row['item_name']} ({$row['quantity']} {$row['unit_measure']})",
                        ]);
                    } else {
                        CashflowTransaction::create([
                            'project_id' => $unit->project_id,
                            'type' => 'keluar',
                            'category' => 'operasional',
                            'amount' => $totalPrice,
                            'transaction_date' => $this->material_purchase_date,
                            'description' => "Pembelian Material Unit {$unit->code}{$storeInfo}: {$row['item_name']} ({$row['quantity']} {$row['unit_measure']})",
                            'reference_type' => WeeklyMaterialPurchase::class,
                            'reference_id' => $mat->id,
                            'created_by' => Auth::id(),
                        ]);
                    }
                } else {
                    if ($cashflow) {
                        $cashflow->delete();
                    }
                }
            });
            session()->flash('success', 'Belanja material unit ' . $unit->code . ' berhasil diperbarui!');
        } else {
            // CREATE MODE: Bulk insert from all materialRows
            $itemCount = count($this->materialRows);
            $grandTotal = 0;

            DB::transaction(function () use ($unit, $photoPath, &$grandTotal) {
                $workerId = $this->material_worker_id ?: Worker::where('status', 'active')->first()?->id;
                $storeInfo = $this->material_store_name ? " (Toko: {$this->material_store_name})" : '';

                foreach ($this->materialRows as $row) {
                    $itemName = trim($row['item_name'] ?? '');
                    if (empty($itemName)) continue;

                    $qty = (float)($row['quantity'] ?? 0);
                    $unitPrice = (float)($row['unit_price'] ?? 0);
                    $totalPrice = $qty * $unitPrice;
                    $grandTotal += $totalPrice;

                    $purchase = WeeklyMaterialPurchase::create([
                        'project_id' => $unit->project_id,
                        'unit_id' => $unit->id,
                        'worker_id' => $workerId,
                        'pengawas_id' => Auth::id(),
                        'purchase_date' => $this->material_purchase_date,
                        'item_name' => $itemName,
                        'store_name' => $this->material_store_name,
                        'quantity' => $qty,
                        'unit_measure' => $row['unit_measure'] ?? 'pcs',
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'payment_status' => $this->material_payment_status,
                        'paid_at' => $this->material_payment_status === 'lunas' ? now() : null,
                        'paid_by' => $this->material_payment_status === 'lunas' ? Auth::id() : null,
                        'receipt_photo_path' => $photoPath,
                        'notes' => $this->material_notes,
                    ]);

                    if ($this->material_payment_status === 'lunas') {
                        CashflowTransaction::create([
                            'project_id' => $unit->project_id,
                            'type' => 'keluar',
                            'category' => 'operasional',
                            'amount' => $totalPrice,
                            'transaction_date' => $this->material_purchase_date,
                            'description' => "Pembelian Material Unit {$unit->code}{$storeInfo}: {$itemName} ({$qty} {$row['unit_measure']})",
                            'reference_type' => WeeklyMaterialPurchase::class,
                            'reference_id' => $purchase->id,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            });

            $itemNames = collect($this->materialRows)->pluck('item_name')->filter()->implode(', ');
            ActivityLogger::log('MATERIAL_PURCHASE_RECORDED', "Pembelian {$itemCount} item material Unit {$unit->code} ({$itemNames}) dicatat sebesar Rp " . number_format($grandTotal, 0, ',', '.') . " (" . strtoupper($this->material_payment_status) . ")");
            session()->flash('success', "{$itemCount} item belanja material unit {$unit->code} berhasil dicatat sekaligus!");
        }

        $this->showMaterialModal = false;
        $this->editingMaterialId = null;
    }
}
