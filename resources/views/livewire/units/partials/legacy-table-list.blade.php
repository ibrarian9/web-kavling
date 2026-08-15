<!-- Table Card List Legacy Sold Units -->
<div class="card-clean overflow-hidden">
    <!-- Card Header & Filter Toolbar -->
    <div class="p-4 border-b border-slate-100 space-y-3">
        <div class="flex items-center justify-between gap-3">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Daftar Unit Terjual & Lunas (Termasuk Pencatatan Masa Lalu)</span>
            </h3>
        </div>

        <!-- Filter Controls Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1 text-xs">
            <!-- Search Input -->
            <x-search-input placeholder="Cari Kode Unit / Nama Pembeli..." containerClass="relative w-full" />

            <!-- Project Filter Dropdown -->
            <div>
                <select wire:model.live="project_filter" class="input-clean w-full text-xs font-semibold">
                    <option value="">Semua Proyek</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Category Filter Dropdown -->
            <div>
                <select wire:model.live="category_filter" class="input-clean w-full text-xs font-semibold">
                    <option value="">Semua Kategori</option>
                    <option value="kavling">Kavling Tanah</option>
                    <option value="rumah">Rumah Siap Huni</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Container with Centered Loading Overlay -->
    <div class="overflow-x-auto relative min-h-[260px]">
        <!-- Reusable System Centered Table Loading Component -->
        <x-table-loading target="search, project_filter, category_filter, gotoPage, nextPage, previousPage" text="Memuat & Menyaring Data Tabel Unit..." subtext="Mohon tunggu sebentar, sistem sedang memproses data unit." />

        <table class="w-full text-left text-xs text-slate-600" wire:loading.class="opacity-30 pointer-events-none transition-opacity duration-300" wire:target="search, project_filter, category_filter, gotoPage, nextPage, previousPage">
            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3.5">Kode Unit & Proyek</th>
                    <th class="px-5 py-3.5">Kategori & Ukuran</th>
                    <th class="px-5 py-3.5">Nama Pembeli</th>
                    <th class="px-5 py-3.5 text-right">Harga HPP</th>
                    <th class="px-5 py-3.5 text-right">Harga Jual Final</th>
                    <th class="px-5 py-3.5 text-center">Status Pembayaran</th>
                    <th class="px-5 py-3.5 text-right">Aksi & Dokumen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($legacyUnits as $unit)
                    <tr class="hover:bg-slate-50/60 transition duration-150">
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-900 font-mono text-sm block">{{ $unit->code }}</span>
                            <span class="text-slate-400 text-[11px] font-medium block">{{ $unit->project->name }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-slate-800 capitalize">{{ $unit->category }} - {{ $unit->type }}</span>
                            <span class="text-slate-400 text-[11px] block font-mono">Luas: {{ number_format($unit->land_area, 0) }} m² ({{ $unit->land_width }}x{{ $unit->land_length }}m)</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($unit->officialDocument)
                                <span class="font-bold text-slate-900 block">{{ $unit->officialDocument->buyer_name }}</span>
                                <span class="text-slate-400 text-[11px] font-mono block">{{ $unit->officialDocument->buyer_contact }}</span>
                            @else
                                <span class="text-slate-400 italic">Terjual</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-medium text-slate-600">
                            Rp {{ number_format($unit->hpp, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-extrabold text-emerald-700 text-sm">
                            Rp {{ number_format($unit->final_selling_price ?? $unit->hpp, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="status-lunas font-bold">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                LUNAS 100%
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                <a href="{{ route('units.show', $unit->id) }}" wire:navigate.hover class="btn-action-unit" title="Lihat Detail Unit">
                                    <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Detail Unit</span>
                                </a>

                                @if($unit->officialDocument)
                                    <button wire:click="openViewerModal('pdf', '{{ route('documents.stream', $unit->officialDocument->id) }}', 'Pratinjau Surat SPP Lunas - {{ $unit->code }}')" class="btn-action-pdf" title="Lihat Surat SPP PDF">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>SPP PDF</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="font-semibold text-slate-600">Tidak Ada Data Unit Terjual Ditemukan</p>
                            <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter proyek Anda.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3.5 border-t border-slate-100">
        {{ $legacyUnits->links() }}
    </div>
</div>
