<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    public function run(): void
    {
        $pengawas = User::where('role', 'pengawas_project')->first() ?? User::where('role', 'supervisor')->first();
        $projects = Project::all();

        if ($projects->isEmpty()) return;

        $project1 = $projects->first();
        $project2 = $projects->skip(1)->first() ?? $project1;
        $project5 = $projects->skip(4)->first() ?? $project1;

        // 1. Create Workers in Pekanbaru Riau Area
        $workersData = [
            [
                'id' => 1,
                'name' => 'Mandor Sugeng Riau',
                'phone' => '081234567890',
                'address' => 'Jl. Garuda Sakti Km 2, Panam, Tuah Madani, Pekanbaru',
                'type' => 'mandor',
                'specialty' => 'Struktur & Pondasi Batu Kali',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'name' => 'Mandor Tengku Supri',
                'phone' => '081298765432',
                'address' => 'Jl. Paus No. 45, Marpoyan Damai, Pekanbaru',
                'type' => 'mandor',
                'specialty' => 'Finishing, Granit & Cat',
                'status' => 'active',
            ],
            [
                'id' => 3,
                'name' => 'Tukang Syahrul',
                'phone' => '085611223344',
                'address' => 'Desa Tarai Bangun, Tuah Madani, Pekanbaru',
                'type' => 'tukang',
                'specialty' => 'Batu & Pasang Keramik Granit',
                'status' => 'active',
            ],
            [
                'id' => 4,
                'name' => 'Tukang Zulkifli',
                'phone' => '085755667788',
                'address' => 'Jl. Melur No. 12, Sukajadi, Pekanbaru',
                'type' => 'tukang',
                'specialty' => 'Kayu & Rangka Atap Baja Ringan',
                'status' => 'active',
            ],
            [
                'id' => 5,
                'name' => 'Tukang Bejo Panam',
                'phone' => '085899001122',
                'address' => 'Jl. Suka Karya, Tampan, Pekanbaru',
                'type' => 'tukang',
                'specialty' => 'Instalasi Listrik & Plambing Air Riau',
                'status' => 'active',
            ],
            [
                'id' => 6,
                'name' => 'Mandor Datuk Hermansyah',
                'phone' => '081344556677',
                'address' => 'Jl. Yos Sudarso Km 5, Rumbai, Pekanbaru',
                'type' => 'mandor',
                'specialty' => 'Konstruksi Rumah Minimalis 2 Lantai',
                'status' => 'active',
            ],
            [
                'id' => 7,
                'name' => 'Tukang Karsiman Riau',
                'phone' => '087711223344',
                'address' => 'Jl. Kulim, Tenayan Raya, Pekanbaru',
                'type' => 'tukang',
                'specialty' => 'Plester & Acian Halus',
                'status' => 'active',
            ],
            [
                'id' => 8,
                'name' => 'Tukang Parto Payung Sekaki',
                'phone' => '082233445566',
                'address' => 'Jl. Durian, Payung Sekaki, Pekanbaru',
                'type' => 'tukang',
                'specialty' => 'Pemasangan Paving Block & Drainase',
                'status' => 'active',
            ],
        ];

        foreach ($workersData as $data) {
            Worker::updateOrCreate(['id' => $data['id']], $data);
        }

        $mandorSugeng = Worker::find(1);
        $mandorToto = Worker::find(2);
        $tukangJoko = Worker::find(3);
        $mandorSupri = Worker::find(6);

        // 2. Assign Workers to Projects & Units
        $unitA1 = Unit::where('project_id', $project1->id)->first();
        $unitB1 = Unit::where('project_id', $project2->id)->first();
        $unitE1 = Unit::where('project_id', $project5->id)->first();

        WorkerAssignment::updateOrCreate(
            ['id' => 1],
            [
                'worker_id' => $mandorSugeng->id,
                'project_id' => $project1->id,
                'unit_id' => null,
                'assigned_role' => 'Mandor Utama Infrastruktur Panam',
                'start_date' => now()->subMonths(3),
                'status' => 'active',
            ]
        );

        WorkerAssignment::updateOrCreate(
            ['id' => 2],
            [
                'worker_id' => $tukangJoko->id,
                'project_id' => $project1->id,
                'unit_id' => $unitA1?->id,
                'assigned_role' => 'Tukang Pasang Keramik & Finishing',
                'start_date' => now()->subWeeks(4),
                'status' => 'active',
            ]
        );

        WorkerAssignment::updateOrCreate(
            ['id' => 3],
            [
                'worker_id' => $mandorToto->id,
                'project_id' => $project2->id,
                'unit_id' => $unitB1?->id,
                'assigned_role' => 'Mandor Finishing Unit B-01 Marpoyan',
                'start_date' => now()->subWeeks(6),
                'status' => 'active',
            ]
        );

        WorkerAssignment::updateOrCreate(
            ['id' => 4],
            [
                'worker_id' => $mandorSupri->id,
                'project_id' => $project5->id,
                'unit_id' => $unitE1?->id,
                'assigned_role' => 'Mandor Konstruksi 2 Lantai Tenayan',
                'start_date' => now()->subWeeks(3),
                'status' => 'active',
            ]
        );

        // 3. Create Weekly Material Purchases (Pengawas Log)
        WeeklyMaterialPurchase::updateOrCreate(
            ['id' => 1],
            [
                'project_id' => $project1->id,
                'unit_id' => $unitA1?->id,
                'worker_id' => $mandorSugeng->id,
                'pengawas_id' => $pengawas?->id,
                'purchase_date' => now()->subDays(6)->toDateString(),
                'item_name' => 'Semen Padang 50kg',
                'quantity' => 10,
                'unit_measure' => 'sak',
                'unit_price' => 68000,
                'total_price' => 680000,
                'notes' => 'Pembelian semen Padang di Toko Bangunan Riau Jaya Panam',
            ]
        );

        WeeklyMaterialPurchase::updateOrCreate(
            ['id' => 2],
            [
                'project_id' => $project5->id,
                'unit_id' => $unitE1?->id,
                'worker_id' => $mandorSupri->id,
                'pengawas_id' => $pengawas?->id,
                'purchase_date' => now()->subDays(2)->toDateString(),
                'item_name' => 'Besi Beton 12mm Polos SNI',
                'quantity' => 25,
                'unit_measure' => 'batang',
                'unit_price' => 105000,
                'total_price' => 2625000,
                'notes' => 'Besi beton struktur lantai 2 Mutiara Tenayan City',
            ]
        );
    }
}
