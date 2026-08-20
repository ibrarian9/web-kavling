<?php

namespace App\Http\Controllers;

use App\Models\ExternalProject;
use Barryvdh\DomPDF\Facade\Pdf;

class ExternalProjectReportController extends Controller
{
    public function exportPdf(int $id)
    {
        if (!auth()->user() || !auth()->user()->isFounder()) {
            abort(403, 'Akses khusus Founder untuk melihat laporan proyek luar.');
        }

        $project = ExternalProject::with([
            'creator',
            'materials' => function ($q) {
                $q->orderBy('purchase_date', 'asc');
            },
            'workerWages' => function ($q) {
                $q->orderBy('payment_date', 'asc');
            }
        ])->findOrFail($id);

        $totalMaterialCost = (float) $project->materials->sum('total_price');
        $totalWageCost = (float) $project->workerWages->sum('amount');
        $totalExpenses = $totalMaterialCost + $totalWageCost;
        $contractValue = (float) $project->contract_value;
        $marginBalance = $contractValue > 0 ? ($contractValue - $totalExpenses) : null;

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('external-projects.report_pdf', [
            'project' => $project,
            'totalMaterialCost' => $totalMaterialCost,
            'totalWageCost' => $totalWageCost,
            'totalExpenses' => $totalExpenses,
            'contractValue' => $contractValue,
            'marginBalance' => $marginBalance,
        ]);

        $cleanProjectName = preg_replace('/[^A-Za-z0-9_-]/', '-', $project->name);
        $fileName = 'REKAP-BIAYA-PROYEK-LUAR-' . $cleanProjectName . '.pdf';

        return $pdf->stream($fileName);
    }
}
