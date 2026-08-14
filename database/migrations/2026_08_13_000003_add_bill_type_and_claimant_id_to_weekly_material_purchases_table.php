<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weekly_material_purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('weekly_material_purchases', 'bill_type')) {
                $table->enum('bill_type', ['material_toko', 'operational_vendor', 'employee_reimbursement'])->default('material_toko')->after('store_name');
            }
            if (!Schema::hasColumn('weekly_material_purchases', 'claimant_id')) {
                $table->foreignId('claimant_id')->nullable()->constrained('users')->onDelete('set null')->after('pengawas_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('weekly_material_purchases', function (Blueprint $table) {
            $table->dropForeign(['claimant_id']);
            $table->dropColumn(['bill_type', 'claimant_id']);
        });
    }
};
