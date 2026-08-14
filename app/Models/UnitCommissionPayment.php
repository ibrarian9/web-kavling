<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class UnitCommissionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_commission_id',
        'payment_date',
        'amount',
        'payment_method',
        'notes',
        'receipt_photo_path',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function commission(): BelongsTo
    {
        return $this->belongsTo(UnitCommission::class, 'unit_commission_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cashflowTransaction(): MorphOne
    {
        return $this->morphOne(CashflowTransaction::class, 'reference');
    }

    public function getReceiptPhotoUrlAttribute(): ?string
    {
        return $this->receipt_photo_path ? asset('storage/' . $this->receipt_photo_path) : null;
    }
}
