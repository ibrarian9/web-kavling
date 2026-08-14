<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'unit_id',
        'marketing_id',
        'seller_name',
        'seller_phone',
        'percentage',
        'commission_amount',
        'paid_amount',
        'status',
        'paid_at',
        'paid_by',
        'receipt_photo_path',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'percentage' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function marketing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marketing_id');
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(UnitCommissionPayment::class, 'unit_commission_id')->latest('payment_date')->latest('id');
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float)$this->commission_amount - (float)($this->paid_amount ?? 0));
    }

    public function recalculateStatus(): void
    {
        $totalPaid = (float) $this->payments()->sum('amount');
        $status = 'belum_dibayar';
        if ($totalPaid >= (float) $this->commission_amount) {
            $status = 'lunas';
        } elseif ($totalPaid > 0) {
            $status = 'berjalan';
        }

        $this->update([
            'paid_amount' => $totalPaid,
            'status' => $status,
            'paid_at' => $status === 'lunas' ? ($this->paid_at ?? now()) : null,
        ]);
    }
}
