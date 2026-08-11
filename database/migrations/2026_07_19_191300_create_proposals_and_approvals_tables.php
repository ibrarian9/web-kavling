<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->decimal('hpp_price', 15, 2);
            $table->decimal('proposed_price', 15, 2);
            $table->decimal('margin', 15, 2);
            $table->boolean('is_below_hpp')->default(false);
            $table->text('discount_reason')->nullable();
            $table->foreignId('proposed_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_proposal_id')->constrained('price_proposals')->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->enum('approver_role', ['founder', 'admin', 'supervisor', 'pengawas_project']);
            $table->enum('decision', ['disetujui', 'ditolak']);
            $table->text('notes')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });

        Schema::create('official_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('price_proposal_id')->constrained('price_proposals')->onDelete('cascade');
            $table->string('document_number')->unique();
            $table->string('buyer_name');
            $table->string('buyer_contact');
            $table->text('buyer_address')->nullable();
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('issued_at')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('official_documents');
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('price_proposals');
    }
};
