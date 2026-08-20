<?php

namespace App\Livewire\ExternalProjects;

use App\Livewire\Traits\WithMediaViewer;
use App\Models\ExternalProject;
use App\Models\ExternalProjectMaterial;
use App\Models\ExternalProjectWorkerWage;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithMediaViewer;

    public string $search = '';
    public string $statusFilter = 'semua';

    // Modal state
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $client_name = '';
    public string $client_phone = '';
    public string $location = '';
    public $contract_value = 0;
    public string $status = 'aktif';
    public ?string $start_date = null;
    public ?string $end_date = null;
    public string $notes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'semua'],
    ];

    public function mount(): void
    {
        if (!auth()->user() || !auth()->user()->isFounder()) {
            abort(403, 'Akses khusus Founder untuk modul Proyek Luar.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function openModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->editingId = $id;

        if ($id) {
            $project = ExternalProject::findOrFail($id);
            $this->name = $project->name;
            $this->client_name = $project->client_name ?? '';
            $this->client_phone = $project->client_phone ?? '';
            $this->location = $project->location ?? '';
            $this->contract_value = (float) $project->contract_value;
            $this->status = $project->status;
            $this->start_date = $project->start_date ? $project->start_date->format('Y-m-d') : null;
            $this->end_date = $project->end_date ? $project->end_date->format('Y-m-d') : null;
            $this->notes = $project->notes ?? '';
        } else {
            $this->name = '';
            $this->client_name = '';
            $this->client_phone = '';
            $this->location = '';
            $this->contract_value = 0;
            $this->status = 'aktif';
            $this->start_date = now()->format('Y-m-d');
            $this->end_date = null;
            $this->notes = '';
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function save(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isFounder()) {
            abort(403, 'Akses khusus Founder.');
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'contract_value' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,selesai,tertunda',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        $data = [
            'name' => $this->name,
            'client_name' => $this->client_name ?: null,
            'client_phone' => $this->client_phone ?: null,
            'location' => $this->location ?: null,
            'contract_value' => (float) ($this->contract_value ?? 0),
            'status' => $this->status,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'notes' => $this->notes ?: null,
        ];

        if ($this->editingId) {
            $project = ExternalProject::findOrFail($this->editingId);
            $project->update($data);
            ActivityLogger::log('EXTERNAL_PROJECT_UPDATED', "User ({$user->name}) memperbarui data Proyek Luar: {$project->name}");
            $msg = "Proyek Luar {$project->name} berhasil diperbarui!";
        } else {
            $data['created_by'] = $user->id;
            $project = ExternalProject::create($data);
            ActivityLogger::log('EXTERNAL_PROJECT_CREATED', "User ({$user->name}) membuat Proyek Luar baru: {$project->name}");
            $msg = "Proyek Luar {$project->name} berhasil ditambahkan!";
        }

        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showModal = false;
    }

    public function deleteProject(int $id): void
    {
        $user = auth()->user();
        if (!$user || !$user->isFounder()) {
            session()->flash('error', 'Akses ditolak. Hanya Founder yang dapat menghapus proyek luar.');
            return;
        }

        $project = ExternalProject::with(['materials', 'workerWages'])->findOrFail($id);
        $projectName = $project->name;

        // Delete receipt files
        foreach ($project->materials as $mat) {
            if ($mat->receipt_photo && Storage::disk('public')->exists($mat->receipt_photo)) {
                Storage::disk('public')->delete($mat->receipt_photo);
            }
        }
        foreach ($project->workerWages as $wage) {
            if ($wage->receipt_photo && Storage::disk('public')->exists($wage->receipt_photo)) {
                Storage::disk('public')->delete($wage->receipt_photo);
            }
        }

        $project->delete();

        ActivityLogger::log('EXTERNAL_PROJECT_DELETED', "User ({$user->name}) menghapus Proyek Luar: {$projectName} beserta seluruh catatan material dan upahnya.");
        $msg = "Proyek Luar {$projectName} berhasil dihapus!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Dihapus!', 'message' => $msg]);
    }

    public function render()
    {
        $query = ExternalProject::withCount(['materials', 'workerWages'])
            ->withSum('materials', 'total_price')
            ->withSum('workerWages', 'amount');

        if ($this->search) {
            $search = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('client_name', 'like', $search)
                    ->orWhere('location', 'like', $search);
            });
        }

        if ($this->statusFilter !== 'semua') {
            $query->where('status', $this->statusFilter);
        }

        $projects = $query->latest()->paginate(10);

        // Global KPIs for External Projects
        $totalProjectsCount = ExternalProject::count();
        $totalActiveProjectsCount = ExternalProject::where('status', 'aktif')->count();
        $totalExternalMaterialSum = (float) ExternalProjectMaterial::sum('total_price');
        $totalExternalWageSum = (float) ExternalProjectWorkerWage::sum('amount');
        $totalOverallExpenses = $totalExternalMaterialSum + $totalExternalWageSum;

        return view('livewire.external-projects.index', [
            'projects' => $projects,
            'totalProjectsCount' => $totalProjectsCount,
            'totalActiveProjectsCount' => $totalActiveProjectsCount,
            'totalExternalMaterialSum' => $totalExternalMaterialSum,
            'totalExternalWageSum' => $totalExternalWageSum,
            'totalOverallExpenses' => $totalOverallExpenses,
        ])->layout('components.layouts.app', ['title' => 'Proyek Luar (Pencatatan Material & Upah)']);
    }
}
