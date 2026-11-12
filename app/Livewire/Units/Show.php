<?php

namespace App\Livewire\Units;

use App\Livewire\Traits\WithFileUploadValidation;
use App\Livewire\Traits\WithMediaViewer;
use App\Livewire\Units\Concerns\HasUnitBookingsAndCommissions;
use App\Livewire\Units\Concerns\HasUnitInstallments;
use App\Livewire\Units\Concerns\HasUnitMaterials;
use App\Livewire\Units\Concerns\HasUnitPayroll;
use App\Livewire\Units\Concerns\HasUnitProposalsAndSpp;
use App\Livewire\Units\Concerns\HasUnitWorkers;
use App\Models\ManualInvoice;
use App\Models\Unit;
use App\Models\UnitCommission;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use App\Services\ActivityLogger;
use App\Services\CascadeDeletionService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;
    use WithFileUploadValidation;
    use WithMediaViewer;
    use HasUnitWorkers;
    use HasUnitMaterials;
    use HasUnitPayroll;
    use HasUnitInstallments;
    use HasUnitBookingsAndCommissions;
    use HasUnitProposalsAndSpp;

    public $unitId;

    // Modal Unit Cost (Legacy)
    public bool $showCostModal = false;
    public string $cost_category = 'tukang';
    public string $cost_description = '';
    public $cost_amount = 0;
    public string $cost_date = '';
    public string $vendor_name = '';
    public string $cost_status = 'dibayar';

    // Modal Edit Unit Spesifikasi (Founder & Finance)
    public bool $showEditUnitModal = false;
    public string $edit_unit_code = '';
    public string $edit_unit_category = 'kavling';
    public string $edit_unit_status = 'tersedia';
    public $edit_land_length = 0;
    public $edit_land_width = 0;
    public $edit_land_area = 0;
    public $edit_excess_land_area = 0;
    public $edit_excess_price_per_sqm = 0;
    public $edit_excess_cost = 0;
    public $edit_building_area = 0;
    public $edit_final_selling_price = 0;
    public string $edit_specifications = '';

    public function mount($id)
    {
        $unit = Unit::findOrFail($id);
        $user = auth()->user();
        if ($user && $user->isPengawasProject()) {
            $isAssigned = WorkerAssignment::where('user_id', $user->id)
                ->where('project_id', $unit->project_id)
                ->where('status', 'active')
                ->exists();
            if (!$isAssigned) {
                abort(403, 'Anda tidak memiliki hak akses pengawasan pada unit proyek ini.');
            }
        }

        $this->unitId = $id;
        $this->cost_date = now()->toDateString();
        $this->material_purchase_date = now()->toDateString();
    }

    public function canManageOperational(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        if ($user->isAdminOrFounder() || $user->isFinance() || $user->isSupervisor()) return true;
        if ($user->isPengawasProject()) {
            $unit = Unit::find($this->unitId);
            return $unit && WorkerAssignment::where('user_id', $user->id)
                ->where('project_id', $unit->project_id)
                ->where('status', 'active')
                ->exists();
        }
        return false;
    }

    public function canManageFinancial(): bool
    {
        $user = auth()->user();
        return $user && ($user->isAdminOrFounder() || $user->isFinance());
    }

    public function updatedEditLandLength(): void
    {
        if ($this->edit_unit_category !== 'infrastruktur' && (float)$this->edit_land_length > 0 && (float)$this->edit_land_width > 0) {
            $this->edit_land_area = round((float)$this->edit_land_length * (float)$this->edit_land_width, 2);
            $this->recalcEditExcessLand();
        }
    }

    public function updatedEditLandWidth(): void
    {
        if ($this->edit_unit_category !== 'infrastruktur' && (float)$this->edit_land_length > 0 && (float)$this->edit_land_width > 0) {
            $this->edit_land_area = round((float)$this->edit_land_length * (float)$this->edit_land_width, 2);
            $this->recalcEditExcessLand();
        }
    }

    public function updatedEditExcessPricePerSqm(): void
    {
        $this->edit_excess_cost = (float)$this->edit_excess_land_area * (float)$this->edit_excess_price_per_sqm;
    }

    protected function recalcEditExcessLand(): void
    {
        $unit = Unit::with('project')->find($this->unitId);
        $standardLand = (float)($unit?->project->standard_land_area ?? 0);
        $this->edit_excess_land_area = max(0, (float)$this->edit_land_area - $standardLand);
        $this->edit_excess_cost = $this->edit_excess_land_area * (float)$this->edit_excess_price_per_sqm;
    }

    public function openEditUnitModal(): void
    {
        if (!$this->canManageFinancial()) {
            $err = 'Akses ditolak. Hanya Founder dan Finance yang dapat mengedit spesifikasi unit.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $err]);
            return;
        }
        $unit = Unit::with('project')->findOrFail($this->unitId);
        $this->resetValidation();
        $this->edit_unit_code = $unit->code;
        $this->edit_unit_category = $unit->category;
        $this->edit_unit_status = $unit->status;
        $this->edit_land_length = $unit->land_length;
        $this->edit_land_width = $unit->land_width;
        $this->edit_land_area = $unit->land_area;
        $standardLand = (float)($unit->project->standard_land_area ?? 0);
        $this->edit_excess_land_area = max(0, (float)$unit->land_area - $standardLand);
        $this->edit_excess_price_per_sqm = (float)($unit->project->excess_price_per_sqm ?? 1500000);
        $this->edit_excess_cost = (float)($unit->excess_cost ?: ($this->edit_excess_land_area * $this->edit_excess_price_per_sqm));
        $this->edit_building_area = $unit->building_area ?? 0;
        $this->edit_final_selling_price = $unit->final_selling_price ?? 0;
        $this->edit_specifications = $unit->specifications ?? '';
        $this->showEditUnitModal = true;
    }

    public function saveEditUnit(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $this->validate([
            'edit_unit_code' => 'required|string|max:50',
            'edit_unit_category' => 'required|in:kavling,rumah,infrastruktur',
            'edit_unit_status' => 'required|string',
            'edit_land_area' => 'required|numeric|min:0',
            'edit_excess_cost' => 'nullable|numeric|min:0',
        ]);
        $unit = Unit::with('project')->findOrFail($this->unitId);
        $landLength = $this->edit_land_length;
        $landWidth = $this->edit_land_width;
        $excessLandArea = 0;
        $excessCost = 0;

        if ($this->edit_unit_category === 'infrastruktur') {
            $landLength = 0;
            $landWidth = 0;
        } elseif ($unit->project) {
            $excessLandArea = max(0, (float)$this->edit_land_area - (float)$unit->project->standard_land_area);
            if ($this->edit_excess_cost !== null && (float)$this->edit_excess_cost >= 0) {
                $excessCost = (float)$this->edit_excess_cost;
            } else {
                $excessCost = $excessLandArea * (float)($this->edit_excess_price_per_sqm ?: $unit->project->excess_price_per_sqm);
            }
        }

        $unit->update([
            'code' => $this->edit_unit_code,
            'category' => $this->edit_unit_category,
            'status' => $this->edit_unit_status,
            'land_length' => $landLength,
            'land_width' => $landWidth,
            'land_area' => $this->edit_land_area,
            'excess_land_area' => $excessLandArea,
            'excess_cost' => $excessCost,
            'building_area' => $this->edit_unit_category === 'rumah' ? $this->edit_building_area : 0,
            'final_selling_price' => $this->edit_unit_category !== 'infrastruktur' ? $this->edit_final_selling_price : 0,
            'specifications' => $this->edit_specifications,
        ]);

        ActivityLogger::log('UNIT_UPDATED', "Spesifikasi Unit {$unit->code} diperbarui oleh " . auth()->user()->name . " (Kelebihan tanah: {$excessLandArea} m², Biaya: Rp " . number_format($excessCost, 0, ',', '.') . ").");

        session()->flash('success', 'Spesifikasi & data unit ' . $unit->code . ' berhasil diperbarui!');
        $this->showEditUnitModal = false;
    }

    public function deleteUnit()
    {
        $user = auth()->user();
        if (!$user || !$user->isSuperAdmin()) {
            session()->flash('error', 'Hanya Founder dan Supervisor yang berhak menghapus unit dari sistem.');
            return;
        }

        $unit = Unit::findOrFail($this->unitId);
        $code = $unit->code;

        DB::transaction(function () use ($unit, $code) {
            CascadeDeletionService::deleteUnit($unit);
            ActivityLogger::log(
                'DELETE_UNIT',
                "Founder menghapus Unit {$code} dari sistem beserta seluruh histori terikatnya"
            );
        });

        session()->flash('success', 'Unit ' . $code . ' berhasil dihapus dari sistem!');
        return redirect()->route('units.index');
    }

    public function render()
    {
        $unit = Unit::with([
            'project',
            'creator',
            'proposals.proposer',
            'proposals.approvals.approver',
            'officialDocument.issuer',
            'installment.payments.creator',
            'activeBooking.creator',
            'bookings.creator',
        ])->findOrFail($this->unitId);

        $unitAssignments = WorkerAssignment::with(['worker', 'user'])
            ->where('status', 'active')
            ->where(function($q) use ($unit) {
                $q->where('unit_id', $unit->id)
                  ->orWhere(function($subQ) use ($unit) {
                      $subQ->where('project_id', $unit->project_id)->whereNull('unit_id');
                  });
            })
            ->get();

        $authUser = auth()->user();
        $canViewUnitExpenses = $authUser ? $authUser->canViewUnitExpensesFor($unit) : false;
        $canViewUnitInstallments = $authUser ? $authUser->canViewUnitInstallmentsFor($unit) : false;

        $manualInvoices = collect();
        $totalCashIn = 0;

        if ($canViewUnitInstallments) {
            $manualInvoices = ManualInvoice::with('creator')
                ->where('unit_id', $unit->id)
                ->latest('invoice_date')
                ->get();

            $manualInvoiceCashIn = $manualInvoices->where('status', 'lunas')->where('type', 'masuk')->sum('amount');
            $totalCashIn = $manualInvoiceCashIn;

            if ($unit->installment) {
                $totalCashIn += $unit->installment->down_payment;
                $totalCashIn += $unit->installment->payments->sum('amount_paid');
            } else {
                $bookingPaid = $unit->bookings->where('status', '!=', 'cancelled')->sum(fn($b) => (float)$b->booking_amount + (float)$b->dp_amount);
                $totalCashIn += $bookingPaid;
            }
        }

        $unitPayrolls = collect();
        $salaryPayments = collect();
        $materialPurchases = collect();
        $combinedExpenses = collect();

        if ($canViewUnitExpenses) {
            $unitPayrolls = WorkerUnitPayroll::with(['worker', 'payments'])
                ->where('unit_id', $unit->id)
                ->latest('id')
                ->get();

            $salaryPayments = WorkerSalaryPayment::whereHas('payroll', function($q) use ($unit) {
                $q->where('unit_id', $unit->id);
            })->with(['payroll.worker'])->get();

            $materialPurchases = WeeklyMaterialPurchase::with(['worker', 'pengawas'])
                ->where('unit_id', $unit->id)
                ->get();

            foreach ($unitPayrolls as $up) {
                $combinedExpenses->push((object)[
                    'id' => $up->id,
                    'source_type' => 'payroll_setup',
                    'date' => $up->created_at,
                    'category_badge' => 'Kontrak Gaji',
                    'badge_class' => 'bg-purple-100 text-purple-800 border-purple-200',
                    'description' => 'Kontrak Borongan Gaji ' . ($up->worker->name ?? 'Pekerja Lapangan') . ' (' . strtoupper($up->status) . ' - Terbayar Rp ' . number_format($up->paid_amount, 0, ',', '.') . ' / Total Rp ' . number_format($up->agreed_salary, 0, ',', '.') . ')',
                    'notes' => $up->notes,
                    'worker_name' => $up->worker->name ?? 'Pekerja Lapangan',
                    'store_name' => null,
                    'amount' => $up->agreed_salary,
                    'gross_amount' => $up->agreed_salary,
                    'loan_deduction' => 0,
                    'receipt_photo_path' => null,
                    'pdf_url' => null,
                    'qr_url' => null,
                    'created_at' => $up->created_at,
                ]);
            }

            foreach ($salaryPayments as $sp) {
                $combinedExpenses->push((object)[
                    'id' => $sp->id,
                    'source_type' => 'salary_payment',
                    'date' => $sp->payment_date,
                    'category_badge' => 'Gaji Worker',
                    'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'description' => 'Pembayaran Gaji ' . ($sp->payroll->worker->name ?? 'Pekerja Lapangan') . ' (' . str_replace('_', ' ', $sp->payment_method) . ')',
                    'notes' => $sp->notes,
                    'worker_name' => $sp->payroll->worker->name ?? 'Pekerja Lapangan',
                    'store_name' => null,
                    'amount' => $sp->amount_paid,
                    'gross_amount' => $sp->amount_gross,
                    'loan_deduction' => $sp->loan_deduction,
                    'receipt_photo_path' => $sp->receipt_photo_path,
                    'pdf_url' => route('payroll.receipt', $sp->uuid),
                    'qr_url' => route('verify.payroll', $sp->uuid),
                    'created_at' => $sp->created_at,
                ]);
            }

            foreach ($materialPurchases as $mp) {
                $combinedExpenses->push((object)[
                    'id' => $mp->id,
                    'source_type' => 'material',
                    'date' => $mp->purchase_date,
                    'category_badge' => 'Barang / Material',
                    'badge_class' => 'bg-amber-100 text-amber-800 border-amber-200',
                    'description' => $mp->item_name . ' (' . number_format($mp->quantity, 0, ',', '.') . ' ' . $mp->unit_measure . ' @ Rp ' . number_format($mp->unit_price, 0, ',', '.') . ')',
                    'notes' => $mp->notes,
                    'worker_name' => $mp->worker->name ?? null,
                    'store_name' => $mp->store_name ?: '-',
                    'amount' => $mp->total_price,
                    'gross_amount' => $mp->total_price,
                    'loan_deduction' => 0,
                    'receipt_photo_path' => $mp->receipt_photo_path,
                    'pdf_url' => route('material-purchases.receipt', $mp->id),
                    'qr_url' => route('verify.material-purchase', $mp->id),
                    'created_at' => $mp->created_at,
                ]);
            }

            $combinedExpenses = $combinedExpenses->sortByDesc(function ($item) {
                return ($item->date ? $item->date->format('Y-m-d') : '0000-00-00') . '_' . $item->id;
            })->values();
        }

        $allWorkers = Worker::where('status', 'active')->orderBy('name')->get();

        $commissionsQuery = UnitCommission::with(['payments.creator', 'marketing', 'creator'])
            ->where('unit_id', $unit->id);

        if ($authUser && $authUser->isMarketing()) {
            $commissionsQuery->where('marketing_id', $authUser->id);
        }

        $unitCommissions = $commissionsQuery->latest('id')->get();

        $totalMaterialCost = (float) $materialPurchases->sum('total_price');
        $totalSalaryCost = (float) $salaryPayments->sum('amount_paid');
        $totalPayrollContractCost = (float) $unitPayrolls->sum('agreed_salary');
        $totalRealizedExpenses = $totalMaterialCost + $totalSalaryCost;

        return view('livewire.units.show', [
            'unit' => $unit,
            'canViewUnitExpenses' => $canViewUnitExpenses,
            'canViewUnitInstallments' => $canViewUnitInstallments,
            'unitAssignments' => $unitAssignments,
            'materialPurchases' => $materialPurchases,
            'manualInvoices' => $manualInvoices,
            'totalCashIn' => $totalCashIn,
            'allWorkers' => $allWorkers,
            'unitPayrolls' => $unitPayrolls,
            'unitCommissions' => $unitCommissions,
            'combinedExpenses' => $combinedExpenses,
            'totalMaterialCost' => $totalMaterialCost,
            'totalSalaryCost' => $totalSalaryCost,
            'totalPayrollContractCost' => $totalPayrollContractCost,
            'totalRealizedExpenses' => $totalRealizedExpenses,
            'showWorkerModal' => $this->showWorkerModal,
            'showBookingModal' => $this->showBookingModal,
            'showPayrollSetupModal' => $this->showPayrollSetupModal,
            'showPayrollPaymentModal' => $this->showPayrollPaymentModal,
            'showInstallmentPaymentModal' => $this->showInstallmentPaymentModal,
            'showSetupInstallmentModal' => $this->showSetupInstallmentModal,
            'showConvertToCashModal' => $this->showConvertToCashModal,
            'showMaterialModal' => $this->showMaterialModal,
            'showViewerModal' => $this->showViewerModal,
            'showDirectSppModal' => $this->showDirectSppModal,
            'showDirectProposalModal' => $this->showDirectProposalModal,
            'showCommissionModal' => $this->showCommissionModal,
            'showCommissionPaymentModal' => $this->showCommissionPaymentModal,
            'editingSalaryPaymentId' => $this->editingSalaryPaymentId,
        ])->layout('components.layouts.app', ['title' => 'Detail Unit ' . $unit->code . ' - ' . $unit->project->name]);
    }
}
