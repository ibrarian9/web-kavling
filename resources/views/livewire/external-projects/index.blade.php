<div class="space-y-6">

    <!-- Header & Action Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 bg-indigo-500/10 text-indigo-600 rounded-xl border border-indigo-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </span>
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Proyek Luar</h1>
                    <p class="text-slate-500 text-xs mt-0.5">Pencatatan material dan upah tukang proyek luar (Terpisah dari Arus Kas Global)</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-button variant="primary" icon="plus" wire:click="openModal">
                <span>Tambah Proyek Luar</span>
            </x-button>
        </div>
    </div>

    <!-- Alert Success / Error Messages -->
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-semibold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- KPI Summary Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="kpi-card-blue bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Proyek Luar</span>
                <div class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">{{ $totalProjectsCount }} Proyek</p>
            <p class="text-[11px] text-slate-400 mt-1">{{ $totalActiveProjectsCount }} Proyek Sedang Berjalan</p>
        </div>

        <div class="kpi-card-amber bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Belanja Material</span>
                <div class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 font-mono mt-2">Rp {{ number_format($totalExternalMaterialSum, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Akumulasi pembelian barang</p>
        </div>

        <div class="kpi-card-teal bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Upah Tukang</span>
                <div class="p-2.5 rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-teal-700 font-mono mt-2">Rp {{ number_format($totalExternalWageSum, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Akumulasi upah dibayarkan</p>
        </div>

        <div class="kpi-card-rose bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pengeluaran Proyek</span>
                <div class="p-2.5 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-700 font-mono mt-2">Rp {{ number_format($totalOverallExpenses, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Material + Upah Pekerja</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <x-card padding="p-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <x-search-input model="search" placeholder="Cari nama proyek luar, klien, atau lokasi..." containerClass="w-full sm:w-96" />

            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-600">Status:</label>
                <select wire:model.live="statusFilter" class="select-clean text-xs font-semibold">
                    <option value="semua">Semua Status</option>
                    <option value="aktif">Aktif / Berjalan</option>
                    <option value="selesai">Selesai</option>
                    <option value="tertunda">Tertunda</option>
                </select>
            </div>
        </div>
    </x-card>

    <!-- Table of External Projects (Streamlined & Compact so no horizontal scrolling needed) -->
    @php
        $headers = [
            'Proyek & Klien',
            'Rincian Biaya (Material & Upah)',
            'Total Pengeluaran',
            'Status',
            ['label' => 'Aksi', 'class' => 'p-3.5 text-center']
        ];
    @endphp

    <x-table :headers="$headers" loadingTarget="search, statusFilter, page, gotoPage, nextPage, previousPage">
        @forelse($projects as $proj)
            @php
                $matCost = (float) ($proj->materials_sum_total_price ?? 0);
                $wageCost = (float) ($proj->worker_wages_sum_amount ?? 0);
                $totalCost = $matCost + $wageCost;
            @endphp
            <tr class="hover:bg-slate-50/80 transition duration-150">
                <td data-label="Proyek & Klien" class="p-3.5">
                    <a href="{{ route('external-projects.show', $proj->id) }}" wire:navigate.hover class="font-bold text-slate-900 text-sm hover:text-indigo-600 transition block">
                        {{ $proj->name }}
                    </a>
                    
                    <div class="text-[11px] text-slate-500 flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-0.5">
                        @if($proj->client_name)
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>{{ $proj->client_name }}</span>
                                @if($proj->client_phone)
                                    <span class="text-slate-400">({{ $proj->client_phone }})</span>
                                @endif
                            </span>
                        @endif

                        @if($proj->location)
                            <span class="text-slate-300">•</span>
                            <span class="inline-flex items-center gap-0.5 text-slate-500">
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $proj->location }}
                            </span>
                        @endif
                    </div>
                </td>

                <td data-label="Rincian Biaya" class="p-3.5">
                    <div class="space-y-1 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-500 text-[11px]">Material ({{ $proj->materials_count }}x):</span>
                            <span class="font-mono font-bold text-amber-700">Rp {{ number_format($matCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-500 text-[11px]">Upah ({{ $proj->worker_wages_count }}x):</span>
                            <span class="font-mono font-bold text-teal-700">Rp {{ number_format($wageCost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </td>

                <td data-label="Total Pengeluaran" class="p-3.5">
                    <span class="font-mono text-sm font-extrabold text-rose-700 block">
                        Rp {{ number_format($totalCost, 0, ',', '.') }}
                    </span>
                    @if($proj->contract_value > 0)
                        <span class="text-[10px] text-slate-500 font-sans block mt-0.5">
                            Kontrak: <strong class="text-slate-700 font-mono">Rp {{ number_format($proj->contract_value, 0, ',', '.') }}</strong>
                        </span>
                    @endif
                </td>

                <td data-label="Status" class="p-3.5">
                    @if($proj->status === 'aktif')
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 whitespace-nowrap">
                            Aktif
                        </span>
                    @elseif($proj->status === 'selesai')
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-300 whitespace-nowrap">
                            Selesai
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300 whitespace-nowrap">
                            Tertunda
                        </span>
                    @endif
                </td>

                <td data-card-action class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <x-button variant="detail" size="xs" href="{{ route('external-projects.show', $proj->id) }}" wire:navigate.hover>
                            Detail & Biaya
                        </x-button>

                        <x-button variant="outline" size="xs" icon="pdf" wire:click="openViewerModal('pdf', '{{ route('external-projects.report-pdf', $proj->id) }}', 'Rekapitulasi Biaya - {{ addslashes($proj->name) }}')" title="Pratinjau Rekap PDF">
                            PDF
                        </x-button>

                        <x-action-dropdown title="Opsi Proyek Luar" size="xs">
                            <div class="py-1">
                                <x-dropdown-item icon="edit" variant="primary" wire:click="openModal({{ $proj->id }})">
                                    Edit Data Proyek
                                </x-dropdown-item>
                            </div>

                            @if(auth()->user()->isFounder())
                                <div class="border-t border-slate-100 py-1">
                                    <x-dropdown-item icon="trash" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Proyek Luar',
                                        message: 'Yakin ingin menghapus proyek luar {{ addslashes($proj->name) }} beserta seluruh catatan belanja material dan upah tukangnya?',
                                        confirmText: 'Ya, Hapus Proyek',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteProject({{ $proj->id }})
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
                <td colspan="5" class="p-8 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="font-bold text-slate-600">Belum ada data proyek luar</p>
                    <p class="text-xs text-slate-400 mt-1">Klik tombol "Tambah Proyek Luar" untuk memulai pencatatan material dan upah.</p>
                </td>
            </tr>
        @endforelse
    </x-table>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $projects->links() }}
    </div>

    <!-- Modal Form Create / Edit External Project -->
    @if($showModal)
        <x-modal-dialog show="showModal" 
                        :title="$editingId ? 'Edit Data Proyek Luar' : 'Tambah Proyek Luar Baru'" 
                        subTitle="Pencatatan proyek di luar kawasan Atlantik" 
                        maxWidth="max-w-lg">
            <form wire:submit.prevent="save" class="space-y-4 text-xs sm:text-sm">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nama Proyek Luar <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="name" required placeholder="Contoh: Renovasi Ruko Bpk. Hendra / Cor Jalan RT 05" class="input-clean w-full font-bold text-xs">
                    @error('name') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nama Klien / Pemilik</label>
                        <input type="text" wire:model="client_name" placeholder="Bpk. Hendra" class="input-clean w-full text-xs">
                        @error('client_name') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">No. Kontak / WA Klien</label>
                        <input type="text" wire:model="client_phone" placeholder="08123456789" class="input-clean w-full font-mono text-xs">
                        @error('client_phone') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Lokasi Proyek</label>
                    <input type="text" wire:model="location" placeholder="Jl. Sudirman No. 12, Pekanbaru" class="input-clean w-full text-xs">
                    @error('location') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <x-currency-input 
                            label="Nilai Kontrak / Anggaran (Rp)" 
                            model="contract_value" 
                            :value="$contract_value"
                            placeholder="50.000.000"
                            badgeColor="indigo"
                            helpText="*Opsional (Nilai kesepakatan dengan klien)"
                        />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Status Proyek <span class="text-rose-500">*</span></label>
                        <select wire:model="status" required class="select-clean w-full">
                            <option value="aktif">Aktif / Sedang Berjalan</option>
                            <option value="selesai">Selesai</option>
                            <option value="tertunda">Tertunda</option>
                        </select>
                        @error('status') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Mulai</label>
                        <input type="date" wire:model="start_date" class="input-clean w-full font-mono text-xs">
                        @error('start_date') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Estimasi Selesai</label>
                        <input type="date" wire:model="end_date" class="input-clean w-full font-mono text-xs">
                        @error('end_date') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan / Lingkup Pekerjaan</label>
                    <textarea wire:model="notes" rows="2" placeholder="Detail lingkup pekerjaan proyek luar..." class="input-clean w-full text-xs"></textarea>
                    @error('notes') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <x-button type="button" variant="secondary" wire:click="closeModal">Batal</x-button>
                    <x-button type="submit" variant="primary" loadingTarget="save">{{ $editingId ? 'Simpan Perubahan' : 'Tambah Proyek Luar' }}</x-button>
                </div>
            </form>
        </x-modal-dialog>
    @endif

    <!-- PDF & Media Viewer Modal -->
    <x-media-viewer-modal 
        :show="$showViewerModal" 
        :type="$viewerType" 
        :url="$viewerUrl" 
        :title="$viewerTitle" 
        closeAction="closeViewerModal" 
    />

</div>
