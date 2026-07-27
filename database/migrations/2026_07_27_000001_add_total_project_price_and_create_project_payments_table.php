<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('total_project_price', 15, 2)->nullable()->default(0)->after('base_price')->comment('Nilai / Target Harga Keseluruhan Proyek');
        });

        Schema::create('project_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->date('payment_date');
            $table->decimal('amount_paid', 15, 2);
            $table->string('payment_method')->default('Transfer Bank');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_payments');
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('total_project_price');
        });
    }
};
