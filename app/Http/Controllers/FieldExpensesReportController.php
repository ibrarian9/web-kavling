<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use App\Models\WeeklyMaterialPurchase;
use App\Models\WorkerSalaryPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FieldExpensesReportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $projectId = $request->query('project_id');
        $unitId = $request->query('unit_id');
        $categoryFilter = $request->query('category_filter', 'all');
        $search = $request->query('search');
        $datePeriod = $request->query('date_period', 'all');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $salaryQuery = WorkerSalaryPayment::with(['payroll.worker', 'payroll.unit', 'payroll.project']);
        if ($projectId) {
            $salaryQuery->whereHas('payroll', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            });
        }
        if ($unitId) {
            $salaryQuery->whereHas('payroll', function ($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            });
        }
        if ($datePeriod !== 'all') {
            $this->applyDateFilter($salaryQuery, 'payment_date', $datePeriod, $startDate, $endDate);
        }
        if ($search) {
            $salaryQuery->whereHas('payroll.worker', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $materialQuery = WeeklyMaterialPurchase::with(['unit', 'project', 'worker', 'pengawas']);
        if ($projectId) {
            $materialQuery->where('project_id', $projectId);
        }
        if ($unitId) {
            $materialQuery->where('unit_id', $unitId);
        }
        if ($datePeriod !== 'all') {
            $this->applyDateFilter($materialQuery, 'purchase_date', $datePeriod, $startDate, $endDate);
        }
        if ($search) {
            $materialQuery->where(function ($q) use ($search) {
                $q->where('item_name', 'like', '%' . $search . '%')
                  ->orWhere('notes', 'like', '%' . $search . '%');
            });
        }

        $combined = collect();

        if ($categoryFilter === 'all' || $categoryFilter === 'salary') {
            foreach ($salaryQuery->latest('payment_date')->latest('id')->get() as $sp) {
                $combined->push((object)[
                    'type' => 'salary',
                    'date' => $sp->payment_date ? $sp->payment_date->format('d/m/Y') : $sp->created_at->format('d/m/Y'),
                    'project_name' => $sp->payroll->project->name ?? '-',
                    'unit_code' => $sp->payroll->unit->code ?? 'General Proyek',
                    'title' => 'Pembayaran Gaji: ' . ($sp->payroll->worker->name ?? 'Worker'),
                    'quantity_label' => '1 Kali Bayar',
                    'amount' => (float)$sp->amount_paid,
                    'timestamp' => $sp->payment_date ? $sp->payment_date->timestamp : $sp->created_at->timestamp,
                ]);
            }
        }

        if ($categoryFilter === 'all' || $categoryFilter === 'material') {
            foreach ($materialQuery->latest('purchase_date')->latest('id')->get() as $mp) {
                $qtyStr = number_format((float)$mp->quantity, ($mp->quantity == floor($mp->quantity) ? 0 : 2), ',', '.') . ' ' . $mp->unit_measure;
                $priceStr = 'Rp ' . number_format((float)$mp->unit_price, 0, ',', '.');
                $combined->push((object)[
                    'type' => 'material',
                    'date' => $mp->purchase_date ? $mp->purchase_date->format('d/m/Y') : $mp->created_at->format('d/m/Y'),
                    'project_name' => $mp->project->name ?? '-',
                    'unit_code' => $mp->unit->code ?? 'General Proyek',
                    'title' => $mp->item_name,
                    'quantity_label' => $qtyStr . ' @ ' . $priceStr,
                    'amount' => (float)$mp->total_price,
                    'timestamp' => $mp->purchase_date ? $mp->purchase_date->timestamp : $mp->created_at->timestamp,
                ]);
            }
        }

        $sortedCombined = $combined->sortByDesc('timestamp')->values();

        if ($sortedCombined->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada data pengeluaran/belanja untuk digenerate PDF.');
        }

        $totalSalary = $sortedCombined->where('type', 'salary')->sum('amount');
        $totalMaterial = $sortedCombined->where('type', 'material')->sum('amount');
        $totalExpenses = $totalSalary + $totalMaterial;

        $projectInfo = $projectId ? Project::find($projectId)?->name : 'Semua Proyek';
        $unitInfo = $unitId ? Unit::find($unitId)?->code : 'Semua Unit';

        $periodInfo = match($datePeriod) {
            'today' => 'Hari Ini (' . now()->format('d/m/Y') . ')',
            'yesterday' => 'Kemarin (' . now()->subDay()->format('d/m/Y') . ')',
            'this_week' => 'Minggu Ini (' . now()->startOfWeek()->format('d/m/Y') . ' - ' . now()->endOfWeek()->format('d/m/Y') . ')',
            'this_month' => 'Bulan Ini (' . now()->locale('id')->isoFormat('MMMM Y') . ')',
            'last_month' => 'Bulan Lalu (' . now()->subMonth()->locale('id')->isoFormat('MMMM Y') . ')',
            'this_year' => 'Tahun Ini (' . now()->year . ')',
            'custom' => ($startDate && $endDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($endDate)->format('d/m/Y') : ($startDate ? 'Sejak ' . \Carbon\Carbon::parse($startDate)->format('d/m/Y') : ($endDate ? 'Sampai ' . \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Rentang Custom'))),
            default => 'Semua Periode Tanggal',
        };

        $verifyUrl = route('verify.field-expenses');
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('field_expenses.pdf_report', [
            'expenses' => $sortedCombined,
            'totalExpenses' => $totalExpenses,
            'totalSalary' => $totalSalary,
            'totalMaterial' => $totalMaterial,
            'projectInfo' => $projectInfo,
            'unitInfo' => $unitInfo,
            'categoryFilter' => $categoryFilter,
            'periodInfo' => $periodInfo,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
        ]);

        return $pdf->stream('LAPORAN-BELANJA-DAN-GAJI-WORKER.pdf');
    }

    private function applyDateFilter($query, string $column, string $period, ?string $start, ?string $end)
    {
        switch ($period) {
            case 'today':
                return $query->whereDate($column, \Carbon\Carbon::today());
            case 'yesterday':
                return $query->whereDate($column, \Carbon\Carbon::yesterday());
            case 'this_week':
                return $query->whereBetween($column, [
                    \Carbon\Carbon::now()->startOfWeek()->toDateString(),
                    \Carbon\Carbon::now()->endOfWeek()->toDateString(),
                ]);
            case 'this_month':
                return $query->whereMonth($column, \Carbon\Carbon::now()->month)
                             ->whereYear($column, \Carbon\Carbon::now()->year);
            case 'last_month':
                $lastMonth = \Carbon\Carbon::now()->subMonth();
                return $query->whereMonth($column, $lastMonth->month)
                             ->whereYear($column, $lastMonth->year);
            case 'this_year':
                return $query->whereYear($column, \Carbon\Carbon::now()->year);
            case 'custom':
                if (!empty($start) && !empty($end)) {
                    return $query->whereBetween($column, [min($start, $end), max($start, $end)]);
                } elseif (!empty($start)) {
                    return $query->whereDate($column, '>=', $start);
                } elseif (!empty($end)) {
                    return $query->whereDate($column, '<=', $end);
                }
                return $query;
            default:
                return $query;
        }
    }

    public function verify()
    {
        return view('field_expenses.verify_public');
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
        }

        $svg = QrCode::size(150)->margin(1)->generate($url);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
