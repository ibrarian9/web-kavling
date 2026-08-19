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
            <x-button variant="rose" size="sm" wire:click="openViewer('Pratinjau Laporan Belanja & Gaji Worker', 'pdf', '{{ route('field-expenses.export-pdf', ['project_id' => $project_id, 'unit_id' => $unit_id, 'category_filter' => $category_filter, 'search' => $search, 'date_period' => $datePeriod, 'start_date' => $startDate, 'end_date' => $endDate]) }}')" icon="pdf">
                <span>Cetak PDF Rekap</span>
            </x-button>
        @else
            <x-button variant="outline" size="sm" disabled icon="pdf" title="Belum ada data transaksi pengeluaran/belanja untuk digenerate PDF" class="opacity-50 cursor-not-allowed">
                <span>PDF Rekap (Belum Ada Data)</span>
            </x-button>
        @endif
    </div>
</div>
