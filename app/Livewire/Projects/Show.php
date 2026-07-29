<?php

namespace App\Livewire\Projects;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\OfficialDocument;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Unit;
use App\Services\ImageCompressor;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public $projectId;
    public string $statusFilter = '';
    public string $typeFilter = '';
    public string $unitSearch = '';
    public string $activeTab = 'units'; // 'units', 'payments', or 'cashflow'

    // Project Payment Modal Form
    public bool $showPaymentModal = false;
    public $payment_amount = 0;
    public $payment_date = '';
    public $payment_method = 'Transfer Bank';
    public $payment_notes = '';
    public $payment_receipt_photo = null;
    public $editingPaymentId = null;

    // Legacy Sale Modal State
    public bool $showLegacyModal = false;
    public string $legacy_code = '';
    public string $legacy_category = 'kavling';
    public string $legacy_type = 'Kavling Standar';
    public float $legacy_land_width = 10.0;
    public float $legacy_land_length = 10.0;
    public float $legacy_land_area = 100.0;
    public ?float $legacy_building_area = null;
    public string $legacy_specifications = '';
    public float $legacy_hpp = 100000000;
    public float $legacy_final_selling_price = 150000000;
    public string $legacy_buyer_name = '';
    public string $legacy_buyer_phone = '';
    public string $legacy_buyer_address = '';
    public string $legacy_sale_date = '';
    public string $legacy_payment_method = 'Tunai / Cash Lunas';
    public bool $legacy_record_cashflow = false;
    public string $legacy_notes = '';

    public function updatedLegacyLandWidth(): void
    {
        $this->calculateLegacyLandArea();
    }

    public function updatedLegacyLandLength(): void
    {
        $this->calculateLegacyLandArea();
    }

    public function calculateLegacyLandArea(): void
    {
        $this->legacy_land_area = (float)($this->legacy_land_width ?? 0) * (float)($this->legacy_land_length ?? 0);
    }

    public function openLegacyModal(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak mencatat penjualan unit masa lalu.');
            return;
        }

        $this->resetValidation();
        $this->reset(['legacy_code', 'legacy_buyer_name', 'legacy_buyer_phone', 'legacy_buyer_address', 'legacy_notes', 'legacy_specifications', 'legacy_building_area']);
        $this->legacy_land_width = 10.0;
        $this->legacy_land_length = 10.0;
        $this->calculateLegacyLandArea();
        $this->legacy_hpp = 100000000;
        $this->legacy_final_selling_price = 150000000;
        $this->legacy_sale_date = now()->subMonths(6)->toDateString();
        $this->legacy_record_cashflow = false;
        $this->showLegacyModal = true;
    }

    public function closeLegacyModal(): void
    {
        $this->showLegacyModal = false;
    }

    public function submitLegacySale(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak mencatat penjualan unit masa lalu.');
            return;
        }

        $this->validate([
            'legacy_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units', 'code')->where('project_id', $this->projectId),
            ],
            'legacy_category' => 'required|in:kavling,rumah',
            'legacy_type' => 'required|string|max:100',
            'legacy_land_width' => 'required|numeric|min:0.1',
            'legacy_land_length' => 'required|numeric|min:0.1',
            'legacy_land_area' => 'required|numeric|min:0.1',
            'legacy_building_area' => 'nullable|numeric|min:0',
            'legacy_specifications' => 'nullable|string|max:1000',
            'legacy_hpp' => 'required|numeric|min:1000',
            'legacy_final_selling_price' => 'required|numeric|min:1000',
            'legacy_buyer_name' => 'required|string|max:255',
            'legacy_buyer_phone' => 'required|string|max:50',
            'legacy_buyer_address' => 'nullable|string|max:500',
            'legacy_sale_date' => 'required|date',
            'legacy_payment_method' => 'required|string',
            'legacy_record_cashflow' => 'boolean',
            'legacy_notes' => 'nullable|string|max:1000',
        ], [
            'legacy_code.required' => 'Kode unit wajib diisi.',
            'legacy_code.unique' => 'Kode unit "' . strtoupper($this->legacy_code) . '" sudah terdaftar pada proyek ini! Kode unit tidak boleh sama.',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            $project = Project::findOrFail($this->projectId);

            // 1. Create Unit with status 'terjual'
            $unit = Unit::create([
                'project_id' => $this->projectId,
                'code' => strtoupper(trim($this->legacy_code)),
                'category' => $this->legacy_category,
                'type' => $this->legacy_type,
                'land_width' => $this->legacy_land_width,
                'land_length' => $this->legacy_land_length,
                'land_area' => $this->legacy_land_area,
                'building_area' => $this->legacy_category === 'rumah' ? $this->legacy_building_area : null,
                'specifications' => $this->legacy_specifications,
                'hpp' => $this->legacy_hpp,
                'final_selling_price' => $this->legacy_final_selling_price,
                'status' => 'terjual',
                'created_by' => $user->id,
            ]);

            // 2. Create Booking (status converted)
            Booking::create([
                'project_id' => $this->projectId,
                'unit_id' => $unit->id,
                'buyer_name' => $this->legacy_buyer_name,
                'buyer_phone' => $this->legacy_buyer_phone,
                'booking_type' => 'unit',
                'booking_amount' => $this->legacy_final_selling_price,
                'dp_amount' => $this->legacy_final_selling_price,
                'booking_date' => $this->legacy_sale_date,
                'status' => 'converted',
                'notes' => 'Pencatatan Penjualan Masa Lalu / Lunas 100% (Sebelum SIM Properti). ' . ($this->legacy_notes ?? ''),
                'created_by' => $user->id,
            ]);

            // 3. Create PriceProposal (status disetujui)
            $margin = (float)$this->legacy_final_selling_price - (float)$this->legacy_hpp;
            $proposal = \App\Models\PriceProposal::create([
                'unit_id' => $unit->id,
                'hpp_price' => $this->legacy_hpp,
                'proposed_price' => $this->legacy_final_selling_price,
                'margin' => $margin,
                'is_below_hpp' => $margin < 0,
                'discount_reason' => $margin < 0 ? 'Penjualan historis di bawah HPP' : null,
                'proposed_by' => $user->id,
                'status' => 'disetujui',
                'notes' => 'Persetujuan otomatis penjualan historis oleh Founder.',
            ]);

            // 4. Create OfficialDocument (SPP)
            $docNumber = 'SPP/HISTORIS/' . strtoupper($project->name) . '/' . date('Y/m', strtotime($this->legacy_sale_date)) . '/' . str_pad($unit->id, 4, '0', STR_PAD_LEFT);
            $officialDoc = OfficialDocument::create([
                'unit_id' => $unit->id,
                'price_proposal_id' => $proposal->id,
                'document_number' => $docNumber,
                'buyer_name' => $this->legacy_buyer_name,
                'buyer_contact' => $this->legacy_buyer_phone,
                'buyer_address' => $this->legacy_buyer_address ?: '-',
                'issued_by' => $user->id,
                'issued_at' => $this->legacy_sale_date,
            ]);

            // 5. Create UnitInstallment & InstallmentPayment (LUNAS 100%)
            $installment = \App\Models\UnitInstallment::create([
                'unit_id' => $unit->id,
                'official_document_id' => $officialDoc->id,
                'total_price' => $this->legacy_final_selling_price,
                'down_payment' => $this->legacy_final_selling_price,
                'installment_count' => 1,
                'installment_amount' => $this->legacy_final_selling_price,
                'start_date' => $this->legacy_sale_date,
                'status' => 'lunas',
            ]);

            \App\Models\InstallmentPayment::create([
                'unit_installment_id' => $installment->id,
                'payment_date' => $this->legacy_sale_date,
                'amount_paid' => $this->legacy_final_selling_price,
                'payment_method' => $this->legacy_payment_method,
                'notes' => 'Pelunasan Historis Masa Lalu (Terjual & Lunas 100%)',
                'created_by' => $user->id,
            ]);

            // 6. Record to Cashflow if checkbox is checked
            if ($this->legacy_record_cashflow) {
                CashflowTransaction::create([
                    'project_id' => $this->projectId,
                    'type' => 'masuk',
                    'category' => 'penjualan_unit',
                    'amount' => $this->legacy_final_selling_price,
                    'transaction_date' => $this->legacy_sale_date,
                    'description' => "Pencatatan Penjualan Masa Lalu (Lunas 100%): {$this->legacy_buyer_name} (Unit Kode {$unit->code})",
                    'reference_type' => Unit::class,
                    'reference_id' => $unit->id,
                    'created_by' => $user->id,
                ]);
            }

            // 7. Record System Log
            \App\Services\ActivityLogger::log(
                'LEGACY_UNIT_CREATED',
                "Founder mencatat unit historis terjual & lunas 100%: Kode Unit {$unit->code} ({$this->legacy_buyer_name} - Rp " . number_format($this->legacy_final_selling_price, 0, ',', '.') . ")"
            );
        });

        session()->flash('success', 'Penjualan unit masa lalu atas nama ' . $this->legacy_buyer_name . ' (Unit ' . $this->legacy_code . ') berhasil dicatat!');
        $this->closeLegacyModal();
    }

    public function mount($id)
    {
        $this->projectId = $id;
        $this->payment_date = date('Y-m-d');

        if (auth()->user() && auth()->user()->isPengawasProject()) {
            $isAssigned = \App\Models\WorkerAssignment::where('user_id', auth()->id())
                ->where('project_id', $id)
                ->where('status', 'active')
                ->exists();

            if (!$isAssigned) {
                abort(403, 'Anda tidak memiliki hak akses pengawasan pada proyek ini.');
            }
        }
    }

    public function openPaymentModal()
    {
        $project = Project::findOrFail($this->projectId);
        $this->editingPaymentId = null;
        $this->payment_amount = $project->remaining_balance > 0 ? $project->remaining_balance : 0;
        $this->payment_date = date('Y-m-d');
        $this->payment_method = 'Transfer Bank';
        $this->payment_notes = '';
        $this->payment_receipt_photo = null;
        $this->showPaymentModal = true;
    }

    public function editProjectPayment($paymentId)
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Accounting yang berhak mengedit pembayaran proyek.');
            return;
        }

        $payment = ProjectPayment::where('project_id', $this->projectId)->findOrFail($paymentId);
        $this->editingPaymentId = $payment->id;
        $this->payment_amount = $payment->amount_paid;
        $this->payment_date = $payment->payment_date ? $payment->payment_date->format('Y-m-d') : date('Y-m-d');
        $this->payment_method = $payment->payment_method;
        $this->payment_notes = $payment->notes ?? '';
        $this->payment_receipt_photo = null;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->editingPaymentId = null;
        $this->payment_receipt_photo = null;
        $this->showPaymentModal = false;
    }

    public function submitProjectPayment()
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance() && !$user->isSupervisor()) {
            session()->flash('error', 'Hanya Founder, Finance, dan Supervisor yang berhak mencatat pembayaran proyek.');
            return;
        }

        $this->validate([
            'payment_amount' => 'required|numeric|min:1000',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'payment_receipt_photo' => 'nullable|image|max:5120',
        ]);

        $project = Project::findOrFail($this->projectId);

        $photoPath = null;
        if ($this->payment_receipt_photo) {
            $photoPath = ImageCompressor::compressAndStore($this->payment_receipt_photo, 'project-payments');
        }

        // EDIT MODE
        if ($this->editingPaymentId) {
            $payment = ProjectPayment::where('project_id', $this->projectId)->findOrFail($this->editingPaymentId);
            $updateData = [
                'payment_date' => $this->payment_date,
                'amount_paid' => $this->payment_amount,
                'payment_method' => $this->payment_method,
                'notes' => $this->payment_notes,
            ];
            if ($photoPath) {
                $updateData['receipt_photo_path'] = $photoPath;
            }
            $payment->update($updateData);

            // Sync related CashflowTransaction
            CashflowTransaction::where('reference_type', ProjectPayment::class)
                ->where('reference_id', $payment->id)
                ->update([
                    'amount' => $this->payment_amount,
                    'transaction_date' => $this->payment_date,
                    'description' => 'Pembayaran Lahan Proyek ' . $project->name . ' ke Penjual Tanah (' . $this->payment_method . ')',
                ]);

            session()->flash('success', 'Data pembayaran lahan berhasil diperbarui! Arus Kas otomatis disesuaikan.');
            $this->closePaymentModal();
            return;
        }

        // CREATE MODE
        $payment = ProjectPayment::create([
            'project_id' => $project->id,
            'payment_date' => $this->payment_date,
            'amount_paid' => $this->payment_amount,
            'payment_method' => $this->payment_method,
            'notes' => $this->payment_notes,
            'receipt_photo_path' => $photoPath,
            'created_by' => auth()->id(),
        ]);

        // Auto record in Cashflow Transactions as Kas Keluar (Pengeluaran Pembelian Lahan)
        CashflowTransaction::create([
            'project_id' => $project->id,
            'type' => 'keluar',
            'category' => 'operasional',
            'amount' => $this->payment_amount,
            'transaction_date' => $this->payment_date,
            'description' => 'Pembayaran Lahan Proyek ' . $project->name . ' ke Penjual Tanah (' . $this->payment_method . ')',
            'reference_type' => ProjectPayment::class,
            'reference_id' => $payment->id,
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'Pembayaran pembelian lahan sebesar Rp ' . number_format($this->payment_amount, 0, ',', '.') . ' ke penjual tanah berhasil dicatat di Arus Kas (Kas Keluar)!');
        $this->closePaymentModal();
    }

    public function deleteProjectPayment($paymentId)
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Finance yang berhak menghapus catatan pembayaran proyek.');
            return;
        }

        $payment = ProjectPayment::where('project_id', $this->projectId)->findOrFail($paymentId);
        
        // Remove related CashflowTransaction if present
        CashflowTransaction::where('reference_type', ProjectPayment::class)
            ->where('reference_id', $payment->id)
            ->delete();

        $payment->delete();

        session()->flash('success', 'Catatan pembayaran lahan proyek berhasil dihapus.');
    }

    public function render()
    {
        $project = Project::with([
            'creator',
            'assignments.worker',
            'units.proposals',
            'units.officialDocument',
        ])->findOrFail($this->projectId);

        // Fetch units for this project
        $unitsQuery = Unit::with([
            'proposals' => function ($q) {
                $q->latest();
            },
            'officialDocument',
            'installment',
        ])->where('project_id', $project->id);

        if ($this->unitSearch) {
            $search = '%' . trim($this->unitSearch) . '%';
            $unitsQuery->where(function ($q) use ($search) {
                $q->where('code', 'like', $search)
                  ->orWhere('category', 'like', $search)
                  ->orWhere('type', 'like', $search)
                  ->orWhereHas('officialDocument', function ($docQ) use ($search) {
                      $docQ->where('buyer_name', 'like', $search);
                  });
            });
        }

        if ($this->statusFilter) {
            $unitsQuery->where('status', $this->statusFilter);
        }

        if ($this->typeFilter) {
            $unitsQuery->where(function ($q) {
                $q->where('category', $this->typeFilter)->orWhere('type', $this->typeFilter);
            });
        }

        $allUnits = Unit::with([
            'proposals',
            'officialDocument',
            'installment',
        ])->where('project_id', $project->id)->get();

        // Calculate Project Financial Metrics
        $totalUnits = $allUnits->count();
        $commercialUnits = $allUnits->filter(fn($u) => $u->category !== 'infrastruktur' && $u->status !== 'infrastruktur');
        $commercialCount = $commercialUnits->count();
        $soldUnits = $commercialUnits->whereIn('status', ['disetujui', 'booked', 'terjual', 'converted'])->count();
        $availableUnits = $commercialUnits->where('status', 'tersedia')->count();
        $pendingUnits = $commercialUnits->where('status', 'menunggu_persetujuan')->count();
        $infraUnitsCount = $allUnits->filter(fn($u) => $u->category === 'infrastruktur' || $u->status === 'infrastruktur')->count();

        // Total Revenue / Sales & Payments
        $totalSalesRevenue = 0;
        $totalPaidRevenue = 0;
        $totalOutstandingReceivable = 0;
        $totalHppSold = 0;
        $unitPerformances = [];

        foreach ($allUnits as $unit) {
            $hpp = (float)$unit->hpp;

            // Determine selling price (Harga Deal) & paid amount
            $sellingPrice = 0;
            $paidAmount = 0;
            $buyerName = '-';
            $isSold = in_array($unit->status, ['disetujui', 'booked', 'terjual', 'converted']);

            if ($unit->installment) {
                $sellingPrice = (float)$unit->installment->total_price;
                $paidAmount = (float)$unit->installment->down_payment + (float)$unit->installment->payments->sum('amount_paid');
            } elseif ($unit->final_selling_price > 0) {
                $sellingPrice = (float)$unit->final_selling_price;
            } elseif ($unit->officialDocument) {
                $sellingPrice = (float)($unit->officialDocument->proposal->proposed_price ?? 0);
            } elseif ($prop = $unit->proposals->where('status', 'disetujui')->first()) {
                $sellingPrice = (float)$prop->proposed_price;
            } elseif ($prop = $unit->proposals->first()) {
                $sellingPrice = (float)$prop->proposed_price;
            }

            // Get buyer name & booking paid amount if no installment
            $booking = Booking::where('unit_id', $unit->id)->latest()->first();
            if ($booking) {
                if ($buyerName === '-') {
                    $buyerName = $booking->buyer_name;
                }
                if (!$unit->installment) {
                    if ($sellingPrice <= 0) {
                        $sellingPrice = (float)($booking->total_price ?? $booking->booking_amount);
                    }
                    $paidAmount = (float)$booking->booking_amount + (float)$booking->dp_amount;
                }
            }

            if ($unit->officialDocument && $buyerName === '-') {
                $buyerName = $unit->officialDocument->buyer_name;
            }

            $remainingAmount = max(0, $sellingPrice - $paidAmount);
            $profit = 0;

            if ($isSold && $sellingPrice > 0) {
                $profit = $sellingPrice - $hpp;
                $totalSalesRevenue += $sellingPrice;
                $totalPaidRevenue += $paidAmount;
                $totalOutstandingReceivable += $remainingAmount;
                $totalHppSold += $hpp;
            }

            $unitPerformances[$unit->id] = [
                'unit' => $unit,
                'selling_price' => $sellingPrice,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'hpp' => $hpp,
                'unit_costs' => 0,
                'profit' => $profit,
                'buyer_name' => $buyerName,
                'is_sold' => $isSold,
            ];
        }

        // Project-wide material, salary & land purchase expenses
        $materialExpenses = \App\Models\WeeklyMaterialPurchase::where('project_id', $project->id)->sum('total_price');
        $salaryExpenses = \App\Models\WorkerSalaryPayment::whereHas('payroll', function ($q) use ($project) {
            $q->where('project_id', $project->id);
        })->sum('amount_paid');
        $landPaidExpenses = (float) $project->total_paid;
        $totalProjectExpenses = $materialExpenses + $salaryExpenses + $landPaidExpenses;

        // Overall Project Net Profit = Total Revenue - (Total HPP Sold + Project Expenses)
        $totalProjectProfit = $totalSalesRevenue - ($totalHppSold + $totalProjectExpenses);

        $occupancyRate = $commercialCount > 0 ? round(($soldUnits / $commercialCount) * 100, 1) : 0;

        $unitsList = $unitsQuery->get();

        // Project Cashflow Transactions
        $cashflowTransactions = CashflowTransaction::with('creator')
            ->where('project_id', $project->id)
            ->latest('transaction_date')
            ->latest('id')
            ->get();

        $cashflowMasuk = $cashflowTransactions->where('type', 'masuk')->sum('amount');
        $cashflowKeluar = $cashflowTransactions->where('type', 'keluar')->sum('amount');
        $cashflowNet = $cashflowMasuk - $cashflowKeluar;

        // Project Payments List
        $projectPaymentsList = $project->payments()
            ->with('creator')
            ->latest('payment_date')
            ->latest('id')
            ->get();

        $fullyPaidUnitsCount = 0;
        $installmentUnitsCount = 0;

        foreach ($unitPerformances as $perf) {
            if ($perf['is_sold']) {
                if ($perf['remaining_amount'] <= 0) {
                    $fullyPaidUnitsCount++;
                } else {
                    $installmentUnitsCount++;
                }
            }
        }

        return view('livewire.projects.show', [
            'project' => $project,
            'activeTab' => $this->activeTab,
            'unitSearch' => $this->unitSearch,
            'statusFilter' => $this->statusFilter,
            'typeFilter' => $this->typeFilter,
            'unitsList' => $unitsList,
            'unitPerformances' => $unitPerformances,
            'totalUnits' => $totalUnits,
            'soldUnits' => $soldUnits,
            'availableUnits' => $availableUnits,
            'pendingUnits' => $pendingUnits,
            'infraUnitsCount' => $infraUnitsCount,
            'fullyPaidUnitsCount' => $fullyPaidUnitsCount,
            'installmentUnitsCount' => $installmentUnitsCount,
            'totalSalesRevenue' => $totalSalesRevenue,
            'totalPaidRevenue' => $totalPaidRevenue,
            'totalOutstandingReceivable' => $totalOutstandingReceivable,
            'totalProjectExpenses' => $totalProjectExpenses,
            'totalProjectProfit' => $totalProjectProfit,
            'occupancyRate' => $occupancyRate,
            'cashflowTransactions' => $cashflowTransactions,
            'cashflowMasuk' => $cashflowMasuk,
            'cashflowKeluar' => $cashflowKeluar,
            'cashflowNet' => $cashflowNet,
            'projectPaymentsList' => $projectPaymentsList,
            'showPaymentModal' => $this->showPaymentModal,
            'showLegacyModal' => $this->showLegacyModal,
            'editingPaymentId' => $this->editingPaymentId,
        ])->layout('components.layouts.app', ['title' => 'Dashboard Detail Proyek - ' . $project->name]);
    }
}
