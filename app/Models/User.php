<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'nik',
        'position',
        'address',
        'phone',
        'password',
        'role',
        'is_active',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isFounder(): bool
    {
        return $this->role === 'founder';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'founder' || $this->role === 'supervisor';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAdminOrFounder(): bool
    {
        return $this->isFounder() || $this->isAdmin() || $this->isSupervisor();
    }

    public function isPengawasProject(): bool
    {
        return $this->role === 'pengawas_project';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }

    public function isMarketing(): bool
    {
        return $this->role === 'marketing';
    }

    public function canViewSalesPrices(): bool
    {
        return $this->isFounder() || $this->isAdmin() || $this->isFinance() || $this->isMarketing() || $this->isSupervisor();
    }

    public function canViewLandPrices(): bool
    {
        // Land prices are visible to Founder, Admin, Finance, Supervisor
        return $this->isFounder() || $this->isAdmin() || $this->isFinance() || $this->isSupervisor();
    }

    public function canViewMaterialPrices(): bool
    {
        // Material & item prices are hidden for marketing and admin
        return !$this->isMarketing() && !$this->isAdmin();
    }

    public function canViewWorkerWages(): bool
    {
        // Worker wages are hidden for marketing and admin
        return !$this->isMarketing() && !$this->isAdmin();
    }

    public function canViewUnitExpenses(): bool
    {
        // Unit field expenses, material purchases & payrolls are hidden for marketing and admin
        return !$this->isMarketing() && !$this->isAdmin();
    }

    /**
     * Collect all Unit IDs where this user is the closing/handling marketing rep.
     *
     * @return array<int>
     */
    public function dealtUnitIds(): array
    {
        $fromReports = DailyActivityReport::where('user_id', $this->id)
            ->whereNotNull('unit_id')
            ->whereIn('lead_stage', DailyActivityReport::CLOSED_DEAL_STAGES)
            ->pluck('unit_id')
            ->all();

        $fromCommissions = UnitCommission::where('marketing_id', $this->id)
            ->pluck('unit_id')
            ->all();

        $fromBookings = Booking::where('created_by', $this->id)
            ->where('status', '!=', 'cancelled')
            ->pluck('unit_id')
            ->all();

        $fromProposals = PriceProposal::where('proposed_by', $this->id)
            ->where('status', 'disetujui')
            ->pluck('unit_id')
            ->all();

        return array_values(array_unique(array_map('intval', array_filter(array_merge(
            $fromReports,
            $fromCommissions,
            $fromBookings,
            $fromProposals,
        )))));
    }

    public function hasDealtUnit(int $unitId): bool
    {
        return in_array($unitId, $this->dealtUnitIds(), true);
    }

    public function canViewUnitExpensesFor(?Unit $unit = null): bool
    {
        if ($this->isMarketing()) {
            return $unit !== null && $this->hasDealtUnit($unit->id);
        }

        return $this->canViewUnitExpenses();
    }

    public function canViewUnitInstallmentsFor(?Unit $unit = null): bool
    {
        if ($this->isPengawasProject()) {
            return false;
        }

        if ($this->isMarketing()) {
            return $unit !== null && $this->hasDealtUnit($unit->id);
        }

        return true;
    }

    public function canViewHpp(): bool
    {
        if ($this->isAdmin()) {
            return false;
        }
        return $this->isFounder() || $this->isSupervisor() || $this->isFinance();
    }

    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class, 'worker_assignments', 'user_id', 'project_id')->where('worker_assignments.status', 'active');
    }

    public function employeeSalary()
    {
        return $this->hasOne(EmployeeSalary::class, 'user_id');
    }
}
