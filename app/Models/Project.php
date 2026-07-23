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
        'created_by',
        'status',
    ];

    protected $casts = [
        'standard_land_area' => 'decimal:2',
        'excess_price_per_sqm' => 'decimal:2',
        'base_price' => 'decimal:2',
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

    public function costs(): HasMany
    {
        return $this->hasMany(UnitCost::class);
    }

    public function cashflows(): HasMany
    {
        return $this->hasMany(CashflowTransaction::class);
    }
}
