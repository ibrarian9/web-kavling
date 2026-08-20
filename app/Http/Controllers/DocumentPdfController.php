<?php

namespace App\Http\Controllers;

use App\Models\OfficialDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentPdfController extends Controller
{
    public function streamPdf($id)
    {
        $doc = OfficialDocument::with(['unit.project', 'proposal.approvals.approver', 'proposal.proposer', 'issuer'])->findOrFail($id);

        $verifyUrl = route('verify.document', $doc->id);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('documents.pdf', [
            'doc' => $doc,
            'unit' => $doc->unit,
            'project' => $doc->unit->project,
            'proposal' => $doc->proposal,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
        ]);

        $cleanUnitCode = preg_replace('/[^A-Za-z0-9_-]/', '-', $doc->unit->code);
        return $pdf->stream('Invoice-Pembayaran-' . $cleanUnitCode . '.pdf');
    }

    public function streamSpjbPdf($id)
    {
        $doc = OfficialDocument::with(['unit.project', 'proposal.approvals.approver', 'proposal.proposer', 'issuer'])->findOrFail($id);

        $unit = $doc->unit;
        $project = $unit->project;
        $proposal = $doc->proposal;

        $projectCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $project->name), 0, 4));
        $spjbNumber = 'SPJB/APA/' . $projectCode . '/' . strtoupper($unit->code) . '/' . date('Y/m', strtotime($doc->created_at)) . '/' . str_pad($doc->id, 4, '0', STR_PAD_LEFT);

        $agreedPrice = $proposal ? $proposal->proposed_price : ($unit->final_selling_price ?: 0);
        $paymentScheme = $unit->installment ? 'Cicilan ' . $unit->installment->installment_count . ' Bulan' : 'Tunai / Cash';
        $dpAmount = $unit->installment ? $unit->installment->down_payment : $agreedPrice;

        $terbilang = ucfirst(trim($this->terbilang((int)$agreedPrice))) . ' Rupiah';

        $verifyUrl = route('verify.spjb', $doc->id);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('documents.spjb_pdf', [
            'doc' => $doc,
            'unit' => $unit,
            'project' => $project,
            'proposal' => $proposal,
            'spjbNumber' => $spjbNumber,
            'agreedPrice' => $agreedPrice,
            'paymentScheme' => $paymentScheme,
            'dpAmount' => $dpAmount,
            'terbilang' => $terbilang,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
        ]);

        $cleanUnitCode = preg_replace('/[^A-Za-z0-9_-]/', '-', $unit->code);
        $cleanBuyerName = preg_replace('/[^A-Za-z0-9_-]/', '-', $doc->buyer_name);
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="SPJB-Perjanjian-Jual-Beli-' . $cleanUnitCode . '-' . $cleanBuyerName . '.pdf"',
        ]);
    }

    public function verifySpjb($id)
    {
        $doc = OfficialDocument::with(['unit.project', 'proposal.approvals.approver', 'issuer'])->findOrFail($id);

        $unit = $doc->unit;
        $project = $unit->project;
        $proposal = $doc->proposal;

        $projectCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $project->name), 0, 4));
        $spjbNumber = 'SPJB/APA/' . $projectCode . '/' . strtoupper($unit->code) . '/' . date('Y/m', strtotime($doc->created_at)) . '/' . str_pad($doc->id, 4, '0', STR_PAD_LEFT);

        $agreedPrice = $proposal ? $proposal->proposed_price : ($unit->final_selling_price ?: 0);

        return view('documents.verify_spjb_public', [
            'doc' => $doc,
            'unit' => $unit,
            'project' => $project,
            'proposal' => $proposal,
            'spjbNumber' => $spjbNumber,
            'agreedPrice' => $agreedPrice,
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
