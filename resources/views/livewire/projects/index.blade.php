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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        <div class="kpi-card-blue bg-white border border-slate-200/80 rounded-3xl p-4 sm:p-5 shadow-xs min-w-0 flex flex-col justify-between">
            <div class="flex items-center justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Total Proyek Kavling</span>
                <div class="p-2 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <div class="mt-2 min-w-0">
                <p class="text-base sm:text-lg xl:text-2xl font-extrabold text-slate-900 font-mono tracking-tight truncate">{{ $projects->total() }} <span class="text-[10px] sm:text-xs font-normal font-sans text-slate-400">Proyek</span></p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Lokasi perumahan & kavling</p>
            </div>
        </div>

        <div class="kpi-card-emerald bg-white border border-slate-200/80 rounded-3xl p-4 sm:p-5 shadow-xs min-w-0 flex flex-col justify-between">
            <div class="flex items-center justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Total Unit Terdaftar</span>
                <div class="p-2 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
            </div>
            <div class="mt-2 min-w-0">
                <p class="text-base sm:text-lg xl:text-2xl font-extrabold text-slate-900 font-mono tracking-tight truncate">{{ \App\Models\Unit::count() }} <span class="text-[10px] sm:text-xs font-normal font-sans text-slate-400">Unit</span></p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Kavling tanah & rumah</p>
            </div>
        </div>

        <div class="kpi-card-amber bg-white border border-slate-200/80 rounded-3xl p-4 sm:p-5 shadow-xs min-w-0 flex flex-col justify-between col-span-1 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Pengawas Bertugas</span>
                <div class="p-2 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
            <div class="mt-2 min-w-0">
                <p class="text-base sm:text-lg xl:text-2xl font-extrabold text-purple-700 font-mono tracking-tight truncate">
                    {{ \App\Models\WorkerAssignment::where('status', 'active')->whereNotNull('user_id')->count() }} <span class="text-[10px] sm:text-xs font-normal font-sans text-slate-400">Penugasan</span>
                </p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Pengawas proyek aktif</p>
            </div>
        </div>
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
        $headers = ['Nama Proyek & Lokasi', 'Pengawas Proyek', 'Luas Standar (m²)'];
        if(auth()->user()->canViewSalesPrices() && !auth()->user()->isAdmin()) {
            $headers[] = 'Harga Dasar Standar (HPP)';
            $headers[] = 'Tarif Kelebihan / m²';
        }
        $headers[] = 'Jumlah Unit';
        $headers[] = ['label' => 'Aksi Proyek', 'class' => 'p-3.5 text-center'];
    @endphp

    <!-- Unified Responsive Projects Table -->
    <x-table :headers="$headers" loadingTarget="search, page, gotoPage, nextPage, previousPage">
        @forelse($projects as $p)
            @php
                $activePengawasAssignments = $p->assignments->where('status', 'active')->filter(fn($a) => $a->user_id !== null || $a->user !== null);
            @endphp
            <tr class="hover:bg-slate-50/80 transition duration-150">
                <td data-label="Nama Proyek & Lokasi" class="p-3.5">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('units.index', ['project_id' => $p->id]) }}" wire:navigate.hover class="font-bold text-slate-900 text-sm hover:text-emerald-600 transition block">
                            {{ $p->name }}
                        </a>
                    @else
                        <a href="{{ route('projects.show', $p->id) }}" wire:navigate.hover class="font-bold text-slate-900 text-sm hover:text-emerald-600 transition block">
                            {{ $p->name }}
                        </a>
                    @endif
                    <p class="text-slate-500 text-[11px] flex items-center gap-1 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $p->location }}
                    </p>
                </td>
                <td data-label="Pengawas Proyek" class="p-3.5">
                    <div class="flex flex-wrap gap-1.5 max-w-xs">
                        @forelse($activePengawasAssignments as $pa)
                            @if($pa->user)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-200/80 shadow-2xs group" title="Pengawas Project: {{ $pa->user->name }}">
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
                            @endif
                        @empty
                            <span class="text-[10px] text-slate-400 italic">Belum ada pengawas</span>
                        @endforelse
                    </div>
                </td>
                <td data-label="Luas Standar" class="p-3.5 font-mono font-medium text-slate-700 whitespace-nowrap">{{ number_format($p->standard_land_area, 0, ',', '.') }} m²</td>
                @if(auth()->user()->canViewSalesPrices() && !auth()->user()->isAdmin())
                    <td data-label="Harga Dasar (HPP)" class="p-3.5 font-mono text-emerald-700 font-bold whitespace-nowrap">Rp {{ number_format($p->base_price, 0, ',', '.') }}</td>
                    <td data-label="Tarif Kelebihan" class="p-3.5 font-mono text-slate-700 whitespace-nowrap">Rp {{ number_format($p->excess_price_per_sqm, 0, ',', '.') }} / m²</td>
                @endif
                <td data-label="Jumlah Unit" class="p-3.5 font-bold text-slate-800 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold whitespace-nowrap">
                        {{ $p->units_count }} Unit
                    </span>
                </td>
                <td data-card-action class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <x-button variant="outline" size="xs" href="{{ route('units.index', ['project_id' => $p->id]) }}" wire:navigate.hover title="Kelola Unit untuk Proyek {{ $p->name }}">
                            <span>Kelola Unit</span>
                        </x-button>

                        <x-action-dropdown title="Menu Opsi Proyek" size="xs">
                            <div class="py-1">
                                @if(!auth()->user()->isAdmin())
                                    <x-dropdown-item icon="detail" href="{{ route('projects.show', $p->id) }}" wire:navigate.hover>
                                        Detail Dashboard
                                    </x-dropdown-item>
                                @endif

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
            <x-table-empty colspan="8" title="Belum Ada Proyek Properti Dibuat" message="Gunakan tombol 'Tambah Proyek Baru' di atas untuk mendaftarkan proyek kavling." />
        @endforelse
    </x-table>

    <div>{{ $projects->links() }}</div>

    <!-- Modal Form Create / Edit Proyek -->
    @include('livewire.projects.partials.modal-project-form')

    <!-- Modal Form Kelola Pengawas Proyek -->
    @include('livewire.projects.partials.modal-manage-pengawas')

</div>
