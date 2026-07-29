<?php

namespace App\Http\Controllers;

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;

class CashflowVerificationController extends Controller
{
    /**
     * Public Guest Verification Page for Cashflow PDF Reports
     */
    public function verify(Request $request)
    {
        $viewMode = $request->query('view_mode', 'global');
        $projectId = $request->query('project_id');
        $unitId = $request->query('unit_id');
        $month = $request->query('month');

        $query = CashflowTransaction::with(['project', 'creator']);

        $project = null;
        $unit = null;

        if ($viewMode === 'project' && $projectId) {
            $query->where('project_id', $projectId);
            $project = Project::find($projectId);
        }

        if ($unitId) {
            $unit = Unit::with('project')->find($unitId);
            if ($unit) {
                $query->where(function ($q) use ($unit) {
                    $q->where('description', 'like', '%' . $unit->code . '%');
                });
            }
        }

        if ($month) {
            $parts = explode('-', $month);
            if (count($parts) === 2) {
                $query->whereYear('transaction_date', $parts[0])
                      ->whereMonth('transaction_date', $parts[1]);
            }
        }

        $transactionCount = (clone $query)->count();
        $totalMasuk = (clone $query)->where('type', 'masuk')->sum('amount');
        $totalKeluar = (clone $query)->where('type', 'keluar')->sum('amount');
        $netCashflow = $totalMasuk - $totalKeluar;

        $lastUpdated = (clone $query)->latest('updated_at')->value('updated_at') ?? now();

        return view('cashflow.verify_public', [
            'viewMode' => $viewMode,
            'project' => $project,
            'unit' => $unit,
            'month' => $month,
            'transactionCount' => $transactionCount,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'netCashflow' => $netCashflow,
            'lastUpdated' => $lastUpdated,
        ]);
    }
}
