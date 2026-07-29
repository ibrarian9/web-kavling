<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cashflow_transactions', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->change();
            $table->string('category')->change();
        });
    }

    public function down(): void
    {
        Schema::table('cashflow_transactions', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable(false)->change();
        });
    }
};
