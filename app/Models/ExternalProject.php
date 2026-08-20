<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExternalProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_name',
        'client_phone',
        'location',
        'contract_value',
        'status',
        'start_date',
        'end_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'contract_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ExternalProjectMaterial::class);
    }

    public function workerWages(): HasMany
    {
        return $this->hasMany(ExternalProjectWorkerWage::class);
    }

    public function getTotalMaterialCostAttribute(): float
    {
        return (float) $this->materials()->sum('total_price');
    }

    public function getTotalWageCostAttribute(): float
    {
        return (float) $this->workerWages()->sum('amount');
    }

    public function getTotalExpensesAttribute(): float
    {
        return $this->total_material_cost + $this->total_wage_cost;
    }

    public function getMarginOrBalanceAttribute(): float
    {
        return (float) $this->contract_value - $this->total_expenses;
    }
}
