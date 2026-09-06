<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_debts', function (Blueprint $table) {
            $table->id();
            $table->enum('creditor_type', ['subkon', 'vendor', 'operasional_luar', 'talangan_modal', 'lainnya'])->default('subkon');
            $table->string('creditor_name');
            $table->string('creditor_phone')->nullable();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->date('debt_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('company_debt_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_debt_id')->constrained('company_debts')->onDelete('cascade');
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->default('Transfer Bank');
            $table->text('notes')->nullable();
            $table->string('receipt_photo_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_debt_payments');
        Schema::dropIfExists('company_debts');
    }
};
