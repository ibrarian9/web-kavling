<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('worker_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->string('employee_name');
            $table->string('employee_type')->default('staf'); // 'staf' (Users) or 'lapangan' (Workers)
            $table->string('position')->nullable(); // Direktur, Supervisor, Finance, Mandor, Security
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('allowance', 15, 2)->default(0); // Tunjangan Jabatan / Transport / Makan
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0); // BPJS / Kasbon / Potongan
            $table->decimal('net_salary', 15, 2)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('employee_payroll_payments', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('employee_salary_id')->constrained('employee_salaries')->cascadeOnDelete();
            $table->integer('payroll_month');
            $table->integer('payroll_year');
            $table->date('payment_date');
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('allowance', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);
            $table->string('payment_method')->default('transfer'); // 'transfer', 'cash'
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('receipt_photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('cashflow_transaction_id')->nullable()->constrained('cashflow_transactions')->nullOnDelete();
            $table->string('status')->default('dibayar'); // 'draft', 'dibayar'
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_payroll_payments');
        Schema::dropIfExists('employee_salaries');
    }
};
