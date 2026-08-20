<div class="space-y-6">

    <!-- Top Navigation & Header Card -->
    <x-card padding="p-4 sm:p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-1.5 text-xs text-slate-500 flex-wrap">
                    <a href="{{ route('external-projects.index') }}" wire:navigate.hover class="hover:text-indigo-600 font-semibold inline-flex items-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Proyek Luar</span>
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="font-bold text-slate-700">Detail & Pencatatan Biaya</span>
                </nav>

                <!-- Title & Status Badges -->
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ $project->name }}</h1>
                    
                    @if($project->status === 'aktif')
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 whitespace-nowrap">
                            Aktif
                        </span>
                    @elseif($project->status === 'selesai')
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-extrabold bg-blue-100 text-blue-800 border border-blue-300 whitespace-nowrap">
                            Selesai
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300 whitespace-nowrap">
                            Tertunda
                        </span>
                    @endif
                </div>

                @if($project->location || $project->client_name)
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 pt-0.5">
                        @if($project->client_name)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <strong class="text-slate-700">Klien:</strong> {{ $project->client_name }} {{ $project->client_phone ? '('.$project->client_phone.')' : '' }}
                            </span>
                        @endif
                        @if($project->location)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $project->location }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Toolbar Actions -->
            <div class="flex items-center gap-2 flex-wrap pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100 shrink-0">
                <x-button variant="outline" size="sm" icon="back" href="{{ route('external-projects.index') }}" wire:navigate.hover>
                    <span>Kembali</span>
                </x-button>

                <x-button variant="secondary" size="sm" icon="pdf" wire:click="openViewerModal('pdf', '{{ route('external-projects.report-pdf', $project->id) }}', 'Rekapitulasi Biaya - {{ addslashes($project->name) }}')">
                    <span>Pratinjau Rekap PDF</span>
                </x-button>
            </div>
        </div>
    </x-card>

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

    <!-- Financial KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="kpi-card-amber bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Belanja Barang</span>
                <div class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 font-mono mt-2">Rp {{ number_format($totalMaterialCost, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Biaya bahan bangunan & logistik</p>
        </div>

        <div class="kpi-card-teal bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Upah Tukang</span>
                <div class="p-2.5 rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-teal-700 font-mono mt-2">Rp {{ number_format($totalWageCost, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Upah tukang & mandor luar</p>
        </div>

        <div class="kpi-card-rose bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pengeluaran Proyek</span>
                <div class="p-2.5 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-700 font-mono mt-2">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Material + Upah Tukang</p>
        </div>

        @if($contractValue > 0)
            <div class="kpi-card-emerald bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sisa Anggaran / Margin</span>
                    <div class="p-2.5 rounded-2xl {{ $marginBalance >= 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }} border shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold font-mono mt-2 {{ $marginBalance >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ $marginBalance >= 0 ? '+' : '' }} Rp {{ number_format($marginBalance, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">Dari Kontrak Rp {{ number_format($contractValue, 0, ',', '.') }}</p>
            </div>
        @else
            <div class="kpi-card-blue bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status Pencatatan</span>
                    <div class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <p class="text-xl font-extrabold text-indigo-900 mt-2">Pencatatan Mandiri</p>
                <p class="text-[11px] text-slate-400 mt-1">Terpisah dari Arus Kas Global</p>
            </div>
        @endif
    </div>

    <!-- Main Navigation Tabs -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="flex items-center border-b border-slate-200/80 px-4 pt-3 gap-2 bg-slate-50/70 overflow-x-auto">
            <button type="button" wire:click="$set('activeTab', 'materials')" class="px-4 py-2.5 font-bold text-xs sm:text-sm rounded-t-xl transition-all border-b-2 flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'materials' ? 'bg-white text-indigo-700 border-indigo-600 shadow-2xs' : 'text-slate-500 hover:text-slate-800 border-transparent hover:bg-slate-100/60' }}">
                <svg class="w-4 h-4 {{ $activeTab === 'materials' ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Pencatatan Material / Barang</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $activeTab === 'materials' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-200 text-slate-700' }}">
                    {{ $materials->total() }}
                </span>
            </button>

            <button type="button" wire:click="$set('activeTab', 'wages')" class="px-4 py-2.5 font-bold text-xs sm:text-sm rounded-t-xl transition-all border-b-2 flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'wages' ? 'bg-white text-teal-700 border-teal-600 shadow-2xs' : 'text-slate-500 hover:text-slate-800 border-transparent hover:bg-slate-100/60' }}">
                <svg class="w-4 h-4 {{ $activeTab === 'wages' ? 'text-teal-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Pencatatan Upah Tukang</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $activeTab === 'wages' ? 'bg-teal-100 text-teal-800' : 'bg-slate-200 text-slate-700' }}">
                    {{ $wages->total() }}
                </span>
            </button>
        </div>

        <!-- Tab 1: Materials Content -->
        @if($activeTab === 'materials')
            <div class="p-4 sm:p-5 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <x-search-input model="materialSearch" placeholder="Cari nama barang, supplier, catatan..." containerClass="w-full sm:w-80" />

                    <div class="flex items-center gap-2">
                        <x-button variant="primary" size="sm" icon="plus" wire:click="openMaterialModal">
                            <span>Tambah Pembelian Barang</span>
                        </x-button>
                    </div>
                </div>

                @php
                    $matHeaders = [
                        'Tanggal Pembelian',
                        'Nama Material / Barang',
                        'Supplier / Toko',
                        'Jumlah & Satuan',
                        'Harga Satuan',
                        'Total Biaya',
                        'Nota / Bukti',
                        ['label' => 'Aksi', 'class' => 'p-3.5 text-center']
                    ];
                @endphp

                <x-table :headers="$matHeaders" loadingTarget="materialSearch, materialsPage">
                    @forelse($materials as $mat)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            <td data-label="Tanggal Pembelian" class="p-3.5 font-mono text-xs text-slate-700 whitespace-nowrap">
                                {{ $mat->purchase_date ? $mat->purchase_date->format('d/m/Y') : '-' }}
                            </td>

                            <td data-label="Nama Material" class="p-3.5">
                                <span class="font-bold text-slate-900 text-xs block">{{ $mat->item_name }}</span>
                                @if($mat->notes)
                                    <p class="text-[11px] text-slate-500 italic mt-0.5">{{ $mat->notes }}</p>
                                @endif
                            </td>

                            <td data-label="Supplier" class="p-3.5 text-slate-600 text-xs">
                                {{ $mat->supplier ?: '-' }}
                            </td>

                            <td data-label="Jumlah & Satuan" class="p-3.5 font-mono text-xs font-semibold text-slate-700 whitespace-nowrap">
                                {{ number_format($mat->quantity, 2, ',', '.') }} {{ $mat->unit }}
                            </td>

                            <td data-label="Harga Satuan" class="p-3.5 font-mono text-xs text-slate-600 whitespace-nowrap">
                                Rp {{ number_format($mat->unit_price, 0, ',', '.') }}
                            </td>

                            <td data-label="Total Biaya" class="p-3.5 font-mono text-xs text-amber-700 font-extrabold whitespace-nowrap">
                                Rp {{ number_format($mat->total_price, 0, ',', '.') }}
                            </td>

                            <td data-label="Nota / Bukti" class="p-3.5 whitespace-nowrap">
                                @if($mat->receipt_photo)
                                    <button type="button" wire:click="openViewerModal('image', '{{ Storage::url($mat->receipt_photo) }}', 'Nota: {{ addslashes($mat->item_name) }}')" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-[11px] font-semibold transition shadow-2xs" title="Lihat Foto Nota">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Lihat Nota</span>
                                    </button>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Tidak ada nota</span>
                                @endif
                            </td>

                            <td data-card-action class="p-3.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <x-button variant="edit" size="xs" wire:click="openMaterialModal({{ $mat->id }})">
                                        Edit
                                    </x-button>

                                    <x-button variant="danger" size="xs" @click="confirmModalAction({
                                        title: 'Hapus Catatan Material',
                                        message: 'Yakin ingin menghapus catatan pembelian {{ addslashes($mat->item_name) }}?',
                                        confirmText: 'Ya, Hapus',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteMaterial({{ $mat->id }})
                                    })">
                                        Hapus
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <p class="font-bold text-slate-600">Belum ada catatan belanja material</p>
                                <p class="text-xs text-slate-400 mt-1">Klik tombol "Tambah Pembelian Barang" untuk mencatat belanja semen, pasir, cat, dll.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-table>

                <div class="mt-4">
                    {{ $materials->links() }}
                </div>
            </div>
        @endif

        <!-- Tab 2: Wages Content -->
        @if($activeTab === 'wages')
            <div class="p-4 sm:p-5 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <x-search-input model="wageSearch" placeholder="Cari nama tukang, peran, catatan..." containerClass="w-full sm:w-80" />

                    <div class="flex items-center gap-2">
                        <x-button variant="primary" size="sm" icon="plus" wire:click="openWageModal">
                            <span>Catat Upah Tukang</span>
                        </x-button>
                    </div>
                </div>

                @php
                    $wageHeaders = [
                        'Tanggal Bayar',
                        'Nama Tukang / Pekerja',
                        'Peran / Posisi',
                        'Skema Upah',
                        'Nominal Upah',
                        'Bukti / Kwitansi',
                        ['label' => 'Aksi', 'class' => 'p-3.5 text-center']
                    ];
                @endphp

                <x-table :headers="$wageHeaders" loadingTarget="wageSearch, wagesPage">
                    @forelse($wages as $w)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            <td data-label="Tanggal Bayar" class="p-3.5 font-mono text-xs text-slate-700 whitespace-nowrap">
                                {{ $w->payment_date ? $w->payment_date->format('d/m/Y') : '-' }}
                            </td>

                            <td data-label="Nama Tukang" class="p-3.5">
                                <span class="font-bold text-slate-900 text-xs block">{{ $w->worker_name }}</span>
                                @if($w->notes)
                                    <p class="text-[11px] text-slate-500 italic mt-0.5">{{ $w->notes }}</p>
                                @endif
                            </td>

                            <td data-label="Peran" class="p-3.5 text-slate-700 text-xs capitalize">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-semibold border border-slate-200 text-[11px]">
                                    {{ ucfirst($w->role_type) }}
                                </span>
                            </td>

                            <td data-label="Skema Upah" class="p-3.5 text-slate-600 text-xs capitalize">
                                {{ ucfirst($w->wage_type) }}
                            </td>

                            <td data-label="Nominal Upah" class="p-3.5 font-mono text-xs text-teal-700 font-extrabold whitespace-nowrap">
                                Rp {{ number_format($w->amount, 0, ',', '.') }}
                            </td>

                            <td data-label="Bukti / Kwitansi" class="p-3.5 whitespace-nowrap">
                                @if($w->receipt_photo)
                                    <button type="button" wire:click="openViewerModal('image', '{{ Storage::url($w->receipt_photo) }}', 'Kwitansi: {{ addslashes($w->worker_name) }}')" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 text-[11px] font-semibold transition shadow-2xs" title="Lihat Foto Bukti Kwitansi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Lihat Bukti</span>
                                    </button>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Tidak ada bukti</span>
                                @endif
                            </td>

                            <td data-card-action class="p-3.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <x-button variant="edit" size="xs" wire:click="openWageModal({{ $w->id }})">
                                        Edit
                                    </x-button>

                                    <x-button variant="danger" size="xs" @click="confirmModalAction({
                                        title: 'Hapus Catatan Upah',
                                        message: 'Yakin ingin menghapus catatan upah {{ addslashes($w->worker_name) }} sebesar Rp {{ number_format($w->amount, 0, ',', '.') }}?',
                                        confirmText: 'Ya, Hapus',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteWage({{ $w->id }})
                                    })">
                                        Hapus
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="font-bold text-slate-600">Belum ada catatan upah tukang</p>
                                <p class="text-xs text-slate-400 mt-1">Klik tombol "Catat Upah Tukang" untuk mencatat pembayaran harian, mingguan, atau borongan.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-table>

                <div class="mt-4">
                    {{ $wages->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Form Material / Barang (MULTI-ROW / BANYAK SEKALIGUS) -->
    @if($showMaterialModal)
        <x-modal-dialog show="showMaterialModal" 
                        :title="$editingMaterialId ? 'Edit Catatan Pembelian Material' : 'Catat Pembelian Barang (Bisa Banyak Sekaligus)'" 
                        subTitle="Proyek Luar: {{ $project->name }}" 
                        maxWidth="max-w-4xl">
            <form wire:submit.prevent="saveMaterial" class="space-y-4 text-xs sm:text-sm">
                
                <!-- Batch Info Header (Tanggal, Supplier Utama, Nota) -->
                <div class="p-3.5 bg-slate-50/90 rounded-2xl border border-slate-200/80 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Pembelian <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="material_purchase_date" required class="input-clean w-full font-mono text-xs">
                        @error('material_purchase_date') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Toko / Supplier (Utama)</label>
                        <input type="text" wire:model="material_supplier_global" placeholder="Toko Bangunan Berkah..." class="input-clean w-full text-xs">
                        @error('material_supplier_global') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Foto Nota / Kwitansi</label>
                        <input type="file" wire:model="material_receipt_photo" accept="image/*" class="input-clean w-full text-[11px] file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @if($existingMaterialReceipt)
                            <p class="text-[10px] text-slate-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Sudah ada foto tersimpan</span>
                            </p>
                        @endif
                        @error('material_receipt_photo') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Multi-Row Material Table -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="font-extrabold text-slate-800 text-xs uppercase tracking-wider flex items-center gap-1.5">
                            <span>Daftar Item Barang</span>
                            <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-mono font-bold">{{ count($material_rows) }} Item</span>
                        </label>

                        @if(!$editingMaterialId)
                            <button type="button" wire:click="addMaterialRow" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>+ Tambah Baris Barang</span>
                            </button>
                        @endif
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                        <div class="overflow-x-auto max-h-[45vh]">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead class="bg-slate-100/80 sticky top-0 z-10 text-slate-600 uppercase tracking-wider text-[10px] border-b border-slate-200">
                                    <tr>
                                        <th class="p-2.5 text-center w-8">#</th>
                                        <th class="p-2.5 min-w-[160px]">Nama Barang <span class="text-rose-500">*</span></th>
                                        <th class="p-2.5 w-20">Qty <span class="text-rose-500">*</span></th>
                                        <th class="p-2.5 w-20">Satuan <span class="text-rose-500">*</span></th>
                                        <th class="p-2.5 min-w-[110px]">Harga Satuan (Rp)</th>
                                        <th class="p-2.5 min-w-[130px]">Total Biaya (Rp) <span class="text-rose-500">*</span></th>
                                        <th class="p-2.5 min-w-[120px]">Catatan</th>
                                        @if(!$editingMaterialId)
                                            <th class="p-2.5 text-center w-10">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @php $grandTotalModal = 0; @endphp
                                    @foreach($material_rows as $index => $row)
                                        @php $grandTotalModal += (float) ($row['total_price'] ?? 0); @endphp
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="p-2 text-center text-slate-400 font-mono text-[11px]">
                                                {{ $index + 1 }}
                                            </td>

                                            <td class="p-2">
                                                <input type="text" wire:model="material_rows.{{ $index }}.item_name" required placeholder="Contoh: Semen Padang 50 Kg" class="input-clean w-full font-bold text-xs">
                                                @error("material_rows.{$index}.item_name") <span class="text-rose-500 text-[10px] block mt-0.5 font-semibold">{{ $message }}</span> @enderror
                                            </td>

                                            <td class="p-2">
                                                <input type="number" step="0.01" min="0.01" wire:model.live.debounce.200ms="material_rows.{{ $index }}.quantity" required class="input-clean w-full font-mono text-xs text-center">
                                                @error("material_rows.{$index}.quantity") <span class="text-rose-500 text-[10px] block mt-0.5 font-semibold">{{ $message }}</span> @enderror
                                            </td>

                                            <td class="p-2">
                                                <input type="text" wire:model="material_rows.{{ $index }}.unit" required placeholder="sak, pcs" class="input-clean w-full text-xs text-center">
                                                @error("material_rows.{$index}.unit") <span class="text-rose-500 text-[10px] block mt-0.5 font-semibold">{{ $message }}</span> @enderror
                                            </td>

                                            <td class="p-2">
                                                <input type="number" step="1" min="0" wire:model.live.debounce.200ms="material_rows.{{ $index }}.unit_price" placeholder="65000" class="input-clean w-full font-mono text-xs text-right">
                                            </td>

                                            <td class="p-2">
                                                <input type="number" step="1" min="0" wire:model.live.debounce.200ms="material_rows.{{ $index }}.total_price" required placeholder="6500000" class="input-clean w-full font-mono text-xs text-right font-bold text-amber-700">
                                                @error("material_rows.{$index}.total_price") <span class="text-rose-500 text-[10px] block mt-0.5 font-semibold">{{ $message }}</span> @enderror
                                            </td>

                                            <td class="p-2">
                                                <input type="text" wire:model="material_rows.{{ $index }}.notes" placeholder="Ket..." class="input-clean w-full text-xs">
                                            </td>

                                            @if(!$editingMaterialId)
                                                <td class="p-2 text-center">
                                                    @if(count($material_rows) > 1)
                                                        <button type="button" wire:click="removeMaterialRow({{ $index }})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Baris">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    @else
                                                        <span class="text-slate-300">-</span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-slate-50 border-t border-slate-200">
                                    <tr>
                                        <td colspan="5" class="p-2.5 text-right font-bold text-slate-700">
                                            TOTAL AKUMULASI NOTA INI:
                                        </td>
                                        <td class="p-2.5 text-right font-mono font-extrabold text-amber-800 text-sm">
                                            Rp {{ number_format($grandTotalModal, 0, ',', '.') }}
                                        </td>
                                        <td colspan="{{ !$editingMaterialId ? 2 : 1 }}"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <div>
                        @if(!$editingMaterialId)
                            <button type="button" wire:click="addMaterialRow" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Tambah Baris Baru</span>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 justify-end">
                        <x-button type="button" variant="secondary" wire:click="$set('showMaterialModal', false)">Batal</x-button>
                        <x-button type="submit" variant="primary" loadingTarget="saveMaterial">
                            {{ $editingMaterialId ? 'Simpan Perubahan' : 'Simpan ' . count($material_rows) . ' Item Barang' }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-modal-dialog>
    @endif

    <!-- Modal Form Wage / Upah Tukang -->
    @if($showWageModal)
        <x-modal-dialog show="showWageModal" 
                        :title="$editingWageId ? 'Edit Catatan Upah Tukang' : 'Catat Pembayaran Upah Tukang'" 
                        subTitle="Proyek Luar: {{ $project->name }}" 
                        maxWidth="max-w-md">
            <form wire:submit.prevent="saveWage" class="space-y-4 text-xs sm:text-sm">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nama Tukang / Pekerja <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="wage_worker_name" required placeholder="Contoh: Pak Budi / Tim Borongan Pak Slamet" class="input-clean w-full font-bold text-xs">
                    @error('wage_worker_name') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Peran / Posisi <span class="text-rose-500">*</span></label>
                        <select wire:model="wage_role_type" required class="select-clean w-full">
                            <option value="tukang">Tukang</option>
                            <option value="mandor">Mandor</option>
                            <option value="kenek">Kenek / Kuli</option>
                            <option value="borongan">Tim Borongan</option>
                            <option value="operator">Operator Alat</option>
                        </select>
                        @error('wage_role_type') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Skema Upah <span class="text-rose-500">*</span></label>
                        <select wire:model="wage_type" required class="select-clean w-full">
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="borongan">Borongan</option>
                            <option value="kasbon">Kasbon</option>
                        </select>
                        @error('wage_type') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <x-currency-input 
                            label="Nominal Upah (Rp)" 
                            model="wage_amount" 
                            :value="$wage_amount"
                            placeholder="750.000"
                            badgeColor="teal"
                            required
                        />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Bayar <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="wage_payment_date" required class="input-clean w-full font-mono text-xs">
                        @error('wage_payment_date') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <x-receipt-upload 
                    model="wage_receipt_photo" 
                    :photo="$wage_receipt_photo" 
                    :existing="$existingWageReceipt"
                    label="Foto Kwitansi / Tanda Terima Upah" 
                />

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan Pekerjaan yang Diupahi</label>
                    <textarea wire:model="wage_notes" rows="2" placeholder="Misal: Upah plester dinding 3 hari..." class="input-clean w-full text-xs"></textarea>
                    @error('wage_notes') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <x-button type="button" variant="secondary" wire:click="$set('showWageModal', false)">Batal</x-button>
                    <x-button type="submit" variant="primary" loadingTarget="saveWage">Simpan Pembayaran Upah</x-button>
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
