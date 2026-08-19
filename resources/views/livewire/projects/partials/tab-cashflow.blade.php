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
    <x-card padding="p-0" class="overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Rincian Transaksi Mutasi Kas Proyek</h3>
                <span class="text-xs font-mono font-semibold text-slate-500">{{ count($cashflowTransactions) }} Transaksi Recorded</span>
            </div>

            <div class="flex items-center gap-2">
                @if(count($cashflowTransactions) > 0)
                    <x-button variant="outline" size="sm" href="{{ route('cashflow.export-pdf', ['view_mode' => 'project', 'project_id' => $project->id]) }}" target="_blank" icon="pdf">
                        <span>Lihat PDF Rekap</span>
                    </x-button>
                @else
                    <x-button variant="outline" size="sm" disabled icon="pdf" title="Belum ada data arus kas untuk digenerate PDF" class="opacity-50 cursor-not-allowed">
                        <span>PDF Rekap (Belum Ada Data)</span>
                    </x-button>
                @endif
            </div>
        </div>

        <!-- Mobile Card Layout -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($cashflowTransactions as $tx)
                <div class="p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-slate-700 text-xs font-bold">{{ format_id_date($tx->transaction_date) }}</span>
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
                        <x-category-badge :category="$tx->category" />
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">{{ $tx->description }}</p>
                    <div class="flex items-center gap-1.5 flex-wrap pt-1">
                        @if ($tx->receipt_photo_url)
                            <x-button variant="outline" size="xs" wire:click="openImageModal('{{ $tx->receipt_photo_url }}', 'Foto Struk Resi Kas - {{ $tx->description }}')" class="bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100" title="Buka Foto Struk Bukti Transfer / Transaksi">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Foto Struk</span>
                            </x-button>
                        @endif
                        <x-button variant="detail" size="xs" wire:click="openDetailModal({{ $tx->id }})">
                            <span>Detail</span>
                        </x-button>

                        @if(auth()->user()->isSuperAdmin())
                            <x-button variant="delete" size="xs" @click="confirmModalAction({
                                title: 'Hapus Mutasi Kas',
                                message: 'Yakin ingin menghapus catatan transaksi mutasi kas ini?',
                                confirmText: 'Hapus Mutasi Kas',
                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                onConfirm: () => $wire.deleteTransaction({{ $tx->id }})
                            })" title="Hapus Mutasi Kas">
                                <span>Hapus</span>
                            </x-button>
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
                            <td class="px-4 py-3 font-mono text-slate-700 font-bold whitespace-nowrap">{{ format_id_date($tx->transaction_date) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <x-category-badge :category="$tx->category" />
                            </td>
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
                                <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    @if ($tx->receipt_photo_url)
                                        <x-button variant="amber" size="xs" wire:click="openImageModal('{{ $tx->receipt_photo_url }}', 'Foto Struk Resi Kas - {{ $tx->description }}')" title="Buka Foto Struk Bukti Transfer / Transaksi">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Foto Struk</span>
                                        </x-button>
                                    @endif
                                    <x-button variant="detail" size="xs" wire:click="openDetailModal({{ $tx->id }})">
                                        <span>Detail</span>
                                    </x-button>

                                    @if(auth()->user()->isSuperAdmin())
                                        <x-action-dropdown title="Menu Opsi Kas" size="xs">
                                            <div class="py-1">
                                                <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                                    title: 'Hapus Mutasi Kas',
                                                    message: 'Yakin ingin menghapus catatan transaksi mutasi kas ini?',
                                                    confirmText: 'Hapus Mutasi Kas',
                                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                    onConfirm: () => $wire.deleteTransaction({{ $tx->id }})
                                                })">
                                                    Hapus Mutasi
                                                </x-dropdown-item>
                                            </div>
                                        </x-action-dropdown>
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
    </x-card>
</div>
