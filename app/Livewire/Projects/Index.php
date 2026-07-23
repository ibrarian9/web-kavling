<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $showModal = false;
    public $name = '';
    public $location = '';
    public $standard_land_area = 100.00;
    public $excess_price_per_sqm = 1500000.00;
    public $base_price = 150000000.00;
    public $editingProjectId = null;

    // Worker Assignment Modal
    public bool $showWorkerModal = false;
    public ?int $assignProjectId = null;
    public ?int $worker_id = null;
    public string $assigned_role = 'Mandor Proyek';

    protected $rules = [
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'standard_land_area' => 'required|numeric|min:1',
        'excess_price_per_sqm' => 'required|numeric|min:0',
        'base_price' => 'required|numeric|min:0',
    ];

    public function openModal()
    {
        $this->resetInputFields();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->location = '';
        $this->standard_land_area = 100.00;
        $this->excess_price_per_sqm = 1500000.00;
        $this->base_price = 150000000.00;
        $this->editingProjectId = null;
    }

    public function saveProject()
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isSupervisor()) {
            session()->flash('error', 'Hanya Founder dan Supervisor yang berhak mengedit data proyek.');
            return;
        }

        $this->validate();

        Project::updateOrCreate(
            ['id' => $this->editingProjectId],
            [
                'name' => $this->name,
                'location' => $this->location,
                'standard_land_area' => $this->standard_land_area,
                'excess_price_per_sqm' => $this->excess_price_per_sqm,
                'base_price' => $this->base_price,
                'created_by' => auth()->id(),
                'status' => 'aktif',
            ]
        );

        session()->flash('success', 'Data proyek berhasil disimpan!');
        $this->closeModal();
    }

    public function editProject($id)
    {
        $project = Project::findOrFail($id);
        $this->editingProjectId = $project->id;
        $this->name = $project->name;
        $this->location = $project->location;
        $this->standard_land_area = $project->standard_land_area;
        $this->excess_price_per_sqm = $project->excess_price_per_sqm;
        $this->base_price = $project->base_price;

        $this->showModal = true;
    }

    // Direct Worker Assignment for Project
    public function openWorkerModal($projectId)
    {
        $this->assignProjectId = $projectId;
        $this->worker_id = Worker::where('status', 'active')->first()?->id;
        $this->assigned_role = 'Mandor Utama Proyek';
        $this->showWorkerModal = true;
    }

    public function saveWorkerAssignment()
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isSupervisor() && !$user->isPengawasProject()) {
            session()->flash('error', 'Hanya Tim Operasional Lapangan (Founder, Supervisor, Pengawas) yang berhak menugaskan pekerja.');
            return;
        }

        $this->validate([
            'worker_id' => 'required|exists:workers,id',
            'assigned_role' => 'required|string|max:255',
        ]);

        $project = Project::findOrFail($this->assignProjectId);

        WorkerAssignment::create([
            'worker_id' => $this->worker_id,
            'project_id' => $project->id,
            'unit_id' => null,
            'assigned_role' => $this->assigned_role,
            'start_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        session()->flash('success', 'Pekerja berhasil ditugaskan pada proyek ' . $project->name . '!');
        $this->showWorkerModal = false;
    }

    public function render()
    {
        $projects = Project::with(['units', 'assignments.worker'])
            ->withCount('units')
            ->latest()
            ->paginate(10);

        $allWorkers = Worker::where('status', 'active')->orderBy('name')->get();

        return view('livewire.projects.index', [
            'projects' => $projects,
            'allWorkers' => $allWorkers,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Proyek Properti']);
    }
}
