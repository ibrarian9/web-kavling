<?php

namespace App\Livewire\Workers;

use App\Models\Worker;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';
    public string $statusFilter = '';

    // Form modal state
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $type = 'tukang';
    public string $specialty = '';
    public string $status = 'active';
    public string $notes = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'type' => 'required|in:mandor,tukang',
            'specialty' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['editingId', 'name', 'phone', 'address', 'type', 'specialty', 'status', 'notes']);
        $this->type = 'tukang';
        $this->status = 'active';
        $this->showModal = true;
    }

    public function edit(Worker $worker): void
    {
        $this->resetValidation();
        $this->editingId = $worker->id;
        $this->name = $worker->name;
        $this->phone = $worker->phone ?? '';
        $this->address = $worker->address ?? '';
        $this->type = $worker->type;
        $this->specialty = $worker->specialty ?? '';
        $this->status = $worker->status;
        $this->notes = $worker->notes ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $worker = Worker::findOrFail($this->editingId);
            $worker->update($validated);
            session()->flash('success', 'Data pekerja berhasil diperbarui.');
        } else {
            Worker::create($validated);
            session()->flash('success', 'Pekerja baru berhasil didaftarkan.');
        }

        $this->showModal = false;
        $this->reset(['editingId', 'name', 'phone', 'address', 'type', 'specialty', 'status', 'notes']);
    }

    // Quick Assignment Modal State
    public bool $showAssignModal = false;
    public ?int $assignWorkerId = null;
    public ?int $assignProjectId = null;
    public ?int $assignUnitId = null;
    public string $assignedRole = 'Mandor Utama Proyek';

    public function toggleStatus(Worker $worker): void
    {
        $newStatus = $worker->status === 'active' ? 'inactive' : 'active';
        $worker->update(['status' => $newStatus]);
        session()->flash('success', "Status {$worker->name} diubah menjadi {$newStatus}.");
    }

    public function openAssignModal(Worker $worker): void
    {
        $this->assignWorkerId = $worker->id;
        $this->assignProjectId = \App\Models\Project::first()?->id;
        $this->assignUnitId = null;
        $this->assignedRole = $worker->type === 'mandor' ? 'Mandor Utama Proyek' : 'Tukang Konstruksi Unit';
        $this->showAssignModal = true;
    }

    public function saveAssignment(): void
    {
        $this->validate([
            'assignWorkerId' => 'required|exists:workers,id',
            'assignProjectId' => 'required|exists:projects,id',
            'assignUnitId' => 'nullable|exists:units,id',
            'assignedRole' => 'nullable|string|max:255',
        ]);

        \App\Models\WorkerAssignment::create([
            'worker_id' => $this->assignWorkerId,
            'project_id' => $this->assignProjectId,
            'unit_id' => $this->assignUnitId,
            'assigned_role' => $this->assignedRole,
            'start_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        session()->flash('success', 'Penugasan pekerja berhasil disimpan.');
        $this->showAssignModal = false;
    }

    public function render()
    {
        $query = Worker::query()
            ->with(['activeAssignments.project', 'activeAssignments.unit'])
            ->withCount(['assignments', 'loans'])
            ->withSum('loans', 'amount')
            ->withSum('loans', 'paid_amount');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('specialty', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->typeFilter)) {
            $query->where('type', $this->typeFilter);
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $workers = $query->orderBy('name')->paginate(10);
        $projects = \App\Models\Project::orderBy('name')->get();
        $availableUnits = $this->assignProjectId ? \App\Models\Unit::where('project_id', $this->assignProjectId)->orderBy('code')->get() : collect();

        return view('livewire.workers.index', [
            'workers' => $workers,
            'projects' => $projects,
            'availableUnits' => $availableUnits,
            'showModal' => $this->showModal,
            'showAssignModal' => $this->showAssignModal,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Pekerja Lapangan (Mandor & Tukang)']);
    }
}

