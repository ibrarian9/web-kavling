<?php

namespace App\Actions\Installments;

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Services\ActivityLogger;
use App\Services\ImageCompressor;
use Illuminate\Support\Facades\DB;

class RecordLandPaymentAction
{
    public function execute(array $data, $receiptPhoto = null, ?int $editingId = null): ProjectPayment
    {
        return DB::transaction(function () use ($data, $receiptPhoto, $editingId) {
            $project = Project::findOrFail($data['project_id']);

            $photoPath = null;
            if ($receiptPhoto) {
                $photoPath = ImageCompressor::compressAndStore($receiptPhoto, 'project-payment-receipts');
            }

            if ($editingId) {
                $payment = ProjectPayment::findOrFail($editingId);
                $updateData = [
                    'project_id' => $data['project_id'],
                    'payment_date' => $data['payment_date'],
                    'amount_paid' => $data['amount_paid'],
                    'payment_method' => $data['payment_method'] ?? 'Transfer Bank',
                    'notes' => $data['notes'] ?? '',
                ];
                if ($photoPath) {
                    $updateData['receipt_photo_path'] = $photoPath;
                }
                $payment->update($updateData);

                CashflowTransaction::where('reference_type', ProjectPayment::class)
                    ->where('reference_id', $payment->id)
                    ->update([
                        'project_id' => $project->id,
                        'amount' => $data['amount_paid'],
                        'transaction_date' => $data['payment_date'],
                        'description' => 'Pembayaran Lahan Proyek ' . $project->name . ' ke Penjual Tanah (' . ($data['payment_method'] ?? 'Transfer Bank') . ')',
                    ]);

                ActivityLogger::log('PROJECT_PAYMENT_UPDATED', "Pembayaran lahan Proyek {$project->name} sebesar Rp " . number_format($data['amount_paid'], 0, ',', '.') . " diperbarui.");

                return $payment;
            }

            $payment = ProjectPayment::create([
                'project_id' => $project->id,
                'payment_date' => $data['payment_date'],
                'amount_paid' => $data['amount_paid'],
                'payment_method' => $data['payment_method'] ?? 'Transfer Bank',
                'notes' => $data['notes'] ?? '',
                'receipt_photo_path' => $photoPath,
                'created_by' => auth()->id(),
            ]);

            CashflowTransaction::create([
                'project_id' => $project->id,
                'type' => 'keluar',
                'category' => 'operasional',
                'amount' => $data['amount_paid'],
                'transaction_date' => $data['payment_date'],
                'description' => 'Pembayaran Lahan Proyek ' . $project->name . ' ke Penjual Tanah (' . ($data['payment_method'] ?? 'Transfer Bank') . ')',
                'reference_type' => ProjectPayment::class,
                'reference_id' => $payment->id,
                'created_by' => auth()->id(),
            ]);

            ActivityLogger::log('PROJECT_PAYMENT_CREATED', "Pembayaran lahan Proyek {$project->name} sebesar Rp " . number_format($data['amount_paid'], 0, ',', '.') . " dicatat di Arus Kas.");

            return $payment;
        });
    }
}
