<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_receivables', function (Blueprint $table) {
            $table->id();
            $table->enum('debtor_type', ['worker', 'user', 'other'])->default('other');
            $table->string('debtor_name');
            $table->foreignId('worker_id')->nullable()->constrained('workers')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->date('loan_date');
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('receivable_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_receivable_id')->constrained('company_receivables')->onDelete('cascade');
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->default('Cash / Tunai');
            $table->text('notes')->nullable();
            $table->string('receipt_photo_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receivable_payments');
        Schema::dropIfExists('company_receivables');
    }
};
