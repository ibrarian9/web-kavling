<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\EmployeeSalary;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\Worker;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // 1. Core KPIs
        $totalProjects = Project::where('status', 'aktif')->count();
        $totalUnits = Unit::count();
        $availableUnits = Unit::where('status', 'tersedia')->count();
        $bookedUnits = Unit::whereIn('status', ['booked', 'booking', 'dibooking', 'menunggu_persetujuan'])->count();
        $soldUnits = Unit::whereIn('status', ['terjual', 'disetujui', 'converted'])->count();

        $pendingProposalsCount = PriceProposal::where('status', 'menunggu')->count();
        $approvedProposalsCount = PriceProposal::where('status', 'disetujui')->count();

        // 2. Financial Metrics
        $totalCashIn = CashflowTransaction::where('type', 'masuk')->sum('amount');
        $totalCashOut = CashflowTransaction::where('type', 'keluar')->sum('amount');
        $netCashflow = $totalCashIn - $totalCashOut;

        $totalBookingAmount = Booking::sum('booking_amount');
        $totalBookingsCount = Booking::count();

        // 3. Worker & Field Metrics
        $activeWorkersCount = Worker::where('status', 'active')->count();

        // 4. Marketing Specific Metrics
        $marketingDailyReportCount = 0;
        $marketingHotDealsCount = 0;
        if ($user->isMarketing()) {
            $marketingDailyReportCount = \App\Models\DailyActivityReport::where('user_id', $user->id)->count();
            $marketingHotDealsCount = \App\Models\DailyActivityReport::where('user_id', $user->id)->whereIn('lead_stage', ['hot_deal', 'booking', 'cash_lunas'])->count();
        } else {
            $marketingDailyReportCount = \App\Models\DailyActivityReport::count();
            $marketingHotDealsCount = \App\Models\DailyActivityReport::whereIn('lead_stage', ['hot_deal', 'booking', 'cash_lunas'])->count();
        }

        // 5. Recent Data Feeds
        $recentProposals = PriceProposal::with(['unit.project', 'proposer', 'approvals.approver'])
            ->latest()
            ->take(5)
            ->get();

        $recentUnits = Unit::with('project')
            ->latest()
            ->take(5)
            ->get();

        // 6. Monthly Cashflow Trend Chart Data (Last 6 Months) optimized in 1 batch query
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth()->toDateString();
        $monthlySums = CashflowTransaction::where('transaction_date', '>=', $sixMonthsAgo)
            ->selectRaw('YEAR(transaction_date) as year_num, MONTH(transaction_date) as month_num, type, SUM(amount) as total')
            ->groupBy('year_num', 'month_num', 'type')
            ->get();

        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = (int)$date->month;
            $year = (int)$date->year;

            $chartLabels[] = $date->translatedFormat('M Y');

            $mMasuk = $monthlySums->first(fn($item) => (int)$item->year_num === $year && (int)$item->month_num === $month && $item->type === 'masuk')?->total ?? 0;
            $mKeluar = $monthlySums->first(fn($item) => (int)$item->year_num === $year && (int)$item->month_num === $month && $item->type === 'keluar')?->total ?? 0;

            $chartMasuk[] = (float)$mMasuk;
            $chartKeluar[] = (float)$mKeluar;
        }

        // 7. Employee Salary Info for Non-Founder Users
        $userSalary = null;
        $latestSalaryPayment = null;
        if (!$user->isFounder()) {
            $userSalary = EmployeeSalary::with(['payrollPayments' => fn($q) => $q->latest('payment_date')])
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('employee_name', $user->name);
                })
                ->first();

            if ($userSalary) {
                $latestSalaryPayment = $userSalary->payrollPayments->first();
            }
        }

        return view('livewire.dashboard', [
            'user' => $user,
            'userSalary' => $userSalary,
            'latestSalaryPayment' => $latestSalaryPayment,
            'totalProjects' => $totalProjects,
            'totalUnits' => $totalUnits,
            'availableUnits' => $availableUnits,
            'bookedUnits' => $bookedUnits,
            'soldUnits' => $soldUnits,
            'pendingProposalsCount' => $pendingProposalsCount,
            'approvedProposalsCount' => $approvedProposalsCount,
            'totalCashIn' => $totalCashIn,
            'totalCashOut' => $totalCashOut,
            'netCashflow' => $netCashflow,
            'totalBookingAmount' => $totalBookingAmount,
            'totalBookingsCount' => $totalBookingsCount,
            'activeWorkersCount' => $activeWorkersCount,
            'marketingDailyReportCount' => $marketingDailyReportCount,
            'marketingHotDealsCount' => $marketingHotDealsCount,
            'recentProposals' => $recentProposals,
            'recentUnits' => $recentUnits,
            'chartLabels' => $chartLabels,
            'chartMasuk' => $chartMasuk,
            'chartKeluar' => $chartKeluar,
        ])->layout('components.layouts.app', ['title' => 'Dashboard Executive Overview']);
    }
}

