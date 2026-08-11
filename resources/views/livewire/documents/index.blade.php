<div class="space-y-6">

    <!-- Header Section -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Surat Pemesanan Properti (SPP PDF)</h2>
            <p class="text-slate-500 text-xs mt-0.5">Arsip resmi dokumen SPP & kuitansi pemesanan unit properti yang telah diterbitkan</p>
        </div>

        @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance() || auth()->user()->isMarketing())
            <button wire:click="openGenerateModal" class="btn-primary text-xs sm:text-sm font-bold shadow-md" title="Generate & Terbitkan Surat Pemesanan Properti (SPP PDF) Baru">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Generate SPP PDF Baru</span>
            </button>
        @endif
    </div>

    <!-- KPI Summary Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Dokumen SPP Diterbitkan</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 font-mono tracking-tight mt-2">{{ $totalDocs }} Dokumen</p>
            <p class="text-[11px] text-slate-400 mt-1">Surat resmi pemesanan unit kavling/rumah</p>
        </div>

        <div class="kpi-card-blue">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Nilai Pemesanan Unit</span>
                <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-blue-600 font-mono tracking-tight mt-2">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Total harga jual final yang disahkan</p>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="card-clean p-4 flex flex-col md:flex-row gap-3">
        <div class="w-full md:w-64">
            <select wire:model.live="project_id" class="input-clean w-full">
                <option value="">Semua Perumahan / Proyek</option>
                @foreach ($projects as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari no. SPP, nama pembeli, no. HP, atau kode unit..." class="input-clean w-full">
        </div>
    </div>

    <!-- Documents Table -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">No. Invoice Pemesanan</th>
                        <th class="px-5 py-3.5">Nama Pembeli</th>
                        <th class="px-5 py-3.5">Unit & Proyek</th>
                        <th class="px-5 py-3.5 text-right">Harga Jual Final</th>
                        <th class="px-5 py-3.5">Diterbitkan Oleh</th>
                        <th class="px-5 py-3.5">Tgl Terbit</th>
                        <th class="px-5 py-3.5 text-center">Dokumen Resi</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            <td class="px-5 py-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                                {{ $doc->document_number }}
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900">{{ $doc->buyer_name }}</p>
                                <p class="text-slate-400 text-[11px] font-mono">{{ $doc->buyer_contact }}</p>
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-800">
                                <a href="{{ route('units.show', $doc->unit_id) }}" wire:navigate.hover class="font-bold text-slate-900 font-mono text-sm block hover:underline hover:text-emerald-700 transition" title="Ke Detail Unit {{ $doc->unit->code }}">
                                    {{ $doc->unit->code }}
                                </a>
                                <p class="text-emerald-700 font-semibold text-[11px] mt-0.5">{{ $doc->unit->project->name }}</p>
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-emerald-700 text-sm whitespace-nowrap">
                                Rp {{ number_format($doc->proposal->proposed_price ?? $doc->unit->final_selling_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-700">
                                {{ $doc->issuer->name ?? 'Sistem' }}
                            </td>
                            <td class="px-5 py-4 text-slate-600 font-mono whitespace-nowrap">
                                {{ $doc->issued_at ? $doc->issued_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    <button wire:click="openViewerModal('pdf', '{{ route('documents.stream', $doc->id) }}', 'Pratinjau Dokumen SPP - {{ $doc->document_number }}')" class="btn-action-pdf" title="Lihat Dokumen SPP PDF">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span>SPP PDF</span>
                                    </button>
                                    <button wire:click="openViewerModal('pdf', '{{ route('documents.spjb-pdf', $doc->id) }}', 'Pratinjau Surat Perjanjian Jual Beli (SPJB) - {{ $doc->unit->code }}')" class="btn-action-pdf" title="Cetak Surat Perjanjian Jual Beli (SPJB) PDF">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>SPJB PDF</span>
                                    </button>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    @if (auth()->user()->isFounder())
                                        <button wire:click="editDocument({{ $doc->id }})" class="btn-action-edit" title="Edit Dokumen SPP">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" @click="confirmModalAction({
                                            title: 'Hapus Dokumen SPP',
                                            message: 'Yakin ingin MENGHAPUS dokumen SPP {{ $doc->document_number }} ini?',
                                            confirmText: 'Hapus Dokumen',
                                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                            onConfirm: () => $wire.deleteDocument({{ $doc->id }})
                                        })" class="btn-action-delete" title="Hapus Dokumen SPP">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                    @if ($doc->unit_id)
                                        <a href="{{ route('units.show', $doc->unit_id) }}" wire:navigate.hover class="btn-action-unit" title="Lihat Detail Unit {{ $doc->unit->code }}">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            <span>Unit</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-bold text-slate-600">Belum Ada Dokumen Surat Pemesanan Properti (SPP)</p>
                                <p class="text-xs text-slate-400 mt-1">Klik tombol "Generate SPP PDF Baru" di atas untuk menerbitkan dokumen resmi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $documents->links() }}
        </div>
    </div>

    <!-- Modal Form Generate SPP PDF Baru -->
    @if($showGenerateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>{{ $editingDocumentId ? 'Edit Data Dokumen SPP' : 'Generate & Terbitkan SPP PDF Baru' }}</span>
                    </h3>
                    <button wire:click="$set('showGenerateModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="generateDocument" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Pilih Unit Kavling / Rumah *</label>
                        <select wire:model="selected_unit_id" required class="input-clean w-full font-semibold">
                            <option value="">Pilih Unit</option>
                            @foreach($allUnits as $u)
                                <option value="{{ $u->id }}">{{ $u->code }} - {{ $u->project->name }} (Status: {{ ucfirst($u->status) }})</option>
                            @endforeach
                        </select>
                        @error('selected_unit_id') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Nama Lengkap Pembeli *</label>
                            <input type="text" wire:model="buyer_name" required placeholder="Contoh: Bpk. H. Hendra Wijaya" class="input-clean w-full font-bold">
                            @error('buyer_name') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">NIK KTP Pembeli (16 Digit)</label>
                            <input type="text" wire:model="buyer_nik" placeholder="3271234567890001" class="input-clean w-full font-mono">
                            @error('buyer_nik') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">No. HP / WhatsApp Pembeli *</label>
                        <input type="text" wire:model="buyer_contact" required placeholder="081234567890" class="input-clean w-full font-mono">
                        @error('buyer_contact') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Alamat Lengkap Pembeli</label>
                        <textarea wire:model="buyer_address" rows="2" placeholder="Jl. Sudirman No. 123, Pekanbaru" class="input-clean w-full"></textarea>
                    </div>

                    <!-- Section Identitas Penjual / Founder -->
                    <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-slate-800 text-[11px] uppercase tracking-wider">Identitas Penjual (Founder)</span>
                            <span class="text-[10px] text-slate-400">Otomatis Terisi & Bisa Diubah</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <div>
                                <label class="block font-semibold text-slate-600 text-[10px] mb-0.5">Nama Founder / Penjual</label>
                                <input type="text" wire:model="seller_name" class="input-clean w-full font-bold text-xs" required>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-600 text-[10px] mb-0.5">NIK KTP Founder</label>
                                <input type="text" wire:model="seller_nik" class="input-clean w-full font-mono text-xs" placeholder="1471012304850001">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showGenerateModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">{{ $editingDocumentId ? 'Simpan Perubahan' : 'Generate SPP PDF' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- PDF Viewer Modal (Dokumen SPP PDF) -->
    @if($showViewerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-4xl w-full p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        {{ $viewerTitle }}
                    </h3>
                    <button wire:click="closeViewerModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>
                <div class="flex-1 overflow-hidden min-h-[500px]">
                    <iframe src="{{ $viewerUrl }}" class="w-full h-full rounded-2xl border border-slate-200 min-h-[500px]"></iframe>
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                    <a href="{{ $viewerUrl }}" target="_blank" class="btn-secondary text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Buka di Tab Baru / Cetak Direct</span>
                    </a>
                    <button wire:click="closeViewerModal" class="btn-primary bg-slate-800 hover:bg-slate-900 text-xs px-5 py-2">Tutup Pratinjau</button>
                </div>
            </div>
        </div>
    @endif

</div>
