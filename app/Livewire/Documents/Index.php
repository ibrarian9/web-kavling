<?php

namespace App\Livewire\Documents;

use App\Models\OfficialDocument;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

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
