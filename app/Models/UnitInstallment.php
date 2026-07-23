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
        return $this->down_payment + $this->payments()->sum('amount_paid');
    }

    public function getRemainingBalanceAttribute(): float
    {
        return max(0, $this->total_price - $this->total_paid);
    }
}
