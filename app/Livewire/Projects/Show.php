<?php

namespace App\Livewire\Projects;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\OfficialDocument;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitCost;
use Livewire\Component;

class Show extends Component
{
    public $projectId;
    public string $statusFilter = '';
    public string $typeFilter = '';
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
            'units.costs',
            'units.proposals',
            'units.officialDocument',
            'costs',
        ])->findOrFail($this->projectId);

        // Fetch units for this project
        $unitsQuery = Unit::with([
            'proposals' => function ($q) {
                $q->latest();
            },
            'officialDocument',
            'installment',
            'costs',
        ])->where('project_id', $project->id);

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
            'costs',
        ])->where('project_id', $project->id)->get();

        // Calculate Project Financial Metrics
        $totalUnits = $allUnits->count();
        $soldUnits = $allUnits->whereIn('status', ['disetujui', 'booked', 'terjual', 'converted'])->count();
        $availableUnits = $allUnits->where('status', 'tersedia')->count();
        $pendingUnits = $allUnits->where('status', 'menunggu_persetujuan')->count();

        // Total Revenue / Sales
        $totalSalesRevenue = 0;
        $totalHppSold = 0;
        $unitPerformances = [];

        foreach ($allUnits as $unit) {
            $unitCostsSum = $unit->costs->sum('amount');
            $hpp = (float)$unit->hpp;

            // Determine selling price if sold/booked/approved
            $sellingPrice = 0;
            $buyerName = '-';
            $isSold = in_array($unit->status, ['disetujui', 'booked', 'terjual', 'converted']);

            if ($unit->final_selling_price > 0) {
                $sellingPrice = (float)$unit->final_selling_price;
            } elseif ($unit->officialDocument) {
                $sellingPrice = (float)($unit->officialDocument->proposal->proposed_price ?? 0);
            } elseif ($unit->proposals->where('status', 'disetujui')->first()) {
                $sellingPrice = (float)$unit->proposals->where('status', 'disetujui')->first()->proposed_price;
            } elseif ($unit->proposals->first()) {
                $sellingPrice = (float)$unit->proposals->first()->proposed_price;
            }

            // Get buyer name if available
            if ($unit->officialDocument) {
                $buyerName = $unit->officialDocument->buyer_name;
            } else {
                $booking = Booking::where('unit_id', $unit->id)->latest()->first();
                if ($booking) {
                    $buyerName = $booking->buyer_name;
                }
            }

            $profit = 0;
            if ($isSold && $sellingPrice > 0) {
                $profit = $sellingPrice - ($hpp + $unitCostsSum);
                $totalSalesRevenue += $sellingPrice;
                $totalHppSold += $hpp;
            }

            $unitPerformances[$unit->id] = [
                'unit' => $unit,
                'selling_price' => $sellingPrice,
                'hpp' => $hpp,
                'unit_costs' => $unitCostsSum,
                'profit' => $profit,
                'buyer_name' => $buyerName,
                'is_sold' => $isSold,
            ];
        }

        // Project-wide costs (direct project costs + unit costs)
        $projectLevelCosts = UnitCost::where('project_id', $project->id)->sum('amount');
        $cashflowOutflow = CashflowTransaction::where('project_id', $project->id)->where('type', 'keluar')->sum('amount');

        // Total Expenses = Project Direct Costs + Cashflow Outflows
        $totalProjectExpenses = max($projectLevelCosts, $cashflowOutflow);

        // Overall Project Net Profit = Total Revenue - (Total HPP Sold + Project Expenses)
        $totalProjectProfit = $totalSalesRevenue - ($totalHppSold + $totalProjectExpenses);

        $occupancyRate = $totalUnits > 0 ? round(($soldUnits / $totalUnits) * 100, 1) : 0;

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
            'unitsList' => $unitsList,
            'unitPerformances' => $unitPerformances,
            'totalUnits' => $totalUnits,
            'soldUnits' => $soldUnits,
            'availableUnits' => $availableUnits,
            'pendingUnits' => $pendingUnits,
            'totalSalesRevenue' => $totalSalesRevenue,
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
