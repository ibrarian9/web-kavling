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
        'buyer_contact',
        'buyer_address',
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
}
