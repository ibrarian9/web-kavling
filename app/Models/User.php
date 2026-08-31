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
        // Admin cannot view property sales prices, unit prices, booking fees, installments, or commissions
        if ($this->isAdmin()) {
            return false;
        }
        return $this->isFounder() || $this->isFinance() || $this->isMarketing() || $this->isSupervisor();
    }

    public function canViewLandPrices(): bool
    {
        // Land prices are visible to Founder, Admin, Finance, Supervisor
        return $this->isFounder() || $this->isAdmin() || $this->isFinance() || $this->isSupervisor();
    }

    public function canViewMaterialPrices(): bool
    {
        // Material & item prices are visible to all roles except marketing
        return !$this->isMarketing();
    }

    public function canViewWorkerWages(): bool
    {
        // Worker wages are visible to all roles except marketing
        return !$this->isMarketing();
    }

    public function canViewUnitExpenses(): bool
    {
        // Unit field expenses, material purchases & payrolls are visible to all roles except marketing
        return !$this->isMarketing();
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
