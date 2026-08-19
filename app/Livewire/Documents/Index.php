<?php

namespace App\Livewire\Documents;

use App\Models\OfficialDocument;
use App\Services\ActivityLogger;
use App\Traits\WithDatePeriodFilter;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithDatePeriodFilter;

    public string $search = '';
    public ?int $project_id = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'project_id' => ['except' => null],
        'datePeriod' => ['except' => 'all'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    // Viewer Modal (PDF Viewer)
    public bool $showViewerModal = false;
    public string $viewerType = 'pdf';
    public string $viewerUrl = '';
    public string $viewerTitle = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedProjectId(): void
    {
        $this->resetPage();
    }

    public function openViewerModal(string $type, string $url, string $title = ''): void
    {
        $this->viewerType = $type;
        $this->viewerUrl = $url;
        $this->viewerTitle = $title ?: 'Pratinjau Surat SPP PDF';
        $this->showViewerModal = true;
    }

    public function closeViewerModal(): void
    {
        $this->showViewerModal = false;
        $this->viewerType = '';
        $this->viewerUrl = '';
        $this->viewerTitle = '';
    }

    // Generate / Edit Document Modal
    public bool $showGenerateModal = false;
    public ?int $editingDocumentId = null;
    public ?int $selected_unit_id = null;
    public string $buyer_name = '';
    public string $buyer_nik = '';
    public string $buyer_contact = '';
    public string $buyer_address = '';
    public string $seller_name = '';
    public string $seller_nik = '';

    public function openGenerateModal(): void
    {
        $this->editingDocumentId = null;
        $this->selected_unit_id = null;
        $this->buyer_name = '';
        $this->buyer_nik = '';
        $this->buyer_contact = '';
        $this->buyer_address = '';

        $founder = auth()->user()->isFounder() ? auth()->user() : User::where('role', 'founder')->first();
        $this->seller_name = $founder?->name ?? 'Founder PT. Atlantik Perkasa Abadi';
        $this->seller_nik = '1471012304850001';

        $this->showGenerateModal = true;
    }

    public function editDocument($id): void
    {
        $user = auth()->user();
        if (!$user->isAdminOrFounder()) {
            session()->flash('error', 'Hanya Admin & Founder yang berhak mengubah dokumen SPP.');
            return;
        }

        $doc = OfficialDocument::findOrFail((int) $id);
        $this->editingDocumentId = $doc->id;
        $this->selected_unit_id = $doc->unit_id;
        $this->buyer_name = $doc->buyer_name;
        $this->buyer_nik = $doc->buyer_nik ?? '';
        $this->buyer_contact = $doc->buyer_contact;
        $this->buyer_address = $doc->buyer_address;

        $this->seller_name = $doc->seller_name ?? $doc->effective_seller_name;
        $this->seller_nik = $doc->seller_nik ?? $doc->effective_seller_nik;

        $this->showGenerateModal = true;
    }

    public function generateDocument(): void
    {
        $user = auth()->user();

        if ($this->editingDocumentId) {
            if (!$user->isAdminOrFounder()) {
                session()->flash('error', 'Hanya Admin & Founder yang berhak mengedit dokumen SPP.');
                return;
            }

            $this->validate([
                'selected_unit_id' => 'required|exists:units,id',
                'buyer_name' => 'required|string|max:255',
                'buyer_contact' => 'required|string|max:255',
            ]);

            $doc = OfficialDocument::findOrFail($this->editingDocumentId);
            $unit = \App\Models\Unit::with('project', 'proposals')->findOrFail($this->selected_unit_id);
            $proposal = $unit->proposals()->where('status', 'disetujui')->latest()->first();

            $doc->update([
                'unit_id' => $unit->id,
                'price_proposal_id' => $proposal?->id ?? $doc->price_proposal_id,
                'buyer_name' => $this->buyer_name,
                'buyer_nik' => $this->buyer_nik ?: null,
                'buyer_contact' => $this->buyer_contact,
                'buyer_address' => $this->buyer_address ?: '-',
                'seller_name' => $this->seller_name ?: null,
                'seller_nik' => $this->seller_nik ?: null,
            ]);

            ActivityLogger::log('DOCUMENT_UPDATED', "Dokumen SPP {$doc->document_number} (ID #{$doc->id}) telah diperbarui oleh Founder.");

            session()->flash('success', 'Dokumen SPP ' . $doc->document_number . ' berhasil diperbarui oleh Founder.');
            $this->showGenerateModal = false;
            $this->editingDocumentId = null;
            return;
        }

        $this->validate([
            'selected_unit_id' => 'required|exists:units,id',
            'buyer_name' => 'required|string|max:255',
            'buyer_contact' => 'required|string|max:255',
        ]);

        $unit = \App\Models\Unit::with('project', 'proposals')->findOrFail($this->selected_unit_id);

        $docNumber = 'SPP/' . strtoupper($unit->project->name) . '/' . date('Y/m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $proposal = $unit->proposals()->where('status', 'disetujui')->latest()->first();

        if (!$proposal) {
            $hppPrice = (float) $unit->hpp;
            $proposedPrice = (float) $unit->final_selling_price;
            $margin = $proposedPrice - $hppPrice;

            $proposal = \App\Models\PriceProposal::create([
                'unit_id' => $unit->id,
                'hpp_price' => $hppPrice,
                'proposed_price' => $proposedPrice,
                'margin' => $margin,
                'is_below_hpp' => $proposedPrice < $hppPrice,
                'discount_reason' => 'Penerbitan Dokumen Resmi SPP',
                'proposed_by' => auth()->id(),
                'status' => 'disetujui',
                'notes' => 'Persetujuan otomatis penerbitan SPP & SPJB PDF',
            ]);
        }

        $unit->update([
            'status' => 'terjual',
        ]);

        $doc = OfficialDocument::create([
            'unit_id' => $unit->id,
            'price_proposal_id' => $proposal->id,
            'document_number' => $docNumber,
            'buyer_name' => $this->buyer_name,
            'buyer_nik' => $this->buyer_nik ?: null,
            'buyer_contact' => $this->buyer_contact,
            'buyer_address' => $this->buyer_address ?: '-',
            'seller_name' => $this->seller_name ?: null,
            'seller_nik' => $this->seller_nik ?: null,
            'issued_by' => auth()->id(),
            'issued_at' => now(),
        ]);

        ActivityLogger::log('DOCUMENT_CREATED', "Dokumen SPP {$docNumber} diterbitkan untuk pembeli {$this->buyer_name} (Unit {$unit->code}).");

        $this->showGenerateModal = false;
        session()->flash('success', 'Surat Pemesanan Properti (SPP PDF) ' . $docNumber . ' berhasil diterbitkan!');

        $this->openViewerModal('pdf', route('documents.stream', $doc->id), 'Pratinjau Surat SPP PDF - ' . $doc->document_number);
    }

    public function deleteDocument($id): void
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin()) {
            session()->flash('error', 'Hanya Admin Utama / Supervisor yang berhak menghapus dokumen SPP.');
            return;
        }

        $doc = OfficialDocument::with(['unit.activeBooking', 'unit.proposals', 'unit.installment'])->findOrFail((int) $id);
        $docNumber = $doc->document_number;
        $unit = $doc->unit;

        $doc->delete();

        $statusMsg = '';
        if ($unit) {
            if ($unit->installment && $unit->installment->status === 'berjalan') {
                $unit->update(['status' => 'disetujui']);
                $statusMsg = "Status unit {$unit->code} otomatis dikembalikan ke 'Disetujui' (skema cicilan berjalan).";
            } elseif ($unit->activeBooking) {
                $unit->update(['status' => 'booked']);
                $statusMsg = "Status unit {$unit->code} otomatis dikembalikan ke 'Booked'.";
            } elseif ($unit->proposals()->where('status', 'disetujui')->exists()) {
                $unit->update(['status' => 'disetujui']);
                $statusMsg = "Status unit {$unit->code} otomatis dikembalikan ke 'Disetujui'.";
            } else {
                $unit->update(['status' => 'tersedia']);
                $statusMsg = "Status unit {$unit->code} otomatis dikembalikan ke 'Tersedia'.";
            }
        }

        ActivityLogger::log('DOCUMENT_DELETED', "Dokumen SPP {$docNumber} (ID #{$id}) telah dihapus oleh Founder. {$statusMsg}");

        session()->flash('success', 'Dokumen SPP ' . $docNumber . ' berhasil dihapus. ' . $statusMsg);
    }

    public function render()
    {
        $query = OfficialDocument::with(['unit.project', 'proposal', 'issuer']);

        if ($this->project_id) {
            $query->whereHas('unit', function ($q) {
                $q->where('project_id', $this->project_id);
            });
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('document_number', 'like', '%' . $this->search . '%')
                  ->orWhere('buyer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('buyer_contact', 'like', '%' . $this->search . '%')
                  ->orWhereHas('unit', function ($uQ) {
                      $uQ->where('code', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->datePeriod !== 'all') {
            $this->applyDatePeriodFilter($query, 'issued_at');
        }

        $documents = $query->latest('id')->paginate(10);
        $projects = \App\Models\Project::orderBy('name')->get();
        $allUnits = \App\Models\Unit::with('project')->orderBy('code')->get();
        $totalValue = (float) $query->get()->sum(fn ($doc) => $doc->proposal->proposed_price ?? $doc->unit->final_selling_price ?? 0);

        return view('livewire.documents.index', [
            'documents' => $documents,
            'projects' => $projects,
            'allUnits' => $allUnits,
            'totalDocs' => $documents->total(),
            'totalValue' => $totalValue,
        ])->layout('components.layouts.app', ['title' => 'Surat Pemesanan Properti (SPP)']);
    }
}
