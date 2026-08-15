<!-- TAB 2: PEMBAYARAN & SKEMA LAHAN PROYEK -->
<div class="space-y-6">

    <!-- KPI Summary Cards for Land Payments -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="kpi-card-purple bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Nilai Lahan Seluruh Proyek</span>
                <div class="p-2.5 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">Rp {{ number_format($totalLandCost, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Kesepakatan beli tanah {{ $projects->count() }} proyek</p>
        </div>

        <div class="kpi-card-emerald bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Terbayar ke Pemilik</span>
                <div class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">Rp {{ number_format($totalLandPaid, 0, ',', '.') }}</p>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1">{{ $totalLandCost > 0 ? round(($totalLandPaid / $totalLandCost) * 100, 1) : 0 }}% dari total nilai lahan</p>
        </div>

        <div class="kpi-card-amber bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sisa Hutang / Tanggungan Lahan</span>
                <div class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 font-mono mt-2">Rp {{ number_format($totalLandRemaining, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Sisa termin yang harus dibayar</p>
        </div>

        <div class="kpi-card-blue bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kuitansi Pembayaran</span>
                <div class="p-2.5 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-blue-700 font-mono mt-2">{{ $totalLandTransactions }} Transaksi</p>
            <p class="text-[11px] text-slate-400 mt-1">Histori transfer & pembayaran</p>
        </div>
    </div>

    <!-- Ringkasan Status Pembayaran Tiap Proyek -->
    <x-card>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Ringkasan Skema Pembayaran Tanah Tiap Proyek</h3>
                <p class="text-xs text-slate-500 mt-0.5">Status pelunasan pembelian lahan ke pemilik tanah per masing-masing lokasi proyek.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($projects as $proj)
                @php
                    $cost = (float)$proj->total_project_price;
                    $paid = (float)$proj->payments->sum('amount_paid');
                    $rem = max(0, $cost - $paid);
                    $pct = $cost > 0 ? min(100, round(($paid / $cost) * 100, 1)) : 0;
                @endphp
                <div class="p-3.5 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-emerald-300 transition duration-150 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('projects.show', $proj->id) }}" class="font-bold text-slate-900 text-xs hover:text-emerald-600 truncate flex-1">
                            {{ $proj->name }}
                        </a>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold {{ $rem == 0 && $cost > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $rem == 0 && $cost > 0 ? 'LUNAS' : ($pct > 0 ? $pct . '%' : 'BELUM BAYAR') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Nilai Lahan</span>
                            <span class="font-bold font-mono text-slate-800">Rp {{ number_format($cost, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Terbayar</span>
                            <span class="font-bold font-mono text-emerald-700">Rp {{ number_format($paid, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-slate-500">
                            <span>Sisa: <strong class="font-mono text-amber-700">Rp {{ number_format($rem, 0, ',', '.') }}</strong></span>
                            <button type="button" wire:click="openViewerModal('pdf', '{{ route('projects.land-payments-pdf', $proj->id) }}', 'Rekap Pembayaran Lahan Proyek {{ $proj->name }}')" class="text-rose-600 hover:text-rose-700 font-bold flex items-center gap-0.5" title="Lihat Rekap PDF">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span>PDF</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 col-span-full">Belum ada data proyek.</p>
            @endforelse
        </div>
    </x-card>

    <!-- Search & Project Filter Bar for Land Payments -->
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-3">
        <!-- Live Search Input -->
        <x-search-input placeholder="Cari nama proyek, catatan, metode..." wireModel="landSearch" containerClass="w-full sm:w-72" />

        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end flex-wrap">
            <!-- Project Filter Dropdown -->
            <select wire:model.live="landProjectIdFilter" class="select-clean text-xs font-bold">
                <option value="">Semua Proyek Properti</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>

            @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                <x-button variant="emerald" size="sm" wire:click="openLandPaymentModal" icon="plus">
                    Catat Bayar Lahan
                </x-button>
            @endif
        </div>
    </div>

    <!-- Table of Land Payments -->
    <x-table :headers="['Tanggal', 'Proyek Properti', 'Jumlah Pembayaran', 'Metode Bayar', 'Keterangan / Catatan', 'Resi & Dokumen', 'Dicatat Oleh', ['label' => 'Aksi', 'class' => 'p-3.5 text-center']]" loadingTarget="landSearch, landProjectIdFilter, land_page">
        @forelse($landPayments as $pay)
            <tr class="hover:bg-slate-50/80 transition duration-150">
                <td class="p-3.5 font-mono font-bold text-slate-900 text-xs">
                    {{ $pay->payment_date ? $pay->payment_date->format('d M Y') : '-' }}
                </td>
                <td class="p-3.5">
                    <a href="{{ route('projects.show', $pay->project_id) }}" class="font-bold text-slate-900 text-xs hover:text-emerald-600 block">
                        {{ $pay->project->name ?? '-' }}
                    </a>
                    <span class="text-[10px] text-slate-400">{{ $pay->project->location ?? '' }}</span>
                </td>
                <td class="p-3.5 font-mono font-bold text-rose-700 text-xs">
                    Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}
                </td>
                <td class="p-3.5">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-slate-100 border border-slate-200 text-slate-700">
                        {{ $pay->payment_method }}
                    </span>
                </td>
                <td class="p-3.5 text-xs text-slate-600 max-w-xs">
                    <p class="line-clamp-2">{{ $pay->notes ?: '-' }}</p>
                </td>
                <td class="p-3.5">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if($pay->receipt_photo_url)
                            <button type="button" wire:click="openViewerModal('image', '{{ $pay->receipt_photo_url }}', 'Foto Bukti Pembayaran Lahan - {{ $pay->project->name ?? '' }}')" class="p-1 rounded-lg bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-100 transition" title="Lihat Foto Resi / Bukti Transfer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </button>
                        @endif

                        @if($pay->uuid)
                            <button type="button" wire:click="openViewerModal('pdf', '{{ route('land-payment.receipt', $pay->uuid) }}', 'Kuitansi Pembayaran Lahan - {{ $pay->project->name ?? '' }}')" class="p-1 rounded-lg bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 transition" title="Pratinjau Kuitansi PDF">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </button>
                            <button type="button" wire:click="openViewerModal('qr', '{{ route('verify.land-payment', $pay->uuid) }}', 'Verifikasi Keabsahan Kuitansi Lahan - {{ $pay->project->name ?? '' }}')" class="p-1 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100 transition" title="Scan QR Verifikasi Publik">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </button>
                        @endif
                    </div>
                </td>
                <td class="p-3.5 text-xs text-slate-600">
                    <span class="font-medium">{{ $pay->creator->name ?? 'Sistem' }}</span>
                </td>
                <td class="p-3.5 text-center">
                    <div class="inline-flex items-center justify-center gap-1.5">
                        @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                            <x-button variant="amber" size="xs" wire:click="openLandPaymentModal({{ $pay->id }})" title="Edit Pembayaran Lahan">
                                Edit
                            </x-button>

                            <button type="button" @click="confirmModalAction({
                                title: 'Hapus Pembayaran Lahan',
                                message: 'Yakin ingin menghapus catatan pembayaran lahan sebesar Rp {{ number_format($pay->amount_paid, 0, ',', '.') }} untuk Proyek {{ $pay->project->name ?? '' }}?',
                                confirmText: 'Hapus Pembayaran',
                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                onConfirm: () => $wire.deleteLandPayment({{ $pay->id }})
                            })" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="font-bold text-slate-600">Belum Ada Riwayat Pembayaran Lahan</p>
                    <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Catat Bayar Lahan" untuk mencatat termin pembayaran pembelian tanah ke penjual.</p>
                </td>
            </tr>
        @endforelse
    </x-table>
    <div>{{ $landPayments->links() }}</div>

</div>
