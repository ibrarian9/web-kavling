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
        Schema::create('daily_activity_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->date('report_date');
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('lead_source')->default('whatsapp'); // facebook_ads, instagram, tiktok, banner_brosur, canvassing, walk_in, referral, whatsapp, lainnya
            $table->string('interaction_type')->default('chat_wa'); // chat_wa, telepon, survey_lokasi, presentasi, booking_dp, cash_lunas
            $table->string('lead_stage')->default('cold'); // cold, warm, hot_deal, booking, cash_lunas, batal
            $table->string('payment_type')->default('tanpa_dp'); // tanpa_dp, dp_booking, cash_bertahap, cash_lunas
            $table->decimal('deal_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_activity_reports');
    }
};
