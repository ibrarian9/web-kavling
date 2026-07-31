<!-- TAB 3: Laporan Arus Kas Proyek (Inflow & Outflow) -->
<div class="space-y-4">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kas Masuk Proyek</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-emerald-700 mt-2">Rp {{ number_format($cashflowMasuk, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">DP, Booking Fee, & Setoran Cicilan</p>
        </div>

        <div class="kpi-card-rose">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kas Keluar Proyek</span>
                <div class="p-2.5 rounded-xl bg-rose-50 text-rose-600 border border-rose-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-rose-700 mt-2">Rp {{ number_format($cashflowKeluar, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Upah Tukang, Material, Lahan, & Operasional</p>
        </div>

        <div class="kpi-card-blue">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Saldo Bersih Arus Kas</span>
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono mt-2 {{ $cashflowNet >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                Rp {{ number_format($cashflowNet, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Selisih Mutasi Kas Masuk & Keluar</p>
        </div>
    </div>

    <!-- Transaction Logs Table -->
    <div class="card-clean overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Rincian Transaksi Mutasi Kas Proyek</h3>
                <span class="text-xs font-mono font-semibold text-slate-500">{{ count($cashflowTransactions) }} Transaksi Recorded</span>
            </div>

            <div class="flex items-center gap-2">
                @if(count($cashflowTransactions) > 0)
                    <a href="{{ route('cashflow.export-pdf', ['view_mode' => 'project', 'project_id' => $project->id]) }}" target="_blank" class="btn-header-pdf">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span>Lihat PDF Rekap</span>
                    </a>
                @else
                    <button disabled class="btn-header-pdf-disabled" title="Belum ada data arus kas untuk digenerate PDF">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span>PDF Rekap (Belum Ada Data)</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Mobile Card Layout -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($cashflowTransactions as $tx)
                <div class="p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-slate-600 text-xs">{{ $tx->transaction_date ? $tx->transaction_date->format('d/m/Y') : '-' }}</span>
                            @if($tx->type === 'masuk')
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-800 rounded-full border border-emerald-200">MASUK</span>
                            @else
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-rose-100 text-rose-800 rounded-full border border-rose-200">KELUAR</span>
                            @endif
                        </div>
                        <span class="font-mono font-extrabold text-sm {{ $tx->type === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $tx->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="font-semibold text-slate-800 capitalize">{{ str_replace('_', ' ', $tx->category) }}</span>
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">{{ $tx->description }}</p>
                    <div class="flex items-center gap-1.5 flex-wrap pt-1">
                        <button wire:click="openDetailModal({{ $tx->id }})" class="btn-action-detail">
                            <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Detail</span>
                        </button>

                        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                            <button onclick="confirm('Yakin ingin menghapus catatan transaksi mutasi kas ini?') || event.stopImmediatePropagation()" wire:click="deleteTransaction({{ $tx->id }})" class="btn-action-delete" title="Hapus Mutasi Kas">
                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <p class="font-semibold text-slate-600">Belum Ada Mutasi Kas Terdeteksi</p>
                    <p class="text-xs text-slate-400 mt-1">Transaksi kas masuk dari booking & kas keluar biaya akan tampil di sini secara otomatis.</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table Layout -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/90 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Kategori Mutasi</th>
                        <th class="px-4 py-3">Deskripsi / Keterangan Transaksi</th>
                        <th class="px-4 py-3 text-center">Jenis Mutasi</th>
                        <th class="px-4 py-3 text-right">Nominal (Rp)</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cashflowTransactions as $tx)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 font-mono text-slate-600 whitespace-nowrap">{{ $tx->transaction_date ? $tx->transaction_date->format('d/m/Y') : '-' }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800 capitalize whitespace-nowrap">{{ str_replace('_', ' ', $tx->category) }}</td>
                            <td class="px-4 py-3 text-slate-800">{{ $tx->description }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if($tx->type === 'masuk')
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-800 rounded-full border border-emerald-200">MASUK</span>
                                @else
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold bg-rose-100 text-rose-800 rounded-full border border-rose-200">KELUAR</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-extrabold whitespace-nowrap {{ $tx->type === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $tx->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    <button wire:click="openDetailModal({{ $tx->id }})" class="btn-action-detail">
                                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Detail</span>
                                    </button>

                                    @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                        <button onclick="confirm('Yakin ingin menghapus catatan transaksi mutasi kas ini?') || event.stopImmediatePropagation()" wire:click="deleteTransaction({{ $tx->id }})" class="btn-action-delete" title="Hapus Mutasi Kas">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Mutasi Kas Terdeteksi</p>
                                <p class="text-xs text-slate-400 mt-1">Transaksi kas masuk dari booking & kas keluar biaya akan tampil di sini secara otomatis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
