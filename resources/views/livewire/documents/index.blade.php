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
                                <a href="{{ route('documents.stream', $doc->id) }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold shadow transition inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Unduh Invoice PDF
                                </a>
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

</div>
