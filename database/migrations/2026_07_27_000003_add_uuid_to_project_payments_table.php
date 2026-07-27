<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_payments', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });

        // Generate UUID for existing payments
        $payments = DB::table('project_payments')->whereNull('uuid')->get();
        foreach ($payments as $payment) {
            DB::table('project_payments')
                ->where('id', $payment->id)
                ->update(['uuid' => (string) Str::uuid()]);
        }
    }

    public function down(): void
    {
        Schema::table('project_payments', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
