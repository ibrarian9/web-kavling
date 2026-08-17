<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'official_document_id',
        'total_price',
        'down_payment',
        'installment_count',
        'installment_amount',
        'start_date',
        'status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'start_date' => 'date',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function officialDocument(): BelongsTo
    {
        return $this->belongsTo(OfficialDocument::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InstallmentPayment::class);
    }

    public function getTotalPaidAttribute(): float
    {
        $paid = $this->relationLoaded('payments') 
            ? (float) $this->payments->sum('amount_paid') 
            : (float) $this->payments()->sum('amount_paid');

        return (float) $this->down_payment + $paid;
    }

    public function getRemainingBalanceAttribute(): float
    {
        return max(0, (float) $this->total_price - $this->total_paid);
    }

    public function getBuyerNameAttribute(): string
    {
        if ($this->relationLoaded('officialDocument') && $this->officialDocument?->buyer_name) {
            return $this->officialDocument->buyer_name;
        }

        if ($this->relationLoaded('unit') && $this->unit) {
            return $this->unit->buyer_name;
        }

        return $this->officialDocument?->buyer_name 
            ?? $this->unit?->buyer_name 
            ?? 'Konsumen Pembeli';
    }

    public function getBuyerPhoneAttribute(): ?string
    {
        if ($this->relationLoaded('officialDocument') && $this->officialDocument?->buyer_contact) {
            return $this->officialDocument->buyer_contact;
        }

        if ($this->relationLoaded('unit') && $this->unit) {
            return $this->unit->buyer_phone;
        }

        return $this->officialDocument?->buyer_contact 
            ?? $this->unit?->buyer_phone;
    }
}
