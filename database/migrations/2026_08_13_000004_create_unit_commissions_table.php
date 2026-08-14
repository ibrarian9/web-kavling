<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('cascade');
            $table->foreignId('marketing_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('seller_name');
            $table->string('seller_phone')->nullable();
            $table->decimal('percentage', 5, 2)->default(0);
            $table->decimal('commission_amount', 15, 2);
            $table->enum('status', ['belum_dibayar', 'lunas'])->default('belum_dibayar');
            $table->dateTime('paid_at')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('receipt_photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_commissions');
    }
};
