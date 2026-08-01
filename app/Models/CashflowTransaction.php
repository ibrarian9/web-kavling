<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashflowTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'type',
        'category',
        'amount',
        'transaction_date',
        'description',
        'receipt_photo_path',
        'reference_type',
        'reference_id',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getReceiptPhotoUrlAttribute(): ?string
    {
        if ($this->receipt_photo_path) {
            return asset('storage/' . $this->receipt_photo_path);
        }

        if ($this->reference_type && $this->reference_id) {
            try {
                $refClass = $this->reference_type;
                if (class_exists($refClass)) {
                    $ref = $refClass::find($this->reference_id);
                    if ($ref && !empty($ref->receipt_photo_path)) {
                        return asset('storage/' . $ref->receipt_photo_path);
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        return null;
    }
}
