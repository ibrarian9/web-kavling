<?php

namespace App\Http\Controllers;

use App\Models\ManualInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ManualInvoiceController extends Controller
{
    /**
     * Stream Official PDF for Manual Invoice (Khusus Founder & Finance)
     */
    public function streamPdf(string $uuid)
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses mengunduh invoice manual.');
        }

        $invoice = ManualInvoice::with(['project', 'unit', 'creator'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $verifyUrl = route('verify.manual-invoice', $invoice->uuid);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);
        $terbilang = ucfirst(trim($this->terbilang((int)$invoice->amount) . ' Rupiah'));

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('manual_invoices.pdf', [
            'invoice' => $invoice,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'terbilang' => $terbilang,
        ]);

        $fileName = 'Invoice-Manual-' . $invoice->invoice_number . '.pdf';
        return $pdf->stream($fileName);
    }

    /**
     * Public Guest Verification Page scanned via QR Code (Tanpa Login)
     */
    public function verify(string $uuid)
    {
        $invoice = ManualInvoice::with(['project', 'unit', 'creator'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('manual_invoices.verify_public', [
            'invoice' => $invoice,
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
