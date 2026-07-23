<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->date('loan_date');
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->string('purpose')->nullable()->comment('Keperluan pinjaman / kas bon / transaksi barang');
            $table->enum('status', ['pending', 'approved', 'partially_paid', 'paid'])->default('approved');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('worker_loan_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_loan_id')->constrained('worker_loans')->onDelete('cascade');
            $table->date('payment_date');
            $table->decimal('amount_paid', 15, 2);
            $table->string('payment_method')->default('potong_opname');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('weekly_material_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('cascade');
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->foreignId('pengawas_id')->constrained('users')->onDelete('cascade');
            $table->date('purchase_date');
            $table->string('item_name');
            $table->decimal('quantity', 10, 2);
            $table->string('unit_measure')->default('pcs');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->boolean('is_deducted_from_loan')->default(true)->comment('Opsi 1: Otomatis memotong/menjadi piutang worker');
            $table->foreignId('worker_loan_id')->nullable()->constrained('worker_loans')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_material_purchases');
        Schema::dropIfExists('worker_loan_payments');
        Schema::dropIfExists('worker_loans');
    }
};
