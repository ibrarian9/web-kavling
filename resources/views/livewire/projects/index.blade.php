<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Daftar Proyek Kavling & Properti</h2>
            <p class="text-slate-500 text-xs mt-0.5">Kelola standar luas kavling, penugasan mandor/tukang, dan lihat dashboard penjualan & arus kas proyek</p>
        </div>

        @if(auth()->user()->isFounder() || auth()->user()->isSupervisor())
            <button wire:click="openModal" class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-md transition inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Proyek Baru</span>
            </button>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Projects Table Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/80 text-slate-700 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Nama Proyek & Lokasi</th>
                        <th class="p-3.5">Pekerja Lapangan</th>
                        <th class="p-3.5">Luas Standar (m²)</th>
                        @if(auth()->user()->canViewHpp())
                            <th class="p-3.5">Harga Beli Lahan (Penjual)</th>
                            <th class="p-3.5">Harga Dasar Standar (HPP)</th>
                        @endif
                        <th class="p-3.5">Tarif Kelebihan / m²</th>
                        <th class="p-3.5">Jumlah Unit</th>
                        <th class="p-3.5 text-right">Aksi Proyek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $p)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            <td class="p-3.5">
                                <a href="{{ route('projects.show', $p->id) }}" class="font-bold text-slate-900 text-sm hover:text-emerald-600 transition block">
                                    {{ $p->name }}
                                </a>
                                <p class="text-slate-500 text-[11px] flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $p->location }}
                                </p>
                            </td>
                            <td class="p-3.5">
                                @php
                                    $pengawasAssign = $p->assignments->where('status', 'active')->filter(fn($a) => $a->user_id !== null);
                                @endphp
                                <div class="space-y-1">
                                    @forelse($pengawasAssign as $pa)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-200/80 shadow-2xs group" title="Pengawas Project System">
                                            <svg class="w-3.5 h-3.5 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span>{{ $pa->user->name ?? 'Pengawas' }}</span>
                                            @if(auth()->user()->isFounder())
                                                <button wire:click="removePengawasAssignment({{ $pa->id }})" wire:confirm="Yakin ingin mencopot Pengawas {{ $pa->user->name ?? 'ini' }} dari proyek {{ $p->name }}?" class="ml-1 text-purple-400 hover:text-rose-600 transition font-bold" title="Copot Pengawas dari Proyek ini">
                                                    ✕
                                                </button>
                                            @endif
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-slate-400 italic">Belum ada pengawas</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="p-3.5 font-mono font-medium text-slate-700">{{ number_format($p->standard_land_area, 0, ',', '.') }} m²</td>
                            @if(auth()->user()->canViewHpp())
                                <td class="p-3.5 font-mono text-purple-700 font-bold">
                                    @if($p->total_project_price > 0)
                                        Rp {{ number_format($p->total_project_price, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400 font-normal italic">-</span>
                                    @endif
                                </td>
                                <td class="p-3.5 font-mono text-emerald-700 font-bold">Rp {{ number_format($p->base_price, 0, ',', '.') }}</td>
                            @endif
                            <td class="p-3.5 font-mono text-slate-700">Rp {{ number_format($p->excess_price_per_sqm, 0, ',', '.') }} / m²</td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-full border border-slate-200">
                                    {{ $p->units_count }} Unit
                                </span>
                            </td>
                            <td class="p-3.5 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('projects.show', $p->id) }}" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>{{ auth()->user()->isPengawasProject() ? 'Detail Proyek' : 'Detail Dashboard & Arus Kas' }}</span>
                                </a>

                                @if(auth()->user()->isFounder())
                                    <button wire:click="openWorkerModal({{ $p->id }})" class="px-2.5 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-xs font-semibold border border-purple-200 inline-flex items-center gap-1 transition shadow-xs" title="Hanya Founder yang berhak menugaskan Pengawas Project">
                                        + Pengawas
                                    </button>
                                @endif

                                <a href="{{ route('units.index', ['project_id' => $p->id]) }}" class="px-2.5 py-1 bg-sky-50 hover:bg-sky-100 text-sky-700 rounded-lg text-xs font-semibold border border-sky-200 inline-flex items-center gap-1">
                                    Lihat Unit
                                </a>

                                @if(auth()->user()->isFounder() || auth()->user()->isSupervisor())
                                    <button wire:click="editProject({{ $p->id }})" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold border border-slate-300">
                                        Edit
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->canViewHpp() ? 8 : 6 }}" class="p-8 text-center text-slate-400">Belum ada proyek yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200">
            {{ $projects->links() }}
        </div>
    </div>

    <!-- Modal Form Proyek -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">
                        {{ $editingProjectId ? 'Edit Data Proyek' : 'Tambah Proyek Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveProject" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Proyek</label>
                        <input type="text" wire:model="name" placeholder="Grand Kavling..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 outline-none">
                        @error('name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Lokasi Proyek</label>
                        <input type="text" wire:model="location" placeholder="Panam, Pekanbaru, Riau" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 outline-none">
                        @error('location') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-purple-900 mb-1 uppercase tracking-wider">Harga Beli Lahan Proyek (Rp)</label>
                        <x-currency-input model="total_project_price" class="w-full bg-purple-50/50 border border-purple-200 rounded-xl px-3 py-2 text-purple-900 font-bold font-mono focus:ring-2 focus:ring-purple-500 outline-none" placeholder="0" />
                        <p class="text-[10px] text-slate-500 mt-0.5">Harga kesepakatan akuisisi / pembelian lahan tanah dari penjual</p>
                        @error('total_project_price') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Luas Standar (m²)</label>
                            <input type="number" step="0.01" wire:model="standard_land_area" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('standard_land_area') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Harga Dasar Kavling (Rp)</label>
                            <x-currency-input model="base_price" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-bold font-mono focus:ring-2 focus:ring-emerald-500 outline-none" />
                            @error('base_price') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Harga per m² Kelebihan Tanah (Rp)</label>
                        <x-currency-input model="excess_price_per_sqm" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-bold font-mono focus:ring-2 focus:ring-emerald-500 outline-none" />
                        @error('excess_price_per_sqm') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-500 shadow-md transition">Simpan Proyek</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Form Kelola & Penugasan Pengawas Project (Founder Only) -->
    @if($showWorkerModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
            <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Kelola Pengawas Proyek {{ $selectedProjectForModal->name ?? '' }}</h3>
                        <p class="text-slate-500 text-[11px]">Tugaskan, pindahkan, atau copot pengawas lapangan</p>
                    </div>
                    <button wire:click="$set('showWorkerModal', false)" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-4 text-xs flex-1 overflow-y-auto pr-1">
                    @if($selectedProjectForModal)
                        <!-- Section 1: Daftar Pengawas Aktif Saat Ini di Proyek ini -->
                        <div>
                            <h4 class="font-bold text-slate-800 mb-2 uppercase text-[11px] tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Pengawas Aktif Saat Ini di Proyek Ini
                            </h4>
                            @php
                                $activeAssignments = $selectedProjectForModal->assignments->where('status', 'active')->filter(fn($a) => $a->user_id !== null);
                            @endphp
                            @forelse($activeAssignments as $pa)
                                <div class="p-3 bg-purple-50/70 border border-purple-200/80 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <div>
                                        <span class="font-bold text-purple-900 text-xs flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $pa->user->name ?? 'Pengawas' }}
                                        </span>
                                        <p class="text-slate-500 text-[10px]">{{ $pa->user->email ?? '' }} ({{ $pa->assigned_role ?? 'Pengawas Proyek' }})</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <!-- Dropdown Pindahkan Proyek -->
                                        <select onchange="if(this.value) @this.call('movePengawasAssignment', {{ $pa->id }}, this.value)" class="text-[11px] bg-white border border-purple-200 rounded-lg px-2 py-1 font-semibold text-purple-900 focus:ring-1 focus:ring-purple-500">
                                            <option value="">-- Pindahkan Proyek --</option>
                                            @foreach($allProjects as $otherProj)
                                                @if($otherProj->id !== $selectedProjectForModal->id)
                                                    <option value="{{ $otherProj->id }}">Ke {{ $otherProj->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button wire:click="removePengawasAssignment({{ $pa->id }})" wire:confirm="Yakin ingin mencopot {{ $pa->user->name ?? 'Pengawas ini' }} dari proyek ini?" class="btn-secondary text-[11px] px-2.5 py-1 text-rose-600 hover:bg-rose-50 hover:border-rose-200 font-semibold" title="Copot Pengawas">
                                            Copot
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-400 text-xs italic bg-slate-50 p-3 rounded-xl border border-slate-100 mb-2">Belum ada pengawas yang ditugaskan pada proyek ini.</p>
                            @endforelse
                        </div>
                    @endif

                    <!-- Section 2: Form Tambah Penugasan Pengawas Baru -->
                    <div class="pt-3 border-t border-slate-100">
                        <h4 class="font-bold text-slate-800 mb-2 uppercase text-[11px] tracking-wider">+ Penugasan Pengawas Baru</h4>
                        <form wire:submit.prevent="saveWorkerAssignment" class="space-y-3">
                            <div>
                                <label class="block font-semibold text-purple-900 mb-1">Pilih Akun Pengawas Project</label>
                                <select wire:model="assign_user_id" class="w-full input-clean font-bold text-xs">
                                    @forelse($pengawasUsers as $pu)
                                        <option value="{{ $pu->id }}">{{ $pu->name }} ({{ $pu->email }})</option>
                                    @empty
                                        <option value="">Semua Pengawas Project sudah ditugaskan pada proyek ini</option>
                                    @endforelse
                                </select>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Peran / Posisi Penugasan</label>
                                <input type="text" wire:model="assigned_role" placeholder="Pengawas Utama Proyek A..." class="w-full input-clean font-bold text-xs">
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="btn-primary bg-purple-600 hover:bg-purple-700 text-xs px-4 py-2">Tugaskan Pengawas Baru</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showWorkerModal', false)" class="btn-secondary text-xs px-4 py-2">Tutup</button>
                </div>
            </div>
        </div>
    @endif

</div>
