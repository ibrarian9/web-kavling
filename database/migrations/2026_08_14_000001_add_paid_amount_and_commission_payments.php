<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('unit_commissions')) {
            Schema::table('unit_commissions', function (Blueprint $table) {
                if (!Schema::hasColumn('unit_commissions', 'paid_amount')) {
                    $table->decimal('paid_amount', 15, 2)->default(0)->after('commission_amount');
                }
            });

            // Alter status column to string if needed to support 'berjalan'
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE unit_commissions MODIFY COLUMN status VARCHAR(50) DEFAULT 'belum_dibayar'");
            } catch (\Throwable $e) {
                // Ignore if DB driver doesn't support direct statement
            }
        }

        if (!Schema::hasTable('unit_commission_payments')) {
            Schema::create('unit_commission_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('unit_commission_id')->constrained('unit_commissions')->onDelete('cascade');
                $table->date('payment_date');
                $table->decimal('amount', 15, 2);
                $table->string('payment_method')->default('Transfer Bank');
                $table->text('notes')->nullable();
                $table->string('receipt_photo_path')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_commission_payments');

        if (Schema::hasTable('unit_commissions')) {
            Schema::table('unit_commissions', function (Blueprint $table) {
                if (Schema::hasColumn('unit_commissions', 'paid_amount')) {
                    $table->dropColumn('paid_amount');
                }
            });
        }
    }
};
