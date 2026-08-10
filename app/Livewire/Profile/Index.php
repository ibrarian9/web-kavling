<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Index extends Component
{
    public string $name = '';
    public string $email = '';
    public string $nik = '';
    public string $position = '';
    public string $address = '';
    public string $phone = '';
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount(): void
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->isFounder()) {
            abort(403, 'Akses menu profil dan legalitas khusus untuk Founder.');
        }

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->nik = $user->nik ?? '1471012304850001';
        $this->position = $user->position ?? 'Direktur Utama PT. Atlantik Perkasa Abadi';
        $this->address = $user->address ?? 'Jl. Utama Properti No. 88, Pekanbaru, Riau';
        $this->phone = $user->phone ?? '081234567890';
    }

    public function updateProfile(): void
    {
        /** @var User $user */
        $user = User::findOrFail(auth()->id());

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'nik' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'nik' => $this->nik ?: null,
            'position' => $this->position ?: null,
            'address' => $this->address ?: null,
            'phone' => $this->phone ?: null,
        ]);

        ActivityLogger::log('PROFILE_UPDATED', "Profil akun {$user->name} ({$user->role}) diperbarui (NIK: {$this->nik}).");

        $msg = 'Profil Founder berhasil diperbarui!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /** @var User $user */
        $user = User::findOrFail(auth()->id());

        if (!Hash::check($this->current_password, $user->password)) {
            ActivityLogger::log('PASSWORD_CHANGE_FAILED', "Percobaan ubah kata sandi gagal untuk akun {$user->name} (Password lama salah).");
            $this->addError('current_password', 'Password lama tidak cocok.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        ActivityLogger::log('PASSWORD_CHANGED', "Kata sandi akun {$user->name} ({$user->role}) berhasil diperbarui.");

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        $passMsg = 'Password berhasil diubah!';
        session()->flash('password_success', $passMsg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $passMsg]);
    }

    public function render()
    {
        return view('livewire.profile.index')
            ->layout('layouts.app', ['title' => 'Profil Akun & Data Founder']);
    }
}
