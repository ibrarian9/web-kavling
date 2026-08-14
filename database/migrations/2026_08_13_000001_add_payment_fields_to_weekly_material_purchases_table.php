<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weekly_material_purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('weekly_material_purchases', 'store_name')) {
                $table->string('store_name')->nullable()->after('item_name');
            }
            if (!Schema::hasColumn('weekly_material_purchases', 'payment_status')) {
                $table->enum('payment_status', ['lunas', 'belum_lunas'])->default('lunas')->after('total_price');
            }
            if (!Schema::hasColumn('weekly_material_purchases', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('weekly_material_purchases', 'paid_by')) {
                $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null')->after('paid_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('weekly_material_purchases', function (Blueprint $table) {
            $table->dropForeign(['paid_by']);
            $table->dropColumn(['store_name', 'payment_status', 'paid_at', 'paid_by']);
        });
    }
};
