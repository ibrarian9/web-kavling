<?php

namespace App\Console\Commands;

use App\Services\OrphanCashflowAuditor;
use Illuminate\Console\Command;

class AuditOrphanCashflows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashflow:audit-orphans {--fix : Purge orphan records automatically}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit & Clean orphan records in Arus Kas Global (cashflow_transactions)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mengaudit data Arus Kas Global (cashflow_transactions)...');

        $orphans = OrphanCashflowAuditor::audit();
        $totalOrphans = count($orphans);

        if ($totalOrphans === 0) {
            $this->info('✓ SELAMAT! Seluruh data Arus Kas Global 100% SINGKRON dan TIDAK ADA orphan record.');
            return 0;
        }

        $this->warn("! DITEMUKAN {$totalOrphans} ORPHAN RECORD pada Arus Kas Global:");

        $tableData = array_map(function ($o) {
            return [
                $o['id'],
                $o['transaction_date'],
                strtoupper($o['type']),
                $o['category'],
                'Rp ' . number_format($o['amount'], 0, ',', '.'),
                $o['description'],
                $o['reason'],
            ];
        }, $orphans);

        $this->table(['ID', 'Tanggal', 'Tipe', 'Kategori', 'Nominal', 'Keterangan', 'Penyebab Orphan'], $tableData);

        if ($this->option('fix')) {
            if ($this->confirm('Apakah Anda yakin ingin menghapus seluruh orphan record di atas dari Arus Kas Global?')) {
                $deletedCount = OrphanCashflowAuditor::purge();
                $this->info("✓ BERHASIL! {$deletedCount} orphan record telah dihapus dari Arus Kas Global.");
            }
        } else {
            $this->comment('Petunjuk: Jalankan `php artisan cashflow:audit-orphans --fix` untuk menghapus data orphan secara otomatis.');
        }

        return 0;
    }
}
