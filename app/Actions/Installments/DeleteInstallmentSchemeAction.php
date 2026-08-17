<?php

namespace App\Actions\Installments;

use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\UnitInstallment;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;

class DeleteInstallmentSchemeAction
{
    public function execute(int $id): string
    {
        return DB::transaction(function () use ($id) {
            $inst = UnitInstallment::with('unit')->findOrFail($id);
            $code = $inst->unit->code ?? '-';

            $paymentIds = InstallmentPayment::where('unit_installment_id', $inst->id)->pluck('id');

            // Delete associated cashflow transactions
            CashflowTransaction::where('reference_type', UnitInstallment::class)
                ->where('reference_id', $inst->id)
                ->delete();

            if ($paymentIds->count() > 0) {
                CashflowTransaction::where('reference_type', InstallmentPayment::class)
                    ->whereIn('reference_id', $paymentIds)
                    ->delete();
            }

            // Delete payments
            InstallmentPayment::where('unit_installment_id', $inst->id)->delete();

            // Delete unit installment scheme
            $inst->delete();

            ActivityLogger::log(
                'DELETE_INSTALLMENT_SCHEME',
                "Founder menghapus skema cicilan & piutang pembeli untuk Unit {$code}"
            );

            return $code;
        });
    }
}
