<?php

namespace App\Http\Controllers;

use App\Models\WorkerUnitPayroll;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WorkerSpkController extends Controller
{
    public function streamSpk(int $id)
    {
        $payroll = WorkerUnitPayroll::with([
            'worker',
            'project',
            'unit',
            'creator'
        ])->findOrFail($id);

        $worker = $payroll->worker;
        $project = $payroll->project;
        $unit = $payroll->unit;
        $creator = $payroll->creator;

        $projectCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $project->name), 0, 4));
        $spkNumber = 'SPK/APA/' . $projectCode . '/' . strtoupper($unit->code) . '/' . date('Y/m', strtotime($payroll->created_at)) . '/' . str_pad($payroll->id, 4, '0', STR_PAD_LEFT);

        $verifyUrl = route('verify.worker-spk', $payroll->id);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $terbilang = $this->terbilang((int)$payroll->agreed_salary) . ' Rupiah';

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('units.spk_pdf', [
            'payroll' => $payroll,
            'worker' => $worker,
            'project' => $project,
            'unit' => $unit,
            'creator' => $creator,
            'spkNumber' => $spkNumber,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'terbilang' => ucfirst(trim($terbilang)),
        ]);

        $cleanUnitCode = preg_replace('/[^A-Za-z0-9_-]/', '-', $unit->code);
        $cleanWorkerName = preg_replace('/[^A-Za-z0-9_-]/', '-', $worker->name);
        $fileName = 'SPK-BORONGAN-' . $cleanUnitCode . '-' . $cleanWorkerName . '.pdf';
        return $pdf->stream($fileName);
    }

    public function verify(int $id)
    {
        $payroll = WorkerUnitPayroll::with([
            'worker',
            'project',
            'unit',
            'creator'
        ])->findOrFail($id);

        $projectCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $payroll->project->name), 0, 4));
        $spkNumber = 'SPK/APA/' . $projectCode . '/' . strtoupper($payroll->unit->code) . '/' . date('Y/m', strtotime($payroll->created_at)) . '/' . str_pad($payroll->id, 4, '0', STR_PAD_LEFT);

        return view('units.verify_spk_public', [
            'payroll' => $payroll,
            'worker' => $payroll->worker,
            'project' => $payroll->project,
            'unit' => $payroll->unit,
            'creator' => $payroll->creator,
            'spkNumber' => $spkNumber,
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
