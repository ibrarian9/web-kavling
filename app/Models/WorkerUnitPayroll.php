<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkerUnitPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'project_id',
        'unit_id',
        'agreed_salary',
        'paid_amount',
        'payment_frequency',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'agreed_salary' => 'decimal:2',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(WorkerSalaryPayment::class);
    }

    public function getRemainingSalaryAttribute(): float
    {
        return max(0, (float)$this->agreed_salary - (float)$this->paid_amount);
    }

    public function getProgressPercentageAttribute(): float
    {
        if ((float)$this->agreed_salary <= 0) {
            return 0;
        }
        return min(100, round(((float)$this->paid_amount / (float)$this->agreed_salary) * 100, 1));
    }
}
