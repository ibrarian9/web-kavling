<!-- TAB 2: PEMBAYARAN & SKEMA LAHAN PROYEK -->
<div class="space-y-6">

    <!-- KPI Summary Cards for Land Payments -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="kpi-card-purple bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    {{ $selectedProjectName ? 'Nilai Lahan (' . $selectedProjectName . ')' : 'Total Nilai Lahan Seluruh Proyek' }}
                </span>
                <div class="p-2.5 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">Rp {{ number_format($totalLandCost, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">
                {{ $selectedProjectName ? 'Target nilai lahan proyek terpilih' : 'Kesepakatan beli tanah ' . $projects->count() . ' proyek' }}
            </p>
        </div>

        <div class="kpi-card-emerald bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    {{ $selectedProjectName ? 'Terbayar (' . $selectedProjectName . ')' : 'Total Terbayar ke Pemilik' }}
                </span>
                <div class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">Rp {{ number_format($totalLandPaid, 0, ',', '.') }}</p>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1">
                @if($totalLandCost > 0)
                    {{ round(($totalLandPaid / $totalLandCost) * 100, 1) }}% dari total nilai lahan
                @else
                    Akumulasi pembayaran tercatat
                @endif
            </p>
        </div>

        <div class="kpi-card-amber bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    {{ $selectedProjectName ? 'Sisa Tanggungan (' . $selectedProjectName . ')' : 'Sisa Hutang / Tanggungan Lahan' }}
                </span>
                <div class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 font-mono mt-2">Rp {{ number_format($totalLandRemaining, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">
                {{ $totalLandRemaining > 0 ? 'Sisa termin yang harus dibayar' : 'Semua termin lunas / target tercapai' }}
            </p>
        </div>

        <div class="kpi-card-blue bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kuitansi Pembayaran</span>
                <div class="p-2.5 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
                    $rem = $cost > 0 ? max(0, $cost - $paid) : 0;
                    $pct = $cost > 0 ? min(100, round(($paid / $cost) * 100, 1)) : 0;
                @endphp
                <div class="p-3.5 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-emerald-300 transition duration-150 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('projects.show', $proj->id) }}" class="font-bold text-slate-900 text-xs hover:text-emerald-600 truncate flex-1">
                            {{ $proj->name }}
                        </a>
                        @if($cost <= 0)
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-slate-200 text-slate-700">
                                TARGET BELUM DISET
                            </span>
                        @elseif($rem == 0)
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                LUNAS
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-100 text-amber-800">
                                {{ $pct }}%
                            </span>
                        @endif
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
                        <div class="flex items-center justify-between text-[10px] text-slate-500 pt-0.5">
                            <span>Sisa: <strong class="font-mono text-amber-700">Rp {{ number_format($rem, 0, ',', '.') }}</strong></span>
                            @if($paid > 0)
                                <x-button variant="outline" size="xs" wire:click="openViewerModal('pdf', '{{ route('projects.land-payments-pdf', $proj->id) }}', 'Rekap Pembayaran Lahan Proyek {{ $proj->name }}')" title="Lihat Rekap PDF" class="!py-0.5 !px-2 !min-h-[28px] text-rose-600 border-rose-200 hover:bg-rose-50" icon="pdf">
                                    <span>PDF</span>
                                </x-button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 col-span-full">Belum ada data proyek.</p>
            @endforelse
        </div>
    </x-card>

    <!-- 2-Baris Search & Filter Controls Bar for Land Payments -->
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl space-y-3 shadow-xs">
        <!-- Baris 1: Full-Width Search Input -->
        <div class="w-full">
            <x-search-input placeholder="Cari nama proyek, catatan pembayaran, metode transfer..." wireModel="landSearch" containerClass="w-full" />
        </div>

        <!-- Baris 2: Filter Controls & Action Button -->
        <div class="flex items-center justify-between gap-2.5 flex-wrap pt-1 border-t border-slate-100/80">
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Global Date Period Filter -->
                <x-date-period-filter 
                    periodModel="landDatePeriod" 
                    startModel="landStartDate" 
                    endModel="landEndDate" 
                    :periodValue="$landDatePeriod" 
                />

                <!-- Project Filter Dropdown -->
                <select wire:model.live="landProjectIdFilter" class="select-clean text-xs font-bold">
                    <option value="">Semua Proyek Properti</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>

                @if($landSearch || $landProjectIdFilter || $landDatePeriod !== 'all')
                    <button wire:click="$set('landSearch', ''); $set('landProjectIdFilter', ''); $set('landDatePeriod', 'all'); $set('landStartDate', ''); $set('landEndDate', '');" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset Filter</span>
                    </button>
                @endif
            </div>

            @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                <x-button variant="emerald" size="sm" wire:click="openLandPaymentModal" icon="plus">
                    Catat Bayar Lahan
                </x-button>
            @endif
        </div>
    </div>

    <!-- Table of Land Payments -->
    <x-table :headers="['Tanggal', 'Proyek Properti', 'Jumlah Pembayaran', 'Metode Bayar', 'Keterangan / Catatan', 'Resi & Dokumen', 'Dicatat Oleh', ['label' => 'Aksi', 'class' => 'p-3.5 text-center']]" loadingTarget="landSearch, landProjectIdFilter, landDatePeriod, landStartDate, landEndDate, land_page">
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
                <td class="p-3.5 text-xs text-slate-600 max-w-xs cursor-pointer hover:text-teal-700 transition" wire:click="showLandPaymentDetail({{ $pay->id }})" title="Klik untuk melihat detail catatan">
                    <p class="line-clamp-2">{{ $pay->notes ?: '-' }}</p>
                </td>
                <td class="p-3.5 whitespace-nowrap">
                    <div class="inline-flex items-center gap-1.5 whitespace-nowrap">
                        @if($pay->receipt_photo_url)
                            <x-button variant="outline" size="xs" wire:click="openViewerModal('image', '{{ $pay->receipt_photo_url }}', 'Foto Bukti Pembayaran Lahan - {{ $pay->project->name ?? '' }}')" class="bg-amber-50 hover:bg-amber-100 text-amber-900 border-amber-200 shadow-2xs font-bold" title="Lihat Foto Resi / Bukti Transfer">
                                <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Struk</span>
                            </x-button>
                        @endif

                        @if($pay->uuid)
                            <x-button variant="pdf" size="xs" wire:click="openViewerModal('pdf', '{{ route('land-payment.receipt', $pay->uuid) }}', 'Kuitansi Pembayaran Lahan - {{ $pay->project->name ?? '' }}')" title="Pratinjau Kuitansi PDF" icon="pdf">
                                <span>PDF</span>
                            </x-button>
                            <x-button variant="qr" size="xs" wire:click="openViewerModal('qr', '{{ route('verify.land-payment', $pay->uuid) }}', 'Verifikasi Keabsahan Kuitansi Lahan - {{ $pay->project->name ?? '' }}')" title="Scan QR Verifikasi Publik" icon="qr">
                                <span>QR</span>
                            </x-button>
                        @endif
                    </div>
                </td>
                <td class="p-3.5 text-xs text-slate-600">
                    <span class="font-medium">{{ $pay->creator->name ?? 'Sistem' }}</span>
                </td>
                <td class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <x-button variant="detail" size="xs" wire:click="showLandPaymentDetail({{ $pay->id }})" title="Lihat Rincian Lengkap">
                            <span>Detail</span>
                        </x-button>

                        @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                            <x-action-dropdown title="Menu Opsi Pembayaran Lahan" size="xs">
                                <div class="py-1">
                                    <x-dropdown-item icon="edit" wire:click="openLandPaymentModal({{ $pay->id }})">
                                        Edit Data
                                    </x-dropdown-item>
                                </div>
                                <div class="py-1">
                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Pembayaran Lahan',
                                        message: 'Yakin ingin menghapus catatan pembayaran lahan sebesar Rp {{ number_format($pay->amount_paid, 0, ',', '.') }} untuk Proyek {{ $pay->project->name ?? '' }}?',
                                        confirmText: 'Hapus Pembayaran',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteLandPayment({{ $pay->id }})
                                    })">
                                        Hapus Pembayaran
                                    </x-dropdown-item>
                                </div>
                            </x-action-dropdown>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="font-bold text-slate-600">Belum Ada Riwayat Pembayaran Lahan</p>
                    <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Catat Bayar Lahan" untuk mencatat termin pembayaran pembelian tanah ke penjual.</p>
                </td>
            </tr>
        @endforelse

        @if($landPayments->count() > 0)
            <tr class="bg-slate-100/90 font-bold border-t-2 border-slate-200">
                <td colspan="2" class="p-3.5 text-xs text-slate-700 uppercase tracking-wider font-extrabold text-right">
                    Total Akumulasi Terbayar (Sesuai Filter):
                </td>
                <td class="p-3.5 font-mono font-extrabold text-rose-700 text-sm">
                    Rp {{ number_format($filteredLandPaymentsTotal, 0, ',', '.') }}
                </td>
                <td colspan="5" class="p-3.5 text-xs text-slate-500 font-semibold">
                    {{ $totalLandTransactions }} transaksi pembayaran tercatat
                </td>
            </tr>
        @endif
    </x-table>
    <div>{{ $landPayments->links() }}</div>

</div>
