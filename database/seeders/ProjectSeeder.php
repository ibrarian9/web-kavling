<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $founder = User::where('role', 'founder')->first() ?? User::first();
        $createdById = $founder?->id;

        $projects = [
            [
                'id' => 1,
                'name' => 'Grand Kavling Panam Residence',
                'location' => 'Panam / Tuah Madani, Kota Pekanbaru, Riau',
                'standard_land_area' => 100.00,
                'excess_price_per_sqm' => 1350000.00,
                'base_price' => 135000000.00,
                'created_by' => $createdById,
                'status' => 'aktif',
            ],
            [
                'id' => 2,
                'name' => 'Cluster Permata Arifin Ahmad',
                'location' => 'Marpoyan Damai, Kota Pekanbaru, Riau',
                'standard_land_area' => 120.00,
                'excess_price_per_sqm' => 1850000.00,
                'base_price' => 225000000.00,
                'created_by' => $createdById,
                'status' => 'aktif',
            ],
            [
                'id' => 3,
                'name' => 'Griya Asri Payung Sekaki',
                'location' => 'Payung Sekaki, Kota Pekanbaru, Riau',
                'standard_land_area' => 90.00,
                'excess_price_per_sqm' => 1150000.00,
                'base_price' => 115000000.00,
                'created_by' => $createdById,
                'status' => 'aktif',
            ],
            [
                'id' => 4,
                'name' => 'Kavling Graha Rumbai Asri',
                'location' => 'Rumbai Barat, Kota Pekanbaru, Riau',
                'standard_land_area' => 108.00,
                'excess_price_per_sqm' => 900000.00,
                'base_price' => 98000000.00,
                'created_by' => $createdById,
                'status' => 'selesai',
            ],
            [
                'id' => 5,
                'name' => 'Cluster Mutiara Tenayan City',
                'location' => 'Tenayan Raya, Kota Pekanbaru, Riau',
                'standard_land_area' => 150.00,
                'excess_price_per_sqm' => 2200000.00,
                'base_price' => 280000000.00,
                'created_by' => $createdById,
                'status' => 'aktif',
            ],
        ];

        foreach ($projects as $proj) {
            Project::updateOrCreate(['id' => $proj['id']], $proj);
        }

        // Assign seeded Pengawas Project users to initial projects
        $pengawasUsers = User::where('role', 'pengawas_project')->get();
        foreach ($pengawasUsers as $pengawas) {
            foreach ([1, 2] as $pId) {
                \App\Models\WorkerAssignment::updateOrCreate(
                    ['user_id' => $pengawas->id, 'project_id' => $pId],
                    [
                        'assigned_role' => 'Pengawas Lapangan Proyek',
                        'status' => 'active',
                        'start_date' => now()->toDateString(),
                    ]
                );
            }
        }
    }
}
