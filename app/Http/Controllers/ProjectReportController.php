<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProjectReportController extends Controller
{
    /**
     * Export PDF Rekapitulasi Pembayaran Lahan Proyek ke Penjual
     */
    public function exportLandPaymentsPdf(int $projectId)
    {
        $project = Project::with(['creator', 'payments.creator'])->findOrFail($projectId);
        $payments = $project->payments()->with('creator')->latest('payment_date')->latest('id')->get();

        if ($payments->isEmpty()) {
            return redirect()->route('projects.show', $project->id)->with('error', 'Belum ada data pembayaran lahan untuk proyek ini untuk digenerate PDF.');
        }

        $verifyUrl = route('verify.project-land-payments', $project->id);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $totalPaid = $payments->sum('amount_paid');
        $remaining = max(0, (float)$project->total_project_price - $totalPaid);

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('projects.land_payments_pdf', [
            'project' => $project,
            'payments' => $payments,
            'totalPaid' => $totalPaid,
            'remaining' => $remaining,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'printedBy' => auth()->user()->name,
            'printedAt' => now()->translatedFormat('d F Y H:i'),
        ]);

        $fileName = 'REKAP-PEMBAYARAN-LAHAN-' . str_replace(' ', '-', strtoupper($project->name)) . '.pdf';
        \App\Services\ActivityLogger::log('PDF_EXPORT_LAND_PAYMENTS', "Pengguna " . auth()->user()->name . " mencetak / mengunduh Rekapitulasi Pembayaran Lahan Proyek {$project->name} PDF.");
        return $pdf->stream($fileName);
    }

    /**
     * Export PDF Rekapitulasi Penjualan & Profit Per Unit Proyek
     */
    public function exportSalesProfitPdf(int $projectId)
    {
        $project = Project::findOrFail($projectId);
        $allUnits = Unit::with([
            'proposals',
            'officialDocument',
            'installment',
        ])->where('project_id', $project->id)->get();

        if ($allUnits->isEmpty()) {
            return redirect()->route('projects.show', $project->id)->with('error', 'Belum ada data unit kavling pada proyek ini untuk digenerate PDF.');
        }

        $unitPerformances = [];
        $totalSalesRevenue = 0;
        $totalPaidRevenue = 0;
        $totalOutstandingReceivable = 0;
        $totalHppSold = 0;
        $soldCount = 0;

        foreach ($allUnits as $unit) {
            $hpp = (float)$unit->hpp;
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
                $soldCount++;
            }

            $unitPerformances[] = (object)[
                'code' => $unit->code,
                'category' => $unit->category ?? $unit->type,
                'land_area' => $unit->land_area,
                'status' => $unit->status,
                'buyer_name' => $buyerName,
                'selling_price' => $sellingPrice,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'hpp' => $hpp,
                'profit' => $profit,
                'is_sold' => $isSold,
            ];
        }

        $verifyUrl = route('verify.project-sales-profit', $project->id);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('projects.sales_profit_pdf', [
            'project' => $project,
            'unitPerformances' => $unitPerformances,
            'totalUnits' => count($allUnits),
            'soldCount' => $soldCount,
            'totalSalesRevenue' => $totalSalesRevenue,
            'totalPaidRevenue' => $totalPaidRevenue,
            'totalOutstandingReceivable' => $totalOutstandingReceivable,
            'totalHppSold' => $totalHppSold,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'printedBy' => auth()->user()->name,
            'printedAt' => now()->translatedFormat('d F Y H:i'),
        ]);

        $fileName = 'REKAP-PENJUALAN-PROFIT-' . str_replace(' ', '-', strtoupper($project->name)) . '.pdf';
        \App\Services\ActivityLogger::log('PDF_EXPORT_SALES_PROFIT', "Pengguna " . auth()->user()->name . " mencetak / mengunduh Rekapitulasi Penjualan & Profit Proyek {$project->name} PDF.");
        return $pdf->stream($fileName);
    }

    public function verifyLandPayments(int $projectId)
    {
        $project = Project::with(['payments.creator'])->findOrFail($projectId);
        $payments = $project->payments;

        return view('projects.verify_land_payments_public', [
            'project' => $project,
            'payments' => $payments,
            'totalPaid' => $payments->sum('amount_paid'),
        ]);
    }

    public function verifySalesProfit(int $projectId)
    {
        $project = Project::with('units')->findOrFail($projectId);

        return view('projects.verify_sales_profit_public', [
            'project' => $project,
            'totalUnits' => $project->units->count(),
            'soldUnits' => $project->units->whereIn('status', ['disetujui', 'booked', 'terjual', 'converted'])->count(),
        ]);
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
