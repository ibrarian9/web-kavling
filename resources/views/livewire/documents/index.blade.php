<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Daftar Invoice Pemesanan Properti</h2>
            <p class="text-slate-500 text-xs mt-0.5">Dokumen invoice resmi yang diterbitkan setelah pengajuan harga jual disetujui penuh</p>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/80 text-slate-700 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">No. Invoice Pemesanan</th>
                        <th class="p-3.5">Pembeli</th>
                        <th class="p-3.5">Unit Properti</th>
                        <th class="p-3.5">Harga Jual Final</th>
                        <th class="p-3.5">Diterbitkan Oleh</th>
                        <th class="p-3.5">Tanggal Terbit</th>
                        <th class="p-3.5 text-right">Aksi PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            <td class="p-3.5 font-mono font-bold text-slate-900">
                                {{ $doc->document_number }}
                            </td>
                            <td class="p-3.5">
                                <p class="font-bold text-slate-800 text-sm">{{ $doc->buyer_name }}</p>
                                <p class="text-slate-500 text-[11px]">{{ $doc->buyer_contact }}</p>
                            </td>
                            <td class="p-3.5">
                                <span class="font-bold text-slate-900">{{ $doc->unit->code }}</span>
                                <p class="text-slate-500 text-[11px]">{{ $doc->unit->project->name }}</p>
                            </td>
                            <td class="p-3.5 font-mono font-bold text-emerald-700 text-sm">
                                Rp {{ number_format($doc->proposal->proposed_price, 0, ',', '.') }}
                            </td>
                            <td class="p-3.5 font-medium text-slate-700">
                                {{ $doc->issuer->name }}
                            </td>
                            <td class="p-3.5 text-slate-600">
                                {{ $doc->issued_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-3.5 text-right space-x-1">
                                <button wire:click="openViewerModal('pdf', '{{ route('documents.stream', $doc->id) }}', 'Pratinjau Dokumen SPP - {{ $doc->document_number }}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold shadow transition inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    PDF
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada surat resmi yang diterbitkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200">
            {{ $documents->links() }}
        </div>
    </div>

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
