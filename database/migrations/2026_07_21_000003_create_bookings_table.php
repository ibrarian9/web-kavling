<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('cascade');
            $table->string('buyer_name');
            $table->string('buyer_phone');
            $table->enum('booking_type', ['project', 'unit'])->default('unit');
            $table->decimal('booking_amount', 15, 2)->default(0);
            $table->decimal('dp_amount', 15, 2)->default(0);
            $table->date('booking_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'converted', 'cancelled', 'refunded'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
