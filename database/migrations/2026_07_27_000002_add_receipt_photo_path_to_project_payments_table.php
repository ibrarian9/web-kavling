<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_payments', function (Blueprint $table) {
            $table->string('receipt_photo_path')->nullable()->after('notes')->comment('Foto Resi / Bukti Transfer Pembayaran Lahan');
        });
    }

    public function down(): void
    {
        Schema::table('project_payments', function (Blueprint $table) {
            $table->dropColumn('receipt_photo_path');
        });
    }
};
