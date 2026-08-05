<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('official_documents', function (Blueprint $table) {
            $table->string('buyer_nik')->nullable()->after('buyer_name');
            $table->string('seller_name')->nullable()->after('buyer_address');
            $table->string('seller_nik')->nullable()->after('seller_name');
            $table->string('seller_position')->nullable()->after('seller_nik');
            $table->text('seller_address')->nullable()->after('seller_position');
        });
    }

    public function down(): void
    {
        Schema::table('official_documents', function (Blueprint $table) {
            $table->dropColumn(['buyer_nik', 'seller_name', 'seller_nik', 'seller_position', 'seller_address']);
        });
    }
};
