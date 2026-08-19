<div class="space-y-6">

    <!-- Top Header Card -->
    <x-card title="Manajemen User & Hak Akses Sistem" subtitle="Kelola akun pengguna, penetapan peran (Founder, Supervisor, Pengawas, Finance, Marketing), dan status aktifasi">
        <x-slot:actions>
            <x-button variant="primary" icon="plus" wire:click="openCreateModal">
                Tambah User Baru
            </x-button>
        </x-slot:actions>
    </x-card>

    <!-- Toolbar Filter & Search -->
    <div class="card-clean p-4 flex flex-col sm:flex-row items-center justify-between gap-3 border border-slate-200/80 rounded-3xl">
        <x-search-input placeholder="Cari nama atau email user..." containerClass="w-full sm:w-72" />

        <div class="w-full sm:w-48">
            <select wire:model.live="roleFilter" class="select-clean w-full text-xs font-medium">
                <option value="">Semua Peran / Role</option>
                <option value="founder">Founder Executive</option>
                <option value="admin">Administrator Utama (Admin)</option>
                <option value="supervisor">Field Supervisor</option>
                <option value="pengawas_project">Pengawas Lapangan</option>
                <option value="finance">Finance Admin</option>
                <option value="marketing">Sales Marketing</option>
            </select>
        </div>
    </div>

    <!-- Table Card -->
    <x-table :headers="['Pengguna & Email', 'Peran / Role Akses', ['label' => 'Status Akun', 'class' => 'px-5 py-3.5 text-center'], 'Tanggal Terdaftar', ['label' => 'Aksi Manajemen', 'class' => 'px-5 py-3.5 text-right']]" loadingTarget="search, roleFilter, page">
        @forelse($users as $u)
            <tr class="hover:bg-slate-50/60 transition duration-150">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center text-xs shrink-0">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">{{ $u->name }}</p>
                            <p class="text-slate-400 text-[11px] font-mono">{{ $u->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4">
                    @if($u->role === 'founder')
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 uppercase">
                            Founder Executive
                        </span>
                    @elseif($u->role === 'admin')
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-purple-50 text-purple-800 border border-purple-200 uppercase">
                            Administrator Utama
                        </span>
                    @elseif($u->role === 'supervisor')
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-800 border border-indigo-200 uppercase">
                            Field Supervisor
                        </span>
                    @elseif($u->role === 'pengawas_project')
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-sky-50 text-sky-800 border border-sky-200 uppercase">
                            Pengawas Lapangan
                        </span>
                    @elseif($u->role === 'finance')
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 uppercase">
                            Finance Admin
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-teal-50 text-teal-800 border border-teal-200 uppercase">
                            Sales Marketing
                        </span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    @if($u->is_active)
                        <x-status-badge status="lunas" label="Aktif" />
                    @else
                        <x-status-badge status="ditolak" label="Nonaktif" />
                    @endif
                </td>
                <td class="px-5 py-4 font-mono text-slate-600 text-xs">
                    {{ format_id_datetime($u->created_at, false) }}
                </td>
                <td class="px-5 py-4 text-right whitespace-nowrap">
                    <div class="inline-flex items-center justify-end gap-1.5 whitespace-nowrap">
                        <x-button variant="edit" size="xs" wire:click="openEditModal({{ $u->id }})">
                            <span>Edit</span>
                        </x-button>

                        @if($u->id !== auth()->id())
                            <x-action-dropdown title="Menu Opsi User" size="xs">
                                <div class="py-1">
                                    <x-dropdown-item icon="toggle" :variant="$u->is_active ? 'warning' : 'success'" @click="confirmModalAction({
                                        title: '{{ $u->is_active ? 'Nonaktifkan Akun User' : 'Aktifkan Akun User' }}',
                                        message: '{{ $u->is_active ? 'Yakin ingin menonaktifkan akun user ' . $u->name . '? User ini tidak akan dapat login ke dalam sistem.' : 'Yakin ingin mengaktifkan kembali akun user ' . $u->name . '? User ini dapat kembali login ke dalam sistem.' }}',
                                        confirmText: '{{ $u->is_active ? 'Nonaktifkan User' : 'Aktifkan User' }}',
                                        btnClass: '{{ $u->is_active ? 'px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5' : 'px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5' }}',
                                        onConfirm: () => $wire.toggleStatus({{ $u->id }})
                                    })">
                                        {{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                                    </x-dropdown-item>
                                </div>

                                <div class="py-1">
                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Akun User',
                                        message: 'Yakin ingin menghapus akun user {{ $u->name }} ({{ $u->email }}) secara permanen dari sistem?',
                                        confirmText: 'Hapus User',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteUser({{ $u->id }})
                                    })">
                                        Hapus User
                                    </x-dropdown-item>
                                </div>
                            </x-action-dropdown>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <p class="font-semibold text-slate-600">Tidak Ada Akun User Ditemukan</p>
                    <p class="text-xs text-slate-400 mt-1">Coba ganti filter kata kunci pencarian atau tambah user baru.</p>
                </td>
            </tr>
        @endforelse
    </x-table>

    <!-- Modal Form User (Tambah / Edit) -->
    <x-modal-dialog show="showModal" :title="$editingUserId ? 'Edit Akun Pengguna' : 'Tambah User Baru'" maxWidth="max-w-md">
        <form wire:submit.prevent="saveUser" class="space-y-4 text-xs">
            <div>
                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" wire:model="name" required placeholder="Contoh: Hendra Wijaya" class="input-clean w-full font-bold">
                @error('name') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Alamat Email</label>
                <input type="email" wire:model="email" required placeholder="user@atlantikperkasa.co.id" class="input-clean w-full font-mono">
                @error('email') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">
                    Kata Sandi / Password {{ $editingUserId ? '(Kosongkan jika tidak diubah)' : '' }}
                </label>
                <input type="password" wire:model="password" {{ $editingUserId ? '' : 'required' }} placeholder="Min. 6 Karakter..." class="input-clean w-full font-mono">
                @error('password') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Peran / Role Akses Sistem</label>
                <select wire:model="role" class="input-clean w-full font-bold">
                    <option value="founder">Founder Executive (Akses Penuh)</option>
                    <option value="admin">Administrator Utama (Admin Operasional)</option>
                    <option value="supervisor">Field Supervisor (Operasional & Approval)</option>
                    <option value="pengawas_project">Pengawas Lapangan (Log & Penugasan)</option>
                    <option value="finance">Finance Admin (Keuangan & Booking ACC)</option>
                    <option value="marketing">Sales Marketing (Penjualan & Pengajuan)</option>
                </select>
                @error('role') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="is_active" wire:model="is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <label for="is_active" class="font-bold text-slate-700 cursor-pointer">Akun Aktif (Dapat Login ke Sistem)</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <x-button variant="secondary" wire:click="$set('showModal', false)">Batal</x-button>
                <x-button variant="primary" type="submit">Simpan User</x-button>
            </div>
        </form>
    </x-modal-dialog>

</div>
