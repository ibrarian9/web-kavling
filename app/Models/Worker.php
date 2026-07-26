<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'type',
        'specialty',
        'status',
        'notes',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(WorkerAssignment::class);
    }

    public function activeAssignments(): HasMany
    {
        return $this->hasMany(WorkerAssignment::class)->where('status', 'active');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(WorkerLoan::class);
    }

    public function weeklyPurchases(): HasMany
    {
        return $this->hasMany(WeeklyMaterialPurchase::class);
    }

    public function unitPayrolls(): HasMany
    {
        return $this->hasMany(WorkerUnitPayroll::class);
    }

    public function getTotalLoanAttribute(): float
    {
        return $this->loans()->sum('amount');
    }

    public function getTotalPaidLoanAttribute(): float
    {
        return $this->loans()->sum('paid_amount');
    }

    public function getRemainingLoanAttribute(): float
    {
        return max(0, $this->total_loan - $this->total_paid_loan);
    }
}
