<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\ManualInvoice;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\WeeklyMaterialPurchase;
use App\Models\WorkerAssignment;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use Illuminate\Support\Facades\DB;

class CascadeDeletionService
{
    /**
     * Delete a Unit and ALL associated child data & cashflow transactions cleanly.
     */
    public static function deleteUnit(Unit $unit): void
    {
        DB::transaction(function () use ($unit) {
            $unitId = $unit->id;

            // 1. Clean up Bookings & their Cashflow Transactions
            $bookingIds = Booking::where('unit_id', $unitId)->pluck('id');
            if ($bookingIds->count() > 0) {
                CashflowTransaction::where('reference_type', Booking::class)
                    ->whereIn('reference_id', $bookingIds)
                    ->delete();
                Booking::whereIn('id', $bookingIds)->delete();
            }

            // 2. Clean up Installments, Installment Payments & their Cashflow Transactions
            $installment = UnitInstallment::where('unit_id', $unitId)->first();
            if ($installment) {
                $installmentId = $installment->id;
                $paymentIds = InstallmentPayment::where('unit_installment_id', $installmentId)->pluck('id');

                CashflowTransaction::where('reference_type', UnitInstallment::class)
                    ->where('reference_id', $installmentId)
                    ->delete();

                if ($paymentIds->count() > 0) {
                    CashflowTransaction::where('reference_type', InstallmentPayment::class)
                        ->whereIn('reference_id', $paymentIds)
                        ->delete();
                    InstallmentPayment::whereIn('id', $paymentIds)->delete();
                }

                $installment->delete();
            }

            // 3. Clean up Worker Unit Payrolls, Salary Payments & their Cashflow Transactions
            $unitPayrollIds = WorkerUnitPayroll::where('unit_id', $unitId)->pluck('id');
            if ($unitPayrollIds->count() > 0) {
                $salaryPaymentIds = WorkerSalaryPayment::whereIn('worker_unit_payroll_id', $unitPayrollIds)->pluck('id');
                if ($salaryPaymentIds->count() > 0) {
                    CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                        ->whereIn('reference_id', $salaryPaymentIds)
                        ->delete();
                    WorkerSalaryPayment::whereIn('id', $salaryPaymentIds)->delete();
                }
                WorkerUnitPayroll::whereIn('id', $unitPayrollIds)->delete();
            }

            // 4. Clean up Weekly Material Purchases & their Cashflow Transactions
            $materialPurchaseIds = WeeklyMaterialPurchase::where('unit_id', $unitId)->pluck('id');
            if ($materialPurchaseIds->count() > 0) {
                CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
                    ->whereIn('reference_id', $materialPurchaseIds)
                    ->delete();
                WeeklyMaterialPurchase::whereIn('id', $materialPurchaseIds)->delete();
            }

            // 5. Clean up Direct Cashflow Transactions tied to Unit
            CashflowTransaction::where('reference_type', Unit::class)
                ->where('reference_id', $unitId)
                ->delete();

            // 6. Clean up Worker Assignments, Proposals, Approvals, Official Documents (Surat SPP) & Storage Files
            WorkerAssignment::where('unit_id', $unitId)->delete();

            $proposalIds = PriceProposal::where('unit_id', $unitId)->pluck('id');
            if ($proposalIds->count() > 0) {
                \App\Models\Approval::whereIn('price_proposal_id', $proposalIds)->delete();
                PriceProposal::whereIn('id', $proposalIds)->delete();
            }

            $officialDocs = OfficialDocument::where('unit_id', $unitId)
                ->orWhere(function ($q) use ($proposalIds) {
                    if ($proposalIds->count() > 0) {
                        $q->whereIn('price_proposal_id', $proposalIds);
                    }
                })->get();

            foreach ($officialDocs as $doc) {
                if ($doc->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
                }
                $doc->delete();
            }

            ManualInvoice::where('unit_id', $unitId)->update(['unit_id' => null]);

            // 7. Finally delete the Unit model
            $unit->delete();
        });
    }

    /**
     * Delete a Project and ALL associated units, payments & global cashflow transactions.
     */
    public static function deleteProject(Project $project): void
    {
        DB::transaction(function () use ($project) {
            $projectId = $project->id;

            // 1. Delete all Units & their full cascades
            $units = Unit::where('project_id', $projectId)->get();
            foreach ($units as $unit) {
                self::deleteUnit($unit);
            }

            // 2. Delete Project Payments (Land Payments) & their Cashflow Transactions
            $projectPaymentIds = ProjectPayment::where('project_id', $projectId)->pluck('id');
            if ($projectPaymentIds->count() > 0) {
                CashflowTransaction::where('reference_type', ProjectPayment::class)
                    ->whereIn('reference_id', $projectPaymentIds)
                    ->delete();
                ProjectPayment::whereIn('id', $projectPaymentIds)->delete();
            }

            // 3. Delete Worker Assignments for the Project
            WorkerAssignment::where('project_id', $projectId)->delete();

            // 4. Delete ANY remaining Cashflow Transactions tied to this Project
            CashflowTransaction::where('project_id', $projectId)->delete();

            // 5. Unlink Manual Invoices tied to this Project
            ManualInvoice::where('project_id', $projectId)->update(['project_id' => null]);

            // 6. Finally delete the Project model
            $project->delete();
        });
    }
}
