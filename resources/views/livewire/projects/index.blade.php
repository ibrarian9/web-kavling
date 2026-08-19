<div class="space-y-6">

    <!-- Header Section -->
    <x-card padding="p-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Daftar Proyek Kavling & Properti</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[11px] font-extrabold border border-blue-200">Master Data</span>
                </h2>
                <p class="text-slate-500 text-xs mt-0.5">Kelola standar luas kavling, penugasan pengawas lapangan, skema tanah, dan pantau unit properti.</p>
            </div>

            @if(auth()->user()->isAdminOrFounder() || auth()->user()->isSupervisor())
                <x-button variant="emerald" size="sm" wire:click="openModal" icon="plus">
                    Tambah Proyek Baru
                </x-button>
            @endif
        </div>
    </x-card>

    <!-- Summary KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="kpi-card-blue bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Proyek Kavling</span>
                <div class="p-2.5 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">{{ $projects->total() }} Proyek</p>
            <p class="text-[11px] text-slate-400 mt-1">Lokasi perumahan & kavling terdaftar</p>
        </div>

        <div class="kpi-card-emerald bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Unit Terdaftar</span>
                <div class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">{{ \App\Models\Unit::count() }} Unit</p>
            <p class="text-[11px] text-slate-400 mt-1">Kavling tanah & rumah di seluruh proyek</p>
        </div>

        @if(auth()->user()->canViewHpp())
            <div class="kpi-card-amber bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Harga Beli Lahan</span>
                    <div class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-amber-600 font-mono mt-2">
                    Rp {{ number_format(\App\Models\Project::sum('total_project_price'), 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">Akumulasi komitmen bayar ke penjual</p>
            </div>
        @else
            <div class="kpi-card-amber bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengawas Bertugas</span>
                    <div class="p-2.5 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-purple-700 font-mono mt-2">
                    {{ \App\Models\WorkerAssignment::where('status', 'active')->whereNotNull('user_id')->count() }} Penugasan
                </p>
                <p class="text-[11px] text-slate-400 mt-1">Pengawas proyek aktif</p>
            </div>
        @endif
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-3 shadow-2xs">
        <div class="flex-1 w-full">
            <x-search-input placeholder="Cari nama perumahan, proyek kavling, atau lokasi..." containerClass="w-full" />
        </div>
        @if($search)
            <x-reset-filter-button wire:click="$set('search', '')" />
        @endif
    </div>

    <!-- Projects Table -->
    @php
        $headers = ['Nama Proyek & Lokasi', 'Pekerja Lapangan', 'Luas Standar (m²)'];
        if(auth()->user()->canViewHpp()) {
            $headers[] = 'Harga Beli Lahan (Penjual)';
            $headers[] = 'Harga Dasar Standar (HPP)';
        }
        $headers[] = 'Tarif Kelebihan / m²';
        $headers[] = 'Jumlah Unit';
        $headers[] = ['label' => 'Aksi Proyek', 'class' => 'p-3.5 text-center'];
    @endphp

    <x-table :headers="$headers" loadingTarget="search, page, gotoPage, nextPage, previousPage">
        @forelse($projects as $p)
            <tr class="hover:bg-slate-50/80 transition duration-150">
                <td class="p-3.5">
                    <a href="{{ route('projects.show', $p->id) }}" wire:navigate.hover class="font-bold text-slate-900 text-sm hover:text-emerald-600 transition block">
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
                                @if(auth()->user()->isAdminOrFounder())
                                     <button type="button" @click="confirmModalAction({
                                         title: 'Copot Pengawas Proyek',
                                         message: 'Yakin ingin mencopot Pengawas {{ $pa->user->name ?? 'ini' }} dari proyek {{ $p->name }}?',
                                         confirmText: 'Copot Pengawas',
                                         btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                         onConfirm: () => $wire.removePengawasAssignment({{ $pa->id }})
                                     })" class="ml-1 text-purple-400 hover:text-rose-600 transition font-bold" title="Copot Pengawas dari Proyek ini">
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
                <td class="p-3.5 font-bold text-slate-800 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold whitespace-nowrap">
                        {{ $p->units_count }} Unit
                    </span>
                </td>
                <td class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <x-button variant="outline" size="xs" href="{{ route('units.index', ['project_id' => $p->id]) }}" wire:navigate.hover title="Kelola Unit untuk Proyek {{ $p->name }}">
                            <span>Kelola Unit</span>
                        </x-button>

                        <x-action-dropdown title="Menu Opsi Proyek" size="xs">
                            <div class="py-1">
                                <x-dropdown-item icon="detail" href="{{ route('projects.show', $p->id) }}" wire:navigate.hover>
                                    Detail Dashboard
                                </x-dropdown-item>

                                @if(auth()->user()->isAdminOrFounder() || auth()->user()->isSupervisor())
                                    <x-dropdown-item icon="edit" wire:click="editProject({{ $p->id }})">
                                        Edit Parameter
                                    </x-dropdown-item>
                                @endif

                                @if(auth()->user()->isAdminOrFounder())
                                    <x-dropdown-item icon="plus" variant="purple" wire:click="openWorkerModal({{ $p->id }})">
                                        Kelola Pengawas
                                    </x-dropdown-item>
                                @endif
                            </div>

                            @if(auth()->user()->isSuperAdmin())
                                <div class="py-1">
                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Proyek',
                                        message: 'Yakin ingin menghapus proyek {{ $p->name }}? Seluruh unit dan data terkait akan terhapus!',
                                        confirmText: 'Hapus Proyek',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteProject({{ $p->id }})
                                    })">
                                        Hapus Proyek
                                    </x-dropdown-item>
                                </div>
                            @endif
                        </x-action-dropdown>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="font-semibold text-slate-600">Belum Ada Proyek Properti Dibuat</p>
                    <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Tambah Proyek Baru" di atas untuk mendaftarkan proyek kavling.</p>
                </td>
            </tr>
        @endforelse
    </x-table>

    <div>{{ $projects->links() }}</div>

    <!-- Modal Form Create / Edit Proyek -->
    @include('livewire.projects.partials.modal-project-form')

    <!-- Modal Form Kelola Pengawas Proyek -->
    @include('livewire.projects.partials.modal-manage-pengawas')

</div>
