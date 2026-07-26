<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weekly_material_purchases', function (Blueprint $table) {
            $table->string('receipt_photo_path')->nullable()->after('notes')->comment('Path foto resi/nota pembelian barang');
        });
    }

    public function down(): void
    {
        Schema::table('weekly_material_purchases', function (Blueprint $table) {
            $table->dropColumn('receipt_photo_path');
        });
    }
};
