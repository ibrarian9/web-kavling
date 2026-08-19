<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\WeeklyMaterialPurchase;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UnitExpensesReportController extends Controller
{
    public function exportPdf(int $id)
    {
        $unit = Unit::with(['project', 'installment'])->findOrFail($id);

        $unitPayrolls = WorkerUnitPayroll::with(['worker'])
            ->where('unit_id', $unit->id)
            ->get();

        $payrollIds = $unitPayrolls->pluck('id')->toArray();
        $salaryPayments = WorkerSalaryPayment::whereIn('worker_unit_payroll_id', $payrollIds)
            ->with(['payroll.worker'])
            ->get();

        $materialPurchases = WeeklyMaterialPurchase::with(['worker', 'pengawas'])
            ->where('unit_id', $unit->id)
            ->get();

        $combinedExpenses = collect();

        foreach ($unitPayrolls as $up) {
            $combinedExpenses->push((object)[
                'id' => $up->id,
                'source_type' => 'payroll_setup',
                'date' => $up->created_at,
                'category_badge' => 'Kontrak Gaji',
                'description' => 'Kontrak Borongan Gaji ' . ($up->worker->name ?? 'Pekerja Lapangan') . ' (' . strtoupper($up->status) . ' - Terbayar Rp ' . number_format($up->paid_amount, 0, ',', '.') . ' / Total Rp ' . number_format($up->agreed_salary, 0, ',', '.') . ')',
                'amount' => $up->agreed_salary,
                'created_at' => $up->created_at,
            ]);
        }

        foreach ($salaryPayments as $sp) {
            $combinedExpenses->push((object)[
                'id' => $sp->id,
                'source_type' => 'salary_payment',
                'date' => $sp->payment_date,
                'category_badge' => 'Gaji Worker',
                'description' => 'Pembayaran Gaji ' . ($sp->payroll->worker->name ?? 'Pekerja Lapangan') . ' (' . str_replace('_', ' ', $sp->payment_method) . ')',
                'amount' => $sp->amount_paid,
                'created_at' => $sp->created_at,
            ]);
        }

        foreach ($materialPurchases as $mp) {
            $combinedExpenses->push((object)[
                'id' => $mp->id,
                'source_type' => 'material',
                'date' => $mp->purchase_date,
                'category_badge' => 'Belanja Material',
                'description' => $mp->item_name . ' (' . number_format($mp->quantity, 0, ',', '.') . ' ' . $mp->unit_measure . ' @ Rp ' . number_format($mp->unit_price, 0, ',', '.') . ')',
                'amount' => $mp->total_price,
                'created_at' => $mp->created_at,
            ]);
        }

        $combinedExpenses = $combinedExpenses->sortByDesc(function ($item) {
            return ($item->date ? $item->date->format('Y-m-d') : '0000-00-00') . '_' . $item->id;
        })->values();

        $totalMaterialCost = $materialPurchases->sum('total_price');
        $totalSalaryCost = $salaryPayments->sum('amount_paid');
        $totalPayrollContractCost = $unitPayrolls->sum('agreed_salary');
        $totalExpenses = $totalMaterialCost + $totalSalaryCost;

        $verifyUrl = route('verify.unit-expenses', $unit->id);
        $qrCodeUrl = $this->generateQrBase64($verifyUrl);

        $pdf = Pdf::setOption([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
        ])->loadView('units.expenses_pdf', [
            'unit' => $unit,
            'project' => $unit->project,
            'combinedExpenses' => $combinedExpenses,
            'totalMaterialCost' => $totalMaterialCost,
            'totalSalaryCost' => $totalSalaryCost,
            'totalPayrollContractCost' => $totalPayrollContractCost,
            'totalExpenses' => $totalExpenses,
            'verifyUrl' => $verifyUrl,
            'qrCodeUrl' => $qrCodeUrl,
        ]);

        $filename = 'LAPORAN-BIAYA-UNIT-' . str_replace(' ', '-', strtoupper($unit->code)) . '.pdf';
        return $pdf->stream($filename);
    }

    public function verify(int $id)
    {
        $unit = Unit::with(['project'])->findOrFail($id);

        $materialPurchases = WeeklyMaterialPurchase::where('unit_id', $unit->id)->get();
        $unitPayrolls = WorkerUnitPayroll::where('unit_id', $unit->id)->get();
        $unitPayrollIds = $unitPayrolls->pluck('id')->toArray();
        $salaryPayments = WorkerSalaryPayment::whereIn('worker_unit_payroll_id', $unitPayrollIds)->get();

        $totalMaterialCost = $materialPurchases->sum('total_price');
        $totalSalaryCost = $salaryPayments->sum('amount_paid');
        $totalExpenses = $totalMaterialCost + $totalSalaryCost;

        return view('units.verify_expenses_public', [
            'unit' => $unit,
            'project' => $unit->project,
            'totalMaterialCost' => $totalMaterialCost,
            'totalSalaryCost' => $totalSalaryCost,
            'totalExpenses' => $totalExpenses,
            'materialCount' => $materialPurchases->count(),
            'salaryCount' => $salaryPayments->count(),
            'payrollCount' => $unitPayrolls->count(),
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

        try {
            $svg = QrCode::size(150)->margin(1)->generate($url);
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        } catch (\Throwable $e) {
            return '';
        }
    }
}
