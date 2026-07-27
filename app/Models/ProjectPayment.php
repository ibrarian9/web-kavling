<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'project_id',
        'payment_date',
        'amount_paid',
        'payment_method',
        'notes',
        'receipt_photo_path',
        'created_by',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (empty($payment->uuid)) {
                $payment->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function getReceiptPhotoUrlAttribute(): ?string
    {
        return $this->receipt_photo_path ? asset('storage/' . $this->receipt_photo_path) : null;
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
