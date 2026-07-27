<div class="space-y-6">

    <!-- Header Section & Toolbar -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Arus Kas Per-Proyek, Per-Unit & Konsolidasi Global</h2>
            <p class="text-slate-500 text-xs mt-0.5">Rekapitulasi kas masuk & keluar per unit & lokasi perumahan serta rincian konsolidasi global</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Mode Switcher -->
            <div class="bg-slate-100 p-1 rounded-xl border border-slate-200/80 flex text-xs">
                <button wire:click="$set('view_mode', 'global')" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $view_mode === 'global' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Kas Global
                </button>
                <button wire:click="$set('view_mode', 'project')" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $view_mode === 'project' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Per Proyek / Unit
                </button>
            </div>

            @if ($view_mode === 'project')
                <!-- Filter Proyek -->
                <select wire:model.live="filter_project_id" class="input-clean font-semibold text-xs py-2">
                    <option value="">-- Semua Proyek --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>

                <!-- Filter Unit -->
                <select wire:model.live="filter_unit_id" class="input-clean font-semibold text-xs py-2">
                    <option value="">-- Semua Unit --</option>
                    @foreach($availableUnits as $u)
                        <option value="{{ $u->id }}">Unit {{ $u->code }} ({{ $u->project->name }})</option>
                    @endforeach
                </select>
            @endif

            @if(auth()->user()->isFinance() || auth()->user()->isFounder() || auth()->user()->isPengawasProject())
                <button wire:click="openManualModal" class="btn-primary whitespace-nowrap text-xs px-3.5 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Catat Mutasi Kas</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Summary KPI Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Total Masuk -->
        <div class="card-clean p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    {{ $view_mode === 'global' ? 'Kas Masuk Global' : 'Kas Masuk (Tersaring)' }}
                </span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 font-mono mt-2">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Penjualan unit, DP, booking & cicilan pembeli</p>
        </div>

        <!-- Total Keluar -->
        <div class="card-clean p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    {{ $view_mode === 'global' ? 'Kas Keluar Global' : 'Kas Keluar (Tersaring)' }}
                </span>
                <div class="p-2.5 rounded-xl bg-rose-50 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-600 font-mono mt-2">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Upah tukang, material mingguan & operasional</p>
        </div>

        <!-- Net Cashflow -->
        <div class="bg-slate-900 text-white rounded-2xl p-5 shadow-xl space-y-1 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    Saldo Kas {{ $view_mode === 'global' ? 'Konsolidasi Global' : 'Bersih' }}
                </span>
                <div class="p-2 rounded-xl bg-emerald-500/20 text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-white mt-2">Rp {{ number_format($netCashflow, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Total akumulasi saldo kas bersih</p>
        </div>
    </div>

    @if ($view_mode === 'global')
        <!-- Breakdown per perumahan -->
        <div class="card-clean p-5 space-y-4">
            <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Ringkasan Kas Konsolidasi per Perumahan / Proyek</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($projectBreakdown as $pb)
                    <div class="p-4 bg-slate-50/80 border border-slate-200/80 rounded-xl space-y-2">
                        <div class="font-bold text-slate-900 text-xs flex justify-between items-center">
                            <span>{{ $pb['name'] }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">ID: #{{ $pb['id'] }}</span>
                        </div>
                        <div class="text-xs space-y-1.5 font-mono pt-1">
                            <div class="flex justify-between text-emerald-600 font-medium">
                                <span class="text-slate-500 font-sans">Kas Masuk:</span>
                                <span>Rp {{ number_format($pb['masuk'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-rose-600 font-medium">
                                <span class="text-slate-500 font-sans">Kas Keluar:</span>
                                <span>Rp {{ number_format($pb['keluar'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-200/80 pt-1.5 font-extrabold text-slate-900">
                                <span class="font-sans text-slate-700">Saldo Bersih:</span>
                                <span>Rp {{ number_format($pb['net'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Mutasi Arus Kas Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Jurnal Mutasi Transaksi Kas</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Proyek</th>
                        <th class="px-5 py-3.5">Tipe</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Keterangan Transaksi</th>
                        <th class="px-5 py-3.5">Pencatat</th>
                        <th class="px-5 py-3.5 text-right">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            <td class="px-5 py-4 text-slate-600 font-mono font-medium">
                                {{ $trx->transaction_date->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $trx->project->name }}
                            </td>
                            <td class="px-5 py-4">
                                @if($trx->type === 'masuk')
                                    <span class="status-disetujui">Kas Masuk</span>
                                @else
                                    <span class="status-ditolak">Kas Keluar</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="capitalize font-semibold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md text-[10px] border border-slate-200/60">
                                    {{ str_replace('_', ' ', $trx->category) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-800">
                                {{ $trx->description }}
                            </td>
                            <td class="px-5 py-4 text-slate-500">
                                {{ $trx->creator->name ?? 'System' }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-sm {{ $trx->type === 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $trx->type === 'masuk' ? '+' : '-' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Mutasi Transaksi Kas Tercatat</p>
                                <p class="text-xs text-slate-400 mt-1">Catat transaksi kas masuk atau keluar baru menggunakan tombol di atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Modal Catat Kas Manual -->
    @if($showManualModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Catat Mutasi Kas Manual</h3>
                    <button wire:click="$set('showManualModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveTransaction" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Proyek</label>
                        <select wire:model="project_id" class="input-clean w-full font-semibold">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Tipe Arus Kas</label>
                            <select wire:model="type" class="input-clean w-full font-bold">
                                <option value="masuk">Pemasukan (Masuk)</option>
                                <option value="keluar">Pengeluaran (Keluar)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Tanggal Mutasi</label>
                            <input type="date" wire:model="transaction_date" class="input-clean w-full font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nominal (Rp)</label>
                        <x-currency-input model="amount" class="input-clean w-full font-bold text-sm font-mono" placeholder="Rp 0" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Keterangan Transaksi</label>
                        <input type="text" wire:model="description" required placeholder="Pendapatan lain / Konsumsi tukang..." class="input-clean w-full">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showManualModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Mutasi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
