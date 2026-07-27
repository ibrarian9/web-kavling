<?php

namespace Database\Seeders;

use App\Models\OfficialDocument;
use App\Models\User;
use Illuminate\Database\Seeder;

class OfficialDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $marketing = User::where('role', 'marketing')->first() ?? User::first();
        $marketing2 = $marketing;
        $marketing3 = $marketing;

        $mktId = $marketing ? $marketing->id : 4;
        $mkt2Id = $marketing2 ? $marketing2->id : 5;
        $mkt3Id = $marketing3 ? $marketing3->id : 6;

        $docsData = [
            [
                'id' => 1,
                'unit_id' => 1, // A-01
                'price_proposal_id' => 1,
                'document_number' => 'SPP/GRAND PANAM/2026/05/001',
                'buyer_name' => 'Bpk. Dr. H. Hendra Wijaya, M.Si.',
                'buyer_contact' => '081234567890',
                'buyer_address' => 'Jl. HR. Soebrantas Panam No. 88, Tuah Madani, Pekanbaru, Riau',
                'issued_by' => $mktId,
                'issued_at' => now()->subDays(90),
                'file_path' => null,
            ],
            [
                'id' => 2,
                'unit_id' => 2, // A-02
                'price_proposal_id' => 2,
                'document_number' => 'SPP/GRAND PANAM/2026/05/002',
                'buyer_name' => 'Ibu Hj. Syarifah Nurhaliza',
                'buyer_contact' => '085678901234',
                'buyer_address' => 'Jl. Arifin Ahmad No. 12B, Marpoyan Damai, Pekanbaru, Riau',
                'issued_by' => $mktId,
                'issued_at' => now()->subDays(85),
                'file_path' => null,
            ],
            [
                'id' => 3,
                'unit_id' => 6, // A-06
                'price_proposal_id' => 5,
                'document_number' => 'SPP/GRAND PANAM/2026/06/003',
                'buyer_name' => 'Bpk. Muhammad Al-Ghazali',
                'buyer_contact' => '087890123456',
                'buyer_address' => 'Jl. Tuanku Tambusai / Nangka No. 45, Sukajadi, Pekanbaru',
                'issued_by' => $mkt2Id,
                'issued_at' => now()->subDays(60),
                'file_path' => null,
            ],
            [
                'id' => 4,
                'unit_id' => 9, // B-01
                'price_proposal_id' => 7,
                'document_number' => 'SPP/PERMATA ARIFIN AHMAD/2026/04/001',
                'buyer_name' => 'Ibu Rahmi Laila, S.Pd.',
                'buyer_contact' => '082134567890',
                'buyer_address' => 'Jl. Soekarno-Hatta / Arengka No. 102, Sidomulyo Timur, Pekanbaru',
                'issued_by' => $mkt2Id,
                'issued_at' => now()->subDays(120),
                'file_path' => null,
            ],
            [
                'id' => 5,
                'unit_id' => 18, // D-01
                'price_proposal_id' => 12,
                'document_number' => 'SPP/GRAHA RUMBAI ASRI/2026/03/001',
                'buyer_name' => 'Bpk. Capt. Tengku Rizal',
                'buyer_contact' => '081398765432',
                'buyer_address' => 'Jl. Yos Sudarso No. 14, Rumbai Barat, Pekanbaru',
                'issued_by' => $mktId,
                'issued_at' => now()->subDays(150),
                'file_path' => null,
            ],
            [
                'id' => 6,
                'unit_id' => 19, // D-02
                'price_proposal_id' => 13,
                'document_number' => 'SPP/GRAHA RUMBAI ASRI/2026/03/002',
                'buyer_name' => 'Bpk. Agus Syafruddin',
                'buyer_contact' => '081122334455',
                'buyer_address' => 'Jl. Sekolah No. 8, Rumbai Pesisir, Pekanbaru',
                'issued_by' => $mktId,
                'issued_at' => now()->subDays(145),
                'file_path' => null,
            ],
            [
                'id' => 7,
                'unit_id' => 27, // B-09
                'price_proposal_id' => 15,
                'document_number' => 'SPP/PERMATA ARIFIN AHMAD/2026/05/002',
                'buyer_name' => 'Drs. H. Supriyadi, M.Si.',
                'buyer_contact' => '081566778899',
                'buyer_address' => 'Jl. Rambutan No. 77, Marpoyan Damai, Pekanbaru',
                'issued_by' => $mkt2Id,
                'issued_at' => now()->subDays(70),
                'file_path' => null,
            ],
            [
                'id' => 8,
                'unit_id' => 29, // C-05
                'price_proposal_id' => 16,
                'document_number' => 'SPP/GRIYA PAYUNG SEKAKI/2026/06/001',
                'buyer_name' => 'Bpk. Datuk Riko Sukaesih',
                'buyer_contact' => '081900112233',
                'buyer_address' => 'Jl. Riau No. 120, Payung Sekaki, Pekanbaru',
                'issued_by' => $mktId,
                'issued_at' => now()->subDays(40),
                'file_path' => null,
            ],
            [
                'id' => 9,
                'unit_id' => 34, // D-04
                'price_proposal_id' => 18,
                'document_number' => 'SPP/GRAHA RUMBAI ASRI/2026/04/003',
                'buyer_name' => 'Ibu Ningsih Utami Melayu',
                'buyer_contact' => '085233445566',
                'buyer_address' => 'Jl. Sembiring No. 3, Rumbai, Pekanbaru',
                'issued_by' => $mkt3Id,
                'issued_at' => now()->subDays(110),
                'file_path' => null,
            ],
            [
                'id' => 10,
                'unit_id' => 35, // D-05
                'price_proposal_id' => 19,
                'document_number' => 'SPP/GRAHA RUMBAI ASRI/2026/04/004',
                'buyer_name' => 'Bpk. Tri Wibowo Riau',
                'buyer_contact' => '087788990011',
                'buyer_address' => 'Jl. Palas No. 12, Rumbai Barat, Pekanbaru',
                'issued_by' => $mkt2Id,
                'issued_at' => now()->subDays(105),
                'file_path' => null,
            ],
            [
                'id' => 11,
                'unit_id' => 36, // E-01
                'price_proposal_id' => 20,
                'document_number' => 'SPP/MUTIARA TENAYAN CITY/2026/06/001',
                'buyer_name' => 'Ir. H. Gunawan Wibisono',
                'buyer_contact' => '081199001122',
                'buyer_address' => 'Jl. Badak Komplek Pemko Pekanbaru No. 25, Tenayan Raya, Pekanbaru',
                'issued_by' => $mktId,
                'issued_at' => now()->subDays(30),
                'file_path' => null,
            ],
        ];

        foreach ($docsData as $dData) {
            OfficialDocument::updateOrCreate(['id' => $dData['id']], $dData);
        }
    }
}
