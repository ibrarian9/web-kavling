<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyMaterialPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'unit_id',
        'worker_id',
        'pengawas_id',
        'purchase_date',
        'item_name',
        'quantity',
        'unit_measure',
        'unit_price',
        'total_price',
        'is_deducted_from_loan',
        'worker_loan_id',
        'notes',
        'receipt_photo_path',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'is_deducted_from_loan' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function pengawas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }

    public function workerLoan(): BelongsTo
    {
        return $this->belongsTo(WorkerLoan::class);
    }
}
