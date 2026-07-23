<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->decimal('standard_land_area', 10, 2)->comment('Luas standar kavling dalam m2');
            $table->decimal('excess_price_per_sqm', 15, 2)->comment('Harga per m2 kelebihan tanah');
            $table->decimal('base_price', 15, 2)->comment('Harga dasar kavling standar');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['aktif', 'selesai', 'ditutup'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
