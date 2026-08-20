<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalProjectMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_project_id',
        'item_name',
        'supplier',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'purchase_date',
        'receipt_photo',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'purchase_date' => 'date',
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
