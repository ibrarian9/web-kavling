<?php

namespace App\Http\Controllers;

use App\Models\WorkerSalaryPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PayrollReceiptController extends Controller
{
    public function streamReceipt(string $uuid)
    {
        $payment = WorkerSalaryPayment::with([
            'payroll.worker',
            'payroll.project',
            'payroll.unit',
            'creator'
        ])->where('uuid', $uuid)->firstOrFail();

        $payroll = $payment->payroll;
        $worker = $payroll->worker;
        $project = $payroll->project;
        $unit = $payroll->unit;

        $verifyUrl = route('verify.payroll', $payment->uuid);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $terbilang = $this->terbilang((int)$payment->amount_paid) . ' Rupiah';

        // Base64 encoding for receipt photo if present to embed cleanly in Dompdf
        $receiptPhotoBase64 = null;
        if ($payment->receipt_photo_path) {
            $fullPath = storage_path('app/public/' . $payment->receipt_photo_path);
            if (file_exists($fullPath)) {
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                $receiptPhotoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('payroll.receipt_pdf', [
            'payment' => $payment,
            'payroll' => $payroll,
            'worker' => $worker,
            'project' => $project,
            'unit' => $unit,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'terbilang' => ucfirst(trim($terbilang)),
            'receiptPhotoBase64' => $receiptPhotoBase64,
        ]);

        $receiptNo = 'RESI-GAJI-' . substr($payment->uuid, 0, 8);
        return $pdf->stream($receiptNo . '.pdf');
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
