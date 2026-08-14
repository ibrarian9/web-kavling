<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivablePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_receivable_id',
        'payment_date',
        'amount',
        'payment_method',
        'notes',
        'receipt_photo_path',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function receivable(): BelongsTo
    {
        return $this->belongsTo(CompanyReceivable::class, 'company_receivable_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
