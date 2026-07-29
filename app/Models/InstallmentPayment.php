<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallmentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'unit_installment_id',
        'payment_date',
        'amount_paid',
        'payment_method',
        'notes',
        'created_by',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (empty($payment->uuid)) {
                $payment->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function installment(): BelongsTo
    {
        return $this->belongsTo(UnitInstallment::class, 'unit_installment_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
