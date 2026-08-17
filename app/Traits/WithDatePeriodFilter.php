<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait WithDatePeriodFilter
{
    public string $datePeriod = 'all'; // 'all', 'today', 'yesterday', 'this_week', 'this_month', 'last_month', 'this_year', 'custom'
    public string $startDate = '';
    public string $endDate = '';

    public function setDatePeriod(string $period): void
    {
        $this->datePeriod = $period;
        if ($period !== 'custom') {
            $this->startDate = '';
            $this->endDate = '';
        }
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function updatedDatePeriod(): void
    {
        if ($this->datePeriod !== 'custom') {
            $this->startDate = '';
            $this->endDate = '';
        }
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function updatedStartDate(): void
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function updatedEndDate(): void
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    /**
     * Apply date period filter to an Eloquent query builder.
     * Supports custom period, startDate, and endDate parameters for multi-tab support.
     */
    public function applyDatePeriodFilter(
        Builder $query,
        string $column = 'created_at',
        ?string $period = null,
        ?string $startDate = null,
        ?string $endDate = null
    ): Builder {
        $activePeriod = $period ?? $this->datePeriod;
        $activeStart = $startDate ?? $this->startDate;
        $activeEnd = $endDate ?? $this->endDate;

        switch ($activePeriod) {
            case 'today':
                return $query->whereDate($column, Carbon::today());

            case 'yesterday':
                return $query->whereDate($column, Carbon::yesterday());

            case 'this_week':
                return $query->whereBetween($column, [
                    Carbon::now()->startOfWeek()->toDateString(),
                    Carbon::now()->endOfWeek()->toDateString(),
                ]);

            case 'this_month':
                return $query->whereMonth($column, Carbon::now()->month)
                             ->whereYear($column, Carbon::now()->year);

            case 'last_month':
                $lastMonth = Carbon::now()->subMonth();
                return $query->whereMonth($column, $lastMonth->month)
                             ->whereYear($column, $lastMonth->year);

            case 'this_year':
                return $query->whereYear($column, Carbon::now()->year);

            case 'custom':
                if (!empty($activeStart) && !empty($activeEnd)) {
                    return $query->whereBetween($column, [
                        min($activeStart, $activeEnd),
                        max($activeStart, $activeEnd),
                    ]);
                } elseif (!empty($activeStart)) {
                    return $query->whereDate($column, '>=', $activeStart);
                } elseif (!empty($activeEnd)) {
                    return $query->whereDate($column, '<=', $activeEnd);
                }
                return $query;

            case 'all':
            default:
                return $query;
        }
    }
}
