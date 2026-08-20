<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalProjectWorkerWage extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_project_id',
        'worker_name',
        'role_type',
        'wage_type',
        'amount',
        'payment_date',
        'receipt_photo',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function externalProject(): BelongsTo
    {
        return $this->belongsTo(ExternalProject::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
