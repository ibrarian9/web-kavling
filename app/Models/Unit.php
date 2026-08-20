<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'code',
        'category',
        'type',
        'land_width',
        'land_length',
        'land_area',
        'building_area',
        'floors_count',
        'specifications',
        'excess_land_area',
        'excess_cost',
        'hpp',
        'final_selling_price',
        'status',
        'created_by',
    ];

    protected $casts = [
        'land_width' => 'decimal:2',
        'land_length' => 'decimal:2',
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'floors_count' => 'integer',
        'excess_land_area' => 'decimal:2',
        'excess_cost' => 'decimal:2',
        'hpp' => 'decimal:2',
        'final_selling_price' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(PriceProposal::class);
    }

    public function activeProposal(): HasOne
    {
        return $this->hasOne(PriceProposal::class)->latestOfMany();
    }

    public function officialDocument(): HasOne
    {
        return $this->hasOne(OfficialDocument::class);
    }

    public function installment(): HasOne
    {
        return $this->hasOne(UnitInstallment::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(WorkerAssignment::class);
    }

    public function activeAssignments(): HasMany
    {
        return $this->hasMany(WorkerAssignment::class)->where('status', 'active');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBooking(): HasOne
    {
        return $this->hasOne(Booking::class)->whereIn('status', ['active', 'converted'])->latestOfMany();
    }


    /**
     * Hitung otomatis kelebihan tanah dan rekomendasi HPP berbasis standar proyek
     */
    public function recalculateLandAndHpp(): void
    {
        if (!$this->project) {
            $this->load('project');
        }

        $project = $this->project;
        if (!$project) return;

        // Auto hitung luas jika belum terisi tapi width/length terisi
        if ($this->land_area <= 0 && $this->land_width > 0 && $this->land_length > 0) {
            $this->land_area = $this->land_width * $this->land_length;
        }

        $standardLand = $project->standard_land_area;
        $excessSqm = max(0, $this->land_area - $standardLand);
        $excessCost = $excessSqm * $project->excess_price_per_sqm;
        
        $this->excess_land_area = $excessSqm;
        $this->excess_cost = $excessCost;

        if (is_null($this->hpp)) {
            $this->hpp = $project->base_price + $excessCost;
        }
    }

    public function getBasePriceAttribute(): float
    {
        return (float) ($this->project?->base_price ?? 0);
    }

    public function getTotalPriceAttribute(): float
    {
        if ($this->hpp > 0) {
            return (float) $this->hpp;
        }
        return (float) ($this->base_price + (float)$this->excess_cost);
    }

    public function getIsSoldAttribute(): bool
    {
        return \App\Enums\UnitStatus::isSold($this->status);
    }

    public function getIsAvailableAttribute(): bool
    {
        return \App\Enums\UnitStatus::isAvailable($this->status);
    }

    public function getIsBookedAttribute(): bool
    {
        return \App\Enums\UnitStatus::isBooked($this->status);
    }

    public function getStatusLabelAttribute(): string
    {
        return \App\Enums\UnitStatus::label($this->status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return \App\Enums\UnitStatus::badgeClass($this->status);
    }

    public function getBuyerNameAttribute(): string
    {
        if ($this->relationLoaded('officialDocument') && $this->officialDocument?->buyer_name) {
            return $this->officialDocument->buyer_name;
        }

        if ($this->relationLoaded('activeBooking') && $this->activeBooking?->buyer_name) {
            return $this->activeBooking->buyer_name;
        }

        if ($this->relationLoaded('bookings')) {
            $latest = $this->bookings->sortByDesc('id')->first();
            if ($latest?->buyer_name) {
                return $latest->buyer_name;
            }
        }

        return $this->officialDocument()->value('buyer_name') 
            ?? $this->activeBooking()->value('buyer_name') 
            ?? $this->bookings()->latest('id')->value('buyer_name') 
            ?? 'Konsumen Pembeli';
    }

    public function getBuyerPhoneAttribute(): ?string
    {
        if ($this->relationLoaded('officialDocument') && $this->officialDocument?->buyer_contact) {
            return $this->officialDocument->buyer_contact;
        }

        if ($this->relationLoaded('activeBooking') && $this->activeBooking?->buyer_phone) {
            return $this->activeBooking->buyer_phone;
        }

        if ($this->relationLoaded('bookings')) {
            $latest = $this->bookings->sortByDesc('id')->first();
            if ($latest?->buyer_phone) {
                return $latest->buyer_phone;
            }
        }

        return $this->officialDocument()->value('buyer_contact') 
            ?? $this->activeBooking()->value('buyer_phone') 
            ?? $this->bookings()->latest('id')->value('buyer_phone');
    }
}
