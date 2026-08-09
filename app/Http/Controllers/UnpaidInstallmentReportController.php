<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\UnitInstallment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UnpaidInstallmentReportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $projectId = $request->query('project_id');
        $search = $request->query('search');
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        $query = UnitInstallment::with(['unit.project', 'unit.activeBooking', 'unit.bookings', 'officialDocument', 'payments'])
            ->where('status', 'berjalan')
            ->whereDoesntHave('payments', function ($pq) use ($month, $year) {
                $pq->whereMonth('payment_date', $month)
                   ->whereYear('payment_date', $year);
            });

        if ($projectId) {
            $query->whereHas('unit', function ($uq) use ($projectId) {
                $uq->where('project_id', $projectId);
            });
        }

        if ($search) {
            $s = '%' . trim($search) . '%';
            $query->where(function ($q) use ($s) {
                $q->whereHas('unit', function ($uq) use ($s) {
                    $uq->where('code', 'like', $s)
                        ->orWhereHas('project', function ($pq) use ($s) {
                            $pq->where('name', 'like', $s);
                        })
                        ->orWhereHas('bookings', function ($bq) use ($s) {
                            $bq->where('buyer_name', 'like', $s);
                        });
                })->orWhereHas('officialDocument', function ($dq) use ($s) {
                    $dq->where('buyer_name', 'like', $s);
                });
            });
        }

        $installments = $query->latest()->get();

        $totalUnpaidCount = $installments->count();
        $totalUnpaidAmount = $installments->sum(fn($i) => (float)$i->installment_amount);
        $totalRemainingBalance = $installments->sum(fn($i) => (float)$i->remaining_balance);

        $projectInfo = $projectId ? Project::find($projectId)?->name : 'Semua Proyek Properti';
        $periodName = \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY');

        $verifyUrl = route('verify.unpaid-installments');
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('pdf.unpaid_installments_report', [
            'installments' => $installments,
            'totalUnpaidCount' => $totalUnpaidCount,
            'totalUnpaidAmount' => $totalUnpaidAmount,
            'totalRemainingBalance' => $totalRemainingBalance,
            'projectInfo' => $projectInfo,
            'periodName' => $periodName,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'generatedAt' => now()->locale('id')->isoFormat('DD MMMM YYYY HH:mm'),
        ]);

        return $pdf->stream('LAPORAN-TUNGGAKAN-CICILAN-' . strtoupper(\Illuminate\Support\Str::slug($periodName)) . '.pdf');
    }

    public function verify()
    {
        return view('pdf.verify_unpaid_installments_public');
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
