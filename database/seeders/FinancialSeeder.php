<?php

namespace Database\Seeders;

use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\UnitInstallment;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinancialSeeder extends Seeder
{
    public function run(): void
    {
        $finance = User::where('email', 'finance@kavling.com')->first();
        $finId = $finance ? $finance->id : 3;

        // -------------------------------------------------------------
        // 1. SETUP UNIT INSTALLMENTS & PAYMENTS
        // -------------------------------------------------------------

        // Installment 1: Unit A-01 (Grand Kavling Panam Residence)
        $inst1 = UnitInstallment::updateOrCreate(
            ['id' => 1],
            [
                'unit_id' => 1,
                'official_document_id' => 1,
                'total_price' => 165000000.00,
                'down_payment' => 33000000.00,
                'installment_count' => 12,
                'installment_amount' => 11000000.00,
                'start_date' => '2026-05-01',
                'status' => 'berjalan',
            ]
        );

        CashflowTransaction::updateOrCreate(
            ['reference_type' => UnitInstallment::class, 'reference_id' => 1, 'description' => 'Pembayaran Uang Muka (DP) Unit A-01 Panam'],
            [
                'project_id' => 1,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => 33000000.00,
                'transaction_date' => '2026-05-01',
                'created_by' => $finId,
            ]
        );

        $paymentsInst1 = [
            ['date' => '2026-05-15', 'amount' => 11000000.00, 'method' => 'Transfer Bank (BRK Syariah / Bank Riau Kepri)', 'note' => 'Setoran cicilan bulan ke-1'],
            ['date' => '2026-06-15', 'amount' => 11000000.00, 'method' => 'Transfer Bank (BRK Syariah / Bank Riau Kepri)', 'note' => 'Setoran cicilan bulan ke-2'],
            ['date' => '2026-07-15', 'amount' => 11000000.00, 'method' => 'Transfer Bank (BRK Syariah / Bank Riau Kepri)', 'note' => 'Setoran cicilan bulan ke-3'],
        ];

        foreach ($paymentsInst1 as $p) {
            InstallmentPayment::create([
                'unit_installment_id' => $inst1->id,
                'payment_date' => $p['date'],
                'amount_paid' => $p['amount'],
                'payment_method' => $p['method'],
                'notes' => $p['note'],
                'created_by' => $finId,
            ]);

            CashflowTransaction::create([
                'project_id' => 1,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => $p['amount'],
                'transaction_date' => $p['date'],
                'description' => 'Setoran Cicilan Pembeli Unit A-01 Panam (' . $p['method'] . ')',
                'reference_type' => UnitInstallment::class,
                'reference_id' => $inst1->id,
                'created_by' => $finId,
            ]);
        }

        // Installment 2: Unit A-02 (Grand Kavling Panam Residence)
        $inst2 = UnitInstallment::updateOrCreate(
            ['id' => 2],
            [
                'unit_id' => 2,
                'official_document_id' => 2,
                'total_price' => 225000000.00,
                'down_payment' => 45000000.00,
                'installment_count' => 6,
                'installment_amount' => 30000000.00,
                'start_date' => '2026-04-01',
                'status' => 'menunggak',
            ]
        );

        CashflowTransaction::updateOrCreate(
            ['reference_type' => UnitInstallment::class, 'reference_id' => 2, 'description' => 'Pembayaran Uang Muka (DP) Unit A-02 Panam'],
            [
                'project_id' => 1,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => 45000000.00,
                'transaction_date' => '2026-04-01',
                'created_by' => $finId,
            ]
        );

        $paymentsInst2 = [
            ['date' => '2026-04-10', 'amount' => 30000000.00, 'method' => 'Transfer Bank (Mandiri Pekanbaru)', 'note' => 'Setoran cicilan bulan ke-1'],
        ];

        foreach ($paymentsInst2 as $p) {
            InstallmentPayment::create([
                'unit_installment_id' => $inst2->id,
                'payment_date' => $p['date'],
                'amount_paid' => $p['amount'],
                'payment_method' => $p['method'],
                'notes' => $p['note'],
                'created_by' => $finId,
            ]);

            CashflowTransaction::create([
                'project_id' => 1,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => $p['amount'],
                'transaction_date' => $p['date'],
                'description' => 'Setoran Cicilan Pembeli Unit A-02 (' . $p['method'] . ')',
                'reference_type' => UnitInstallment::class,
                'reference_id' => $inst2->id,
                'created_by' => $finId,
            ]);
        }

        // Installment 3: Unit A-06 (Rumah - Grand Kavling Panam Residence)
        $inst3 = UnitInstallment::updateOrCreate(
            ['id' => 3],
            [
                'unit_id' => 6,
                'official_document_id' => 3,
                'total_price' => 310000000.00,
                'down_payment' => 62000000.00,
                'installment_count' => 12,
                'installment_amount' => 20666667.00,
                'start_date' => '2026-06-01',
                'status' => 'berjalan',
            ]
        );

        CashflowTransaction::updateOrCreate(
            ['reference_type' => UnitInstallment::class, 'reference_id' => 3, 'description' => 'Pembayaran Uang Muka (DP) Unit A-06 Rumah Panam'],
            [
                'project_id' => 1,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => 62000000.00,
                'transaction_date' => '2026-06-01',
                'created_by' => $finId,
            ]
        );

        $paymentsInst3 = [
            ['date' => '2026-06-10', 'amount' => 20666667.00, 'method' => 'Transfer Bank (BRI Pekanbaru)', 'note' => 'Setoran cicilan bulan ke-1'],
            ['date' => '2026-07-10', 'amount' => 20666667.00, 'method' => 'Transfer Bank (BRI Pekanbaru)', 'note' => 'Setoran cicilan bulan ke-2'],
        ];

        foreach ($paymentsInst3 as $p) {
            InstallmentPayment::create([
                'unit_installment_id' => $inst3->id,
                'payment_date' => $p['date'],
                'amount_paid' => $p['amount'],
                'payment_method' => $p['method'],
                'notes' => $p['note'],
                'created_by' => $finId,
            ]);

            CashflowTransaction::create([
                'project_id' => 1,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => $p['amount'],
                'transaction_date' => $p['date'],
                'description' => 'Setoran Cicilan Pembeli Unit A-06 (' . $p['method'] . ')',
                'reference_type' => UnitInstallment::class,
                'reference_id' => $inst3->id,
                'created_by' => $finId,
            ]);
        }

        // Installment 4: Unit B-01 (Cluster Permata Arifin Ahmad)
        $inst4 = UnitInstallment::updateOrCreate(
            ['id' => 4],
            [
                'unit_id' => 9,
                'official_document_id' => 4,
                'total_price' => 285000000.00,
                'down_payment' => 57000000.00,
                'installment_count' => 4,
                'installment_amount' => 57000000.00,
                'start_date' => '2026-03-01',
                'status' => 'lunas',
            ]
        );

        CashflowTransaction::updateOrCreate(
            ['reference_type' => UnitInstallment::class, 'reference_id' => 4, 'description' => 'Pembayaran Uang Muka (DP) Unit B-01 Marpoyan'],
            [
                'project_id' => 2,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => 57000000.00,
                'transaction_date' => '2026-03-01',
                'created_by' => $finId,
            ]
        );

        $paymentsInst4 = [
            ['date' => '2026-03-15', 'amount' => 57000000.00, 'method' => 'Transfer Bank (BCA Pekanbaru)', 'note' => 'Cicilan 1/4'],
            ['date' => '2026-04-15', 'amount' => 57000000.00, 'method' => 'Transfer Bank (BCA Pekanbaru)', 'note' => 'Cicilan 2/4'],
            ['date' => '2026-05-15', 'amount' => 57000000.00, 'method' => 'Transfer Bank (BCA Pekanbaru)', 'note' => 'Cicilan 3/4'],
            ['date' => '2026-06-15', 'amount' => 57000000.00, 'method' => 'Transfer Bank (BCA Pekanbaru)', 'note' => 'Cicilan 4/4 (LUNAS)'],
        ];

        foreach ($paymentsInst4 as $p) {
            InstallmentPayment::create([
                'unit_installment_id' => $inst4->id,
                'payment_date' => $p['date'],
                'amount_paid' => $p['amount'],
                'payment_method' => $p['method'],
                'notes' => $p['note'],
                'created_by' => $finId,
            ]);

            CashflowTransaction::create([
                'project_id' => 2,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => $p['amount'],
                'transaction_date' => $p['date'],
                'description' => 'Setoran Cicilan Pembeli Unit B-01 Marpoyan (' . $p['method'] . ')',
                'reference_type' => UnitInstallment::class,
                'reference_id' => $inst4->id,
                'created_by' => $finId,
            ]);
        }

        // Installment 5: Unit D-01 (Graha Rumbai Asri - Cash Keras)
        $inst5 = UnitInstallment::updateOrCreate(
            ['id' => 5],
            [
                'unit_id' => 18,
                'official_document_id' => 5,
                'total_price' => 110000000.00,
                'down_payment' => 110000000.00,
                'installment_count' => 1,
                'installment_amount' => 0.00,
                'start_date' => '2026-03-01',
                'status' => 'lunas',
            ]
        );

        CashflowTransaction::updateOrCreate(
            ['reference_type' => UnitInstallment::class, 'reference_id' => 5, 'description' => 'Pembayaran Cash Keras 100% Unit D-01 Rumbai'],
            [
                'project_id' => 4,
                'type' => 'masuk',
                'category' => 'penjualan_unit',
                'amount' => 110000000.00,
                'transaction_date' => '2026-03-01',
                'created_by' => $finId,
            ]
        );
    }
}
