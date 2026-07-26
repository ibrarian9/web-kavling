<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_unit_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->decimal('agreed_salary', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->string('payment_frequency')->default('fleksibel');
            $table->enum('status', ['berjalan', 'lunas'])->default('berjalan');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('worker_salary_payments', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('worker_unit_payroll_id')->constrained('worker_unit_payrolls')->onDelete('cascade');
            $table->date('payment_date');
            $table->decimal('amount_gross', 15, 2)->default(0);
            $table->decimal('loan_deduction', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2);
            $table->string('payment_method')->default('Transfer Bank');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('receipt_photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_salary_payments');
        Schema::dropIfExists('worker_unit_payrolls');
    }
};
