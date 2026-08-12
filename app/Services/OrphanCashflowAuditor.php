<?php

namespace App\Services;

use App\Models\CashflowTransaction;
use App\Models\Project;

class OrphanCashflowAuditor
{
    /**
     * Detect all orphan records in cashflow_transactions.
     * An orphan record is a transaction whose project or reference model has been deleted.
     */
    public static function audit(): array
    {
        $transactions = CashflowTransaction::all();
        $orphans = [];

        foreach ($transactions as $c) {
            $isOrphan = false;
            $reason = '';

            // Check 1: Project relation
            if ($c->project_id && !Project::find($c->project_id)) {
                $isOrphan = true;
                $reason = "Proyek ID #{$c->project_id} sudah tidak ada di database";
            }
            // Check 2: Polymorphic reference model
            elseif ($c->reference_type && $c->reference_id) {
                $class = $c->reference_type;
                if (class_exists($class)) {
                    if (!$class::find($c->reference_id)) {
                        $isOrphan = true;
                        $shortName = class_basename($class);
                        $reason = "Model {$shortName} ID #{$c->reference_id} sudah dihapus dari sistem";
                    }
                } else {
                    $isOrphan = true;
                    $reason = "Tipe Referensi {$c->reference_type} tidak valid";
                }
            }

            if ($isOrphan) {
                $orphans[] = [
                    'id' => $c->id,
                    'transaction_date' => $c->transaction_date ? \Carbon\Carbon::parse($c->transaction_date)->format('Y-m-d') : '-',
                    'type' => $c->type,
                    'category' => $c->category,
                    'amount' => (float) $c->amount,
                    'description' => $c->description,
                    'reference_type' => $c->reference_type,
                    'reference_id' => $c->reference_id,
                    'project_id' => $c->project_id,
                    'reason' => $reason,
                ];
            }
        }

        return $orphans;
    }

    /**
     * Purge all orphan records in cashflow_transactions.
     */
    public static function purge(): int
    {
        $orphans = self::audit();
        $orphanIds = array_column($orphans, 'id');

        if (count($orphanIds) > 0) {
            CashflowTransaction::whereIn('id', $orphanIds)->delete();
        }

        return count($orphanIds);
    }
}
