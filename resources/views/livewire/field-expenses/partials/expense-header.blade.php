<!-- Header Toolbar -->
<div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <span class="text-[10px] uppercase font-extrabold tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                Laporan Operasional Pengawas
            </span>
        </div>
        <h1 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">Laporan Belanja & Gaji Worker</h1>
        <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi pengeluaran pembayaran gaji worker dan belanja material barang unit</p>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        @if(count($expenses) > 0)
            <button wire:click="openViewer('Pratinjau Laporan Belanja & Gaji Worker', 'pdf', '{{ route('field-expenses.export-pdf', ['project_id' => $project_id, 'unit_id' => $unit_id, 'category_filter' => $category_filter, 'search' => $search]) }}')" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-sm">
                <svg class="w-4 h-4 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Lihat PDF Rekap</span>
            </button>
        @else
            <button disabled class="px-3.5 py-2 bg-slate-100 text-slate-400 border border-slate-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 cursor-not-allowed opacity-75" title="Belum ada data transaksi pengeluaran/belanja untuk digenerate PDF">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span>PDF Rekap (Belum Ada Data)</span>
            </button>
        @endif
    </div>
</div>

@if (session()->has('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session()->has('error'))
    <div class="p-4 bg-rose-50 border border-rose-200/80 rounded-2xl text-rose-800 text-xs font-semibold flex items-center gap-2">
        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('error') }}</span>
    </div>
@endif
