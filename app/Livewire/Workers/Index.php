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

    public function mount(): void
    {
        if (auth()->user()?->isMarketing()) {
            abort(403, 'Akses Ditolak. Role Marketing tidak memiliki hak akses ke manajemen pekerja.');
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'type' => 'required|in:mandor,tukang,kontraktor',
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
            \App\Services\ActivityLogger::log('WORKER_UPDATED', "Data pekerja {$worker->name} ({$worker->type}) diperbarui.");
            $msg = 'Data pekerja berhasil diperbarui.';
            session()->flash('success', $msg);
            $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        } else {
            $worker = Worker::create($validated);
            \App\Services\ActivityLogger::log('WORKER_CREATED', "Pekerja baru {$worker->name} ({$worker->type}) didaftarkan.");
            $msg = 'Pekerja baru berhasil didaftarkan.';
            session()->flash('success', $msg);
            $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
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
        \App\Services\ActivityLogger::log('WORKER_STATUS_TOGGLED', "Status pekerja {$worker->name} diubah menjadi {$newStatus}.");
        $msg = "Status {$worker->name} diubah menjadi {$newStatus}.";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    public function openAssignModal(Worker $worker): void
    {
        $this->assignWorkerId = $worker->id;
        $this->assignProjectId = \App\Models\Project::first()?->id;
        $this->assignUnitId = null;
        $this->assignedRole = $worker->type === 'mandor' ? 'Mandor Utama Proyek' : ($worker->type === 'kontraktor' ? 'Kontraktor Utama Proyek' : 'Tukang Konstruksi Unit');
        $this->showAssignModal = true;
    }

    public function updatedAssignProjectId(): void
    {
        $this->assignUnitId = null;
    }

    public function saveAssignment(): void
    {
        $this->validate([
            'assignWorkerId' => 'required|exists:workers,id',
            'assignProjectId' => 'required|exists:projects,id',
            'assignUnitId' => 'nullable|exists:units,id',
            'assignedRole' => 'nullable|string|max:255',
        ]);

        $worker = Worker::findOrFail($this->assignWorkerId);

        \App\Models\WorkerAssignment::create([
            'worker_id' => $this->assignWorkerId,
            'project_id' => $this->assignProjectId,
            'unit_id' => $this->assignUnitId,
            'assigned_role' => $this->assignedRole,
            'start_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        \App\Services\ActivityLogger::log('WORKER_ASSIGNED', "Pekerja {$worker->name} ({$worker->type}) ditugaskan sebagai {$this->assignedRole}.");

        $msg = 'Penugasan pekerja berhasil disimpan.';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showAssignModal = false;
    }

    public function render()
    {
        $query = Worker::query()
            ->with(['activeAssignments.project', 'activeAssignments.unit'])
            ->withCount(['assignments']);

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
        ])->layout('components.layouts.app', ['title' => 'Manajemen Pekerja Lapangan (Mandor, Tukang & Kontraktor)']);
    }
}

