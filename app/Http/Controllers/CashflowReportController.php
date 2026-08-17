<?php

namespace App\Http\Controllers;

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CashflowReportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses mengunduh laporan arus kas.');
        }

        $viewMode = $request->query('view_mode', 'global');
        $projectId = $request->query('project_id');
        $unitId = $request->query('unit_id');
        $month = $request->query('month'); // format YYYY-MM

        $query = CashflowTransaction::with(['project', 'creator']);

        $project = null;
        $unit = null;

        if ($viewMode === 'project' && $projectId) {
            if ($projectId === 'non_project') {
                $query->whereNull('project_id');
            } else {
                $query->where('project_id', $projectId);
                $project = Project::find($projectId);
            }
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

        $transactions = (clone $query)->latest('transaction_date')->latest('id')->get();
        $totalMasuk = (clone $query)->where('type', 'masuk')->sum('amount');
        $totalKeluar = (clone $query)->where('type', 'keluar')->sum('amount');
        $netCashflow = $totalMasuk - $totalKeluar;

        $verifyUrl = route('verify.cashflow', array_filter([
            'view_mode' => $viewMode,
            'project_id' => $projectId,
            'unit_id' => $unitId,
            'month' => $month,
        ]));
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('cashflow.report_pdf', [
            'transactions' => $transactions,
            'viewMode' => $viewMode,
            'project' => $project,
            'unit' => $unit,
            'month' => $month,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'netCashflow' => $netCashflow,
            'printedBy' => $user->name,
            'printedAt' => now()->translatedFormat('d F Y H:i'),
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
        ]);

        $fileName = 'Laporan-Arus-Kas-' . ($month ?: date('Y-m')) . '.pdf';
        return $pdf->stream($fileName);
    }

    private function generateQrBase64(string $url): string
    {
        try {
            $remoteUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($url);
            $pngContent = @file_get_contents($remoteUrl);
            if ($pngContent) {
                return 'data:image/png;base64,' . base64_encode($pngContent);
            }
        } catch (\Throwable $e) {
            // Fallback to local SVG
        }

        $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->margin(1)->generate($url);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses mengunduh laporan arus kas.');
        }

        $viewMode = $request->query('view_mode', 'global');
        $projectId = $request->query('project_id');
        $unitId = $request->query('unit_id');
        $month = $request->query('month');

        $query = CashflowTransaction::with(['project', 'creator']);

        if ($viewMode === 'project' && $projectId) {
            $query->where('project_id', $projectId);
        }

        if ($unitId) {
            $unit = Unit::find($unitId);
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

        $transactions = $query->latest('transaction_date')->latest('id')->get();
        $fileName = 'Laporan-Arus-Kas-' . ($month ?: date('Y-m')) . '.csv';

        $response = new StreamedResponse(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($handle, [
                'No',
                'Tanggal Mutasi',
                'Nama Proyek',
                'Tipe Transaksi',
                'Kategori',
                'Keterangan / Deskripsi',
                'Nominal (Rp)',
                'Petugas Pencatat',
            ]);

            $no = 1;
            foreach ($transactions as $trx) {
                fputcsv($handle, [
                    $no++,
                    $trx->transaction_date ? $trx->transaction_date->format('d/m/Y') : '-',
                    $trx->project->name ?? 'Global',
                    $trx->type === 'masuk' ? 'Pemasukan (Masuk)' : 'Pengeluaran (Keluar)',
                    str_replace('_', ' ', ucfirst($trx->category)),
                    $trx->description,
                    ($trx->type === 'masuk' ? '' : '-') . (float)$trx->amount,
                    $trx->creator->name ?? 'System',
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}
