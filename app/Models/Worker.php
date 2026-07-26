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

    public function weeklyPurchases(): HasMany
    {
        return $this->hasMany(WeeklyMaterialPurchase::class);
    }

    public function unitPayrolls(): HasMany
    {
        return $this->hasMany(WorkerUnitPayroll::class);
    }
}
