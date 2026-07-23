<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProjectSeeder::class,
            UnitSeeder::class,
            BookingSeeder::class,
            PriceProposalSeeder::class,
            OfficialDocumentSeeder::class,
            FinancialSeeder::class,
            WorkerSeeder::class,
        ]);
    }
}

