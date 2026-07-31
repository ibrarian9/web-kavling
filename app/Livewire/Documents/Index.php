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
        $totalValue = (float) $query->get()->sum(fn ($doc) => $doc->proposal->proposed_price ?? 0);

        return view('livewire.documents.index', [
            'documents' => $documents,
            'projects' => $projects,
            'totalDocs' => $documents->total(),
            'totalValue' => $totalValue,
        ])->layout('components.layouts.app', ['title' => 'Surat Pemesanan Properti (SPP)']);
    }
}
