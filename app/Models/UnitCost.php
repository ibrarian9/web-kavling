<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'project_id',
        'category',
        'description',
        'amount',
        'cost_date',
        'vendor_name',
        'status',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cost_date' => 'date',
    ];

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
}
