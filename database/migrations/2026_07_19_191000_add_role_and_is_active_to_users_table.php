<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['founder', 'admin', 'pengawas_project', 'supervisor', 'finance', 'marketing'])->default('marketing')->after('email');
            $table->string('signature_path')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('signature_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
