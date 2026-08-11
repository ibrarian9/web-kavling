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
    public $total_project_price = 0;
    public $editingProjectId = null;

    // Pengawas Assignment Modal (Founder Only)
    public bool $showWorkerModal = false; // keep variable name for blade compatibility
    public ?int $assignProjectId = null;
    public ?int $assign_user_id = null;
    public string $assigned_role = 'Pengawas Lapangan Proyek';

    protected $rules = [
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'standard_land_area' => 'required|numeric|min:1',
        'excess_price_per_sqm' => 'required|numeric|min:0',
        'base_price' => 'required|numeric|min:0',
        'total_project_price' => 'nullable|numeric|min:0',
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
        $this->total_project_price = 0;
        $this->editingProjectId = null;
    }

    public function saveProject()
    {
        $user = auth()->user();
        if (!$user->isAdminOrFounder() && !$user->isSupervisor()) {
            session()->flash('error', 'Hanya Founder, Admin, dan Supervisor yang berhak mengedit data proyek.');
            return;
        }

        $this->validate();

        $isEdit = !empty($this->editingProjectId);

        Project::updateOrCreate(
            ['id' => $this->editingProjectId],
            [
                'name' => $this->name,
                'location' => $this->location,
                'standard_land_area' => $this->standard_land_area,
                'excess_price_per_sqm' => $this->excess_price_per_sqm,
                'base_price' => $this->base_price,
                'total_project_price' => $this->total_project_price ?: 0,
                'created_by' => auth()->id(),
                'status' => 'aktif',
            ]
        );

        if ($isEdit) {
            \App\Services\ActivityLogger::log('PROJECT_UPDATED', "Data proyek {$this->name} diperbarui oleh " . auth()->user()->name);
        } else {
            \App\Services\ActivityLogger::log('PROJECT_CREATED', "Proyek baru {$this->name} (Lokasi: {$this->location}) berhasil ditambahkan ke sistem.");
        }

        $msg = 'Data proyek berhasil disimpan!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
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
        $this->total_project_price = $project->total_project_price;

        $this->showModal = true;
    }

    public function deleteProject($id)
    {
        $user = auth()->user();
        if (!$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak menghapus proyek.');
            return;
        }

        $project = Project::with(['units', 'assignments', 'cashflows', 'payments'])->findOrFail($id);
        $projectName = $project->name;

        $project->assignments()->delete();
        $project->payments()->delete();
        foreach ($project->units as $unit) {
            $unit->delete();
        }
        $project->delete();

        \App\Services\ActivityLogger::log('PROJECT_DELETE', "Founder menghapus proyek {$projectName} beserta seluruh data terikatnya.");
        $msg = "Proyek {$projectName} berhasil dihapus dari sistem!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    // Direct Pengawas Project Assignment (Founder & Admin)
    public function openWorkerModal($projectId)
    {
        if (!auth()->user()->isAdminOrFounder()) {
            session()->flash('error', 'Hanya Founder dan Admin yang berhak menugaskan Pengawas ke proyek.');
            return;
        }

        $this->assignProjectId = $projectId;

        // Filter out Pengawas users who are already assigned to this project
        $alreadyAssignedUserIds = WorkerAssignment::where('project_id', $projectId)
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->pluck('user_id');

        $this->assign_user_id = \App\Models\User::where('role', 'pengawas_project')
            ->where('is_active', true)
            ->whereNotIn('id', $alreadyAssignedUserIds)
            ->first()?->id;

        $this->assigned_role = 'Pengawas Lapangan Proyek';
        $this->showWorkerModal = true;
    }

    public function saveWorkerAssignment()
    {
        $user = auth()->user();
        if (!$user->isAdminOrFounder()) {
            session()->flash('error', 'Hanya Founder dan Admin yang berhak menugaskan Pengawas ke proyek.');
            return;
        }

        $this->validate([
            'assign_user_id' => 'required|exists:users,id',
            'assigned_role' => 'required|string|max:255',
        ]);

        $project = Project::findOrFail($this->assignProjectId);
        $pengawasUser = \App\Models\User::findOrFail($this->assign_user_id);

        WorkerAssignment::updateOrCreate(
            [
                'user_id' => $pengawasUser->id,
                'project_id' => $project->id,
            ],
            [
                'worker_id' => null,
                'unit_id' => null,
                'assigned_role' => $this->assigned_role,
                'start_date' => now()->toDateString(),
                'status' => 'active',
            ]
        );

        \App\Services\ActivityLogger::log('PROJECT_ASSIGN_PENGAWAS', "Penugasan Pengawas Project {$pengawasUser->name} pada proyek {$project->name} oleh " . auth()->user()->name);
        session()->flash('success', "Pengawas Project {$pengawasUser->name} berhasil ditugaskan pada proyek {$project->name}!");

        $this->showWorkerModal = false;
    }

    public function removePengawasAssignment($assignmentId)
    {
        $user = auth()->user();
        if (!$user->isAdminOrFounder()) {
            session()->flash('error', 'Hanya Founder dan Admin yang berhak mencopot Pengawas dari proyek.');
            return;
        }

        $assignment = WorkerAssignment::with(['user', 'project'])->findOrFail($assignmentId);
        $pengawasName = $assignment->user->name ?? 'Pengawas';
        $projectName = $assignment->project->name ?? 'Proyek';

        $assignment->delete();

        \App\Services\ActivityLogger::log('PROJECT_REMOVE_PENGAWAS', "Pencopotan Pengawas Project {$pengawasName} dari proyek {$projectName} oleh " . auth()->user()->name);
        session()->flash('success', "Pengawas Project {$pengawasName} berhasil dicopot dari proyek {$projectName}!");
    }

    public function movePengawasAssignment($assignmentId, $targetProjectId)
    {
        $user = auth()->user();
        if (!$user->isAdminOrFounder()) {
            session()->flash('error', 'Hanya Founder dan Admin yang berhak memindahkan Pengawas ke proyek lain.');
            return;
        }

        if (!$targetProjectId) {
            session()->flash('error', 'Silakan pilih proyek tujuan pemindahan.');
            return;
        }

        $assignment = WorkerAssignment::with(['user', 'project'])->findOrFail($assignmentId);
        $oldProjectName = $assignment->project->name ?? 'Proyek Lama';
        $targetProject = Project::findOrFail($targetProjectId);
        $pengawasName = $assignment->user->name ?? 'Pengawas';

        // Check if user is already assigned to target project
        $exists = WorkerAssignment::where('user_id', $assignment->user_id)
            ->where('project_id', $targetProject->id)
            ->where('status', 'active')
            ->where('id', '!=', $assignmentId)
            ->exists();

        if ($exists) {
            $assignment->delete();
        } else {
            $assignment->update([
                'project_id' => $targetProject->id,
                'status' => 'active',
            ]);
        }

        \App\Services\ActivityLogger::log('PROJECT_MOVE_PENGAWAS', "Founder memindahkan Pengawas Project {$pengawasName} dari proyek {$oldProjectName} ke proyek {$targetProject->name}.");
        session()->flash('success', "Pengawas Project {$pengawasName} berhasil dipindahkan ke proyek {$targetProject->name}!");
    }

    public function render()
    {
        $projectsQuery = Project::with(['units', 'assignments.user'])
            ->withCount('units')
            ->latest();

        // Scope projects for Pengawas Project user to only their assigned projects
        if (auth()->user() && auth()->user()->isPengawasProject()) {
            $assignedProjectIds = WorkerAssignment::where('user_id', auth()->id())
                ->where('status', 'active')
                ->pluck('project_id');
            $projectsQuery->whereIn('id', $assignedProjectIds);
        }

        $projects = $projectsQuery->paginate(10);

        // Exclude Pengawas users who are already assigned to the selected modal project
        $alreadyAssignedUserIds = [];
        if ($this->assignProjectId) {
            $alreadyAssignedUserIds = WorkerAssignment::where('project_id', $this->assignProjectId)
                ->where('status', 'active')
                ->whereNotNull('user_id')
                ->pluck('user_id')
                ->toArray();
        }

        $pengawasUsers = \App\Models\User::where('role', 'pengawas_project')
            ->where('is_active', true)
            ->whereNotIn('id', $alreadyAssignedUserIds)
            ->orderBy('name')
            ->get();

        $allProjects = Project::orderBy('name')->get();
        $selectedProjectForModal = $this->assignProjectId ? Project::with('assignments.user')->find($this->assignProjectId) : null;

        return view('livewire.projects.index', [
            'projects' => $projects,
            'pengawasUsers' => $pengawasUsers,
            'allProjects' => $allProjects,
            'selectedProjectForModal' => $selectedProjectForModal,
            'showModal' => $this->showModal,
            'showWorkerModal' => $this->showWorkerModal,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Proyek Properti']);
    }
}
