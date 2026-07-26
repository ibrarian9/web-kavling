<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Alter enum columns to varchar(255) for MariaDB/MySQL
        DB::statement("ALTER TABLE units MODIFY category VARCHAR(255) NOT NULL DEFAULT 'kavling'");
        DB::statement("ALTER TABLE units MODIFY status VARCHAR(255) NOT NULL DEFAULT 'tersedia'");
    }

    public function down(): void
    {
        // Revert if needed
    }
};
