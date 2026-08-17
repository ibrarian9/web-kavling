<?php

namespace App\Actions\Installments;

use App\Models\CashflowTransaction;
use App\Models\ProjectPayment;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;

class DeleteLandPaymentAction
{
    public function execute(int $paymentId): string
    {
        return DB::transaction(function () use ($paymentId) {
            $payment = ProjectPayment::with('project')->findOrFail($paymentId);
            $projectName = $payment->project->name ?? '-';

            // Remove related CashflowTransaction if present
            CashflowTransaction::where('reference_type', ProjectPayment::class)
                ->where('reference_id', $payment->id)
                ->delete();

            $payment->delete();

            ActivityLogger::log('PROJECT_PAYMENT_DELETED', "Catatan pembayaran lahan proyek {$projectName} (ID #{$paymentId}) dihapus.");

            return $projectName;
        });
    }
}
