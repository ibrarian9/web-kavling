<?php

namespace App\Livewire\Proposals;

use App\Models\Approval;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Unit;
use App\Services\ActivityLogger;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $create_unit_id = null;
    public $editingProposalId = null;
    public $showCreateModal = false;
    public $showApprovalModal = false;
    public $showDocModal = false;

    // Proposal Form fields
    public $unit_id = '';
    public $hpp_price = 0;
    public $proposed_price = 0;
    public $margin = 0;
    public $proposal_notes = '';

    // Approval Modal fields
    public $selectedProposalId = null;
    public $approvalProposal = null;
    public $approval_decision = 'disetujui';
    public $approval_notes = '';

    // Official Document Form fields
    public $doc_proposal_id = null;
    public $buyer_name = '';
    public $buyer_contact = '';
    public $buyer_address = '';

    // Viewer Modal (PDF Viewer)
    public bool $showViewerModal = false;
    public string $viewerType = 'pdf';
    public string $viewerUrl = '';
    public string $viewerTitle = '';

    public function openViewerModal(string $type, string $url, string $title = ''): void
    {
        $this->viewerType = $type;
        $this->viewerUrl = $url;
        $this->viewerTitle = $title ?: 'Pratinjau Dokumen SPP PDF';
        $this->showViewerModal = true;
    }

    public function closeViewerModal(): void
    {
        $this->showViewerModal = false;
        $this->viewerType = '';
        $this->viewerUrl = '';
        $this->viewerTitle = '';
    }

    protected $queryString = ['create_unit_id'];

    public function mount()
    {
        if ($this->create_unit_id) {
            $unit = Unit::find($this->create_unit_id);
            if ($unit) {
                $this->selectUnitForProposal($unit->id);
                $this->showCreateModal = true;
            }
        }
    }

    public function selectUnitForProposal($unitId)
    {
        $this->unit_id = $unitId;
        $unit = Unit::find($unitId);
        if ($unit) {
            $this->hpp_price = $unit->hpp ?? 0;
            $this->proposed_price = max($this->hpp_price, $this->hpp_price + 10000000);
            $this->calculateMargin();
        }
    }

    public function updatedProposedPrice()
    {
        $this->calculateMargin();
    }

    public function calculateMargin()
    {
        $this->margin = (float)$this->proposed_price - (float)$this->hpp_price;
    }

    public function openCreateModal()
    {
        $this->editingProposalId = null;
        $this->resetProposalForm();
        $firstAvailable = Unit::where('status', 'tersedia')->first();
        if ($firstAvailable) {
            $this->selectUnitForProposal($firstAvailable->id);
        }
        $this->showCreateModal = true;
    }

    public function resetProposalForm()
    {
        $this->unit_id = '';
        $this->hpp_price = 0;
        $this->proposed_price = 0;
        $this->margin = 0;
        $this->proposal_notes = '';
        $this->discount_reason = '';
    }

    public $discount_reason = '';

    public function editProposal($id)
    {
        $user = auth()->user();
        if (!$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak mengubah pengajuan harga.');
            return;
        }

        $proposal = PriceProposal::with('unit')->findOrFail((int) $id);
        $this->editingProposalId = $proposal->id;
        $this->unit_id = $proposal->unit_id;
        $this->hpp_price = (float) $proposal->hpp_price;
        $this->proposed_price = (float) $proposal->proposed_price;
        $this->margin = (float) $proposal->margin;
        $this->discount_reason = $proposal->discount_reason ?? '';
        $this->proposal_notes = $proposal->notes ?? '';
        $this->showCreateModal = true;
    }

    public function submitProposal()
    {
        $user = auth()->user();

        if ($this->editingProposalId) {
            if (!$user->isFounder()) {
                session()->flash('error', 'Hanya Founder yang berhak mengedit pengajuan harga.');
                return;
            }

            $this->validate([
                'unit_id' => 'required|exists:units,id',
                'proposed_price' => 'required|numeric|min:0',
            ]);

            $proposal = PriceProposal::with('unit')->findOrFail($this->editingProposalId);
            $unit = Unit::findOrFail($this->unit_id);
            $hpp = (float) $unit->hpp;
            $proposed = (float) $this->proposed_price;
            $isBelowHpp = $proposed < $hpp;

            $proposal->update([
                'unit_id' => $unit->id,
                'hpp_price' => $hpp,
                'proposed_price' => $proposed,
                'margin' => $proposed - $hpp,
                'is_below_hpp' => $isBelowHpp,
                'discount_reason' => $this->discount_reason,
                'notes' => $this->proposal_notes,
            ]);

            if ($proposal->status === 'disetujui') {
                $unit->update(['final_selling_price' => $proposed]);
            }

            ActivityLogger::log('PROPOSAL_UPDATED', "Pengajuan harga unit {$unit->code} (ID #{$proposal->id}) telah diperbarui oleh Founder.");

            session()->flash('success', 'Pengajuan harga unit ' . $unit->code . ' berhasil diperbarui oleh Founder.');
            $this->showCreateModal = false;
            $this->editingProposalId = null;
            $this->resetProposalForm();
            return;
        }

        if (!$user->isMarketing() && !$user->isFounder()) {
            session()->flash('error', 'Hanya Marketing dan Founder yang berhak membuat pengajuan harga baru.');
            return;
        }

        $this->validate([
            'unit_id' => 'required|exists:units,id',
            'proposed_price' => 'required|numeric|min:0',
        ]);

        $unit = Unit::findOrFail($this->unit_id);
        $hpp = (float)$unit->hpp;
        $proposed = (float)$this->proposed_price;
        $isBelowHpp = $proposed < $hpp;

        $proposal = PriceProposal::create([
            'unit_id' => $unit->id,
            'hpp_price' => $hpp,
            'proposed_price' => $proposed,
            'margin' => $proposed - $hpp,
            'is_below_hpp' => $isBelowHpp,
            'discount_reason' => $this->discount_reason,
            'proposed_by' => auth()->id(),
            'status' => 'menunggu',
            'notes' => $this->proposal_notes,
        ]);

        // Update unit status to awaiting approval
        $unit->update(['status' => 'menunggu_persetujuan']);

        ActivityLogger::log('PROPOSAL_CREATED', "Pengajuan harga unit {$unit->code} sebesar Rp " . number_format($proposed, 0, ',', '.') . " diajukan oleh " . auth()->user()->name);

        $msg = $isBelowHpp
            ? 'Pengajuan harga Penawaran (< HPP) unit ' . $unit->code . ' sebesar Rp ' . number_format($proposed, 0, ',', '.') . ' berhasil diajukan!'
            : 'Pengajuan harga unit ' . $unit->code . ' sebesar Rp ' . number_format($proposed, 0, ',', '.') . ' berhasil diajukan!';

        session()->flash('success', $msg);
        $this->showCreateModal = false;
        $this->resetProposalForm();
    }

    public function deleteProposal($id)
    {
        $user = auth()->user();
        if (!$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak menghapus pengajuan harga.');
            return;
        }

        $proposal = PriceProposal::with(['unit', 'approvals'])->findOrFail((int) $id);
        $unitCode = $proposal->unit->code ?? 'Unit';

        if ($proposal->unit && $proposal->unit->status === 'menunggu_persetujuan') {
            $proposal->unit->update(['status' => 'tersedia']);
        }

        OfficialDocument::where('price_proposal_id', $proposal->id)->delete();
        Approval::where('price_proposal_id', $proposal->id)->delete();

        $proposal->delete();

        ActivityLogger::log('PROPOSAL_DELETED', "Pengajuan harga unit {$unitCode} (ID #{$id}) telah dihapus oleh Founder.");

        session()->flash('success', 'Pengajuan harga unit ' . $unitCode . ' berhasil dihapus oleh Founder.');
    }

    public function openApprovalModal($proposalId)
    {
        $this->selectedProposalId = $proposalId;
        $this->approvalProposal = PriceProposal::with(['unit.project', 'proposer', 'approvals.approver'])->findOrFail($proposalId);
        $this->approval_decision = 'disetujui';
        $this->approval_notes = '';
        $this->showApprovalModal = true;
    }

    public function submitApproval()
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isSupervisor()) {
            session()->flash('error', 'Hanya Founder dan Supervisor yang berhak mengesahkan approval harga.');
            return;
        }

        $proposal = PriceProposal::with('unit')->findOrFail($this->selectedProposalId);
        $userRole = $user->role;

        // Record or update approval decision
        Approval::updateOrCreate(
            [
                'price_proposal_id' => $proposal->id,
                'approver_role' => $userRole,
            ],
            [
                'approver_id' => $user->id,
                'decision' => $this->approval_decision,
                'notes' => $this->approval_notes,
                'decided_at' => now(),
            ]
        );

        ActivityLogger::log(
            $this->approval_decision === 'ditolak' ? 'PROPOSAL_REJECTED' : 'PROPOSAL_APPROVED',
            "Pengajuan harga unit " . ($proposal->unit->code ?? 'Unit') . " (" . ucfirst($this->approval_decision) . ") oleh " . ucfirst($userRole) . " (" . auth()->user()->name . ")."
        );

        // Re-evaluate proposal status
        if ($this->approval_decision === 'ditolak') {
            $proposal->update(['status' => 'ditolak']);
            $proposal->unit->update(['status' => 'ditolak']);
            session()->flash('error', 'Pengajuan harga ditolak oleh ' . ucfirst($userRole) . '. Unit kembali ke status ditolak.');
        } else {
            if ($proposal->isFullyApproved()) {
                $proposal->update(['status' => 'disetujui']);
                $proposal->unit->update([
                    'status' => 'disetujui',
                    'final_selling_price' => $proposal->proposed_price,
                ]);

                // Auto-create OfficialDocument so Marketing gets the PDF immediately upon Founder approval
                $existingDoc = OfficialDocument::where('price_proposal_id', $proposal->id)->first();
                if (!$existingDoc) {
                    $docNumber = 'INV/SPP/' . date('Y/m') . '/' . str_pad($proposal->id, 4, '0', STR_PAD_LEFT);
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

                session()->flash('success', 'Pengajuan harga disetujui penuh! Surat Persetujuan PDF kini langsung tersedia untuk diunduh oleh Marketing.');
            } else {
                session()->flash('success', 'Keputusan ' . ucfirst($userRole) . ' disetujui. Menunggu persetujuan dari pengesah lainnya.');
            }
        }

        $this->showApprovalModal = false;
    }

    public function openDocModal($proposalId)
    {
        $proposal = PriceProposal::with('unit')->findOrFail($proposalId);
        if ($proposal->status !== 'disetujui') {
            session()->flash('error', 'Surat Resmi HANYA BISA diterbitkan jika pengajuan sudah Disetujui penuh!');
            return;
        }

        $this->doc_proposal_id = $proposal->id;
        $this->buyer_name = '';
        $this->buyer_contact = '';
        $this->buyer_address = '';
        $this->showDocModal = true;
    }

    public function issueDocument()
    {
        $user = auth()->user();
        if (!$user->isMarketing() && !$user->isFinance() && !$user->isFounder()) {
            session()->flash('error', 'Hanya Marketing, Finance, dan Founder yang berhak menerbitkan Surat Pemesanan Properti (SPP) resmi.');
            return;
        }

        $this->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_contact' => 'required|string|max:255',
        ]);

        $proposal = PriceProposal::with('unit.project')->findOrFail($this->doc_proposal_id);

        $docNumber = 'SPP/' . strtoupper($proposal->unit->project->name) . '/' . date('Y/m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        $doc = OfficialDocument::create([
            'unit_id' => $proposal->unit_id,
            'price_proposal_id' => $proposal->id,
            'document_number' => $docNumber,
            'buyer_name' => $this->buyer_name,
            'buyer_contact' => $this->buyer_contact,
            'buyer_address' => $this->buyer_address,
            'issued_by' => auth()->id(),
            'issued_at' => now(),
        ]);

        // Update unit status to terjual
        $proposal->unit->update(['status' => 'terjual']);

        ActivityLogger::log('OFFICIAL_DOCUMENT_ISSUED', "Surat Pemesanan Properti ({$docNumber}) diterbitkan untuk pembeli {$this->buyer_name} (Unit {$proposal->unit->code}).");

        session()->flash('success', 'Surat Pemesanan Properti (' . $docNumber . ') berhasil diterbitkan!');
        $this->showDocModal = false;
    }

    public $projectIdFilter = '';
    public string $statusFilter = 'all';
    public string $search = '';

    public function updatedProjectIdFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PriceProposal::with(['unit.project', 'proposer', 'approvals.approver']);

        if ($this->projectIdFilter) {
            $query->whereHas('unit', function ($q) {
                $q->where('project_id', $this->projectIdFilter);
            });
        }

        if ($this->statusFilter && $this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->whereHas('unit', function ($unitQ) use ($term) {
                    $unitQ->where('code', 'like', $term)
                          ->orWhereHas('project', function ($projQ) use ($term) {
                              $projQ->where('name', 'like', $term);
                          });
                })
                ->orWhereHas('proposer', function ($propQ) use ($term) {
                    $propQ->where('name', 'like', $term);
                });
            });
        }

        $proposals = $query->latest()->paginate(10);

        $projects = \App\Models\Project::orderBy('name')->get();
        $availableUnits = Unit::where('status', 'tersedia')->where('category', '!=', 'infrastruktur')->get();

        return view('livewire.proposals.index', [
            'proposals' => $proposals,
            'projects' => $projects,
            'availableUnits' => $availableUnits,
        ])->layout('components.layouts.app', ['title' => 'Pengajuan & Approval Harga Jual']);
    }
}
