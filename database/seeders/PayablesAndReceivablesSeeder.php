<?php

namespace Database\Seeders;

use App\Models\CashflowTransaction;
use App\Models\CompanyReceivable;
use App\Models\Project;
use App\Models\ReceivablePayment;
use App\Models\Unit;
use App\Models\UnitCommission;
use App\Models\UnitCommissionPayment;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use Illuminate\Database\Seeder;

class PayablesAndReceivablesSeeder extends Seeder
{
    public function run(): void
    {
        $founder = User::where('role', 'founder')->first() ?? User::factory()->create(['role' => 'founder']);
        $finance = User::where('role', 'finance')->first() ?? User::factory()->create(['role' => 'finance']);
        $marketing = User::where('role', 'marketing')->first() ?? User::factory()->create(['role' => 'marketing']);
        $project = Project::first() ?? Project::create([
            'name' => 'Kavling Harmoni Residence',
            'location' => 'Malang',
            'standard_land_area' => 100,
            'excess_price_per_sqm' => 500000,
            'base_price' => 150000000,
            'total_project_price' => 500000000,
            'status' => 'aktif',
            'created_by' => $founder->id,
        ]);
        $unit = Unit::where('project_id', $project->id)->first() ?? Unit::create([
            'project_id' => $project->id,
            'code' => 'BLK-A1',
            'type' => 'kavling',
            'category' => 'kavling',
            'land_width' => 10,
            'land_length' => 10,
            'land_area' => 100,
            'price' => 150000000,
            'hpp' => 80000000,
            'status' => 'tersedia',
            'created_by' => $founder->id,
        ]);
        $worker = Worker::first() ?? Worker::create([
            'name' => 'Mandor Slamet',
            'type' => 'mandor',
            'specialty' => 'Pondasi & Konstruksi',
            'daily_rate' => 200000,
            'status' => 'active',
        ]);

        // -------------------------------------------------------------
        // 1. SEED TAB 1: HUTANG TOKO MATERIAL & VENDOR
        // -------------------------------------------------------------
        WeeklyMaterialPurchase::create([
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'worker_id' => $worker->id,
            'pengawas_id' => $founder->id,
            'purchase_date' => now()->subDays(5)->toDateString(),
            'item_name' => 'Semen Gresik 50 Sak (Pondasi Utama)',
            'store_name' => 'TB Harapan Jaya',
            'quantity' => 50,
            'unit_measure' => 'sak',
            'unit_price' => 65000,
            'total_price' => 3250000,
            'payment_status' => 'belum_lunas',
            'notes' => 'Tempo pembayaran 14 hari dari toko',
        ]);

        WeeklyMaterialPurchase::create([
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'worker_id' => $worker->id,
            'pengawas_id' => $founder->id,
            'purchase_date' => now()->subDays(3)->toDateString(),
            'item_name' => 'Besi Ulir 12mm 50 Batang',
            'store_name' => 'Toko Besi Maju Sejahtera',
            'quantity' => 50,
            'unit_measure' => 'btg',
            'unit_price' => 95000,
            'total_price' => 4750000,
            'payment_status' => 'belum_lunas',
            'notes' => 'Nota tagihan fisik ada di meja kantor',
        ]);

        $settledMat = WeeklyMaterialPurchase::create([
            'project_id' => null, // Operasional Umum
            'unit_id' => null,
            'worker_id' => null,
            'pengawas_id' => $founder->id,
            'purchase_date' => now()->subDays(10)->toDateString(),
            'item_name' => 'Tagihan Listrik Daya Sementara Proyek',
            'store_name' => 'PLN Corporate',
            'quantity' => 1,
            'unit_measure' => 'paket',
            'unit_price' => 1500000,
            'total_price' => 1500000,
            'payment_status' => 'lunas',
            'paid_at' => now()->subDays(10)->toDateString(),
            'paid_by' => $finance->id,
            'notes' => 'Langsung dipotong kas keluar kantor',
        ]);

        CashflowTransaction::create([
            'project_id' => null,
            'type' => 'keluar',
            'category' => 'operasional',
            'amount' => 1500000,
            'transaction_date' => now()->subDays(10)->toDateString(),
            'description' => 'Pelunasan Tagihan Operasional (Operasional Umum / Non-Proyek) (Toko/Vendor: PLN Corporate): Tagihan Listrik Daya Sementara Proyek',
            'reference_type' => WeeklyMaterialPurchase::class,
            'reference_id' => $settledMat->id,
            'created_by' => $finance->id,
        ]);

        // -------------------------------------------------------------
        // 2. SEED TAB 2: SISA UPAH PEKERJA / TUKANG
        // -------------------------------------------------------------
        $payroll = WorkerUnitPayroll::create([
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'worker_id' => $worker->id,
            'agreed_salary' => 15000000,
            'paid_amount' => 10000000,
            'status' => 'berjalan',
            'notes' => 'Pekerjaan Sloof, Kolom & Pondasi Blok A1',
        ]);

        $salaryPay = WorkerSalaryPayment::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'worker_unit_payroll_id' => $payroll->id,
            'payment_date' => now()->subDays(7)->toDateString(),
            'amount_gross' => 10000000,
            'loan_deduction' => 0,
            'amount_paid' => 10000000,
            'payment_method' => 'Transfer Bank',
            'notes' => 'Termin 1 Pengecoran Sloof',
            'created_by' => $finance->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $project->id,
            'type' => 'keluar',
            'category' => 'upah_tukang',
            'amount' => 10000000,
            'transaction_date' => now()->subDays(7)->toDateString(),
            'description' => "Pembayaran Upah Pekerja ({$worker->name}) Unit {$unit->code}: Termin 1 Pengecoran Sloof",
            'reference_type' => WorkerSalaryPayment::class,
            'reference_id' => $salaryPay->id,
            'created_by' => $finance->id,
        ]);

        // -------------------------------------------------------------
        // 3. SEED TAB 3: HUTANG KOMISI PENJUAL UNIT (DENGAN SKEMA CICILAN)
        // -------------------------------------------------------------
        // Sample A: Belum Dibayar Sama Sekali (Total Rp 3.750.000)
        UnitCommission::create([
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'marketing_id' => $marketing->id,
            'seller_name' => $marketing->name . ' (Marketing Internal)',
            'seller_phone' => '081234567890',
            'percentage' => 2.50,
            'commission_amount' => 3750000,
            'paid_amount' => 0,
            'status' => 'belum_dibayar',
            'notes' => 'Komisi 2.5% Penjualan Unit ' . $unit->code,
            'created_by' => $founder->id,
        ]);

        // Sample B: Status Berjalan / Sedang Dicicil (Total Rp 6.000.000, Terbayar Rp 2.500.000)
        $partialComm = UnitCommission::create([
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'marketing_id' => null,
            'seller_name' => 'Agus Agen Freelance',
            'seller_phone' => '085711223344',
            'percentage' => 4.00,
            'commission_amount' => 6000000,
            'paid_amount' => 2500000,
            'status' => 'berjalan',
            'notes' => 'Komisi 4% Penjualan Unit ' . $unit->code . ' (Skema Cicilan 2 Termin)',
            'created_by' => $founder->id,
        ]);

        $commPay1 = UnitCommissionPayment::create([
            'unit_commission_id' => $partialComm->id,
            'payment_date' => now()->subDays(6)->toDateString(),
            'amount' => 2500000,
            'payment_method' => 'Transfer Bank',
            'notes' => 'Cicilan 1 Komisi Closing Penjualan Unit',
            'created_by' => $finance->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $project->id,
            'type' => 'keluar',
            'category' => 'operasional',
            'amount' => 2500000,
            'transaction_date' => now()->subDays(6)->toDateString(),
            'description' => "Pembayaran Cicilan Komisi Penjual (Agus Agen Freelance) Unit {$unit->code}: Rp 2.500.000",
            'reference_type' => UnitCommissionPayment::class,
            'reference_id' => $commPay1->id,
            'created_by' => $finance->id,
        ]);

        // Sample C: Status Lunas 100% (2 Termin Setoran Cicilan)
        $settledComm = UnitCommission::create([
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'marketing_id' => null,
            'seller_name' => 'Siska Properti (Broker Eksternal)',
            'seller_phone' => '089988776655',
            'percentage' => 3.00,
            'commission_amount' => 4500000,
            'paid_amount' => 4500000,
            'status' => 'lunas',
            'paid_at' => now()->subDays(2)->toDateString(),
            'paid_by' => $finance->id,
            'notes' => 'Bonus closing broker eksternal (Lunas 2 Termin)',
            'created_by' => $founder->id,
        ]);

        $fullPay1 = UnitCommissionPayment::create([
            'unit_commission_id' => $settledComm->id,
            'payment_date' => $this->sale_date ?? now()->subDays(8)->toDateString(),
            'amount' => 2000000,
            'payment_method' => 'Transfer Bank',
            'notes' => 'Termin 1 Komisi Broker',
            'created_by' => $finance->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $project->id,
            'type' => 'keluar',
            'category' => 'operasional',
            'amount' => 2000000,
            'transaction_date' => $fullPay1->payment_date,
            'description' => "Pembayaran Cicilan Komisi Penjual (Siska Properti (Broker Eksternal)) Unit {$unit->code}: Rp 2.000.000",
            'reference_type' => UnitCommissionPayment::class,
            'reference_id' => $fullPay1->id,
            'created_by' => $finance->id,
        ]);

        $fullPay2 = UnitCommissionPayment::create([
            'unit_commission_id' => $settledComm->id,
            'payment_date' => now()->subDays(2)->toDateString(),
            'amount' => 2500000,
            'payment_method' => 'Transfer Bank',
            'notes' => 'Termin 2 Pelunasan Final Komisi Broker',
            'created_by' => $finance->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $project->id,
            'type' => 'keluar',
            'category' => 'operasional',
            'amount' => 2500000,
            'transaction_date' => $fullPay2->payment_date,
            'description' => "Pembayaran Cicilan Komisi Penjual (Siska Properti (Broker Eksternal)) Unit {$unit->code}: Rp 2.500.000",
            'reference_type' => UnitCommissionPayment::class,
            'reference_id' => $fullPay2->id,
            'created_by' => $finance->id,
        ]);

        // -------------------------------------------------------------
        // 4. SEED TAB 4: PIUTANG & KASBON STAF / WORKERS
        // -------------------------------------------------------------
        $recWorker = CompanyReceivable::create([
            'debtor_type' => 'worker',
            'debtor_name' => $worker->name . ' (Mandor)',
            'worker_id' => $worker->id,
            'user_id' => null,
            'amount' => 2000000,
            'paid_amount' => 1000000,
            'loan_date' => now()->subDays(15)->toDateString(),
            'status' => 'belum_lunas',
            'notes' => 'Kasbon uang muka perbaikan alat proyek',
            'created_by' => $founder->id,
        ]);

        $recPayment = ReceivablePayment::create([
            'company_receivable_id' => $recWorker->id,
            'payment_date' => now()->subDays(5)->toDateString(),
            'amount' => 1000000,
            'payment_method' => 'Cash / Tunai',
            'notes' => 'Cicilan 1 pengembalian kasbon tunai ke kantor',
            'created_by' => $finance->id,
        ]);

        CashflowTransaction::create([
            'project_id' => null,
            'type' => 'masuk',
            'category' => 'lain_lain',
            'amount' => 1000000,
            'transaction_date' => now()->subDays(5)->toDateString(),
            'description' => "Pengembalian Piutang / Kasbon ({$recWorker->debtor_name}): Rp 1.000.000",
            'reference_type' => ReceivablePayment::class,
            'reference_id' => $recPayment->id,
            'created_by' => $finance->id,
        ]);

        CompanyReceivable::create([
            'debtor_type' => 'user',
            'debtor_name' => $marketing->name . ' (Marketing)',
            'worker_id' => null,
            'user_id' => $marketing->id,
            'amount' => 750000,
            'paid_amount' => 0,
            'loan_date' => now()->subDays(2)->toDateString(),
            'status' => 'belum_lunas',
            'notes' => 'Pinjaman talangan bensin & brosur iklan',
            'created_by' => $founder->id,
        ]);
    }
}
