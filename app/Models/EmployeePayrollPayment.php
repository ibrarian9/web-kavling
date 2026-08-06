<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EmployeePayrollPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'employee_salary_id',
        'payroll_month',
        'payroll_year',
        'payment_date',
        'basic_salary',
        'allowance',
        'bonus',
        'deductions',
        'net_salary',
        'payment_method',
        'bank_name',
        'account_number',
        'receipt_photo_path',
        'notes',
        'cashflow_transaction_id',
        'status',
        'paid_at',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'paid_at' => 'datetime',
        'basic_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
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

    public function employeeSalary(): BelongsTo
    {
        return $this->belongsTo(EmployeeSalary::class, 'employee_salary_id');
    }

    public function cashflowTransaction(): BelongsTo
    {
        return $this->belongsTo(CashflowTransaction::class, 'cashflow_transaction_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
