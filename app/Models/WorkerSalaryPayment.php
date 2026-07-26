<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WorkerSalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'worker_unit_payroll_id',
        'payment_date',
        'amount_gross',
        'loan_deduction',
        'amount_paid',
        'payment_method',
        'bank_name',
        'account_number',
        'receipt_photo_path',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_gross' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(WorkerUnitPayroll::class, 'worker_unit_payroll_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
