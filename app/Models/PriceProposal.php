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

    public function officialDocument(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OfficialDocument::class, 'price_proposal_id');
    }

    /**
     * Cek apakah pengajuan sudah disetujui penuh (Persetujuan Founder cukup untuk langsung mengesahkan SPP)
     */
    public function isFullyApproved(): bool
    {
        if ($this->relationLoaded('approvals')) {
            $founderApproved = $this->approvals->whereIn('approver_role', ['founder', 'admin'])->where('decision', 'disetujui')->isNotEmpty();
            $supervisorApproved = $this->approvals->where('approver_role', 'supervisor')->where('decision', 'disetujui')->isNotEmpty();
            return $founderApproved && $supervisorApproved;
        }

        $founderApproved = $this->approvals()->whereIn('approver_role', ['founder', 'admin'])->where('decision', 'disetujui')->exists();
        $supervisorApproved = $this->approvals()->where('approver_role', 'supervisor')->where('decision', 'disetujui')->exists();

        return $founderApproved && $supervisorApproved;
    }

    /**
     * Cek apakah salah satu pihak menolak
     */
    public function isRejected(): bool
    {
        if ($this->relationLoaded('approvals')) {
            return $this->approvals->where('decision', 'ditolak')->isNotEmpty();
        }
        return $this->approvals()->where('decision', 'ditolak')->exists();
    }
}
