<?php

namespace App\Livewire\Projects;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\OfficialDocument;
use App\Models\Project;
use App\Models\Unit;
use Livewire\Component;

class Show extends Component
{
    public $projectId;
    public string $statusFilter = '';
    public string $typeFilter = '';
    public string $unitSearch = '';
    public string $activeTab = 'units'; // 'units' or 'cashflow'

    public function mount($id)
    {
        $this->projectId = $id;
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

        // Project-wide material & salary expenses
        $materialExpenses = \App\Models\WeeklyMaterialPurchase::where('project_id', $project->id)->sum('total_price');
        $salaryExpenses = \App\Models\WorkerSalaryPayment::whereHas('payroll', function ($q) use ($project) {
            $q->where('project_id', $project->id);
        })->sum('amount_paid');
        $totalProjectExpenses = $materialExpenses + $salaryExpenses;

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
        ])->layout('components.layouts.app', ['title' => 'Dashboard Detail Proyek - ' . $project->name]);
    }
}
