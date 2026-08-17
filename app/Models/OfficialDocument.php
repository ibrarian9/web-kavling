<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficialDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'price_proposal_id',
        'document_number',
        'buyer_name',
        'buyer_nik',
        'buyer_contact',
        'buyer_address',
        'seller_name',
        'seller_nik',
        'seller_position',
        'seller_address',
        'issued_by',
        'issued_at',
        'file_path',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(PriceProposal::class, 'price_proposal_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    protected static ?User $cachedFounder = null;

    protected static function getCachedFounder(): ?User
    {
        if (static::$cachedFounder === null) {
            static::$cachedFounder = User::where('role', 'founder')->first() ?: new User();
        }
        return static::$cachedFounder->exists ? static::$cachedFounder : null;
    }

    public function getEffectiveBuyerNikAttribute(): string
    {
        return $this->buyer_nik ?: '14710' . str_pad($this->id, 11, '0', STR_PAD_LEFT);
    }

    public function getEffectiveSellerNameAttribute(): string
    {
        if ($this->seller_name) {
            return $this->seller_name;
        }

        if ($this->relationLoaded('issuer') && $this->issuer && $this->issuer->isFounder()) {
            return $this->issuer->name;
        }

        $founder = static::getCachedFounder();
        return $founder ? $founder->name : 'Founder PT. Atlantik Perkasa Abadi';
    }

    public function getEffectiveSellerNikAttribute(): string
    {
        if ($this->seller_nik) {
            return $this->seller_nik;
        }

        if ($this->relationLoaded('issuer') && $this->issuer && $this->issuer->nik) {
            return $this->issuer->nik;
        }

        $founder = static::getCachedFounder();
        return $founder?->nik ?: '1471012304850001';
    }

    public function getEffectiveSellerPositionAttribute(): string
    {
        if ($this->seller_position) {
            return $this->seller_position;
        }

        if ($this->relationLoaded('issuer') && $this->issuer && $this->issuer->position) {
            return $this->issuer->position;
        }

        $founder = static::getCachedFounder();
        return $founder?->position ?: 'Direktur Utama PT. Atlantik Perkasa Abadi';
    }

    public function getEffectiveSellerAddressAttribute(): string
    {
        if ($this->seller_address) {
            return $this->seller_address;
        }

        if ($this->relationLoaded('issuer') && $this->issuer && $this->issuer->address) {
            return $this->issuer->address;
        }

        $founder = static::getCachedFounder();
        return $founder?->address ?: 'Jl. Utama Properti No. 88, Pekanbaru, Riau';
    }
}
