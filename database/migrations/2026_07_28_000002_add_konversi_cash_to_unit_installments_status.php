<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE unit_installments MODIFY COLUMN status ENUM('berjalan', 'lunas', 'menunggak', 'konversi_cash') NOT NULL DEFAULT 'berjalan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE unit_installments MODIFY COLUMN status ENUM('berjalan', 'lunas', 'menunggak') NOT NULL DEFAULT 'berjalan'");
    }
};
