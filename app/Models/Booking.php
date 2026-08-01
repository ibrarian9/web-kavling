<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'unit_id',
        'buyer_name',
        'buyer_phone',
        'booking_type',
        'booking_amount',
        'dp_amount',
        'booking_date',
        'expiry_date',
        'status',
        'notes',
        'receipt_photo_path',
        'created_by',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'expiry_date' => 'date',
        'booking_amount' => 'decimal:2',
        'dp_amount' => 'decimal:2',
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
