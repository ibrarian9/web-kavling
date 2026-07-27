<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('worker_assignments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('worker_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('worker_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('worker_assignments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->foreignId('worker_id')->nullable(false)->change();
        });
    }
};
