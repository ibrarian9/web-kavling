<div class="space-y-6">

    <!-- Header Section -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span>Surat Pemesanan Properti (SPP PDF)</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-extrabold border border-emerald-200">Legal Archive</span>
            </h2>
            <p class="text-slate-500 text-xs mt-0.5">Arsip resmi dokumen SPP & kuitansi pemesanan unit properti yang telah diterbitkan</p>
        </div>

        @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance() || auth()->user()->isMarketing())
            <x-button variant="emerald" size="sm" wire:click="openGenerateModal" icon="plus">
                Generate SPP PDF Baru
            </x-button>
        @endif
    </div>

    <!-- KPI Summary Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="kpi-card-emerald bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Dokumen SPP Diterbitkan</span>
                <div class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 font-mono tracking-tight mt-2">{{ $totalDocs }} Dokumen</p>
            <p class="text-[11px] text-slate-400 mt-1">Surat resmi pemesanan unit kavling/rumah</p>
        </div>

        <div class="kpi-card-blue bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Nilai Pemesanan Unit</span>
                <div class="p-2.5 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-blue-600 font-mono tracking-tight mt-2">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Total harga jual final yang disahkan</p>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-3">
        <div class="w-full md:w-64">
            <select wire:model.live="project_id" class="select-clean text-xs font-bold w-full">
                <option value="">Semua Perumahan / Proyek</option>
                @foreach ($projects as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                @endforeach
            </select>
        </div>
        <x-search-input placeholder="Cari no. SPP, nama pembeli, no. HP, atau kode unit..." containerClass="w-full md:w-80" />
    </div>

    <!-- Documents Table -->
    <x-table :headers="['No. Invoice Pemesanan', 'Nama Pembeli', 'Unit & Proyek', ['label' => 'Harga Jual Final', 'class' => 'p-3.5 text-right'], 'Diterbitkan Oleh', 'Tgl Terbit', ['label' => 'Dokumen Resi', 'class' => 'p-3.5 text-center'], ['label' => 'Aksi', 'class' => 'p-3.5 text-right']]" loadingTarget="project_id, search, gotoPage, nextPage, previousPage">
        @forelse($documents as $doc)
            <tr class="hover:bg-slate-50/60 transition duration-150">
                <td class="p-3.5 font-mono font-bold text-slate-900 text-xs whitespace-nowrap">
                    {{ $doc->document_number }}
                </td>
                <td class="p-3.5">
                    <p class="font-bold text-slate-900 text-xs">{{ $doc->buyer_name }}</p>
                    <p class="text-slate-400 text-[10px] font-mono">{{ $doc->buyer_contact }}</p>
                </td>
                <td class="p-3.5 font-medium text-slate-800 text-xs">
                    <a href="{{ route('units.show', $doc->unit_id) }}" wire:navigate.hover class="font-bold text-slate-900 font-mono text-sm block hover:underline hover:text-emerald-700 transition" title="Ke Detail Unit {{ $doc->unit->code }}">
                        {{ $doc->unit->code }}
                    </a>
                    <p class="text-emerald-700 font-semibold text-[10px] mt-0.5">{{ $doc->unit->project->name }}</p>
                </td>
                <td class="p-3.5 text-right font-mono font-extrabold text-emerald-700 text-xs whitespace-nowrap">
                    Rp {{ number_format($doc->proposal->proposed_price ?? $doc->unit->final_selling_price, 0, ',', '.') }}
                </td>
                <td class="p-3.5 font-medium text-slate-700 text-xs">
                    {{ $doc->issuer->name ?? 'Sistem' }}
                </td>
                <td class="p-3.5 text-slate-600 font-mono text-xs whitespace-nowrap">
                    {{ $doc->issued_at ? $doc->issued_at->format('d/m/Y H:i') : '-' }}
                </td>
                <td class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 flex-wrap">
                        <x-button variant="outline" size="xs" wire:click="openViewerModal('pdf', '{{ route('documents.stream', $doc->id) }}', 'Pratinjau Dokumen SPP - {{ $doc->document_number }}')" title="Lihat Dokumen SPP PDF">
                            SPP PDF
                        </x-button>
                        <x-button variant="emerald" size="xs" wire:click="openViewerModal('pdf', '{{ route('documents.spjb-pdf', $doc->id) }}', 'Pratinjau Surat Perjanjian Jual Beli (SPJB) - {{ $doc->unit->code }}')" title="Cetak Surat Perjanjian Jual Beli (SPJB) PDF">
                            SPJB PDF
                        </x-button>
                    </div>
                </td>
                <td class="p-3.5 text-right whitespace-nowrap">
                    <div class="inline-flex items-center justify-end gap-1.5 flex-wrap">
                        @if (auth()->user()->isFounder())
                            <x-button variant="amber" size="xs" wire:click="editDocument({{ $doc->id }})" title="Edit Dokumen SPP">
                                Edit
                            </x-button>
                            <button type="button" @click="confirmModalAction({
                                title: 'Hapus Dokumen SPP',
                                message: 'Yakin ingin MENGHAPUS dokumen SPP {{ $doc->document_number }} ini?',
                                confirmText: 'Hapus Dokumen',
                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                onConfirm: () => $wire.deleteDocument({{ $doc->id }})
                            })" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus Dokumen SPP">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @endif
                        @if ($doc->unit_id)
                            <a href="{{ route('units.show', $doc->unit_id) }}" wire:navigate.hover>
                                <x-button variant="outline" size="xs" title="Lihat Detail Unit {{ $doc->unit->code }}">
                                    Unit
                                </x-button>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="font-bold text-slate-600">Belum Ada Dokumen Surat Pemesanan Properti (SPP)</p>
                    <p class="text-xs text-slate-400 mt-1">Klik tombol "Generate SPP PDF Baru" di atas untuk menerbitkan dokumen resmi.</p>
                </td>
            </tr>
        @endforelse
    </x-table>

    <div>{{ $documents->links() }}</div>

    <!-- Modal Form Generate SPP PDF Baru -->
    @include('livewire.documents.partials.modal-generate')

    <!-- PDF Viewer Modal (Dokumen SPP PDF) -->
    @include('livewire.documents.partials.modal-viewer')

</div>
