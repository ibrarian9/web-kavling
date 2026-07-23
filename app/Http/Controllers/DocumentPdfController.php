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

        $pdf = Pdf::setOption([
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

        return $pdf->stream('Invoice-Pembayaran-' . $doc->unit->code . '.pdf');
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
}
