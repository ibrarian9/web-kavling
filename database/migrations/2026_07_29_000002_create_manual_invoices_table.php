<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('invoice_number')->unique();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->string('recipient_name');
            $table->string('recipient_phone')->nullable();
            $table->string('recipient_address')->nullable();
            $table->enum('type', ['masuk', 'keluar'])->default('masuk');
            $table->string('category')->default('lain_lain');
            $table->decimal('amount', 15, 2);
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->string('payment_method')->default('Transfer Bank');
            $table->enum('status', ['lunas', 'pending', 'draf'])->default('lunas');
            $table->text('description')->nullable();
            $table->boolean('record_cashflow')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_invoices');
    }
};
