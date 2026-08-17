<?php

namespace App\Actions\Installments;

use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\UnitInstallment;
use App\Services\ActivityLogger;
use App\Services\ImageCompressor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RecordInstallmentPaymentAction
{
    public function execute(int $installmentId, array $data, $receiptPhoto = null, ?int $editingPaymentId = null): array
    {
        return DB::transaction(function () use ($installmentId, $data, $receiptPhoto, $editingPaymentId) {
            $inst = UnitInstallment::with('unit')->findOrFail($installmentId);

            $receiptPhotoPath = null;
            if ($receiptPhoto) {
                $receiptPhotoPath = ImageCompressor::compressAndStore($receiptPhoto, 'installment-receipts');
            }

            $hasReceiptColPayment = Schema::hasColumn('installment_payments', 'receipt_photo_path');
            $hasReceiptColCashflow = Schema::hasColumn('cashflow_transactions', 'receipt_photo_path');

            if ($editingPaymentId) {
                $pay = InstallmentPayment::findOrFail($editingPaymentId);
                $payData = [
                    'payment_date' => $data['payment_date'],
                    'amount_paid' => $data['payment_amount'],
                    'payment_method' => $data['payment_method'] ?? 'Transfer Bank',
                    'notes' => $data['payment_notes'] ?? '',
                ];

                if ($receiptPhotoPath && $hasReceiptColPayment) {
                    $payData['receipt_photo_path'] = $receiptPhotoPath;
                }

                $pay->update($payData);

                $cashflow = CashflowTransaction::where('reference_type', InstallmentPayment::class)
                    ->where('reference_id', $pay->id)
                    ->first();

                if ($cashflow) {
                    $cashData = [
                        'amount' => $data['payment_amount'],
                        'transaction_date' => $data['payment_date'],
                        'description' => 'Setoran Cicilan Pembeli Unit ' . ($inst->unit->code ?? '') . ' (' . ($data['payment_method'] ?? 'Transfer Bank') . ')',
                    ];
                    if ($receiptPhotoPath && $hasReceiptColCashflow) {
                        $cashData['receipt_photo_path'] = $receiptPhotoPath;
                    }
                    $cashflow->update($cashData);
                }

                $totalPaid = (float)$inst->down_payment + (float)$inst->payments()->sum('amount_paid');
                $status = ($totalPaid >= (float)$inst->total_price) ? 'lunas' : 'berjalan';
                $inst->update(['status' => $status]);

                $unitCode = $inst->unit->code ?? '-';
                ActivityLogger::log('INSTALLMENT_PAYMENT_UPDATED', "Setoran cicilan Unit {$unitCode} diperbarui.");

                return [
                    'payment' => $pay,
                    'is_settled' => ($status === 'lunas'),
                    'is_edit' => true,
                ];
            }

            $createData = [
                'unit_installment_id' => $inst->id,
                'payment_date' => $data['payment_date'],
                'amount_paid' => $data['payment_amount'],
                'payment_method' => $data['payment_method'] ?? 'Transfer Bank',
                'notes' => $data['payment_notes'] ?? '',
                'created_by' => auth()->id(),
            ];
            if ($receiptPhotoPath && $hasReceiptColPayment) {
                $createData['receipt_photo_path'] = $receiptPhotoPath;
            }

            $payment = InstallmentPayment::create($createData);

            $cashData = [
                'project_id' => $inst->unit->project_id ?? null,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => $data['payment_amount'],
                'transaction_date' => $data['payment_date'],
                'description' => 'Setoran Cicilan Pembeli Unit ' . ($inst->unit->code ?? '') . ' (' . ($data['payment_method'] ?? 'Transfer Bank') . ')',
                'reference_type' => InstallmentPayment::class,
                'reference_id' => $payment->id,
                'created_by' => auth()->id(),
            ];
            if ($receiptPhotoPath && $hasReceiptColCashflow) {
                $cashData['receipt_photo_path'] = $receiptPhotoPath;
            }
            CashflowTransaction::create($cashData);

            $unitCode = $inst->unit->code ?? '-';
            ActivityLogger::log('INSTALLMENT_PAYMENT_RECORDED', "Setoran cicilan Unit {$unitCode} dicatat sebesar Rp " . number_format($data['payment_amount'], 0, ',', '.'));

            $isSettled = false;
            if ($inst->remaining_balance <= 0) {
                $inst->update(['status' => 'lunas']);
                $isSettled = true;
            }

            return [
                'payment' => $payment,
                'is_settled' => $isSettled,
                'is_edit' => false,
            ];
        });
    }
}
