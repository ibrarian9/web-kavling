<?php

namespace App\Livewire\Workers;

use App\Models\Project;
use App\Models\Unit;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use Livewire\Component;
use Livewire\WithPagination;

class Assignments extends Component
{
    use WithPagination;

    public ?int $projectFilter = null;
    public string $statusFilter = 'active';

    // Modal state
    public bool $showModal = false;
    public ?int $worker_id = null;
    public ?int $project_id = null;
    public ?int $unit_id = null;
    public string $assigned_role = '';
    public ?string $start_date = null;
    public ?string $end_date = null;

    protected function rules(): array
    {
        return [
            'worker_id' => 'required|exists:workers,id',
            'project_id' => 'required|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'assigned_role' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['worker_id', 'project_id', 'unit_id', 'assigned_role', 'start_date', 'end_date']);
        $this->start_date = now()->toDateString();
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['status'] = 'active';

        WorkerAssignment::create($validated);

        session()->flash('success', 'Penugasan worker berhasil dibuat.');
        $this->showModal = false;
        $this->reset(['worker_id', 'project_id', 'unit_id', 'assigned_role', 'start_date', 'end_date']);
    }

    public function completeAssignment(WorkerAssignment $assignment): void
    {
        $assignment->update([
            'status' => 'completed',
            'end_date' => now()->toDateString(),
        ]);
        session()->flash('success', 'Penugasan berhasil diselesaikan.');
    }

    public function render()
    {
        $query = WorkerAssignment::query()
            ->with(['worker', 'project', 'unit']);

        if ($this->projectFilter) {
            $query->where('project_id', $this->projectFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $assignments = $query->latest('id')->paginate(10);
        $workers = Worker::where('status', 'active')->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $units = $this->project_id ? Unit::where('project_id', $this->project_id)->orderBy('code')->get() : collect();

        return view('livewire.workers.assignments', [
            'assignments' => $assignments,
            'workers' => $workers,
            'projects' => $projects,
            'availableUnits' => $units,
        ])->layout('components.layouts.app', ['title' => 'Penugasan Mandor & Tukang Lapangan']);
    }
}

