<?php

namespace App\Livewire\Tutorial;

use Livewire\Component;

class Index extends Component
{
    public string $activeTab = 'booking'; // booking, cash, cicilan, dokumen, faq
    public string $viewMode = 'founder'; // 'founder' or 'umum'

    public function mount(): void
    {
        // Default to user's actual role mode if available
        $userRole = auth()->user()->role ?? 'guest';
        $this->viewMode = ($userRole === 'founder') ? 'founder' : 'umum';
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
        return view('livewire.tutorial.index')->layout('components.layouts.app', ['title' => 'Tutorial & Panduan Sistem']);
    }
}
