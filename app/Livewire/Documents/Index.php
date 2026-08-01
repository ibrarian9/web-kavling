<?php

namespace App\Livewire\Documents;

use App\Models\OfficialDocument;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $project_id = null;

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

    // Generate Document Modal
    public bool $showGenerateModal = false;
    public ?int $selected_unit_id = null;
    public string $buyer_name = '';
    public string $buyer_contact = '';
    public string $buyer_address = '';

    public function openGenerateModal(): void
    {
        $this->selected_unit_id = null;
        $this->buyer_name = '';
        $this->buyer_contact = '';
        $this->buyer_address = '';
        $this->showGenerateModal = true;
    }

    public function generateDocument(): void
    {
        $this->validate([
            'selected_unit_id' => 'required|exists:units,id',
            'buyer_name' => 'required|string|max:255',
            'buyer_contact' => 'required|string|max:255',
        ]);

        $unit = \App\Models\Unit::with('project', 'priceProposals')->findOrFail($this->selected_unit_id);

        $docNumber = 'SPP/' . strtoupper($unit->project->name) . '/' . date('Y/m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $proposal = $unit->priceProposals()->where('status', 'disetujui')->latest()->first();

        $doc = OfficialDocument::create([
            'unit_id' => $unit->id,
            'price_proposal_id' => $proposal?->id,
            'document_number' => $docNumber,
            'buyer_name' => $this->buyer_name,
            'buyer_contact' => $this->buyer_contact,
            'buyer_address' => $this->buyer_address ?: '-',
            'issued_by' => auth()->id(),
            'issued_at' => now(),
        ]);

        $this->showGenerateModal = false;
        session()->flash('success', 'Surat Pemesanan Properti (SPP PDF) ' . $docNumber . ' berhasil diterbitkan!');

        $this->openViewerModal('pdf', route('documents.stream', $doc->id), 'Pratinjau Surat SPP PDF - ' . $doc->document_number);
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
