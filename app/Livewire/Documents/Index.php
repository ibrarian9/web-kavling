<?php

namespace App\Livewire\Documents;

use App\Models\OfficialDocument;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Viewer Modal (PDF Viewer)
    public bool $showViewerModal = false;
    public string $viewerType = 'pdf';
    public string $viewerUrl = '';
    public string $viewerTitle = '';

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
        $documents = OfficialDocument::with(['unit.project', 'proposal', 'issuer'])
            ->latest()
            ->paginate(10);

        return view('livewire.documents.index', [
            'documents' => $documents,
        ])->layout('components.layouts.app', ['title' => 'Surat Pemesanan Properti (SPP)']);
    }
}
