<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\PriceProposal;
use App\Models\User;
use Illuminate\Database\Seeder;

class PriceProposalSeeder extends Seeder
{
    public function run(): void
    {
        $marketing = User::where('role', 'marketing')->first() ?? User::first();
        $marketing2 = $marketing;
        $marketing3 = $marketing;
        $founder = User::where('role', 'founder')->first() ?? User::first();
        $supervisor = User::where('role', 'supervisor')->first() ?? User::first();

        $mktId = $marketing ? $marketing->id : 4;
        $mkt2Id = $marketing2 ? $marketing2->id : 5;
        $mkt3Id = $marketing3 ? $marketing3->id : 6;
        $founderId = $founder ? $founder->id : 1;
        $supervisorId = $supervisor ? $supervisor->id : 2;

        $proposalsData = [
            // 1. Unit A-01 (terjual -> approved)
            [
                'id' => 1,
                'unit_id' => 1,
                'hpp_price' => 150000000.00,
                'proposed_price' => 185000000.00,
                'margin' => 35000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Pengajuan harga jual promo awal tahun kavling A-01.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC, margin sesuai target minimum 20%.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'Disetujui. Kontur tanah siap dibangun.'],
                ],
            ],
            // 2. Unit A-02 (terjual -> approved)
            [
                'id' => 2,
                'unit_id' => 2,
                'hpp_price' => 202500000.00,
                'proposed_price' => 245000000.00,
                'margin' => 42500000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Penawaran harga posisi hook kelebihan tanah 35m2.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC harga hook.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'Pagar pembatas kelebihan tanah sudah valid.'],
                ],
            ],
            // 3. Unit A-03 (disetujui)
            [
                'id' => 3,
                'unit_id' => 3,
                'hpp_price' => 150000000.00,
                'proposed_price' => 180000000.00,
                'margin' => 30000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mkt2Id,
                'status' => 'disetujui',
                'notes' => 'Pengajuan skema cash bertahap 6x.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC cash bertahap.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'Kondisi lahan bersih.'],
                ],
            ],
            // 4. Unit A-04 (menunggu_persetujuan)
            [
                'id' => 4,
                'unit_id' => 4,
                'hpp_price' => 180000000.00,
                'proposed_price' => 210000000.00,
                'margin' => 30000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'menunggu',
                'notes' => 'Calon pembeli mengajukan diskon tipis untuk kavling A-04.',
                'approvals' => [
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'Secara teknis oke, menunggu persetujuan Founder.'],
                ],
            ],
            // 5. Unit A-06 (terjual - rumah)
            [
                'id' => 5,
                'unit_id' => 6,
                'hpp_price' => 280000000.00,
                'proposed_price' => 330000000.00,
                'margin' => 50000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Pengajuan harga rumah Type 45/100.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC pembangunan rumah siap huni.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'Spesifikasi material sudah disesuaikan.'],
                ],
            ],
            // 6. Unit A-07 (ditolak)
            [
                'id' => 6,
                'unit_id' => 7,
                'hpp_price' => 150000000.00,
                'proposed_price' => 155000000.00,
                'margin' => 5000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mkt2Id,
                'status' => 'ditolak',
                'notes' => 'Permintaan diskon besar dari konsumen saudara.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'ditolak', 'notes' => 'Margin terlalu tipis (hanya 5 juta). Minimal margin Rp 20 Juta.'],
                ],
            ],
            // 7. Unit B-01 (terjual - rumah Permata Hijau)
            [
                'id' => 7,
                'unit_id' => 9,
                'hpp_price' => 240000000.00,
                'proposed_price' => 290000000.00,
                'margin' => 50000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Paket Rumah Type 36 Cluster Permata Hijau.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC penjualan unit B-01.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'IMB dan fisik siap.'],
                ],
            ],
            // 8. Unit B-02 (disetujui)
            [
                'id' => 8,
                'unit_id' => 10,
                'hpp_price' => 120000000.00,
                'proposed_price' => 145000000.00,
                'margin' => 25000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mkt2Id,
                'status' => 'disetujui',
                'notes' => 'Kavling B-02 skema cicilan 12 bulan.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                ],
            ],
            // 9. Unit B-03 (menunggu_persetujuan)
            [
                'id' => 9,
                'unit_id' => 11,
                'hpp_price' => 144000000.00,
                'proposed_price' => 170000000.00,
                'margin' => 26000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'menunggu',
                'notes' => 'Kavling kelebihan tanah B-03 baru diajukan.',
                'approvals' => [],
            ],
            // 10. Unit C-02 (disetujui)
            [
                'id' => 10,
                'unit_id' => 15,
                'hpp_price' => 204000000.00,
                'proposed_price' => 240000000.00,
                'margin' => 36000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Kavling besar Griya Asri C-02.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'Setuju harga 240jt.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'Setuju.'],
                ],
            ],
            // 11. Unit C-04 (menunggu)
            [
                'id' => 11,
                'unit_id' => 17,
                'hpp_price' => 320000000.00,
                'proposed_price' => 360000000.00,
                'margin' => 40000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mkt2Id,
                'status' => 'menunggu',
                'notes' => 'Rumah Type 54 Griya Asri Residence.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'Founder sudah setuju, tunggu Supervisor.'],
                ],
            ],
            // 12. Unit D-01 (terjual)
            [
                'id' => 12,
                'unit_id' => 18,
                'hpp_price' => 95000000.00,
                'proposed_price' => 115000000.00,
                'margin' => 20000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Penjualan cash keras D-01.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC Cash.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                ],
            ],
            // 13. Unit D-02 (terjual)
            [
                'id' => 13,
                'unit_id' => 19,
                'hpp_price' => 95000000.00,
                'proposed_price' => 115000000.00,
                'margin' => 20000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Penjualan cash bertahap D-02.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                ],
            ],
            // 14. Unit B-06 (disetujui)
            [
                'id' => 14,
                'unit_id' => 24,
                'hpp_price' => 260000000.00,
                'proposed_price' => 310000000.00,
                'margin' => 50000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mkt3Id,
                'status' => 'disetujui',
                'notes' => 'Rumah Type 45/95 Permata Hijau.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC margin bagus.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'Izin pembangunan lengkap.'],
                ],
            ],
            // 15. Unit B-09 (terjual)
            [
                'id' => 15,
                'unit_id' => 27,
                'hpp_price' => 141600000.00,
                'proposed_price' => 170000000.00,
                'margin' => 28400000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mkt2Id,
                'status' => 'disetujui',
                'notes' => 'Kavling B-09 kelebihan tanah.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                ],
            ],
            // 16. Unit C-05 (terjual - 2 Lantai)
            [
                'id' => 16,
                'unit_id' => 29,
                'hpp_price' => 450000000.00,
                'proposed_price' => 540000000.00,
                'margin' => 90000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Rumah 2 Lantai Griya Asri C-05.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC Rumah Mewah C-05.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'Struktur 2 lantai tervalidasi.'],
                ],
            ],
            // 17. Unit C-08 (disetujui)
            [
                'id' => 17,
                'unit_id' => 32,
                'hpp_price' => 214000000.00,
                'proposed_price' => 255000000.00,
                'margin' => 41000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mkt3Id,
                'status' => 'disetujui',
                'notes' => 'Kavling C-08 Hook Griya Asri.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                ],
            ],
            // 18. Unit D-04 (terjual)
            [
                'id' => 18,
                'unit_id' => 34,
                'hpp_price' => 111000000.00,
                'proposed_price' => 135000000.00,
                'margin' => 24000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Kavling Hook Graha Perdana D-04.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                ],
            ],
            // 19. Unit D-05 (terjual)
            [
                'id' => 19,
                'unit_id' => 35,
                'hpp_price' => 175000000.00,
                'proposed_price' => 210000000.00,
                'margin' => 35000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mkt2Id,
                'status' => 'disetujui',
                'notes' => 'Rumah Type 36 Graha Perdana D-05.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                ],
            ],
            // 20. Unit E-01 (terjual - Mutiara Malioboro)
            [
                'id' => 20,
                'unit_id' => 36,
                'hpp_price' => 650000000.00,
                'proposed_price' => 820000000.00,
                'margin' => 170000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'disetujui',
                'notes' => 'Rumah Mewah Malioboro E-01.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC Penjualan Premier.'],
                    ['approver_id' => $supervisorId, 'approver_role' => 'supervisor', 'decision' => 'disetujui', 'notes' => 'Kondisi site siap.'],
                ],
            ],
            // 21. Unit E-02 (disetujui)
            [
                'id' => 21,
                'unit_id' => 37,
                'hpp_price' => 425000000.00,
                'proposed_price' => 520000000.00,
                'margin' => 95000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mkt3Id,
                'status' => 'disetujui',
                'notes' => 'Kavling Prime Hook Malioboro.',
                'approvals' => [
                    ['approver_id' => $founderId, 'approver_role' => 'founder', 'decision' => 'disetujui', 'notes' => 'ACC.'],
                ],
            ],
            // 22. Unit E-03 (menunggu)
            [
                'id' => 22,
                'unit_id' => 38,
                'hpp_price' => 350000000.00,
                'proposed_price' => 410000000.00,
                'margin' => 60000000.00,
                'is_below_hpp' => false,
                'proposed_by' => $mktId,
                'status' => 'menunggu',
                'notes' => 'Kavling E-03 Malioboro pengajuan baru.',
                'approvals' => [],
            ],
        ];

        foreach ($proposalsData as $pData) {
            $approvals = $pData['approvals'];
            unset($pData['approvals']);

            $proposal = PriceProposal::updateOrCreate(['id' => $pData['id']], $pData);

            foreach ($approvals as $appr) {
                Approval::updateOrCreate(
                    [
                        'price_proposal_id' => $proposal->id,
                        'approver_role' => $appr['approver_role'],
                    ],
                    [
                        'approver_id' => $appr['approver_id'],
                        'decision' => $appr['decision'],
                        'notes' => $appr['notes'],
                        'decided_at' => now()->subDays(rand(5, 90)),
                    ]
                );
            }
        }
    }
}
