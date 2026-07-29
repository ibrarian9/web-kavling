<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE workers MODIFY COLUMN type ENUM('mandor', 'tukang', 'kontraktor') NOT NULL DEFAULT 'tukang'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE workers MODIFY COLUMN type ENUM('mandor', 'tukang') NOT NULL DEFAULT 'tukang'");
    }
};
