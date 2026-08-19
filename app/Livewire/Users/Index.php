<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';

    public bool $showModal = false;
    public ?int $editingUserId = null;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'marketing';
    public bool $is_active = true;

    public function mount()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses khusus Admin Utama / Supervisor untuk manajemen user.');
        }
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->editingUserId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'marketing';
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEditModal(int $id)
    {
        $this->resetValidation();
        $user = User::findOrFail($id);
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->role ?? 'marketing';
        $this->is_active = (bool)$user->is_active;
        $this->showModal = true;
    }

    public function saveUser()
    {
        if (!auth()->user()->isSuperAdmin()) {
            session()->flash('error', 'Hanya Admin Utama / Supervisor yang berhak mengelola akun pengguna.');
            return;
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($this->editingUserId ?? 'NULL'),
            'role' => 'required|in:founder,admin,supervisor,pengawas_project,finance,marketing',
            'is_active' => 'boolean',
        ];

        if (!$this->editingUserId) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->editingUserId], $data);
        $user->syncRoles([$this->role]);

        $actor = auth()->user()->name;
        if ($this->editingUserId) {
            \App\Services\ActivityLogger::log('USER_UPDATED', "Akun user {$this->name} ({$this->email} - Role: {$this->role}) diperbarui oleh {$actor}.");
        } else {
            \App\Services\ActivityLogger::log('USER_CREATED', "Akun user baru {$this->name} ({$this->email} - Role: {$this->role}) dibuat oleh {$actor}.");
        }

        $msg = 'Data akun user berhasil ' . ($this->editingUserId ? 'diperbarui' : 'ditambahkan') . '!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showModal = false;
    }

    public function toggleStatus(int $id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            $err = 'Hanya Admin Utama / Supervisor yang berhak mengubah status user.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            $err = 'Anda tidak dapat menonaktifkan akun Anda sendiri.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
        $statusStr = $user->is_active ? 'Aktif' : 'Nonaktif';
        \App\Services\ActivityLogger::log('USER_STATUS_TOGGLED', "Status akun user {$user->name} diubah menjadi {$statusStr} oleh " . auth()->user()->name . ".");

        $msg = 'Status akun ' . $user->name . ' berhasil diubah menjadi ' . ($user->is_active ? 'Aktif' : 'Nonaktif') . '!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    public function deleteUser(int $id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            $err = 'Hanya Admin Utama / Supervisor yang berhak menghapus akun pengguna.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            $err = 'Anda tidak dapat menghapus akun Anda sendiri.';
            session()->flash('error', $err);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $err]);
            return;
        }

        $name = $user->name;
        $email = $user->email;
        $role = $user->role;

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            \App\Models\WorkerAssignment::where('user_id', $user->id)->delete();
            $user->delete();
        });

        \App\Services\ActivityLogger::log(
            'DELETE_USER',
            "Founder menghapus akun user: {$name} ({$email} - Role: {$role})"
        );

        $msg = 'Akun user ' . $name . ' (' . $email . ') berhasil dihapus dari sistem!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    public function render()
    {
        $query = User::query()->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }

        $users = $query->paginate(10);

        return view('livewire.users.index', [
            'users' => $users,
        ])->layout('components.layouts.app', ['title' => 'Manajemen User & Hak Akses']);
    }
}
