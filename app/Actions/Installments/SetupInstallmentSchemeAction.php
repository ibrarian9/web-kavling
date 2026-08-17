<?php

namespace App\Actions\Installments;

use App\Models\CashflowTransaction;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;

class SetupInstallmentSchemeAction
{
    public function execute(array $data, ?int $editingId = null): UnitInstallment
    {
        return DB::transaction(function () use ($data, $editingId) {
            if ($editingId) {
                $installment = UnitInstallment::with(['unit', 'payments'])->findOrFail($editingId);
                $totalPaidSoFar = (float)$data['down_payment'] + (float)$installment->payments->sum('amount_paid');
                $status = ($totalPaidSoFar >= (float)$data['total_price']) ? 'lunas' : 'berjalan';

                $installment->update([
                    'unit_id' => $data['unit_id'],
                    'official_document_id' => $data['official_document_id'] ?? $installment->official_document_id,
                    'total_price' => $data['total_price'],
                    'down_payment' => $data['down_payment'],
                    'installment_count' => $data['installment_count'],
                    'installment_amount' => $data['installment_amount'],
                    'start_date' => $data['start_date'],
                    'status' => $status,
                ]);

                $unitCode = $installment->unit->code ?? '-';
                ActivityLogger::log('INSTALLMENT_SCHEME_UPDATED', "Skema cicilan Unit {$unitCode} diperbarui.");

                return $installment;
            }

            $installment = UnitInstallment::create([
                'unit_id' => $data['unit_id'],
                'official_document_id' => $data['official_document_id'] ?? null,
                'total_price' => $data['total_price'],
                'down_payment' => $data['down_payment'],
                'installment_count' => $data['installment_count'],
                'installment_amount' => $data['installment_amount'],
                'start_date' => $data['start_date'],
                'status' => 'berjalan',
            ]);

            $unit = Unit::with(['project', 'activeBooking'])->find($data['unit_id']);
            $booking = $unit ? $unit->activeBooking : null;
            $alreadyPaid = $booking ? max((float)$booking->dp_amount, (float)$booking->booking_amount) : 0;
            $netDpCashflow = max(0, (float)$data['down_payment'] - $alreadyPaid);

            if ($netDpCashflow > 0 && $unit) {
                CashflowTransaction::create([
                    'project_id' => $unit->project_id,
                    'type' => 'masuk',
                    'category' => 'pembayaran_cicilan_pembeli',
                    'amount' => $netDpCashflow,
                    'transaction_date' => $data['start_date'],
                    'description' => 'Pembayaran Uang Muka (DP) Unit ' . $unit->code . ($alreadyPaid > 0 ? ' (Net Tambahan DP, memperhitungkan Booking Fee Rp ' . number_format($alreadyPaid, 0, ',', '.') . ' yang sudah tercatat)' : ''),
                    'reference_type' => UnitInstallment::class,
                    'reference_id' => $installment->id,
                    'created_by' => auth()->id(),
                ]);
            }

            $unitCode = $unit->code ?? '-';
            ActivityLogger::log('INSTALLMENT_SCHEME_CREATED', "Skema cicilan Unit {$unitCode} dikonfigurasi sebesar Rp " . number_format($data['total_price'], 0, ',', '.'));

            return $installment;
        });
    }
}
