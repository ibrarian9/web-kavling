<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'hpp_price',
        'proposed_price',
        'margin',
        'is_below_hpp',
        'discount_reason',
        'proposed_by',
        'status',
        'notes',
    ];

    protected $casts = [
        'hpp_price' => 'decimal:2',
        'proposed_price' => 'decimal:2',
        'margin' => 'decimal:2',
        'is_below_hpp' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function proposer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proposed_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    /**
     * Cek apakah pengajuan sudah disetujui penuh (Persetujuan Founder cukup untuk langsung mengesahkan SPP)
     */
    public function isFullyApproved(): bool
    {
        $founderApproved = $this->approvals()->where('approver_role', 'founder')->where('decision', 'disetujui')->exists();
        $supervisorApproved = $this->approvals()->where('approver_role', 'supervisor')->where('decision', 'disetujui')->exists();

        return $founderApproved || ($founderApproved && $supervisorApproved);
    }

    /**
     * Cek apakah salah satu pihak menolak
     */
    public function isRejected(): bool
    {
        return $this->approvals()->where('decision', 'ditolak')->exists();
    }
}
