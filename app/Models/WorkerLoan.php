<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkerLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'project_id',
        'unit_id',
        'loan_date',
        'amount',
        'paid_amount',
        'purpose',
        'status',
        'approved_by',
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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(WorkerLoanPayment::class);
    }

    public function getRemainingBalanceAttribute(): float
    {
        return max(0, $this->amount - $this->paid_amount);
    }
}
