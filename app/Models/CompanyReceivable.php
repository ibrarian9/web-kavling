<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyReceivable extends Model
{
    use HasFactory;

    protected $fillable = [
        'debtor_type',
        'debtor_name',
        'worker_id',
        'user_id',
        'amount',
        'paid_amount',
        'loan_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ReceivablePayment::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float)$this->amount - (float)$this->paid_amount);
    }
}
