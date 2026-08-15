<!-- Table Card List Legacy Sold Units -->
<div class="space-y-4">
    <!-- Card Header & Filter Toolbar -->
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-3">
        <x-search-input placeholder="Cari Kode Unit / Nama Pembeli..." containerClass="w-full sm:w-72" />

        <!-- Filter Controls Grid -->
        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end flex-wrap">
            <!-- Project Filter Dropdown -->
            <select wire:model.live="project_filter" class="select-clean text-xs font-bold">
                <option value="">Semua Proyek</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                @endforeach
            </select>

            <!-- Category Filter Dropdown -->
            <select wire:model.live="category_filter" class="select-clean text-xs font-bold">
                <option value="">Semua Kategori</option>
                <option value="kavling">Kavling Tanah</option>
                <option value="rumah">Rumah Siap Huni</option>
            </select>
        </div>
    </div>

    <!-- Table Container -->
    <x-table :headers="['Kode Unit & Proyek', 'Kategori & Ukuran', 'Nama Pembeli', ['label' => 'Harga HPP', 'class' => 'p-3.5 text-right'], ['label' => 'Harga Jual Final', 'class' => 'p-3.5 text-right'], ['label' => 'Status Pembayaran', 'class' => 'p-3.5 text-center'], ['label' => 'Aksi & Dokumen', 'class' => 'p-3.5 text-right']]" loadingTarget="search, project_filter, category_filter, gotoPage, nextPage, previousPage">
        @forelse($legacyUnits as $unit)
            <tr class="hover:bg-slate-50/60 transition duration-150">
                <td class="p-3.5">
                    <span class="font-bold text-slate-900 font-mono text-sm block">{{ $unit->code }}</span>
                    <span class="text-slate-400 text-[11px] font-medium block">{{ $unit->project->name }}</span>
                </td>
                <td class="p-3.5">
                    <span class="font-bold text-slate-800 capitalize text-xs">{{ $unit->category }} - {{ $unit->type }}</span>
                    <span class="text-slate-400 text-[11px] block font-mono">Luas: {{ number_format($unit->land_area, 0) }} m² ({{ $unit->land_width }}x{{ $unit->land_length }}m)</span>
                </td>
                <td class="p-3.5">
                    @if($unit->officialDocument)
                        <span class="font-bold text-slate-900 text-xs block">{{ $unit->officialDocument->buyer_name }}</span>
                        <span class="text-slate-400 text-[10px] font-mono block">{{ $unit->officialDocument->buyer_contact }}</span>
                    @else
                        <span class="text-slate-400 text-xs italic">Terjual</span>
                    @endif
                </td>
                <td class="p-3.5 text-right font-mono font-medium text-slate-600 text-xs">
                    Rp {{ number_format($unit->hpp, 0, ',', '.') }}
                </td>
                <td class="p-3.5 text-right font-mono font-extrabold text-emerald-700 text-sm">
                    Rp {{ number_format($unit->final_selling_price ?? $unit->hpp, 0, ',', '.') }}
                </td>
                <td class="p-3.5 text-center">
                    <x-status-badge status="lunas" label="LUNAS 100%" />
                </td>
                <td class="p-3.5 text-right whitespace-nowrap">
                    <div class="inline-flex items-center justify-end gap-1.5 flex-wrap">
                        <a href="{{ route('units.show', $unit->id) }}" wire:navigate.hover>
                            <x-button variant="outline" size="xs" title="Lihat Detail Unit">
                                Detail Unit
                            </x-button>
                        </a>

                        @if($unit->officialDocument)
                            <x-button variant="emerald" size="xs" wire:click="openViewerModal('pdf', '{{ route('documents.stream', $unit->officialDocument->id) }}', 'Pratinjau Surat SPP Lunas - {{ $unit->code }}')" title="Lihat Surat SPP PDF">
                                SPP PDF
                            </x-button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <p class="font-semibold text-slate-600">Belum Ada Data Penjualan Unit Ditemukan</p>
                    <p class="text-xs text-slate-400 mt-1">Gunakan form pencatatan di atas untuk memasukkan unit masa lalu yang telah lunas.</p>
                </td>
            </tr>
        @endforelse
    </x-table>

    <div>{{ $legacyUnits->links() }}</div>
</div>
