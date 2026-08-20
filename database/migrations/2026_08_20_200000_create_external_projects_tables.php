<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('external_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('location')->nullable();
            $table->decimal('contract_value', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'selesai', 'tertunda'])->default('aktif');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('external_project_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('external_project_id')->constrained('external_projects')->cascadeOnDelete();
            $table->string('item_name');
            $table->string('supplier')->nullable();
            $table->decimal('quantity', 12, 2)->default(1);
            $table->string('unit')->default('pcs');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->date('purchase_date');
            $table->string('receipt_photo')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('external_project_worker_wages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('external_project_id')->constrained('external_projects')->cascadeOnDelete();
            $table->string('worker_name');
            $table->string('role_type')->default('tukang'); // mandor, tukang, kenek, dll.
            $table->string('wage_type')->default('harian'); // harian, mingguan, borongan
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('payment_date');
            $table->string('receipt_photo')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_project_worker_wages');
        Schema::dropIfExists('external_project_materials');
        Schema::dropIfExists('external_projects');
    }
};
