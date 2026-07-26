<?php

namespace App\Http\Controllers;

use App\Models\WorkerSalaryPayment;
use Illuminate\Http\Request;

class PayrollVerificationController extends Controller
{
    public function verify(string $uuid)
    {
        $payment = WorkerSalaryPayment::with([
            'payroll.worker',
            'payroll.project',
            'payroll.unit',
            'creator'
        ])->where('uuid', $uuid)->firstOrFail();

        return view('payroll.verify_public', [
            'payment' => $payment,
            'payroll' => $payment->payroll,
            'worker' => $payment->payroll->worker,
            'project' => $payment->payroll->project,
            'unit' => $payment->payroll->unit,
        ]);
    }
}
