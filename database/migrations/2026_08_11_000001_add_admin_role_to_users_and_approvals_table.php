<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify enum role on users table if MariaDB/MySQL
        if (DB::getDriverName() === 'mysql' || DB::getDriverName() === 'mariadb') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('founder', 'admin', 'pengawas_project', 'supervisor', 'finance', 'marketing') DEFAULT 'marketing'");
            DB::statement("ALTER TABLE approvals MODIFY COLUMN approver_role ENUM('founder', 'admin', 'supervisor', 'pengawas_project')");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql' || DB::getDriverName() === 'mariadb') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('founder', 'pengawas_project', 'supervisor', 'finance', 'marketing') DEFAULT 'marketing'");
            DB::statement("ALTER TABLE approvals MODIFY COLUMN approver_role ENUM('founder', 'supervisor', 'pengawas_project')");
        }
    }
};
