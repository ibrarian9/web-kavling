<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Unit;
use App\Models\PriceProposal;
use App\Models\CashflowTransaction;
use App\Models\UnitInstallment;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        $totalProjects = Project::where('status', 'aktif')->count();
        $totalUnits = Unit::count();
        $availableUnits = Unit::where('status', 'tersedia')->count();
        $pendingProposalsCount = PriceProposal::where('status', 'menunggu')->count();
        $approvedProposalsCount = PriceProposal::where('status', 'disetujui')->count();

        $totalCashIn = CashflowTransaction::where('type', 'masuk')->sum('amount');
        $totalCashOut = CashflowTransaction::where('type', 'keluar')->sum('amount');
        $netCashflow = $totalCashIn - $totalCashOut;

        $recentProposals = PriceProposal::with(['unit.project', 'proposer', 'approvals.approver'])
            ->latest()
            ->take(5)
            ->get();

        $recentUnits = Unit::with('project')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'user' => $user,
            'totalProjects' => $totalProjects,
            'totalUnits' => $totalUnits,
            'availableUnits' => $availableUnits,
            'pendingProposalsCount' => $pendingProposalsCount,
            'approvedProposalsCount' => $approvedProposalsCount,
            'totalCashIn' => $totalCashIn,
            'totalCashOut' => $totalCashOut,
            'netCashflow' => $netCashflow,
            'recentProposals' => $recentProposals,
            'recentUnits' => $recentUnits,
        ])->layout('components.layouts.app', ['title' => 'Dashboard Overview']);
    }
}
