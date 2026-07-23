<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('code');
            $table->string('type')->default('kavling');
            $table->enum('category', ['kavling', 'rumah'])->default('kavling');
            $table->decimal('land_width', 8, 2)->default(0);
            $table->decimal('land_length', 8, 2)->default(0);
            $table->decimal('land_area', 10, 2)->default(0);
            $table->decimal('building_area', 10, 2)->nullable()->comment('Luas Bangunan m2 khusus Rumah');
            $table->integer('floors_count')->nullable()->default(1)->comment('Jumlah Lantai khusus Rumah');
            $table->text('specifications')->nullable()->comment('Spesifikasi fisik bangunan');
            $table->decimal('excess_land_area', 10, 2)->default(0);
            $table->decimal('excess_cost', 15, 2)->default(0);
            $table->decimal('hpp', 15, 2)->nullable();
            $table->decimal('final_selling_price', 15, 2)->nullable();
            $table->enum('status', [
                'draft',
                'tersedia',
                'booked',
                'menunggu_persetujuan',
                'disetujui',
                'ditolak',
                'terjual',
                'batal'
            ])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
