<?php

namespace App\Livewire\Units\Concerns;

use App\Models\Unit;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use App\Services\ActivityLogger;

trait HasUnitWorkers
{
    // Modal Worker Assignment
    public bool $showWorkerModal = false;
    public ?int $worker_id = null;
    public string $assigned_role = 'Mandor Lapangan';
    public ?int $editingAssignmentId = null;

    public function openWorkerModal(): void
    {
        $this->resetValidation();
        $this->editingAssignmentId = null;
        $this->worker_id = Worker::where('status', 'active')->first()?->id;
        $this->assigned_role = 'Mandor Lapangan';
        $this->showWorkerModal = true;
    }

    public function editWorkerAssignment(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki hak akses mengedit penugasan.');
            return;
        }

        $assign = WorkerAssignment::findOrFail($id);
        $this->resetValidation();
        $this->editingAssignmentId = $assign->id;
        $this->worker_id = $assign->worker_id;
        $this->assigned_role = $assign->assigned_role;
        $this->showWorkerModal = true;
    }

    public function deleteWorkerAssignment(int $id): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki hak akses menghapus penugasan.');
            return;
        }

        $assign = WorkerAssignment::with(['worker', 'unit'])->findOrFail($id);
        $workerName = $assign->worker->name ?? 'Pekerja';
        $unitCode = $assign->unit->code ?? $this->unitId;
        $userName = auth()->user()->name ?? 'User';

        $assign->delete();

        ActivityLogger::log(
            'WORKER_UNASSIGNED',
            "Penugasan pekerja {$workerName} pada Unit {$unitCode} dihapus oleh {$userName}."
        );

        session()->flash('success', 'Penugasan pekerja berhasil dihapus!');
    }

    public function saveWorkerAssignment(): void
    {
        if (!$this->canManageOperational()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }

        $this->validate([
            'worker_id' => 'required|exists:workers,id',
            'assigned_role' => 'required|string|max:100',
        ]);

        $unit = Unit::findOrFail($this->unitId);
        $worker = Worker::find($this->worker_id);
        $workerName = $worker->name ?? 'Pekerja';
        $userName = auth()->user()->name ?? 'User';

        if ($this->editingAssignmentId) {
            $assign = WorkerAssignment::findOrFail($this->editingAssignmentId);
            $assign->update([
                'worker_id' => $this->worker_id,
                'assigned_role' => $this->assigned_role,
            ]);

            ActivityLogger::log(
                'WORKER_ASSIGNMENT_UPDATED',
                "Penugasan pekerja {$workerName} ({$this->assigned_role}) pada Unit {$unit->code} diperbarui oleh {$userName}."
            );

            session()->flash('success', 'Penugasan pekerja berhasil diperbarui!');
        } else {
            WorkerAssignment::updateOrCreate([
                'project_id' => $unit->project_id,
                'unit_id' => $unit->id,
                'worker_id' => $this->worker_id,
            ], [
                'assigned_role' => $this->assigned_role,
            ]);

            ActivityLogger::log(
                'WORKER_ASSIGNED',
                "Pekerja {$workerName} ditugaskan pada Unit {$unit->code} sebagai {$this->assigned_role} oleh {$userName}."
            );

            session()->flash('success', 'Mandor/Tukang berhasil ditugaskan ke unit ' . $unit->code . '!');
        }

        $this->showWorkerModal = false;
        $this->editingAssignmentId = null;
    }
}
