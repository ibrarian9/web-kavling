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
        'claimant_id',
        'bill_type',
        'purchase_date',
        'item_name',
        'store_name',
        'quantity',
        'unit_measure',
        'unit_price',
        'total_price',
        'payment_status',
        'paid_at',
        'paid_by',
        'notes',
        'receipt_photo_path',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'paid_at' => 'datetime',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
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

    public function claimant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimant_id');
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
