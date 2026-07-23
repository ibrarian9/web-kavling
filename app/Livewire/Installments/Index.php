<?php

namespace App\Livewire\Installments;

use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\OfficialDocument;
use App\Models\Unit;
use App\Models\UnitInstallment;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $showSetupModal = false;
    public $showPaymentModal = false;

    // Setup Skema Cicilan
    public $unit_id = '';
    public $official_document_id = null;
    public $total_price = 0;
    public $down_payment = 0;
    public $installment_count = 12;
    public $installment_amount = 0;
    public $start_date = '';

    // Payment Form
    public $selectedInstallmentId = null;
    public $activeInstallment = null;
    public $payment_amount = 0;
    public $payment_date = '';
    public $payment_method = 'Transfer Bank';
    public $payment_notes = '';

    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->payment_date = date('Y-m-d');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['total_price', 'down_payment', 'installment_count'])) {
            $this->calculateMonthlyInstallment();
        }
    }

    public function calculateMonthlyInstallment()
    {
        $rem = max(0, (float)$this->total_price - (float)$this->down_payment);
        $count = max(1, (int)$this->installment_count);
        $this->installment_amount = $rem / $count;
    }

    public function selectUnitForInstallment($unitId)
    {
        $this->unit_id = $unitId;
        $unit = Unit::with(['officialDocument', 'activeProposal'])->find($unitId);
        if ($unit) {
            $this->total_price = $unit->final_selling_price ?: ($unit->activeProposal->proposed_price ?? 0);
            $this->official_document_id = $unit->officialDocument->id ?? null;
            $this->down_payment = $this->total_price * 0.20; // Default DP 20%
            $this->calculateMonthlyInstallment();
        }
    }

    public function openSetupModal()
    {
        $firstSold = Unit::whereIn('status', ['terjual', 'disetujui'])->doesntHave('installment')->first();
        if ($firstSold) {
            $this->selectUnitForInstallment($firstSold->id);
        }
        $this->showSetupModal = true;
    }

    public function saveSetup()
    {
        $this->validate([
            'unit_id' => 'required|exists:units,id',
            'total_price' => 'required|numeric|min:1000',
            'installment_count' => 'required|integer|min:1',
            'start_date' => 'required|date',
        ]);

        $installment = UnitInstallment::create([
            'unit_id' => $this->unit_id,
            'official_document_id' => $this->official_document_id,
            'total_price' => $this->total_price,
            'down_payment' => $this->down_payment,
            'installment_count' => $this->installment_count,
            'installment_amount' => $this->installment_amount,
            'start_date' => $this->start_date,
            'status' => 'berjalan',
        ]);

        // If DP > 0, auto record DP payment in cashflow!
        if ($this->down_payment > 0) {
            $unit = Unit::find($this->unit_id);
            CashflowTransaction::create([
                'project_id' => $unit->project_id,
                'type' => 'masuk',
                'category' => 'pembayaran_cicilan_pembeli',
                'amount' => $this->down_payment,
                'transaction_date' => $this->start_date,
                'description' => 'Pembayaran Uang Muka (DP) Unit ' . $unit->code,
                'reference_type' => UnitInstallment::class,
                'reference_id' => $installment->id,
                'created_by' => auth()->id(),
            ]);
        }

        session()->flash('success', 'Skema cicilan/piutang pembeli berhasil dikonfigurasi!');
        $this->showSetupModal = false;
    }

    public function openPaymentModal($installmentId)
    {
        $this->selectedInstallmentId = $installmentId;
        $this->activeInstallment = UnitInstallment::with(['unit.project', 'officialDocument', 'payments'])->findOrFail($installmentId);
        $this->payment_amount = $this->activeInstallment->installment_amount;
        $this->payment_date = date('Y-m-d');
        $this->payment_notes = '';
        $this->showPaymentModal = true;
    }

    public function submitPayment()
    {
        $this->validate([
            'payment_amount' => 'required|numeric|min:1000',
            'payment_date' => 'required|date',
        ]);

        $inst = UnitInstallment::with('unit')->findOrFail($this->selectedInstallmentId);

        InstallmentPayment::create([
            'unit_installment_id' => $inst->id,
            'payment_date' => $this->payment_date,
            'amount_paid' => $this->payment_amount,
            'payment_method' => $this->payment_method,
            'notes' => $this->payment_notes,
            'created_by' => auth()->id(),
        ]);

        // Record in cashflow transaction
        CashflowTransaction::create([
            'project_id' => $inst->unit->project_id,
            'type' => 'masuk',
            'category' => 'pembayaran_cicilan_pembeli',
            'amount' => $this->payment_amount,
            'transaction_date' => $this->payment_date,
            'description' => 'Setoran Cicilan Pembeli Unit ' . $inst->unit->code . ' (' . $this->payment_method . ')',
            'reference_type' => UnitInstallment::class,
            'reference_id' => $inst->id,
            'created_by' => auth()->id(),
        ]);

        // Check if fully paid
        if ($inst->remaining_balance <= 0) {
            $inst->update(['status' => 'lunas']);
            session()->flash('success', 'Pembayaran diterima! Status cicilan Unit ' . $inst->unit->code . ' telah LUNAS!');
        } else {
            session()->flash('success', 'Pembayaran cicilan Rp ' . number_format($this->payment_amount, 0, ',', '.') . ' berhasil dicatat di Arus Kas!');
        }

        $this->showPaymentModal = false;
    }

    public function render()
    {
        $installments = UnitInstallment::with(['unit.project', 'officialDocument', 'payments'])
            ->latest()
            ->paginate(10);

        $eligibleUnits = Unit::whereIn('status', ['terjual', 'disetujui'])
            ->doesntHave('installment')
            ->get();

        return view('livewire.installments.index', [
            'installments' => $installments,
            'eligibleUnits' => $eligibleUnits,
        ])->layout('components.layouts.app', ['title' => 'Cicilan & Piutang Pembeli']);
    }
}
