<?php

namespace App\Actions\Installments;

use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\UnitInstallment;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;

class ConvertInstallmentToCashAction
{
    public function execute(int $installmentId, array $data): UnitInstallment
    {
        return DB::transaction(function () use ($installmentId, $data) {
            $inst = UnitInstallment::with('unit')->findOrFail($installmentId);

            if (($data['cash_payment_amount'] ?? 0) > 0) {
                InstallmentPayment::create([
                    'unit_installment_id' => $inst->id,
                    'payment_date' => $data['cash_payment_date'],
                    'amount_paid' => $data['cash_payment_amount'],
                    'payment_method' => $data['cash_payment_method'] ?? 'Transfer Bank',
                    'notes' => '[Pelunasan Cash - Pembatalan Skema Cicilan] ' . ($data['cash_notes'] ?? ''),
                    'created_by' => auth()->id(),
                ]);

                CashflowTransaction::create([
                    'project_id' => $inst->unit->project_id ?? null,
                    'type' => 'masuk',
                    'category' => 'pembayaran_cicilan_pembeli',
                    'amount' => $data['cash_payment_amount'],
                    'transaction_date' => $data['cash_payment_date'],
                    'description' => 'Pelunasan Cash (Pembatalan Skema Cicilan) Unit ' . ($inst->unit->code ?? '') . ' (' . ($data['cash_payment_method'] ?? 'Transfer Bank') . ')',
                    'reference_type' => UnitInstallment::class,
                    'reference_id' => $inst->id,
                    'created_by' => auth()->id(),
                ]);
            }

            $inst->update(['status' => 'konversi_cash']);

            $unitCode = $inst->unit->code ?? '-';
            ActivityLogger::log(
                'CANCEL_INSTALLMENT_TO_CASH',
                "Founder/Accounting membatalkan skema cicilan Unit {$unitCode} dan menggantinya ke Pelunasan Cash Lunas sebesar Rp " . number_format($data['cash_payment_amount'] ?? 0, 0, ',', '.')
            );

            return $inst;
        });
    }
}
