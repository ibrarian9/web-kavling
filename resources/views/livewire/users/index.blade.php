<div class="space-y-6">

    <!-- Top Navigation & Header -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Manajemen User & Hak Akses Sistem</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola akun pengguna, penetapan peran (Founder, Supervisor, Pengawas, Finance, Marketing), dan status aktifasi</p>
        </div>

        <button wire:click="openCreateModal" class="btn-primary whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span>+ Tambah User Baru</span>
        </button>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200/80 rounded-2xl text-rose-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Toolbar Filter & Search -->
    <div class="card-clean p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="w-full sm:w-72">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email user..." class="input-clean w-full text-xs">
        </div>

        <div class="w-full sm:w-48">
            <select wire:model.live="roleFilter" class="input-clean w-full text-xs">
                <option value="">Semua Peran / Role</option>
                <option value="founder">Founder Executive</option>
                <option value="supervisor">Field Supervisor</option>
                <option value="pengawas_project">Pengawas Lapangan</option>
                <option value="finance">Finance Admin</option>
                <option value="marketing">Sales Marketing</option>
            </select>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Pengguna & Email</th>
                        <th class="px-5 py-3.5">Peran / Role Akses</th>
                        <th class="px-5 py-3.5 text-center">Status Akun</th>
                        <th class="px-5 py-3.5">Tanggal Terdaftar</th>
                        <th class="px-5 py-3.5 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
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
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-mono text-slate-500 text-xs">
                                {{ $u->created_at ? $u->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-5 py-4 text-right space-x-1 whitespace-nowrap">
                                <button wire:click="openEditModal({{ $u->id }})" class="btn-secondary text-[11px] px-2.5 py-1">
                                    Edit Akun
                                </button>
                                @if($u->id !== auth()->id())
                                    <button wire:click="toggleStatus({{ $u->id }})" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold border transition {{ $u->is_active ? 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' }}">
                                        {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                @endif
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
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Form User (Tambah / Edit) -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">
                        {{ $editingUserId ? 'Edit Akun Pengguna' : 'Tambah User Baru' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

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
                        <button type="button" wire:click="$set('showModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
