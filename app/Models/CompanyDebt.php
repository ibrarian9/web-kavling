<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyDebt extends Model
{
    use HasFactory;

    protected $fillable = [
        'creditor_type',
        'creditor_name',
        'creditor_phone',
        'project_id',
        'title',
        'amount',
        'paid_amount',
        'debt_date',
        'due_date',
        'status',
        'notes',
        'attachment_path',
        'created_by',
    ];

    protected $casts = [
        'debt_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CompanyDebtPayment::class, 'company_debt_id');
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float)$this->amount - (float)$this->paid_amount);
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path ? asset('storage/' . $this->attachment_path) : null;
    }

    public function getCreditorTypeLabelAttribute(): string
    {
        return match ($this->creditor_type) {
            'subkon' => 'Sub-kontraktor',
            'vendor' => 'Vendor / Supplier',
            'operasional_luar' => 'Operasional Luar',
            'talangan_modal' => 'Talangan Modal',
            default => 'Lain-lain',
        };
    }
}
