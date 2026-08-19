<?php

namespace App\Livewire\Tutorial;

use Livewire\Component;

class Index extends Component
{
    public string $activeTab = 'master_unit'; // master_unit, booking, proposals, cash_dokumen, cicilan, operasional, keuangan, faq
    public string $viewMode = 'all'; // 'all', 'founder', 'marketing', 'finance', 'supervisor_pengawas'
    public string $searchTopic = '';

    protected $queryString = [
        'activeTab' => ['except' => 'master_unit'],
        'viewMode' => ['except' => 'all'],
        'searchTopic' => ['except' => ''],
    ];

    public function mount(): void
    {
        $userRole = auth()->user()->role ?? 'guest';
        if ($userRole === 'founder') {
            $this->viewMode = 'founder';
        } elseif ($userRole === 'marketing') {
            $this->viewMode = 'marketing';
        } elseif ($userRole === 'finance') {
            $this->viewMode = 'finance';
        } elseif ($userRole === 'supervisor' || $userRole === 'pengawas_project') {
            $this->viewMode = 'supervisor_pengawas';
        } else {
            $this->viewMode = 'all';
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
    }

    public function render()
    {
        return view('livewire.tutorial.index', [
            'activeTab' => $this->activeTab,
            'viewMode' => $this->viewMode,
            'searchTopic' => $this->searchTopic,
        ])->layout('components.layouts.app', ['title' => 'Pusat Panduan & Tutorial Sistem']);
    }
}
