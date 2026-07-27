<?php

namespace App\Livewire\Projects;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\OfficialDocument;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Unit;
use App\Services\ImageCompressor;
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
        $this->payment_amount = $project->remaining_balance > 0 ? $project->remaining_balance : 0;
        $this->payment_date = date('Y-m-d');
        $this->payment_notes = '';
        $this->payment_receipt_photo = null;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
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
        ])->layout('components.layouts.app', ['title' => 'Dashboard Detail Proyek - ' . $project->name]);
    }
}
