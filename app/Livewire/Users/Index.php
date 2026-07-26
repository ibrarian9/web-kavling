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
        if (!auth()->user()->isFounder()) {
            abort(403, 'Akses khusus Founder untuk manajemen user.');
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
        if (!auth()->user()->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak mengelola akun pengguna.');
            return;
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($this->editingUserId ?? 'NULL'),
            'role' => 'required|in:founder,supervisor,pengawas_project,finance,marketing',
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

        User::updateOrCreate(['id' => $this->editingUserId], $data);

        session()->flash('success', 'Data akun user berhasil ' . ($this->editingUserId ? 'diperbarui' : 'ditambahkan') . '!');
        $this->showModal = false;
    }

    public function toggleStatus(int $id)
    {
        if (!auth()->user()->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak mengubah status user.');
            return;
        }

        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun Founder Anda sendiri.');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', 'Status akun ' . $user->name . ' berhasil diubah menjadi ' . ($user->is_active ? 'Aktif' : 'Nonaktif') . '!');
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
