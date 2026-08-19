<?php

namespace App\Livewire\Units;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use Illuminate\Support\Facades\DB;
use App\Livewire\Traits\WithFileUploadValidation;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;
    use WithFileUploadValidation;

    public $unitId;

    // Modal Worker Assignment
    public bool $showWorkerModal = false;
    public ?int $worker_id = null;
    public string $assigned_role = 'Mandor Lapangan';

    // Modal Unit Cost
    public bool $showCostModal = false;
    public string $cost_category = 'tukang';
    public string $cost_description = '';
    public $cost_amount = 0;
    public string $cost_date = '';
    public string $vendor_name = '';
    public string $cost_status = 'dibayar';

    // Modal Booking
    public bool $showBookingModal = false;
    public string $buyer_name = '';
    public string $buyer_phone = '';
    public $booking_amount = 5000000;
    public $dp_amount = 25000000;
    public string $booking_notes = '';
    public $receipt_photo = null;

    // Modal Worker Payroll Setup
    public bool $showPayrollSetupModal = false;
    public ?int $payroll_worker_id = null;
    public $payroll_agreed_salary = 0;
    public string $payroll_payment_frequency = 'fleksibel';
    public string $payroll_notes = '';

    // Modal Worker Payroll Payment
    public bool $showPayrollPaymentModal = false;
    public ?WorkerUnitPayroll $selectedPayroll = null;
    public string $payroll_payment_date = '';
    public $payroll_amount_gross = 0;
    public $payroll_loan_deduction = 0;
    public string $payroll_payment_method = 'transfer_bank';
    public string $payroll_bank_name = '';
    public string $payroll_account_number = '';
    public $payroll_receipt_photo = null;
    public string $payroll_payment_notes = '';
    public $payroll_active_worker_loan = 0;

    // Modal Material Purchase (Catat Belanja Barang Unit) — Bulk Input Support
    public bool $showMaterialModal = false;
    public ?int $material_worker_id = null;
    public string $material_purchase_date = '';
    public string $material_store_name = '';
    public string $material_payment_status = 'lunas';
    public $material_receipt_photo = null;
    public string $material_notes = '';
    public bool $material_is_deducted_from_loan = false;
    public array $materialRows = [];
    public $material_grand_total = 0;

    // Legacy single-item properties kept for edit mode compatibility
    public string $material_item_name = '';
    public $material_quantity = 1;
    public string $material_unit_measure = 'pcs';
    public $material_unit_price = 0;
    public $material_total_price = 0;

    // Modal Buyer Installment Payment (Setoran Cicilan Pembeli)
    public bool $showInstallmentPaymentModal = false;
    public $installment_payment_amount = 0;
    public string $installment_payment_date = '';
    public string $installment_payment_method = 'Transfer Bank';
    public string $installment_payment_notes = '';
    public $installment_payment_receipt_photo = null;
    public ?string $existing_installment_receipt_photo_path = null;

    // Modal Setup Skema Cicilan Baru (Konfigurasi oleh Finance / Founder)
    public bool $showSetupInstallmentModal = false;
    public $setup_total_price = 0;
    public $setup_down_payment = 0;
    public $setup_installment_count = 12;
    public $setup_installment_amount = 0;
    public string $setup_start_date = '';

    // Modal Catat Komisi Penjual Unit (Unit Detail)
    public bool $showCommissionModal = false;
    public string $unit_comm_seller_name = '';
    public string $unit_comm_seller_phone = '';
    public ?int $unit_comm_marketing_id = null;
    public $unit_comm_percentage = 2.5;
    public $unit_comm_amount = 0;
    public string $unit_comm_notes = '';

    // Modal Bayar Cicilan Komisi (Unit Detail)
    public bool $showCommissionPaymentModal = false;
    public ?int $unit_settling_commission_id = null;
    public $unit_pay_comm_amount = 0;
    public string $unit_pay_comm_date = '';
    public string $unit_pay_comm_method = 'Transfer Bank';
    public string $unit_pay_comm_notes = '';
    public $unit_pay_comm_photo = null;

    // Modal Terbitkan / Edit SPP & SPJB PDF Direct (Founder / Admin)
    public bool $showDirectSppModal = false;
    public ?int $editingSppId = null;
    public string $spp_buyer_name = '';
    public string $spp_buyer_nik = '';
    public string $spp_buyer_contact = '';
    public string $spp_buyer_address = '';
    public string $spp_seller_name = '';
    public string $spp_seller_nik = '';
    public string $spp_price_mode = 'total'; // 'total' | 'per_sqm'
    public $spp_price_per_sqm = 0;
    public $spp_cash_price = 0;

    public function openDirectSppModal(): void
    {
        $this->resetValidation();
        $this->editingSppId = null;
        $unit = Unit::with(['installment', 'activeBooking', 'officialDocument', 'project'])->findOrFail($this->unitId);
        $this->spp_buyer_name = $unit->activeBooking?->buyer_name ?? '';
        $this->spp_buyer_nik = '';
        $this->spp_buyer_contact = $unit->activeBooking?->buyer_phone ?? '';
        $this->spp_buyer_address = '';

        $founder = auth()->user()->isFounder() ? auth()->user() : User::where('role', 'founder')->first();
        $this->spp_seller_name = $founder?->name ?? 'Founder PT. Atlantik Perkasa Abadi';
        $this->spp_seller_nik = $founder?->nik ?? '1471012304850001';

        if ($unit->installment && (float)$unit->installment->total_price > 0) {
            $this->spp_cash_price = (float)$unit->installment->total_price;
        } elseif ($unit->final_selling_price && (float)$unit->final_selling_price > 0) {
            $this->spp_cash_price = (float)$unit->final_selling_price;
        } else {
            $this->spp_cash_price = (float)($unit->hpp ? $unit->hpp * 1.3 : ($unit->project->base_price ?? 150000000));
        }

        $this->spp_price_mode = 'total';
        $this->spp_price_per_sqm = (float)($unit->land_area ?? 0) > 0 ? round((float)$this->spp_cash_price / (float)$unit->land_area) : 0;

        $this->showDirectSppModal = true;
    }

    public function editDirectSpp(int $id): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdminOrFounder() && !$user->isFinance())) {
            $err = 'Hanya Founder dan Admin yang berhak mengedit dokumen SPP.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $err]);
            return;
        }

        $doc = \App\Models\OfficialDocument::with(['unit', 'proposal'])->findOrFail($id);
        $this->resetValidation();
        $this->editingSppId = $doc->id;
        $this->spp_buyer_name = $doc->buyer_name;
        $this->spp_buyer_nik = $doc->buyer_nik ?? '';
        $this->spp_buyer_contact = $doc->buyer_contact ?? '';
        $this->spp_buyer_address = $doc->buyer_address ?? '';
        $this->spp_seller_name = $doc->seller_name ?? '';
        $this->spp_seller_nik = $doc->seller_nik ?? '';

        $unit = $doc->unit;
        $this->spp_cash_price = (float)($doc->proposal?->proposed_price ?: ($unit->final_selling_price ?: 0));
        $this->spp_price_mode = 'total';
        $this->spp_price_per_sqm = (float)($unit->land_area ?? 0) > 0 ? round((float)$this->spp_cash_price / (float)$unit->land_area) : 0;

        $this->showDirectSppModal = true;
    }

    public function deleteDirectSpp(int $id): void
    {
        $user = auth()->user();
        if (!$user || !$user->isAdminOrFounder()) {
            $err = 'Hanya Founder dan Admin Utama yang berhak menghapus dokumen SPP.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $err]);
            return;
        }

        $doc = \App\Models\OfficialDocument::with(['unit.activeBooking', 'unit.proposals', 'unit.installment'])->findOrFail($id);
        $docNumber = $doc->document_number;
        $unit = $doc->unit;

        $doc->delete();

        $statusMsg = '';
        if ($unit) {
            if ($unit->installment && $unit->installment->status === 'berjalan') {
                $unit->update(['status' => 'disetujui']);
                $statusMsg = "Status unit {$unit->code} dikembalikan ke 'Disetujui'.";
            } elseif ($unit->activeBooking) {
                $unit->update(['status' => 'booked']);
                $statusMsg = "Status unit {$unit->code} dikembalikan ke 'Booked'.";
            } elseif ($unit->proposals()->where('status', 'disetujui')->exists()) {
                $unit->update(['status' => 'disetujui']);
                $statusMsg = "Status unit {$unit->code} dikembalikan ke 'Disetujui'.";
            } else {
                $unit->update(['status' => 'tersedia']);
                $statusMsg = "Status unit {$unit->code} dikembalikan ke 'Tersedia'.";
            }
        }

        \App\Services\ActivityLogger::log('DOCUMENT_DELETED', "Dokumen SPP {$docNumber} (ID #{$id}) unit {$unit?->code} telah dihapus via Detail Unit oleh {$user->name}. {$statusMsg}");

        $msg = 'Dokumen SPP ' . $docNumber . ' berhasil dihapus. ' . $statusMsg;
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'SPP Dihapus', 'message' => $msg]);
    }

    public function updatedSppPricePerSqm(): void
    {
        $unit = Unit::find($this->unitId);
        $area = (float)($unit->land_area ?? 0);
        if ($area > 0 && $this->spp_price_mode === 'per_sqm') {
            $this->spp_cash_price = round($area * (float)$this->spp_price_per_sqm);
        }
    }

    public function updatedSppCashPrice(): void
    {
        $unit = Unit::find($this->unitId);
        $area = (float)($unit->land_area ?? 0);
        if ($area > 0 && $this->spp_price_mode === 'total' && (float)$this->spp_cash_price > 0) {
            $this->spp_price_per_sqm = round((float)$this->spp_cash_price / $area);
        }
    }

    public function saveDirectSpp(): void
    {
        $user = auth()->user();
        if (!$user->isAdminOrFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder atau Admin yang berhak menerbitkan / mengedit dokumen SPP.');
            return;
        }

        $this->validate([
            'spp_buyer_name' => 'required|string|max:255',
            'spp_buyer_contact' => 'required|string|max:255',
            'spp_cash_price' => 'required|numeric|min:0',
        ]);

        $unit = Unit::with('project', 'proposals')->findOrFail($this->unitId);

        if ($this->editingSppId) {
            $doc = \App\Models\OfficialDocument::with('proposal')->findOrFail($this->editingSppId);
            $doc->update([
                'buyer_name' => $this->spp_buyer_name,
                'buyer_nik' => $this->spp_buyer_nik ?: null,
                'buyer_contact' => $this->spp_buyer_contact,
                'buyer_address' => $this->spp_buyer_address ?: '-',
                'seller_name' => $this->spp_seller_name ?: null,
                'seller_nik' => $this->spp_seller_nik ?: null,
            ]);

            if ($doc->proposal) {
                $hpp = (float)$doc->proposal->hpp_price;
                $proposed = (float)$this->spp_cash_price;
                $doc->proposal->update([
                    'proposed_price' => $proposed,
                    'margin' => $proposed - $hpp,
                    'is_below_hpp' => $proposed < $hpp,
                ]);
            }

            $unit->update([
                'final_selling_price' => (float)$this->spp_cash_price,
            ]);

            \App\Services\ActivityLogger::log('DOCUMENT_UPDATED', "Dokumen SPP {$doc->document_number} (Unit {$unit->code}) diperbarui oleh {$user->name} via Detail Unit.");

            $this->showDirectSppModal = false;
            $this->editingSppId = null;
            $msg = 'Dokumen SPP ' . $doc->document_number . ' berhasil diperbarui!';
            session()->flash('success', $msg);
            $this->dispatch('notify', ['type' => 'success', 'title' => 'SPP Diperbarui!', 'message' => $msg]);
            return;
        }

        // CREATE NEW SPP
        $docNumber = 'SPP/' . strtoupper($unit->project->name) . '/' . date('Y/m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $proposal = $unit->proposals()->where('status', 'disetujui')->latest()->first();

        $unit->update([
            'status' => 'terjual',
            'final_selling_price' => (float) ($this->spp_cash_price ?: $unit->final_selling_price),
        ]);

        if (!$proposal) {
            $hppPrice = (float) $unit->hpp;
            $proposedPrice = (float) ($this->spp_cash_price ?: $unit->final_selling_price);
            $margin = $proposedPrice - $hppPrice;
            $isBelowHpp = $proposedPrice < $hppPrice;

            $proposal = \App\Models\PriceProposal::create([
                'unit_id' => $unit->id,
                'hpp_price' => $hppPrice,
                'proposed_price' => $proposedPrice,
                'margin' => $margin,
                'is_below_hpp' => $isBelowHpp,
                'discount_reason' => 'Penjualan Cash Langsung Founder',
                'proposed_by' => auth()->id(),
                'status' => 'disetujui',
                'notes' => 'Persetujuan langsung pembelian Cash oleh Founder',
            ]);
        }

        $doc = \App\Models\OfficialDocument::create([
            'unit_id' => $unit->id,
            'price_proposal_id' => $proposal->id,
            'document_number' => $docNumber,
            'buyer_name' => $this->spp_buyer_name,
            'buyer_nik' => $this->spp_buyer_nik ?: null,
            'buyer_contact' => $this->spp_buyer_contact,
            'buyer_address' => $this->spp_buyer_address ?: '-',
            'seller_name' => $this->spp_seller_name ?: null,
            'seller_nik' => $this->spp_seller_nik ?: null,
            'issued_by' => auth()->id(),
            'issued_at' => now(),
        ]);

        \App\Services\ActivityLogger::log('DOCUMENT_GENERATED', "Dokumen SPP & SPJB PDF {$docNumber} diterbitkan langsung per unit {$unit->code} oleh Founder.");

        $this->showDirectSppModal = false;
        $msg = 'Dokumen SPP & SPJB PDF ' . $docNumber . ' berhasil diterbitkan!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'SPP Terbit!', 'message' => $msg]);

        $this->openViewerModal('pdf', route('documents.stream', $doc->id), 'Pratinjau SPP & SPJB PDF - ' . $doc->document_number);
    }

    // Modal Direct Proposal State
    public bool $showDirectProposalModal = false;
    public ?int $editingProposalId = null;
    public $prop_hpp_price = 0;
    public $prop_proposed_price = 0;
    public string $prop_price_mode = 'total'; // 'total' | 'per_sqm'
    public $prop_price_per_sqm = 0;
    public string $prop_notes = '';

    public function approveProposal(int $proposalId, string $decision): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdminOrFounder() && !$user->isSupervisor())) {
            $err = 'Hanya Admin, Founder, dan Supervisor yang berhak mengesahkan approval harga.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $proposal = \App\Models\PriceProposal::with('unit.project')->findOrFail($proposalId);
        $userRole = $user->role;

        \App\Models\Approval::updateOrCreate(
            [
                'price_proposal_id' => $proposal->id,
                'approver_role' => $userRole,
            ],
            [
                'approver_id' => $user->id,
                'decision' => $decision,
                'notes' => 'Approval langsung dari Detail Unit ' . $proposal->unit->code,
                'decided_at' => now(),
            ]
        );

        \App\Services\ActivityLogger::log(
            $decision === 'ditolak' ? 'PROPOSAL_REJECTED' : 'PROPOSAL_APPROVED',
            "Pengajuan harga unit {$proposal->unit->code} ({$decision}) oleh " . ucfirst($userRole) . " ({$user->name}) via Detail Unit."
        );

        if ($decision === 'ditolak') {
            $proposal->update(['status' => 'ditolak']);
            $proposal->unit->update(['status' => 'ditolak']);
            $msg = 'Pengajuan harga ditolak. Status unit kembali ke Ditolak.';
            session()->flash('error', $msg);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Ditolak!', 'message' => $msg]);
        } else {
            if ($user->isAdminOrFounder() || $proposal->isFullyApproved()) {
                $proposal->update(['status' => 'disetujui']);
                $proposal->unit->update([
                    'status' => 'disetujui',
                    'final_selling_price' => $proposal->proposed_price,
                ]);

                $existingDoc = \App\Models\OfficialDocument::where('price_proposal_id', $proposal->id)->first();
                if (!$existingDoc) {
                    $docNumber = 'SPP/' . strtoupper($proposal->unit->project->name ?? 'PROYEK') . '/' . date('Y/m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                    \App\Models\OfficialDocument::create([
                        'unit_id' => $proposal->unit_id,
                        'price_proposal_id' => $proposal->id,
                        'document_number' => $docNumber,
                        'buyer_name' => 'Pembeli Unit ' . $proposal->unit->code,
                        'buyer_contact' => '-',
                        'buyer_address' => '-',
                        'issued_by' => auth()->id(),
                        'issued_at' => now(),
                    ]);
                }

                $msg = 'Pengajuan harga disetujui penuh! Dokumen SPP PDF otomatis diterbitkan!';
                session()->flash('success', $msg);
                $this->dispatch('notify', ['type' => 'success', 'title' => 'ACC Berhasil!', 'message' => $msg]);
            } else {
                $msg = 'Keputusan Disetujui dicatat. Menunggu persetujuan Admin/Founder.';
                session()->flash('success', $msg);
                $this->dispatch('notify', ['type' => 'info', 'title' => 'Dicatat', 'message' => $msg]);
            }
        }
    }

    public function openDirectProposalModal(): void
    {
        $unit = Unit::findOrFail($this->unitId);
        $this->resetValidation();
        $this->editingProposalId = null;
        $this->prop_hpp_price = (float)$unit->hpp;
        $this->prop_proposed_price = (float)($unit->final_selling_price ?: ($unit->price ?: $unit->hpp * 1.3));
        $this->prop_price_mode = 'total';
        $this->prop_price_per_sqm = (float)($unit->land_area ?? 0) > 0 ? round((float)$this->prop_proposed_price / (float)$unit->land_area) : 0;
        $this->prop_notes = '';
        $this->showDirectProposalModal = true;
    }

    public function editDirectProposal(int $id): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdminOrFounder() && !$user->isMarketing())) {
            $err = 'Hanya Admin, Founder, dan Marketing yang berhak mengedit proposal.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $err]);
            return;
        }

        $proposal = \App\Models\PriceProposal::with('unit')->findOrFail($id);
        $this->resetValidation();
        $this->editingProposalId = $proposal->id;
        $this->prop_hpp_price = (float)$proposal->hpp_price;
        $this->prop_proposed_price = (float)$proposal->proposed_price;
        $this->prop_price_mode = 'total';
        $unit = $proposal->unit;
        $this->prop_price_per_sqm = (float)($unit?->land_area ?? 0) > 0 ? round((float)$proposal->proposed_price / (float)$unit->land_area) : 0;
        $this->prop_notes = $proposal->notes ?? '';
        $this->showDirectProposalModal = true;
    }

    public function deleteDirectProposal(int $id): void
    {
        $user = auth()->user();
        if (!$user || !$user->isAdminOrFounder()) {
            $err = 'Hanya Founder dan Admin Utama yang berhak menghapus pengajuan harga.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $err]);
            return;
        }

        $proposal = \App\Models\PriceProposal::with(['unit', 'approvals'])->findOrFail($id);
        $unit = $proposal->unit;
        $unitCode = $unit->code ?? 'Unit';

        if ($unit && $unit->status === 'menunggu_persetujuan') {
            $unit->update(['status' => 'tersedia']);
        }

        \App\Models\OfficialDocument::where('price_proposal_id', $proposal->id)->delete();
        \App\Models\Approval::where('price_proposal_id', $proposal->id)->delete();

        $proposal->delete();

        \App\Services\ActivityLogger::log('PROPOSAL_DELETED', "Pengajuan harga unit {$unitCode} (ID #{$id}) telah dihapus via Detail Unit oleh {$user->name}.");

        $msg = 'Pengajuan harga unit ' . $unitCode . ' berhasil dihapus.';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Proposal Dihapus', 'message' => $msg]);
    }

    public function updatedPropPricePerSqm(): void
    {
        $unit = Unit::find($this->unitId);
        $area = (float)($unit->land_area ?? 0);
        if ($area > 0 && $this->prop_price_mode === 'per_sqm') {
            $this->prop_proposed_price = round($area * (float)$this->prop_price_per_sqm);
        }
    }

    public function updatedPropProposedPrice(): void
    {
        $unit = Unit::find($this->unitId);
        $area = (float)($unit->land_area ?? 0);
        if ($area > 0 && $this->prop_price_mode === 'total' && (float)$this->prop_proposed_price > 0) {
            $this->prop_price_per_sqm = round((float)$this->prop_proposed_price / $area);
        }
    }

    public function saveDirectProposal(): void
    {
        $user = auth()->user();
        if (!$user->isMarketing() && !$user->isAdminOrFounder()) {
            $err = 'Hanya Marketing, Admin, dan Founder yang berhak membuat/mengedit pengajuan harga.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $this->validate([
            'prop_proposed_price' => 'required|numeric|min:1000',
        ]);

        $unit = Unit::with('project')->findOrFail($this->unitId);
        $hpp = (float)$unit->hpp;
        $proposed = (float)$this->prop_proposed_price;
        $margin = $proposed - $hpp;
        $isBelowHpp = $proposed < $hpp;

        if ($this->editingProposalId) {
            $proposal = \App\Models\PriceProposal::findOrFail($this->editingProposalId);
            $proposal->update([
                'proposed_price' => $proposed,
                'margin' => $margin,
                'is_below_hpp' => $isBelowHpp,
                'notes' => $this->prop_notes,
            ]);

            if ($proposal->status === 'disetujui') {
                $unit->update(['final_selling_price' => $proposed]);
            }

            \App\Services\ActivityLogger::log('PROPOSAL_UPDATED', "Pengajuan harga unit {$unit->code} (ID #{$proposal->id}) diperbarui via Detail Unit oleh {$user->name}.");

            $this->showDirectProposalModal = false;
            $this->editingProposalId = null;
            $msg = 'Proposal harga unit ' . $unit->code . ' berhasil diperbarui!';
            session()->flash('success', $msg);
            $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
            return;
        }

        $status = $user->isAdminOrFounder() ? 'disetujui' : 'menunggu';

        $proposal = \App\Models\PriceProposal::create([
            'unit_id' => $unit->id,
            'hpp_price' => $hpp,
            'proposed_price' => $proposed,
            'margin' => $margin,
            'is_below_hpp' => $isBelowHpp,
            'proposed_by' => auth()->id(),
            'status' => $status,
            'notes' => $this->prop_notes ?: 'Pengajuan harga langsung dari Detail Unit',
        ]);

        if ($user->isAdminOrFounder()) {
            \App\Models\Approval::create([
                'price_proposal_id' => $proposal->id,
                'approver_id' => auth()->id(),
                'approver_role' => $user->role ?? 'founder',
                'decision' => 'disetujui',
                'notes' => 'Persetujuan langsung oleh ' . $user->name . ' di Detail Unit',
                'decided_at' => now(),
            ]);

            $unit->update([
                'status' => 'disetujui',
                'final_selling_price' => $proposed,
            ]);

            $docNumber = 'SPP/' . strtoupper($unit->project->name ?? 'PROYEK') . '/' . date('Y/m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            \App\Models\OfficialDocument::create([
                'unit_id' => $unit->id,
                'price_proposal_id' => $proposal->id,
                'document_number' => $docNumber,
                'buyer_name' => 'Pembeli Unit ' . $unit->code,
                'buyer_contact' => '-',
                'buyer_address' => '-',
                'issued_by' => auth()->id(),
                'issued_at' => now(),
            ]);
        }

        \App\Services\ActivityLogger::log('PROPOSAL_CREATED', "Proposal harga Rp " . number_format($proposed, 0, ',', '.') . " diajukan untuk Unit {$unit->code} via Detail Unit.");

        $msg = 'Proposal harga berhasil diajukan' . ($user->isAdminOrFounder() ? ' & langsung disetujui!' : '!');
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showDirectProposalModal = false;
    }

    // Viewer Modal (Jendela Melayang untuk Foto Struk, PDF Resi, & Barcode QR)
    public bool $showViewerModal = false;
    public string $viewerType = ''; // 'image', 'pdf', 'qr'
    public string $viewerUrl = '';
    public string $viewerTitle = '';

    public function openViewerModal(string $type, string $url, string $title = ''): void
    {
        $this->viewerType = $type;
        $this->viewerUrl = $url;
        $this->viewerTitle = $title ?: 'Pratinjau Dokumen / Resi';
        $this->showViewerModal = true;
    }

    public function closeViewerModal(): void
    {
        $this->showViewerModal = false;
        $this->viewerType = '';
        $this->viewerUrl = '';
        $this->viewerTitle = '';
    }

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

    public function updatedEditLandLength(): void
    {
        if ((float)$this->edit_land_length > 0 && (float)$this->edit_land_width > 0) {
            $this->edit_land_area = round((float)$this->edit_land_length * (float)$this->edit_land_width, 2);
            $this->recalcEditExcessLand();
        }
    }

    public function updatedEditLandWidth(): void
    {
        if ((float)$this->edit_land_length > 0 && (float)$this->edit_land_width > 0) {
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
        $excessLandArea = 0;
        $excessCost = 0;
        if ($unit->project && $this->edit_unit_category !== 'infrastruktur') {
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
            'land_length' => $this->edit_land_length,
            'land_width' => $this->edit_land_width,
            'land_area' => $this->edit_land_area,
            'excess_land_area' => $excessLandArea,
            'excess_cost' => $excessCost,
            'building_area' => $this->edit_building_area,
            'final_selling_price' => $this->edit_final_selling_price,
            'specifications' => $this->edit_specifications,
        ]);

        \App\Services\ActivityLogger::log('UNIT_UPDATED', "Spesifikasi Unit {$unit->code} diperbarui oleh " . auth()->user()->name . " (Kelebihan tanah: {$excessLandArea} m², Biaya: Rp " . number_format($excessCost, 0, ',', '.') . ").");

        session()->flash('success', 'Spesifikasi & data unit ' . $unit->code . ' berhasil diperbarui!');
        $this->showEditUnitModal = false;
    }

    // Worker Assignment Management
    public ?int $editingAssignmentId = null;

    public function openWorkerModal(): void
    {
        $this->resetValidation();
        $this->editingAssignmentId = null;
        $this->worker_id = Worker::where('status', 'active')->first()?->id;
        $this->assigned_role = 'Mandor Lapangan';
        $this->showWorkerModal = true;
    }

    public function editWorkerAssignment(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki hak akses mengedit penugasan.');
            return;
        }
        $assign = WorkerAssignment::findOrFail($id);
        $this->resetValidation();
        $this->editingAssignmentId = $assign->id;
        $this->worker_id = $assign->worker_id;
        $this->assigned_role = $assign->assigned_role;
        $this->showWorkerModal = true;
    }

    public function deleteWorkerAssignment(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki hak akses menghapus penugasan.');
            return;
        }
        $assign = WorkerAssignment::findOrFail($id);
        $assign->delete();
        session()->flash('success', 'Penugasan pekerja berhasil dihapus!');
    }

    public function saveWorkerAssignment(): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $this->validate([
            'worker_id' => 'required|exists:workers,id',
            'assigned_role' => 'required|string|max:100',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        if ($this->editingAssignmentId) {
            $assign = WorkerAssignment::findOrFail($this->editingAssignmentId);
            $assign->update([
                'worker_id' => $this->worker_id,
                'assigned_role' => $this->assigned_role,
            ]);
            session()->flash('success', 'Penugasan pekerja berhasil diperbarui!');
        } else {
            WorkerAssignment::updateOrCreate([
                'project_id' => $unit->project_id,
                'unit_id' => $unit->id,
                'worker_id' => $this->worker_id,
            ], [
                'assigned_role' => $this->assigned_role,
            ]);
            session()->flash('success', 'Mandor/Tukang berhasil ditugaskan ke unit ' . $unit->code . '!');
        }

        $this->showWorkerModal = false;
        $this->editingAssignmentId = null;
    }

    // Material Purchase Management
    public ?int $editingMaterialId = null;

    protected function emptyMaterialRow(): array
    {
        return ['item_name' => '', 'quantity' => 1, 'unit_measure' => 'pcs', 'unit_price' => 0];
    }

    public function addMaterialRow(): void
    {
        $this->materialRows[] = $this->emptyMaterialRow();
        $this->recalcMaterialGrandTotal();
    }

    public function removeMaterialRow(int $index): void
    {
        if (count($this->materialRows) <= 1) return;
        unset($this->materialRows[$index]);
        $this->materialRows = array_values($this->materialRows);
        $this->recalcMaterialGrandTotal();
    }

    public function updatedMaterialRows(): void
    {
        $this->recalcMaterialGrandTotal();
    }

    protected function recalcMaterialGrandTotal(): void
    {
        $total = 0;
        foreach ($this->materialRows as $row) {
            $qty = is_numeric($row['quantity'] ?? 0) ? (float)$row['quantity'] : 0;
            $price = is_numeric($row['unit_price'] ?? 0) ? (float)$row['unit_price'] : 0;
            $total += $qty * $price;
        }
        $this->material_grand_total = $total;
    }

    public function openMaterialModal(): void
    {
        $this->resetValidation();
        $this->editingMaterialId = null;
        $this->reset(['material_worker_id', 'material_item_name', 'material_store_name', 'material_unit_price', 'material_total_price', 'material_receipt_photo', 'material_notes']);
        $this->material_purchase_date = now()->toDateString();
        $this->material_payment_status = 'lunas';
        $this->material_quantity = 1;
        $this->material_unit_measure = 'pcs';
        $this->material_is_deducted_from_loan = false;
        $this->material_worker_id = Worker::where('status', 'active')->first()?->id;
        $this->materialRows = [$this->emptyMaterialRow()];
        $this->material_grand_total = 0;
        $this->showMaterialModal = true;
    }

    public function editMaterialPurchase(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $mat = WeeklyMaterialPurchase::findOrFail($id);
        $this->resetValidation();
        $this->editingMaterialId = $mat->id;
        $this->material_worker_id = $mat->worker_id;
        $this->material_purchase_date = $mat->purchase_date ? $mat->purchase_date->format('Y-m-d') : date('Y-m-d');
        $this->material_item_name = $mat->item_name;
        $this->material_store_name = $mat->store_name ?? '';
        $this->material_payment_status = $mat->payment_status ?? 'lunas';
        $this->material_quantity = $mat->quantity;
        $this->material_unit_measure = $mat->unit_measure;
        $this->material_unit_price = $mat->unit_price;
        $this->material_total_price = $mat->total_price;
        $this->material_receipt_photo = null;
        $this->material_notes = $mat->notes ?? '';
        // Edit mode: populate materialRows with the single existing item
        $this->materialRows = [[
            'item_name' => $mat->item_name,
            'quantity' => $mat->quantity,
            'unit_measure' => $mat->unit_measure,
            'unit_price' => $mat->unit_price,
        ]];
        $this->material_grand_total = (float)$mat->total_price;
        $this->showMaterialModal = true;
    }

    public function deleteMaterialPurchase(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $mat = WeeklyMaterialPurchase::findOrFail($id);
        DB::transaction(function () use ($mat) {
            CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
                ->where('reference_id', $mat->id)
                ->delete();
            $mat->delete();
        });
        session()->flash('success', 'Pencatatan belanja material berhasil dihapus!');
    }

    public function updatedMaterialQuantity(): void
    {
        $qty = is_numeric($this->material_quantity) ? (float)$this->material_quantity : 0;
        $price = is_numeric($this->material_unit_price) ? (float)$this->material_unit_price : 0;
        $this->material_total_price = $qty * $price;
    }

    public function updatedMaterialUnitPrice(): void
    {
        $qty = is_numeric($this->material_quantity) ? (float)$this->material_quantity : 0;
        $price = is_numeric($this->material_unit_price) ? (float)$this->material_unit_price : 0;
        $this->material_total_price = $qty * $price;
    }

    public function saveMaterialPurchase(): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        // Shared metadata validation
        $this->validate([
            'material_purchase_date' => 'required|date',
            'material_store_name' => 'nullable|string|max:255',
            'material_payment_status' => 'required|in:lunas,belum_lunas',
            'material_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
            'material_notes' => 'nullable|string',
            'materialRows' => 'required|array|min:1',
            'materialRows.*.item_name' => 'required|string|max:255',
            'materialRows.*.quantity' => 'required|numeric|min:0.01',
            'materialRows.*.unit_measure' => 'required|string|max:50',
            'materialRows.*.unit_price' => 'required|numeric|min:0',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        $photoPath = null;
        if ($this->material_receipt_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->material_receipt_photo, 'material-receipts');
        }

        if ($this->editingMaterialId) {
            // EDIT MODE: Update single existing item from first row
            $row = $this->materialRows[0];
            $totalPrice = (float)$row['quantity'] * (float)$row['unit_price'];
            $mat = WeeklyMaterialPurchase::findOrFail($this->editingMaterialId);
            $finalPhoto = $photoPath ?: $mat->receipt_photo_path;

            DB::transaction(function () use ($unit, $mat, $row, $totalPrice, $finalPhoto) {
                $mat->update([
                    'worker_id' => $this->material_worker_id ?: $mat->worker_id,
                    'purchase_date' => $this->material_purchase_date,
                    'item_name' => $row['item_name'],
                    'store_name' => $this->material_store_name,
                    'quantity' => $row['quantity'],
                    'unit_measure' => $row['unit_measure'],
                    'unit_price' => $row['unit_price'],
                    'total_price' => $totalPrice,
                    'payment_status' => $this->material_payment_status,
                    'receipt_photo_path' => $finalPhoto,
                    'notes' => $this->material_notes,
                ]);

                $cashflow = CashflowTransaction::where('reference_type', WeeklyMaterialPurchase::class)
                    ->where('reference_id', $mat->id)
                    ->first();

                if ($this->material_payment_status === 'lunas') {
                    $storeInfo = $this->material_store_name ? " (Toko: {$this->material_store_name})" : '';
                    if ($cashflow) {
                        $cashflow->update([
                            'amount' => $totalPrice,
                            'transaction_date' => $this->material_purchase_date,
                            'description' => "Pembelian Material Unit {$unit->code}{$storeInfo}: {$row['item_name']} ({$row['quantity']} {$row['unit_measure']})",
                        ]);
                    } else {
                        CashflowTransaction::create([
                            'project_id' => $unit->project_id,
                            'type' => 'keluar',
                            'category' => 'operasional',
                            'amount' => $totalPrice,
                            'transaction_date' => $this->material_purchase_date,
                            'description' => "Pembelian Material Unit {$unit->code}{$storeInfo}: {$row['item_name']} ({$row['quantity']} {$row['unit_measure']})",
                            'reference_type' => WeeklyMaterialPurchase::class,
                            'reference_id' => $mat->id,
                            'created_by' => Auth::id(),
                        ]);
                    }
                } else {
                    if ($cashflow) {
                        $cashflow->delete();
                    }
                }
            });
            session()->flash('success', 'Belanja material unit ' . $unit->code . ' berhasil diperbarui!');
        } else {
            // CREATE MODE: Bulk insert from all materialRows
            $itemCount = count($this->materialRows);
            $grandTotal = 0;

            DB::transaction(function () use ($unit, $photoPath, &$grandTotal) {
                $workerId = $this->material_worker_id ?: Worker::where('status', 'active')->first()?->id;
                $storeInfo = $this->material_store_name ? " (Toko: {$this->material_store_name})" : '';

                foreach ($this->materialRows as $row) {
                    $itemName = trim($row['item_name'] ?? '');
                    if (empty($itemName)) continue;

                    $qty = (float)($row['quantity'] ?? 0);
                    $unitPrice = (float)($row['unit_price'] ?? 0);
                    $totalPrice = $qty * $unitPrice;
                    $grandTotal += $totalPrice;

                    $purchase = WeeklyMaterialPurchase::create([
                        'project_id' => $unit->project_id,
                        'unit_id' => $unit->id,
                        'worker_id' => $workerId,
                        'pengawas_id' => Auth::id(),
                        'purchase_date' => $this->material_purchase_date,
                        'item_name' => $itemName,
                        'store_name' => $this->material_store_name,
                        'quantity' => $qty,
                        'unit_measure' => $row['unit_measure'] ?? 'pcs',
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'payment_status' => $this->material_payment_status,
                        'paid_at' => $this->material_payment_status === 'lunas' ? now() : null,
                        'paid_by' => $this->material_payment_status === 'lunas' ? Auth::id() : null,
                        'receipt_photo_path' => $photoPath,
                        'notes' => $this->material_notes,
                    ]);

                    if ($this->material_payment_status === 'lunas') {
                        CashflowTransaction::create([
                            'project_id' => $unit->project_id,
                            'type' => 'keluar',
                            'category' => 'operasional',
                            'amount' => $totalPrice,
                            'transaction_date' => $this->material_purchase_date,
                            'description' => "Pembelian Material Unit {$unit->code}{$storeInfo}: {$itemName} ({$qty} {$row['unit_measure']})",
                            'reference_type' => WeeklyMaterialPurchase::class,
                            'reference_id' => $purchase->id,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            });

            $itemNames = collect($this->materialRows)->pluck('item_name')->filter()->implode(', ');
            \App\Services\ActivityLogger::log('MATERIAL_PURCHASE_RECORDED', "Pembelian {$itemCount} item material Unit {$unit->code} ({$itemNames}) dicatat sebesar Rp " . number_format($grandTotal, 0, ',', '.') . " (" . strtoupper($this->material_payment_status) . ")");
            session()->flash('success', "{$itemCount} item belanja material unit {$unit->code} berhasil dicatat sekaligus!");
        }

        $this->showMaterialModal = false;
        $this->editingMaterialId = null;
    }

    // Payroll Setup Management
    public ?int $editingPayrollId = null;

    public function openPayrollSetupModal(): void
    {
        $this->resetValidation();
        $this->editingPayrollId = null;
        $this->reset(['payroll_worker_id', 'payroll_agreed_salary', 'payroll_notes']);
        $this->payroll_payment_frequency = 'fleksibel';
        $this->payroll_worker_id = Worker::where('status', 'active')->first()?->id;
        $this->showPayrollSetupModal = true;
    }

    public function editPayrollSetup(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $up = WorkerUnitPayroll::findOrFail($id);
        $this->resetValidation();
        $this->editingPayrollId = $up->id;
        $this->payroll_worker_id = $up->worker_id;
        $this->payroll_agreed_salary = $up->agreed_salary;
        $this->payroll_payment_frequency = $up->payment_frequency;
        $this->payroll_notes = $up->notes ?? '';
        $this->showPayrollSetupModal = true;
    }

    public function deletePayrollSetup(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $up = WorkerUnitPayroll::findOrFail($id);
        DB::transaction(function () use ($up) {
            foreach ($up->payments as $sp) {
                CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                    ->where('reference_id', $sp->id)
                    ->delete();
                $sp->delete();
            }
            $up->delete();
        });
        session()->flash('success', 'Penetapan gaji borongan unit berhasil dihapus!');
    }

    public function savePayrollSetup(): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $this->validate([
            'payroll_worker_id' => 'required|exists:workers,id',
            'payroll_agreed_salary' => 'required|numeric|min:10000',
            'payroll_payment_frequency' => 'required|in:harian,mingguan,bulanan,fleksibel',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        if ($this->editingPayrollId) {
            $up = WorkerUnitPayroll::findOrFail($this->editingPayrollId);
            $up->update([
                'worker_id' => $this->payroll_worker_id,
                'agreed_salary' => $this->payroll_agreed_salary,
                'payment_frequency' => $this->payroll_payment_frequency,
                'notes' => $this->payroll_notes,
            ]);
            session()->flash('success', 'Penetapan gaji borongan unit berhasil diperbarui!');
        } else {
            WorkerUnitPayroll::create([
                'worker_id' => $this->payroll_worker_id,
                'project_id' => $unit->project_id,
                'unit_id' => $unit->id,
                'agreed_salary' => $this->payroll_agreed_salary,
                'payment_frequency' => $this->payroll_payment_frequency,
                'status' => 'berjalan',
                'notes' => $this->payroll_notes,
                'created_by' => Auth::id(),
            ]);
            session()->flash('success', 'Penetapan gaji unit ' . $unit->code . ' berhasil disimpan!');
        }

        $this->showPayrollSetupModal = false;
        $this->editingPayrollId = null;
    }

    public ?int $editingSalaryPaymentId = null;

    public function openPayrollPaymentModal(int $payrollId): void
    {
        $this->resetValidation();
        $this->editingSalaryPaymentId = null;
        $this->reset(['payroll_amount_gross', 'payroll_receipt_photo', 'payroll_payment_notes']);
        
        $this->selectedPayroll = WorkerUnitPayroll::with(['worker', 'project', 'unit'])->findOrFail($payrollId);
        $this->payroll_payment_date = now()->toDateString();
        $this->payroll_payment_method = 'transfer_bank';

        $this->showPayrollPaymentModal = true;
    }

    public function editPayrollPayment(int $salaryPaymentId): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $sp = WorkerSalaryPayment::with(['payroll.worker', 'payroll.project', 'payroll.unit'])->findOrFail($salaryPaymentId);
        $this->resetValidation();
        $this->editingSalaryPaymentId = $sp->id;
        $this->selectedPayroll = $sp->payroll;
        $this->payroll_payment_date = $sp->payment_date ? $sp->payment_date->format('Y-m-d') : date('Y-m-d');
        $this->payroll_amount_gross = $sp->amount_gross;
        $this->payroll_payment_method = $sp->payment_method ?? 'transfer_bank';
        $this->payroll_payment_notes = $sp->notes ?? '';
        $this->payroll_receipt_photo = null;

        $this->showPayrollPaymentModal = true;
    }

    public function deletePayrollPayment(int $salaryPaymentId): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $sp = WorkerSalaryPayment::with('payroll')->findOrFail($salaryPaymentId);
        $payroll = $sp->payroll;

        DB::transaction(function () use ($sp, $payroll) {
            CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                ->where('reference_id', $sp->id)
                ->delete();

            $oldGross = (float)$sp->amount_gross;
            $sp->delete();

            if ($payroll) {
                $newPaidTotal = max(0, (float)$payroll->paid_amount - $oldGross);
                $status = $newPaidTotal >= (float)$payroll->agreed_salary ? 'lunas' : 'berjalan';
                $payroll->update([
                    'paid_amount' => $newPaidTotal,
                    'status' => $status,
                ]);
            }
        });

        session()->flash('success', 'Pencatatan pembayaran gaji worker berhasil dihapus!');
    }

    public function savePayrollPayment(): void
    {
        if (!$this->selectedPayroll) {
            return;
        }

        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $sp = $this->editingSalaryPaymentId ? WorkerSalaryPayment::find($this->editingSalaryPaymentId) : null;
        $maxAllowed = (float)$this->selectedPayroll->remaining_salary + ($sp ? (float)$sp->amount_gross : 0);

        $this->validate([
            'payroll_payment_date' => 'required|date',
            'payroll_amount_gross' => 'required|numeric|min:1000|max:' . max(1000, $maxAllowed),
            'payroll_payment_method' => 'required|in:transfer_bank,tunai',
            'payroll_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
            'payroll_payment_notes' => 'nullable|string',
        ]);

        $amountGross = (float) $this->payroll_amount_gross;
        $amountPaid = $amountGross;

        $photoPath = null;
        if ($this->payroll_receipt_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->payroll_receipt_photo, 'payroll-receipts');
        }

        if ($this->editingSalaryPaymentId && $sp) {
            $oldGross = (float)$sp->amount_gross;
            $finalPhoto = $photoPath ?: $sp->receipt_photo_path;

            DB::transaction(function () use ($sp, $amountGross, $amountPaid, $oldGross, $finalPhoto) {
                $sp->update([
                    'payment_date' => $this->payroll_payment_date,
                    'amount_gross' => $amountGross,
                    'amount_paid' => $amountPaid,
                    'payment_method' => $this->payroll_payment_method,
                    'receipt_photo_path' => $finalPhoto,
                    'notes' => $this->payroll_payment_notes,
                ]);

                $newPaidTotal = (float)$this->selectedPayroll->paid_amount - $oldGross + $amountGross;
                $status = $newPaidTotal >= (float)$this->selectedPayroll->agreed_salary ? 'lunas' : 'berjalan';

                $this->selectedPayroll->update([
                    'paid_amount' => $newPaidTotal,
                    'status' => $status,
                ]);

                $cashflow = CashflowTransaction::where('reference_type', WorkerSalaryPayment::class)
                    ->where('reference_id', $sp->id)
                    ->first();

                if ($cashflow) {
                    $cashflow->update([
                        'amount' => $amountPaid,
                        'transaction_date' => $this->payroll_payment_date,
                        'description' => "Gaji Worker: {$this->selectedPayroll->worker->name} (Unit {$this->selectedPayroll->unit->code}) - Rp " . number_format($amountPaid, 0, ',', '.'),
                    ]);
                }
            });

            session()->flash('success', 'Pembayaran gaji unit ' . $this->selectedPayroll->unit->code . ' berhasil diperbarui! Perubahan sudah otomatis memperbarui PDF Resi & QR Code.');
        } else {
            DB::transaction(function () use ($amountGross, $amountPaid, $photoPath) {
                $payment = WorkerSalaryPayment::create([
                    'worker_unit_payroll_id' => $this->selectedPayroll->id,
                    'payment_date' => $this->payroll_payment_date,
                    'amount_gross' => $amountGross,
                    'loan_deduction' => 0,
                    'amount_paid' => $amountPaid,
                    'payment_method' => $this->payroll_payment_method,
                    'bank_name' => null,
                    'account_number' => null,
                    'receipt_photo_path' => $photoPath,
                    'notes' => $this->payroll_payment_notes,
                    'created_by' => Auth::id(),
                ]);

                $newPaidTotal = (float)$this->selectedPayroll->paid_amount + $amountGross;
                $status = $newPaidTotal >= (float)$this->selectedPayroll->agreed_salary ? 'lunas' : 'berjalan';

                $this->selectedPayroll->update([
                    'paid_amount' => $newPaidTotal,
                    'status' => $status,
                ]);

                CashflowTransaction::create([
                    'project_id' => $this->selectedPayroll->project_id,
                    'type' => 'keluar',
                    'category' => 'pembayaran_tukang',
                    'amount' => $amountPaid,
                    'transaction_date' => $this->payroll_payment_date,
                    'description' => "Gaji Worker: {$this->selectedPayroll->worker->name} (Unit {$this->selectedPayroll->unit->code}) - Rp " . number_format($amountPaid, 0, ',', '.'),
                    'reference_type' => WorkerSalaryPayment::class,
                    'reference_id' => $payment->id,
                    'created_by' => Auth::id(),
                ]);
            });

            session()->flash('success', 'Pembayaran gaji unit ' . $this->selectedPayroll->unit->code . ' berhasil disimpan!');
        }

        $this->showPayrollPaymentModal = false;
        $this->selectedPayroll = null;
        $this->editingSalaryPaymentId = null;
    }

    // Modal Setoran Cicilan Pembeli (Khusus Finance & Founder)
    public ?int $editingInstallmentPaymentId = null;

    public function openInstallmentPaymentModal(): void
    {
        $unit = Unit::with('installment')->findOrFail($this->unitId);
        if (!$unit->installment) {
            session()->flash('error', 'Unit ini belum memiliki skema cicilan aktif.');
            return;
        }

        $this->resetValidation();
        $this->editingInstallmentPaymentId = null;
        $this->installment_payment_amount = $unit->installment->installment_amount;
        $this->installment_payment_date = now()->toDateString();
        $this->installment_payment_method = 'Transfer Bank';
        $this->installment_payment_notes = '';
        $this->installment_payment_receipt_photo = null;
        $this->existing_installment_receipt_photo_path = null;
        $this->showInstallmentPaymentModal = true;
    }

    public function editInstallmentPayment(int $id): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Akses ditolak. Hanya Founder dan Finance.');
            return;
        }
        $pay = InstallmentPayment::findOrFail($id);
        $this->resetValidation();
        $this->editingInstallmentPaymentId = $pay->id;
        $this->installment_payment_amount = $pay->amount_paid;
        $this->installment_payment_date = $pay->payment_date ? $pay->payment_date->format('Y-m-d') : date('Y-m-d');
        $this->installment_payment_method = $pay->payment_method;
        $this->installment_payment_notes = $pay->notes ?? '';
        $this->installment_payment_receipt_photo = null;
        $this->existing_installment_receipt_photo_path = $pay->receipt_photo_path;
        $this->showInstallmentPaymentModal = true;
    }

    public function deleteInstallmentPayment(int $id): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Akses ditolak. Hanya Founder dan Finance.');
            return;
        }
        $pay = InstallmentPayment::findOrFail($id);
        $inst = $pay->installment;

        DB::transaction(function () use ($pay, $inst) {
            CashflowTransaction::where('reference_type', InstallmentPayment::class)
                ->where('reference_id', $pay->id)
                ->delete();
            $pay->delete();

            if ($inst) {
                $totalPaid = $inst->down_payment + $inst->payments()->sum('amount_paid');
                $status = ($totalPaid >= $inst->total_price) ? 'lunas' : 'berjalan';
                $inst->update(['status' => $status]);
            }
        });

        session()->flash('success', 'Setoran cicilan pembeli berhasil dihapus!');
    }

    public function saveInstallmentPayment(): void
    {
        $user = auth()->user();
        if (!$user->isFinance() && !$user->isAdminOrFounder()) {
            session()->flash('error', 'Hanya tim Finance dan Founder yang berhak mencatat setoran cicilan pembeli.');
            return;
        }

        $this->validate([
            'installment_payment_amount' => 'required|numeric|min:1000',
            'installment_payment_date' => 'required|date',
            'installment_payment_method' => 'required|string',
            'installment_payment_notes' => 'nullable|string',
            'installment_payment_receipt_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp,heic,heif|max:2048',
        ], [
            'installment_payment_receipt_photo.image' => 'File bukti setoran cicilan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
            'installment_payment_receipt_photo.mimes' => 'File bukti setoran cicilan harus berupa foto/gambar (JPG, JPEG, PNG, WEBP, HEIC).',
        ]);

        $unit = Unit::with('installment')->findOrFail($this->unitId);
        $inst = $unit->installment;

        if (!$inst) {
            session()->flash('error', 'Skema cicilan tidak ditemukan.');
            return;
        }

        $receiptPhotoPath = null;
        if ($this->installment_payment_receipt_photo) {
            $receiptPhotoPath = \App\Services\ImageCompressor::compressAndStore($this->installment_payment_receipt_photo, 'installment-receipts');
        }

        $hasReceiptColPayment = \Illuminate\Support\Facades\Schema::hasColumn('installment_payments', 'receipt_photo_path');
        $hasReceiptColCashflow = \Illuminate\Support\Facades\Schema::hasColumn('cashflow_transactions', 'receipt_photo_path');

        if ($this->editingInstallmentPaymentId) {
            $pay = InstallmentPayment::findOrFail($this->editingInstallmentPaymentId);
            DB::transaction(function () use ($unit, $inst, $pay, $receiptPhotoPath, $hasReceiptColPayment, $hasReceiptColCashflow) {
                $payData = [
                    'payment_date' => $this->installment_payment_date,
                    'amount_paid' => $this->installment_payment_amount,
                    'payment_method' => $this->installment_payment_method,
                    'notes' => $this->installment_payment_notes,
                ];

                if ($receiptPhotoPath && $hasReceiptColPayment) {
                    $payData['receipt_photo_path'] = $receiptPhotoPath;
                }

                $pay->update($payData);

                $cashflow = CashflowTransaction::where('reference_type', InstallmentPayment::class)
                    ->where('reference_id', $pay->id)
                    ->first();

                if ($cashflow) {
                    $cashData = [
                        'amount' => $this->installment_payment_amount,
                        'transaction_date' => $this->installment_payment_date,
                        'description' => 'Setoran Cicilan Pembeli Unit ' . $unit->code . ' (' . $this->installment_payment_method . ')',
                    ];
                    if ($receiptPhotoPath && $hasReceiptColCashflow) {
                        $cashData['receipt_photo_path'] = $receiptPhotoPath;
                    }
                    $cashflow->update($cashData);
                }

                $totalPaid = $inst->down_payment + $inst->payments()->sum('amount_paid');
                $status = ($totalPaid >= $inst->total_price) ? 'lunas' : 'berjalan';
                $inst->update(['status' => $status]);
            });
            session()->flash('success', 'Setoran cicilan pembeli berhasil diperbarui!');
        } else {
            DB::transaction(function () use ($unit, $inst, $receiptPhotoPath, $hasReceiptColPayment, $hasReceiptColCashflow) {
                $createData = [
                    'unit_installment_id' => $inst->id,
                    'payment_date' => $this->installment_payment_date,
                    'amount_paid' => $this->installment_payment_amount,
                    'payment_method' => $this->installment_payment_method,
                    'notes' => $this->installment_payment_notes,
                    'created_by' => Auth::id(),
                ];
                if ($receiptPhotoPath && $hasReceiptColPayment) {
                    $createData['receipt_photo_path'] = $receiptPhotoPath;
                }

                $payment = InstallmentPayment::create($createData);

                $cashData = [
                    'project_id' => $unit->project_id,
                    'type' => 'masuk',
                    'category' => 'pembayaran_cicilan_pembeli',
                    'amount' => $this->installment_payment_amount,
                    'transaction_date' => $this->installment_payment_date,
                    'description' => 'Setoran Cicilan Pembeli Unit ' . $unit->code . ' (' . $this->installment_payment_method . ')',
                    'reference_type' => InstallmentPayment::class,
                    'reference_id' => $payment->id,
                    'created_by' => Auth::id(),
                ];
                if ($receiptPhotoPath && $hasReceiptColCashflow) {
                    $cashData['receipt_photo_path'] = $receiptPhotoPath;
                }
                CashflowTransaction::create($cashData);

                $totalPaid = $inst->down_payment + $inst->payments()->sum('amount_paid');
                if ($totalPaid >= $inst->total_price) {
                    $inst->update(['status' => 'lunas']);
                }
            });
            session()->flash('success', 'Setoran cicilan pembeli Rp ' . number_format($this->installment_payment_amount, 0, ',', '.') . ' berhasil dicatat!');
        }

        $this->showInstallmentPaymentModal = false;
        $this->editingInstallmentPaymentId = null;
        $this->installment_payment_receipt_photo = null;
    }

    // Modal Batalkan Skema Cicilan & Dialihkan ke Cash (Khusus Founder & Finance)
    public bool $showConvertToCashModal = false;
    public $cash_payment_amount = 0;
    public string $cash_payment_date = '';
    public string $cash_payment_method = 'Transfer Bank';
    public string $cash_notes = '';

    public function openConvertToCashModal(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Hanya tim Finance, Founder, dan Admin yang berhak membatalkan cicilan dan menggantinya ke Cash.');
            return;
        }

        $unit = Unit::with('installment.payments')->findOrFail($this->unitId);
        if (!$unit->installment) {
            session()->flash('error', 'Unit ini belum memiliki skema cicilan aktif.');
            return;
        }

        $this->resetValidation();
        $this->cash_payment_amount = $unit->installment->remaining_balance;
        $this->cash_payment_date = now()->toDateString();
        $this->cash_payment_method = 'Transfer Bank';
        $this->cash_notes = 'Pembatalan skema cicilan unit ' . $unit->code . ' dan konversi pelunasan tunai/cash.';
        $this->showConvertToCashModal = true;
    }

    public function saveConvertToCash(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Hanya tim Finance, Founder, dan Admin yang berhak membatalkan skema cicilan.');
            return;
        }

        $this->validate([
            'cash_payment_amount' => 'required|numeric|min:0',
            'cash_payment_date' => 'required|date',
            'cash_payment_method' => 'required|string',
            'cash_notes' => 'nullable|string',
        ]);

        $unit = Unit::with('installment')->findOrFail($this->unitId);
        $inst = $unit->installment;

        if (!$inst) {
            session()->flash('error', 'Skema cicilan tidak ditemukan.');
            return;
        }

        DB::transaction(function () use ($unit, $inst) {
            if ($this->cash_payment_amount > 0) {
                InstallmentPayment::create([
                    'unit_installment_id' => $inst->id,
                    'payment_date' => $this->cash_payment_date,
                    'amount_paid' => $this->cash_payment_amount,
                    'payment_method' => $this->cash_payment_method,
                    'notes' => '[Pelunasan Cash - Pembatalan Skema Cicilan] ' . $this->cash_notes,
                    'created_by' => Auth::id(),
                ]);

                CashflowTransaction::create([
                    'project_id' => $unit->project_id,
                    'type' => 'masuk',
                    'category' => 'pembayaran_cicilan_pembeli',
                    'amount' => $this->cash_payment_amount,
                    'transaction_date' => $this->cash_payment_date,
                    'description' => 'Pelunasan Cash (Pembatalan Skema Cicilan) Unit ' . $unit->code . ' (' . $this->cash_payment_method . ')',
                    'reference_type' => UnitInstallment::class,
                    'reference_id' => $inst->id,
                    'created_by' => Auth::id(),
                ]);
            }

            $inst->update(['status' => 'konversi_cash']);
            $unit->update(['status' => 'lunas']);

            \App\Services\ActivityLogger::log('CANCEL_INSTALLMENT_TO_CASH', "Founder/Accounting membatalkan skema cicilan Unit {$unit->code} dan menggantinya ke Pelunasan Cash Lunas sebesar Rp " . number_format($this->cash_payment_amount, 0, ',', '.'));
        });

        session()->flash('success', 'Skema cicilan unit ' . $unit->code . ' berhasil dibatalkan dan dialihkan ke Pelunasan Cash Lunas!');
        $this->showConvertToCashModal = false;
    }

    // Modal Setup & Edit Skema Cicilan
    public function openSetupInstallmentModal(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Akses ditolak. Hanya Founder dan Finance yang dapat mengonfigurasi skema cicilan.');
            return;
        }

        $unit = Unit::with(['installment', 'activeBooking'])->findOrFail($this->unitId);
        $this->resetValidation();

        $booking = $unit->activeBooking;
        $alreadyPaid = $booking ? max((float)$booking->dp_amount, (float)$booking->booking_amount) : 0;

        if ($unit->installment) {
            $this->setup_total_price = (float)$unit->installment->total_price;
            $this->setup_down_payment = (float)$unit->installment->down_payment;
            $this->setup_installment_count = (int)$unit->installment->installment_count;
            $this->setup_start_date = $unit->installment->start_date ? $unit->installment->start_date->format('Y-m-d') : now()->toDateString();
        } else {
            $approvedProp = $unit->proposals->where('status', 'disetujui')->first();
            $defaultPrice = $unit->final_selling_price ?: ($unit->officialDocument->proposal->proposed_price ?? ($approvedProp->proposed_price ?? ($unit->proposals->first()?->proposed_price ?? 0)));
            $this->setup_total_price = (float)$defaultPrice;
            $this->setup_down_payment = $alreadyPaid > 0 ? $alreadyPaid : ($this->setup_total_price * 0.20);
            $this->setup_installment_count = 12;
            $this->setup_start_date = now()->toDateString();
        }

        $this->calculateMonthlyInstallment();
        $this->showSetupInstallmentModal = true;
    }

    public function calculateMonthlyInstallment(): void
    {
        $rem = max(0, (float)$this->setup_total_price - (float)$this->setup_down_payment);
        $count = max(1, (int)$this->setup_installment_count);
        $this->setup_installment_amount = $rem / $count;
    }

    public function saveSetupInstallment(): void
    {
        if (!$this->canManageFinancial()) {
            session()->flash('error', 'Hanya tim Finance, Founder, dan Admin yang berhak mengonfigurasi skema cicilan.');
            return;
        }

        $this->validate([
            'setup_total_price' => 'required|numeric|min:1000',
            'setup_installment_count' => 'required|integer|min:1',
            'setup_start_date' => 'required|date',
        ]);

        $unit = Unit::with(['officialDocument', 'installment.payments', 'activeBooking'])->findOrFail($this->unitId);

        DB::transaction(function () use ($unit) {
            if ($unit->installment) {
                // Update existing installment scheme
                $paidSoFar = (float)$this->setup_down_payment + (float)$unit->installment->payments->sum('amount_paid');
                $remUnpaid = max(0, (float)$this->setup_total_price - $paidSoFar);
                $newStatus = $remUnpaid <= 0 ? 'lunas' : 'berjalan';

                $unit->installment->update([
                    'total_price' => $this->setup_total_price,
                    'down_payment' => $this->setup_down_payment,
                    'installment_count' => $this->setup_installment_count,
                    'installment_amount' => $this->setup_installment_amount,
                    'start_date' => $this->setup_start_date,
                    'status' => $newStatus,
                ]);

                // Sync Cashflow DP transaction if present
                $dpCashflow = CashflowTransaction::where('reference_type', UnitInstallment::class)
                    ->where('reference_id', $unit->installment->id)
                    ->first();
                if ($dpCashflow) {
                    $dpCashflow->update([
                        'amount' => $this->setup_down_payment,
                        'transaction_date' => $this->setup_start_date,
                    ]);
                }
            } else {
                // Create new installment scheme
                $installment = UnitInstallment::create([
                    'unit_id' => $unit->id,
                    'official_document_id' => $unit->officialDocument->id ?? null,
                    'total_price' => $this->setup_total_price,
                    'down_payment' => $this->setup_down_payment,
                    'installment_count' => $this->setup_installment_count,
                    'installment_amount' => $this->setup_installment_amount,
                    'start_date' => $this->setup_start_date,
                    'status' => 'berjalan',
                ]);

                $booking = $unit->activeBooking;
                $alreadyPaid = $booking ? max((float)$booking->dp_amount, (float)$booking->booking_amount) : 0;
                $netDpCashflow = max(0, (float)$this->setup_down_payment - $alreadyPaid);

                if ($netDpCashflow > 0) {
                    CashflowTransaction::create([
                        'project_id' => $unit->project_id,
                        'type' => 'masuk',
                        'category' => 'pembayaran_cicilan_pembeli',
                        'amount' => $netDpCashflow,
                        'transaction_date' => $this->setup_start_date,
                        'description' => 'Pembayaran Uang Muka (DP) Unit ' . $unit->code . ($alreadyPaid > 0 ? ' (Net Tambahan DP, memperhitungkan Booking Fee Rp ' . number_format($alreadyPaid, 0, ',', '.') . ' yang sudah tercatat)' : ''),
                        'reference_type' => UnitInstallment::class,
                        'reference_id' => $installment->id,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        });

        session()->flash('success', 'Skema cicilan & piutang unit ' . $unit->code . ' berhasil diperbarui!');
        $this->showSetupInstallmentModal = false;
    }

    public function deleteInstallmentScheme(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isSuperAdmin()) {
            session()->flash('error', 'Hanya Founder dan Supervisor yang berhak menghapus skema cicilan pembeli.');
            return;
        }

        $unit = Unit::with('installment')->findOrFail($this->unitId);
        if (!$unit->installment) {
            session()->flash('error', 'Unit ini tidak memiliki skema cicilan aktif.');
            return;
        }

        $instId = $unit->installment->id;
        $code = $unit->code;

        DB::transaction(function () use ($unit, $instId, $code) {
            $paymentIds = InstallmentPayment::where('unit_installment_id', $instId)->pluck('id');

            // 1. Delete associated cashflow transactions
            CashflowTransaction::where('reference_type', UnitInstallment::class)
                ->where('reference_id', $instId)
                ->delete();

            if ($paymentIds->count() > 0) {
                CashflowTransaction::where('reference_type', InstallmentPayment::class)
                    ->whereIn('reference_id', $paymentIds)
                    ->delete();
            }

            // 2. Delete payments
            InstallmentPayment::where('unit_installment_id', $instId)->delete();

            // 3. Delete unit installment scheme
            $unit->installment->delete();

            \App\Services\ActivityLogger::log(
                'DELETE_INSTALLMENT_SCHEME',
                "Founder menghapus skema cicilan & piutang pembeli untuk Unit {$code}"
            );
        });

        session()->flash('success', "Skema cicilan Unit {$code} berhasil dihapus dari sistem!");
    }



    // 3. Direct Booking Handler (Req #2)
    public function openBookingModal(): void
    {
        $unit = Unit::findOrFail($this->unitId);
        $this->buyer_name = '';
        $this->buyer_phone = '';
        $this->booking_amount = 5000000;
        $this->dp_amount = 0;
        $this->booking_notes = 'Booking unit ' . $unit->code . ' via Halaman Detail Unit.';
        $this->receipt_photo = null;
        $this->showBookingModal = true;
    }

    public function updatedReceiptPhoto(): void
    {
        $this->validate([
            'receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
        ]);
    }

    public function saveBooking(): void
    {
        $user = auth()->user();
        if (!$user->isMarketing() && !$user->isFinance() && !$user->isFounder()) {
            session()->flash('error', 'Hanya tim Sales Marketing, Finance, dan Founder yang berhak mendaftarkan booking unit.');
            return;
        }

        if ($this->booking_amount) {
            $this->booking_amount = (float) preg_replace('/[^0-9]/', '', (string)$this->booking_amount);
        }

        $this->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:50',
            'booking_amount' => 'required|numeric|min:1000',
            'receipt_photo' => 'nullable|image|max:2048',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        $receiptPath = null;
        if ($this->receipt_photo) {
            $receiptPath = $this->receipt_photo->store('receipts/bookings', 'public');
        }

        Booking::create([
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'buyer_name' => $this->buyer_name,
            'buyer_phone' => $this->buyer_phone,
            'booking_type' => 'unit',
            'booking_amount' => $this->booking_amount,
            'dp_amount' => 0,
            'booking_date' => now()->toDateString(),
            'expiry_date' => now()->addDays(14)->toDateString(),
            'status' => 'active',
            'notes' => $this->booking_notes,
            'receipt_photo_path' => $receiptPath,
            'created_by' => Auth::id(),
        ]);


        $unit->update(['status' => 'booked']);

        session()->flash('success', 'Booking unit ' . $unit->code . ' atas nama ' . $this->buyer_name . ' berhasil dicatat!');
        $this->showBookingModal = false;
    }

    public function openCommissionModal(): void
    {
        $user = Auth::user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat hutang komisi penjual.');
            return;
        }

        $unit = Unit::with('installment')->findOrFail($this->unitId);
        $this->resetValidation();
        $this->unit_comm_seller_name = '';
        $this->unit_comm_seller_phone = '';
        $this->unit_comm_marketing_id = null;
        $this->unit_comm_percentage = 2.5;
        
        $basePrice = $unit->installment ? (float)$unit->installment->total_price : (float)($unit->final_selling_price ?: $unit->price);
        $this->unit_comm_amount = round(($this->unit_comm_percentage / 100) * $basePrice);
        $this->unit_comm_notes = "Komisi Penjualan Unit {$unit->code}";
        $this->showCommissionModal = true;
    }

    public function updatedUnitCommPercentage(): void
    {
        $unit = Unit::with('installment')->find($this->unitId);
        if ($unit) {
            $basePrice = $unit->installment ? (float)$unit->installment->total_price : (float)($unit->final_selling_price ?: $unit->price);
            $this->unit_comm_amount = round(((float)$this->unit_comm_percentage / 100) * $basePrice);
        }
    }

    public function saveCommission(): void
    {
        $user = Auth::user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat hutang komisi penjual.');
            return;
        }

        if ($this->unit_comm_amount) {
            $this->unit_comm_amount = (float) preg_replace('/[^0-9]/', '', (string)$this->unit_comm_amount);
        }

        $this->validate([
            'unit_comm_seller_name' => 'required|string|max:255',
            'unit_comm_seller_phone' => 'nullable|string|max:50',
            'unit_comm_marketing_id' => 'nullable|exists:users,id',
            'unit_comm_percentage' => 'nullable|numeric|min:0|max:100',
            'unit_comm_amount' => 'required|numeric|min:1000',
            'unit_comm_notes' => 'nullable|string|max:500',
        ]);

        $unit = Unit::findOrFail($this->unitId);

        $comm = \App\Models\UnitCommission::create([
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'marketing_id' => $this->unit_comm_marketing_id ?: null,
            'seller_name' => trim($this->unit_comm_seller_name),
            'seller_phone' => trim($this->unit_comm_seller_phone) ?: null,
            'percentage' => (float)($this->unit_comm_percentage ?: 0),
            'commission_amount' => (float)$this->unit_comm_amount,
            'paid_amount' => 0,
            'status' => 'belum_dibayar',
            'notes' => $this->unit_comm_notes,
            'created_by' => $user->id,
        ]);

        \App\Services\ActivityLogger::log(
            'COMMISSION_DEBT_CREATED',
            "User {$user->name} mencatat hutang komisi penjual '{$comm->seller_name}' untuk Unit {$unit->code} sebesar Rp " . number_format($comm->commission_amount, 0, ',', '.')
        );

        $this->showCommissionModal = false;
        $msg = "Catatan Hutang Komisi Penjual '{$comm->seller_name}' berhasil disimpan!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Komisi Dicatat!', 'message' => $msg]);
    }

    public function openCommissionPaymentModal(int $commissionId): void
    {
        $user = Auth::user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat pembayaran cicilan komisi.');
            return;
        }

        $comm = \App\Models\UnitCommission::with('unit')->findOrFail($commissionId);
        $this->unit_settling_commission_id = $comm->id;
        $this->unit_pay_comm_amount = $comm->remaining_amount;
        $this->unit_pay_comm_date = now()->toDateString();
        $this->unit_pay_comm_method = 'Transfer Bank';
        $this->unit_pay_comm_notes = "Pembayaran Cicilan Komisi: {$comm->seller_name} (Unit " . ($comm->unit->code ?? '-') . ")";
        $this->unit_pay_comm_photo = null;
        $this->showCommissionPaymentModal = true;
    }

    public function processCommissionPayment(): void
    {
        if (!$this->unit_settling_commission_id) {
            return;
        }

        $user = Auth::user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Tim Finance yang berhak mencatat pembayaran cicilan komisi.');
            return;
        }

        $comm = \App\Models\UnitCommission::with('unit')->findOrFail($this->unit_settling_commission_id);

        if ($this->unit_pay_comm_amount) {
            $this->unit_pay_comm_amount = (float) preg_replace('/[^0-9]/', '', (string)$this->unit_pay_comm_amount);
        }

        $this->validate([
            'unit_pay_comm_amount' => 'required|numeric|min:1000|max:' . $comm->remaining_amount,
            'unit_pay_comm_date' => 'required|date',
            'unit_pay_comm_method' => 'required|string',
            'unit_pay_comm_notes' => 'nullable|string',
            'unit_pay_comm_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:2048',
        ], [
            'unit_pay_comm_amount.max' => 'Nominal pembayaran cicilan komisi tidak boleh melebihi sisa hutang komisi (Rp ' . number_format($comm->remaining_amount, 0, ',', '.') . ').',
        ]);

        $photoPath = null;
        if ($this->unit_pay_comm_photo) {
            $photoPath = \App\Services\ImageCompressor::compressAndStore($this->unit_pay_comm_photo, 'commission-receipts');
        }

        DB::transaction(function () use ($comm, $photoPath, $user) {
            $payment = \App\Models\UnitCommissionPayment::create([
                'unit_commission_id' => $comm->id,
                'payment_date' => $this->unit_pay_comm_date,
                'amount' => $this->unit_pay_comm_amount,
                'payment_method' => $this->unit_pay_comm_method,
                'notes' => $this->unit_pay_comm_notes,
                'receipt_photo_path' => $photoPath,
                'created_by' => $user->id,
            ]);

            $unitCode = $comm->unit ? " Unit {$comm->unit->code}" : '';
            $description = "Pembayaran Cicilan Komisi Penjual ({$comm->seller_name}){$unitCode}: Rp " . number_format($this->unit_pay_comm_amount, 0, ',', '.');

            CashflowTransaction::create([
                'project_id' => $comm->project_id,
                'type' => 'keluar',
                'category' => 'operasional',
                'amount' => $this->unit_pay_comm_amount,
                'transaction_date' => $this->unit_pay_comm_date,
                'description' => $description,
                'reference_type' => \App\Models\UnitCommissionPayment::class,
                'reference_id' => $payment->id,
                'receipt_photo_path' => $photoPath,
                'created_by' => $user->id,
            ]);

            $comm->recalculateStatus();
        });

        \App\Services\ActivityLogger::log(
            'COMMISSION_PAYMENT_RECORDED',
            "User {$user->name} mencatat pembayaran cicilan komisi '{$comm->seller_name}' sebesar Rp " . number_format($this->unit_pay_comm_amount, 0, ',', '.') . " & tercatat di Arus Kas."
        );

        $this->showCommissionPaymentModal = false;
        $this->unit_settling_commission_id = null;

        $msg = "Pembayaran cicilan komisi '{$comm->seller_name}' sebesar Rp " . number_format($this->unit_pay_comm_amount, 0, ',', '.') . " berhasil dicatat ke Kas Keluar!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Pembayaran Berhasil!', 'message' => $msg]);
    }

    public function deleteCommission(int $commissionId): void
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            session()->flash('error', 'Hanya Founder dan Supervisor yang berhak menghapus catatan komisi.');
            return;
        }

        $comm = \App\Models\UnitCommission::where('unit_id', $this->unitId)->findOrFail($commissionId);

        DB::transaction(function () use ($comm) {
            foreach ($comm->payments as $p) {
                CashflowTransaction::where('reference_type', \App\Models\UnitCommissionPayment::class)
                    ->where('reference_id', $p->id)
                    ->delete();
            }
            $comm->delete();
        });

        session()->flash('success', 'Catatan komisi penjual berhasil dihapus.');
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

        $manualInvoices = \App\Models\ManualInvoice::with('creator')
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

        $combinedExpenses = collect();

        foreach ($unitPayrolls as $up) {
            $combinedExpenses->push((object)[
                'id' => $up->id,
                'source_type' => 'payroll_setup',
                'date' => $up->created_at,
                'category_badge' => 'Kontrak Gaji',
                'badge_class' => 'bg-purple-100 text-purple-800 border-purple-200',
                'description' => 'Kontrak Borongan Gaji ' . $up->worker->name . ' (' . strtoupper($up->status) . ' - Terbayar Rp ' . number_format($up->paid_amount, 0, ',', '.') . ' / Total Rp ' . number_format($up->agreed_salary, 0, ',', '.') . ')',
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
                'description' => 'Pembayaran Gaji ' . $sp->payroll->worker->name . ' (' . str_replace('_', ' ', $sp->payment_method) . ')',
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

        $allWorkers = Worker::where('status', 'active')->orderBy('name')->get();

        $unitCommissions = \App\Models\UnitCommission::with(['payments.creator', 'marketing', 'creator'])
            ->where('unit_id', $unit->id)
            ->latest('id')
            ->get();

        return view('livewire.units.show', [
            'unit' => $unit,
            'unitAssignments' => $unitAssignments,
            'materialPurchases' => $materialPurchases,
            'manualInvoices' => $manualInvoices,
            'totalCashIn' => $totalCashIn,
            'allWorkers' => $allWorkers,
            'unitPayrolls' => $unitPayrolls,
            'unitCommissions' => $unitCommissions,
            'combinedExpenses' => $combinedExpenses,
            'showWorkerModal' => $this->showWorkerModal,
            'showBookingModal' => $this->showBookingModal,
            'showPayrollSetupModal' => $this->showPayrollSetupModal,
            'showPayrollPaymentModal' => $this->showPayrollPaymentModal,
            'showInstallmentPaymentModal' => $this->showInstallmentPaymentModal,
            'showSetupInstallmentModal' => $this->showSetupInstallmentModal,
            'showConvertToCashModal' => $this->showConvertToCashModal,
            'showMaterialModal' => $this->materialModal ?? $this->showMaterialModal,
            'showViewerModal' => $this->showViewerModal,
            'showDirectSppModal' => $this->showDirectSppModal,
            'showDirectProposalModal' => $this->showDirectProposalModal,
            'showCommissionModal' => $this->showCommissionModal,
            'showCommissionPaymentModal' => $this->showCommissionPaymentModal,
            'editingSalaryPaymentId' => $this->editingSalaryPaymentId,
        ])->layout('components.layouts.app', ['title' => 'Detail Unit ' . $unit->code . ' - ' . $unit->project->name]);
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
            \App\Services\CascadeDeletionService::deleteUnit($unit);
            \App\Services\ActivityLogger::log(
                'DELETE_UNIT',
                "Founder menghapus Unit {$code} dari sistem beserta seluruh histori terikatnya"
            );
        });

        session()->flash('success', 'Unit ' . $code . ' berhasil dihapus dari sistem!');
        return redirect()->route('units.index');
    }
}
