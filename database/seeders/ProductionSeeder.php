<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the production database seeds.
     * Only seeds essential roles and initial user accounts.
     * Omits sample dummy projects, units, proposals, material purchases, worker payrolls, and dummy transactions.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}
