<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InstallmentInvoiceController extends Controller
{
    /**
     * Stream Official Invoice PDF for Buyer Installment Payment (Khusus Founder & Finance)
     */
    public function streamInvoice(string $uuid)
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance() && !$user->isAdmin())) {
            abort(403, 'Akses ditolak. Hanya Founder, Admin, dan Accounting yang berhak mencetak invoice cicilan.');
        }

        $payment = InstallmentPayment::with([
            'installment.unit.project',
            'installment.officialDocument',
            'installment.payments',
            'creator'
        ])->where('uuid', $uuid)->firstOrFail();

        $installment = $payment->installment;
        $unit = $installment->unit;
        $project = $unit->project;
        $officialDoc = $installment->officialDocument;

        $verifyUrl = route('verify.installment', $payment->uuid);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);
        $terbilang = ucfirst(trim($this->terbilang((int)$payment->amount_paid) . ' Rupiah'));

        $totalPaid = (float)$installment->down_payment + (float)$installment->payments->sum('amount_paid');
        $remainingUnpaid = max(0, (float)$installment->total_price - $totalPaid);

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('installments.invoice_pdf', [
            'payment' => $payment,
            'installment' => $installment,
            'unit' => $unit,
            'project' => $project,
            'officialDoc' => $officialDoc,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'terbilang' => $terbilang,
            'totalPaid' => $totalPaid,
            'remainingUnpaid' => $remainingUnpaid,
        ]);

        $invoiceNo = 'INVOICE-SETORAN-' . substr($payment->uuid, 0, 8);
        return $pdf->stream($invoiceNo . '.pdf');
    }

    /**
     * Public Guest Verification Page scanned via QR Code (Tanpa Login)
     */
    public function verify(string $uuid)
    {
        $payment = InstallmentPayment::with([
            'installment.unit.project',
            'installment.officialDocument',
            'installment.payments',
            'creator'
        ])->where('uuid', $uuid)->firstOrFail();

        $installment = $payment->installment;
        $unit = $installment->unit;
        $project = $unit->project;

        $totalPaid = (float)$installment->down_payment + (float)$installment->payments->sum('amount_paid');
        $remainingUnpaid = max(0, (float)$installment->total_price - $totalPaid);

        return view('installments.verify_public', [
            'payment' => $payment,
            'installment' => $installment,
            'unit' => $unit,
            'project' => $project,
            'totalPaid' => $totalPaid,
            'remainingUnpaid' => $remainingUnpaid,
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
            // Fallback to local SVG
        }

        $svg = QrCode::size(150)->margin(1)->generate($url);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function terbilang($number)
    {
        $number = abs($number);
        $words = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp = "";

        if ($number < 12) {
            $temp = " " . $words[$number];
        } else if ($number < 20) {
            $temp = $this->terbilang($number - 10) . " belas";
        } else if ($number < 100) {
            $temp = $this->terbilang((int)($number / 10)) . " puluh" . $this->terbilang($number % 10);
        } else if ($number < 200) {
            $temp = " seratus" . $this->terbilang($number - 100);
        } else if ($number < 1000) {
            $temp = $this->terbilang((int)($number / 100)) . " ratus" . $this->terbilang($number % 100);
        } else if ($number < 2000) {
            $temp = " seribu" . $this->terbilang($number - 1000);
        } else if ($number < 1000000) {
            $temp = $this->terbilang((int)($number / 1000)) . " ribu" . $this->terbilang($number % 1000);
        } else if ($number < 1000000000) {
            $temp = $this->terbilang((int)($number / 1000000)) . " juta" . $this->terbilang($number % 1000000);
        } else if ($number < 1000000000000) {
            $temp = $this->terbilang((int)($number / 1000000000)) . " milyar" . $this->terbilang(fmod($number, 1000000000));
        }

        return $temp;
    }
}
