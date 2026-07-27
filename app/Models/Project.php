<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'standard_land_area',
        'excess_price_per_sqm',
        'base_price',
        'total_project_price',
        'created_by',
        'status',
    ];

    protected $casts = [
        'standard_land_area' => 'decimal:2',
        'excess_price_per_sqm' => 'decimal:2',
        'base_price' => 'decimal:2',
        'total_project_price' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(WorkerAssignment::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function cashflows(): HasMany
    {
        return $this->hasMany(CashflowTransaction::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ProjectPayment::class);
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount_paid');
    }

    public function getRemainingBalanceAttribute(): float
    {
        return max(0, (float) $this->total_project_price - $this->total_paid);
    }

    public function getPaymentProgressPercentageAttribute(): float
    {
        if ((float) $this->total_project_price <= 0) {
            return 0;
        }
        return min(100, round(($this->total_paid / (float) $this->total_project_price) * 100, 1));
    }
}
