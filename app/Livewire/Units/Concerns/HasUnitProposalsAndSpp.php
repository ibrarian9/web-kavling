<?php

namespace App\Livewire\Units\Concerns;

use App\Models\Approval;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Unit;
use App\Models\User;
use App\Services\ActivityLogger;

trait HasUnitProposalsAndSpp
{
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

    // Modal Direct Proposal State
    public bool $showDirectProposalModal = false;
    public ?int $editingProposalId = null;
    public $prop_hpp_price = 0;
    public $prop_proposed_price = 0;
    public string $prop_price_mode = 'total'; // 'total' | 'per_sqm'
    public $prop_price_per_sqm = 0;
    public string $prop_notes = '';

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
            $this->spp_cash_price = (float)($unit->total_price ?: ($unit->project?->base_price ?? 0));
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

        $doc = OfficialDocument::with(['unit', 'proposal'])->findOrFail($id);
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

        $doc = OfficialDocument::with(['unit.activeBooking', 'unit.proposals', 'unit.installment'])->findOrFail($id);
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

        ActivityLogger::log('DOCUMENT_DELETED', "Dokumen SPP {$docNumber} (ID #{$id}) unit {$unit?->code} telah dihapus via Detail Unit oleh {$user->name}. {$statusMsg}");

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
            $doc = OfficialDocument::with('proposal')->findOrFail($this->editingSppId);
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

            ActivityLogger::log('DOCUMENT_UPDATED', "Dokumen SPP {$doc->document_number} (Unit {$unit->code}) diperbarui oleh {$user->name} via Detail Unit.");

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

            $proposal = PriceProposal::create([
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

        $doc = OfficialDocument::create([
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

        ActivityLogger::log('DOCUMENT_GENERATED', "Dokumen SPP & SPJB PDF {$docNumber} diterbitkan langsung per unit {$unit->code} oleh Founder.");

        $this->showDirectSppModal = false;
        $msg = 'Dokumen SPP & SPJB PDF ' . $docNumber . ' berhasil diterbitkan!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'SPP Terbit!', 'message' => $msg]);

        $this->openViewerModal('pdf', route('documents.stream', $doc->id), 'Pratinjau SPP & SPJB PDF - ' . $doc->document_number);
    }

    public function approveProposal(int $proposalId, string $decision): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdminOrFounder() && !$user->isSupervisor())) {
            $err = 'Hanya Admin, Founder, dan Supervisor yang berhak mengesahkan approval harga.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $proposal = PriceProposal::with('unit.project')->findOrFail($proposalId);
        $userRole = $user->role;

        Approval::updateOrCreate(
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

        ActivityLogger::log(
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

                $existingDoc = OfficialDocument::where('price_proposal_id', $proposal->id)->first();
                if (!$existingDoc) {
                    $docNumber = 'SPP/' . strtoupper($proposal->unit->project->name ?? 'PROYEK') . '/' . date('Y/m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                    OfficialDocument::create([
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
        $user = auth()->user();
        if (!$user || !$user->isAdminOrFounder()) {
            $err = 'Hanya Admin dan Founder yang berhak mengajukan harga.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $err]);
            return;
        }

        $unit = Unit::findOrFail($this->unitId);
        $this->resetValidation();
        $this->editingProposalId = null;
        $this->prop_hpp_price = (float)$unit->total_price;
        $this->prop_proposed_price = (float)($unit->final_selling_price ?: ($unit->price ?: $unit->total_price));
        $this->prop_price_mode = 'total';
        $this->prop_price_per_sqm = (float)($unit->land_area ?? 0) > 0 ? round((float)$this->prop_proposed_price / (float)$unit->land_area) : 0;
        $this->prop_notes = '';
        $this->showDirectProposalModal = true;
    }

    public function editDirectProposal(int $id): void
    {
        $user = auth()->user();
        if (!$user || !$user->isAdminOrFounder()) {
            $err = 'Hanya Admin dan Founder yang berhak mengedit proposal.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Akses Ditolak', 'message' => $err]);
            return;
        }

        $proposal = PriceProposal::with('unit')->findOrFail($id);
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

        $proposal = PriceProposal::with(['unit', 'approvals'])->findOrFail($id);
        $unit = $proposal->unit;
        $unitCode = $unit->code ?? 'Unit';

        if ($unit && $unit->status === 'menunggu_persetujuan') {
            $unit->update(['status' => 'tersedia']);
        }

        OfficialDocument::where('price_proposal_id', $proposal->id)->delete();
        Approval::where('price_proposal_id', $proposal->id)->delete();

        $proposal->delete();

        ActivityLogger::log('PROPOSAL_DELETED', "Pengajuan harga unit {$unitCode} (ID #{$id}) telah dihapus via Detail Unit oleh {$user->name}.");

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
        if (!$user || !$user->isAdminOrFounder()) {
            $err = 'Hanya Admin dan Founder yang berhak membuat/mengedit pengajuan harga.';
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
            $proposal = PriceProposal::findOrFail($this->editingProposalId);
            $proposal->update([
                'proposed_price' => $proposed,
                'margin' => $margin,
                'is_below_hpp' => $isBelowHpp,
                'notes' => $this->prop_notes,
            ]);

            if ($proposal->status === 'disetujui') {
                $unit->update(['final_selling_price' => $proposed]);
            }

            ActivityLogger::log('PROPOSAL_UPDATED', "Pengajuan harga unit {$unit->code} (ID #{$proposal->id}) diperbarui via Detail Unit oleh {$user->name}.");

            $this->showDirectProposalModal = false;
            $this->editingProposalId = null;
            $msg = 'Proposal harga unit ' . $unit->code . ' berhasil diperbarui!';
            session()->flash('success', $msg);
            $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
            return;
        }

        $status = $user->isAdminOrFounder() ? 'disetujui' : 'menunggu';

        $proposal = PriceProposal::create([
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
            Approval::create([
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
            OfficialDocument::create([
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

        ActivityLogger::log('PROPOSAL_CREATED', "Proposal harga Rp " . number_format($proposed, 0, ',', '.') . " diajukan untuk Unit {$unit->code} via Detail Unit.");

        $msg = 'Proposal harga berhasil diajukan' . ($user->isAdminOrFounder() ? ' & langsung disetujui!' : '!');
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showDirectProposalModal = false;
    }
}
