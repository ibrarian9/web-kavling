<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyActivityReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'unit_id',
        'report_date',
        'client_name',
        'client_phone',
        'lead_source',
        'interaction_type',
        'lead_stage',
        'payment_type',
        'deal_amount',
        'notes',
        'follow_up_date',
    ];

    protected $casts = [
        'report_date' => 'date',
        'follow_up_date' => 'date',
        'deal_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public static function leadSources(): array
    {
        return [
            'facebook_ads' => 'Facebook Ads',
            'instagram' => 'Instagram',
            'tiktok' => 'TikTok',
            'banner_brosur' => 'Banner / Brosur',
            'canvassing' => 'Canvassing Lapangan',
            'walk_in' => 'Walk-in Lokasi',
            'referral' => 'Referensi Teman',
            'whatsapp' => 'WhatsApp Direct',
            'lainnya' => 'Lainnya',
        ];
    }

    public static function interactionTypes(): array
    {
        return [
            'chat_wa' => 'Chat WA / Follow Up',
            'telepon' => 'Telepon / Call',
            'survey_lokasi' => 'Survey Lokasi / Site Visit',
            'presentasi' => 'Presentasi Proposal',
            'booking_dp' => 'Booking / DP Unit',
            'cash_lunas' => 'Pembelian Cash Lunas',
        ];
    }

    public static function leadStages(): array
    {
        return [
            'cold' => 'Cold Prospect',
            'warm' => 'Warm Prospect',
            'hot_deal' => 'Hot Deal',
            'booking' => 'Booking Fee / DP',
            'cash_lunas' => 'Cash Lunas',
            'batal' => 'Batal / Not Interested',
        ];
    }

    public function getLeadSourceLabelAttribute(): string
    {
        return self::leadSources()[$this->lead_source] ?? ucfirst($this->lead_source);
    }

    public function getInteractionTypeLabelAttribute(): string
    {
        return self::interactionTypes()[$this->interaction_type] ?? ucfirst($this->interaction_type);
    }

    public function getLeadStageLabelAttribute(): string
    {
        return self::leadStages()[$this->lead_stage] ?? ucfirst($this->lead_stage);
    }

    public function getStageBadgeClassAttribute(): string
    {
        return match ($this->lead_stage) {
            'hot_deal' => 'bg-amber-100 text-amber-800 border border-amber-300 font-extrabold',
            'booking' => 'bg-teal-100 text-teal-800 border border-teal-300 font-extrabold',
            'cash_lunas' => 'bg-emerald-100 text-emerald-800 border border-emerald-300 font-extrabold',
            'warm' => 'bg-blue-100 text-blue-800 border border-blue-200 font-bold',
            'batal' => 'bg-rose-100 text-rose-800 border border-rose-200 font-bold',
            default => 'bg-slate-100 text-slate-700 border border-slate-200 font-medium',
        };
    }
}
