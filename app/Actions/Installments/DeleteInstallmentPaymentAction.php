<?php

namespace App\Actions\Installments;

use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\UnitInstallment;
use Illuminate\Support\Facades\DB;

class DeleteInstallmentPaymentAction
{
    public function execute(int $paymentId): array
    {
        return DB::transaction(function () use ($paymentId) {
            $pay = InstallmentPayment::with('installment.unit')->findOrFail($paymentId);
            $instId = $pay->unit_installment_id;
            $unitCode = $pay->installment->unit->code ?? '-';
            $amount = $pay->amount_paid;

            CashflowTransaction::where('reference_type', InstallmentPayment::class)
                ->where('reference_id', $pay->id)
                ->delete();

            $pay->delete();

            $inst = UnitInstallment::find($instId);
            if ($inst) {
                $totalPaid = (float)$inst->down_payment + (float)$inst->payments()->sum('amount_paid');
                $status = ($totalPaid >= (float)$inst->total_price) ? 'lunas' : 'berjalan';
                $inst->update(['status' => $status]);
            }

            return [
                'unit_installment_id' => $instId,
                'unit_code' => $unitCode,
                'amount' => $amount,
            ];
        });
    }
}
