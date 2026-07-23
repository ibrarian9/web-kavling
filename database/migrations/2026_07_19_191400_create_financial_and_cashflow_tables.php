<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->enum('category', ['tukang', 'material', 'perizinan', 'lainnya']);
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->date('cost_date');
            $table->string('vendor_name')->nullable();
            $table->enum('status', ['belum_dibayar', 'dibayar'])->default('belum_dibayar');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('unit_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('official_document_id')->nullable()->constrained('official_documents')->onDelete('set null');
            $table->decimal('total_price', 15, 2);
            $table->decimal('down_payment', 15, 2)->default(0);
            $table->integer('installment_count')->default(1);
            $table->decimal('installment_amount', 15, 2);
            $table->date('start_date');
            $table->enum('status', ['berjalan', 'lunas', 'menunggak'])->default('berjalan');
            $table->timestamps();
        });

        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_installment_id')->constrained('unit_installments')->onDelete('cascade');
            $table->date('payment_date');
            $table->decimal('amount_paid', 15, 2);
            $table->string('payment_method')->default('Transfer Bank');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cashflow_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->enum('type', ['masuk', 'keluar']);
            $table->enum('category', [
                'penjualan_unit',
                'pembayaran_cicilan_pembeli',
                'pembayaran_tukang',
                'operasional',
                'lainnya'
            ]);
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('description');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashflow_transactions');
        Schema::dropIfExists('installment_payments');
        Schema::dropIfExists('unit_installments');
        Schema::dropIfExists('unit_costs');
    }
};
