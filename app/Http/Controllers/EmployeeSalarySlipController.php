<?php

namespace App\Http\Controllers;

use App\Models\EmployeePayrollPayment;
use App\Services\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class EmployeeSalarySlipController extends Controller
{
    public function streamPdf(string $uuid)
    {
        $payment = EmployeePayrollPayment::with(['employeeSalary.user', 'employeeSalary.worker', 'creator'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $pdf = Pdf::loadView('documents.employee_salary_slip_pdf', [
            'payment' => $payment,
            'salary' => $payment->employeeSalary,
        ]);

        $pdf->setPaper('a4', 'portrait');

        $cleanEmpName = preg_replace('/[^A-Za-z0-9_-]/', '-', $payment->employeeSalary->employee_name);
        $fileName = "SLIP_GAJI_{$cleanEmpName}_{$payment->payroll_month}_{$payment->payroll_year}.pdf";

        $userName = auth()->user()->name ?? 'User';
        ActivityLogger::log(
            'PDF_EXPORT_SALARY_SLIP',
            "Pengguna {$userName} mencetak / mengunduh Slip Gaji Karyawan {$payment->employeeSalary->employee_name} (Periode {$payment->payroll_month}/{$payment->payroll_year})."
        );

        return $pdf->stream($fileName);
    }
}
