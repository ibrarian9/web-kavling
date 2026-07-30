<?php

namespace App\Http\Controllers;

use App\Models\WeeklyMaterialPurchase;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MaterialPurchaseReceiptController extends Controller
{
    public function streamReceipt(int $id)
    {
        $material = WeeklyMaterialPurchase::with([
            'project',
            'unit',
            'worker',
            'pengawas'
        ])->findOrFail($id);

        $verifyUrl = route('verify.material-purchase', $material->id);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $terbilang = $this->terbilang((int)$material->total_price) . ' Rupiah';

        $receiptPhotoBase64 = null;
        if ($material->receipt_photo_path) {
            $fullPath = storage_path('app/public/' . $material->receipt_photo_path);
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
        ])->loadView('material_purchases.receipt_pdf', [
            'material' => $material,
            'project' => $material->project,
            'unit' => $material->unit,
            'worker' => $material->worker,
            'pengawas' => $material->pengawas,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'terbilang' => ucfirst(trim($terbilang)),
            'receiptPhotoBase64' => $receiptPhotoBase64,
        ]);

        $receiptNo = 'RESI-MATERIAL-' . $material->id;
        return $pdf->stream($receiptNo . '.pdf');
    }

    public function verify(int $id)
    {
        $material = WeeklyMaterialPurchase::with([
            'project',
            'unit',
            'worker',
            'pengawas'
        ])->findOrFail($id);

        return view('material_purchases.verify_public', [
            'material' => $material,
            'project' => $material->project,
            'unit' => $material->unit,
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
