<?php

namespace App\Http\Controllers;

use App\Models\DailyActivityReport;
use App\Models\Project;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DailyActivityReportPdfController extends Controller
{
    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        $period = $request->query('period', 'all');
        $dateParam = $request->query('date', now()->toDateString());
        $weekStartParam = $request->query('week_start');
        $weekEndParam = $request->query('week_end');
        $monthParam = $request->query('month', now()->format('Y-m'));
        $userIdParam = $request->query('user_id');
        $projectIdParam = $request->query('project_id');
        $leadStageParam = $request->query('lead_stage');
        $leadSourceParam = $request->query('lead_source');
        $searchParam = $request->query('search');

        $query = DailyActivityReport::with(['user', 'project', 'unit']);

        // Scope marketing staff to their own data if not Founder/Admin/Supervisor
        if (!$user->isAdminOrFounder() && !$user->isSupervisor() && $user->isMarketing()) {
            $query->where('user_id', $user->id);
            $userIdParam = $user->id;
        } elseif ($userIdParam) {
            $query->where('user_id', $userIdParam);
        }

        if ($projectIdParam) {
            $query->where('project_id', $projectIdParam);
        }

        if ($leadStageParam) {
            $query->where('lead_stage', $leadStageParam);
        }

        if ($leadSourceParam) {
            $query->where('lead_source', $leadSourceParam);
        }

        if ($searchParam) {
            $query->where(function ($q) use ($searchParam) {
                $q->where('client_name', 'like', '%' . $searchParam . '%')
                  ->orWhere('client_phone', 'like', '%' . $searchParam . '%')
                  ->orWhere('notes', 'like', '%' . $searchParam . '%');
            });
        }

        // Apply Period Filter (Per Hari, Per Minggu, Per Bulan)
        $periodLabel = 'Semua Periode';
        if ($period === 'day') {
            $query->whereDate('report_date', $dateParam);
            $periodLabel = 'Per Hari: ' . \Carbon\Carbon::parse($dateParam)->locale('id')->isoFormat('dddd, D MMMM YYYY');
        } elseif ($period === 'week') {
            $startDate = $weekStartParam ?: \Carbon\Carbon::parse($dateParam)->startOfWeek()->toDateString();
            $endDate = $weekEndParam ?: \Carbon\Carbon::parse($dateParam)->endOfWeek()->toDateString();
            $query->whereBetween('report_date', [$startDate, $endDate]);
            $periodLabel = 'Per Minggu: ' . \Carbon\Carbon::parse($startDate)->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($endDate)->format('d/m/Y');
        } elseif ($period === 'month') {
            $monthCarbon = \Carbon\Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth();
            $query->whereYear('report_date', $monthCarbon->year)
                  ->whereMonth('report_date', $monthCarbon->month);
            $periodLabel = 'Per Bulan: ' . $monthCarbon->locale('id')->isoFormat('MMMM YYYY');
        } elseif ($request->query('filter_start_date') || $request->query('filter_end_date')) {
            $sDate = $request->query('filter_start_date');
            $eDate = $request->query('filter_end_date');
            if ($sDate && $eDate) {
                $query->whereBetween('report_date', [$sDate, $eDate]);
                $periodLabel = 'Rentang Tanggal: ' . \Carbon\Carbon::parse($sDate)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($eDate)->format('d/m/Y');
            } elseif ($sDate) {
                $query->whereDate('report_date', '>=', $sDate);
                $periodLabel = 'Mulai Tanggal: ' . \Carbon\Carbon::parse($sDate)->format('d/m/Y');
            } elseif ($eDate) {
                $query->whereDate('report_date', '<=', $eDate);
                $periodLabel = 'Hingga Tanggal: ' . \Carbon\Carbon::parse($eDate)->format('d/m/Y');
            }
        }

        $reports = $query->orderBy('report_date', 'asc')->orderBy('id', 'asc')->get();

        if ($reports->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada data laporan aktivitas harian untuk kriteria yang dipilih.');
        }

        // Summary Calculations
        $totalReports = $reports->count();
        $hotDealsCount = $reports->whereIn('lead_stage', ['hot_deal', 'booking', 'cash_lunas'])->count();
        $totalDealVolume = $reports->whereIn('lead_stage', ['booking', 'cash_lunas'])->sum('deal_amount');

        // Marketing User Info & Filter Labels
        $staffUser = $userIdParam ? User::find($userIdParam) : null;
        $staffInfo = $staffUser ? ($staffUser->name . ' (' . ($staffUser->phone_number ?: 'Tanpa HP') . ')') : 'Semua Petugas Marketing';
        $projectInfo = $projectIdParam ? Project::find($projectIdParam)?->name : 'Semua Kawasan Proyek';
        $leadStageLabel = $leadStageParam ? (DailyActivityReport::leadStages()[$leadStageParam] ?? strtoupper($leadStageParam)) : 'Semua Tahap Prospek';
        $leadSourceLabel = $leadSourceParam ? (DailyActivityReport::leadSources()[$leadSourceParam] ?? strtoupper($leadSourceParam)) : 'Semua Sumber Lead';

        $verifyUrl = route('daily-activity-reports.index');
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('daily_activity_reports.pdf_report', [
            'reports' => $reports,
            'totalReports' => $totalReports,
            'hotDealsCount' => $hotDealsCount,
            'totalDealVolume' => $totalDealVolume,
            'periodLabel' => $periodLabel,
            'staffUser' => $staffUser,
            'staffInfo' => $staffInfo,
            'projectInfo' => $projectInfo,
            'leadStageLabel' => $leadStageLabel,
            'leadSourceLabel' => $leadSourceLabel,
            'printedBy' => $user->name,
            'printedAt' => now()->locale('id')->isoFormat('D MMMM YYYY HH:mm'),
            'qrCodeUrl' => $qrCodeUrl,
        ]);

        $pdfFilename = 'DAILY-ACTIVITY-REPORT-' . strtoupper($period) . '-' . date('Ymd-His') . '.pdf';

        return $pdf->stream($pdfFilename);
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
