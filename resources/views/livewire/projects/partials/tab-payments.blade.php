<!-- TAB 2: Skema & Riwayat Pembayaran Lahan Proyek -->
<div class="space-y-4">
    <div class="card-clean p-4 flex flex-col sm:flex-row items-center justify-between gap-3 bg-purple-950/5 border-purple-200/80">
        <div>
            <h3 class="font-bold text-purple-950 text-sm">Riwayat Pembayaran Lahan Proyek ke Penjual</h3>
            <p class="text-xs text-purple-700">Daftar setoran termin / pembayaran yang telah diserahkan ke pemilik/penjual tanah proyek {{ $project->name }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(count($projectPaymentsList) > 0)
                <a href="{{ route('projects.land-payments-pdf', $project->id) }}" target="_blank" class="btn-header-pdf">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Lihat PDF Rekap</span>
                </a>
            @else
                <button disabled class="btn-header-pdf-disabled" title="Belum ada data pembayaran lahan untuk digenerate PDF">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>PDF Rekap (Belum Ada Data)</span>
                </button>
            @endif

            @if(auth()->user()->isFounder())
                <button wire:click="openPaymentModal" class="btn-primary text-xs px-4 py-2 flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Bayar Lahan ke Penjual</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Table of Project Payments -->
    <div class="card-clean overflow-hidden">

        <!-- Mobile Card Layout -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($projectPaymentsList as $index => $pay)
                <div class="p-4 space-y-3">
                    <!-- Header: # + Date + Amount -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 text-[10px] font-bold flex items-center justify-center">{{ $index + 1 }}</span>
                            <span class="font-mono font-bold text-slate-800 text-xs">{{ $pay->payment_date ? $pay->payment_date->format('d M Y') : '-' }}</span>
                        </div>
                        <span class="font-mono font-extrabold text-rose-700 text-sm">- Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</span>
                    </div>

                    <!-- Payment details -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-[10px] font-semibold text-slate-700">{{ $pay->payment_method }}</span>
                        <span class="text-slate-400">•</span>
                        <span class="text-slate-600 font-medium">Dicatat oleh: {{ $pay->creator->name ?? 'System' }}</span>
                    </div>

                    <!-- Keterangan / Catatan -->
                    <div class="text-[11px] bg-slate-50 p-2 rounded-lg border border-slate-100">
                        <span class="font-bold text-slate-700 block text-[10px] uppercase tracking-wider text-purple-900">Keterangan:</span>
                        <p class="text-slate-600 leading-relaxed mt-0.5">{{ $pay->notes ?: '-' }}</p>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex flex-wrap items-center gap-1.5">
                        @if($pay->cashflowTransaction)
                            <button wire:click="openDetailModal({{ $pay->cashflowTransaction->id }})" class="btn-action-detail" title="Detail Audit Trail & Log Transaksi">
                                <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Detail</span>
                            </button>
                        @endif
                        @if($pay->receipt_photo_url)
                            <a href="{{ $pay->receipt_photo_url }}" target="_blank" class="btn-action-pdf">
                                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Foto Resi</span>
                            </a>
                        @endif
                        @if($pay->uuid)
                            <a href="{{ route('land-payment.receipt', $pay->uuid) }}" target="_blank" class="btn-action-pdf">
                                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Kuitansi PDF</span>
                            </a>
                            <a href="{{ route('verify.land-payment', $pay->uuid) }}" target="_blank" class="btn-action-qr">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>Scan QR</span>
                            </a>
                        @endif
                        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                            <button wire:click="editProjectPayment({{ $pay->id }})" class="btn-action-edit" title="Edit">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Edit</span>
                            </button>
                            <button onclick="confirm('Hapus pencatatan pembayaran lahan ini?') || event.stopImmediatePropagation()" wire:click="deleteProjectPayment({{ $pay->id }})" class="btn-action-delete" title="Hapus">
                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p class="font-semibold text-slate-600">Belum ada riwayat pembayaran lahan proyek ke penjual</p>
                    <p class="text-xs text-slate-400 mt-1">Klik tombol "Catat Bayar Lahan ke Penjual" untuk memasukkan setoran pelunasan tanah.</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table Layout -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-purple-50 text-purple-900 uppercase text-[10px] font-bold tracking-wider border-b border-purple-100">
                    <tr>
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">Tanggal Pembayaran</th>
                        <th class="px-4 py-3.5">Metode Pembayaran</th>
                        <th class="px-4 py-3.5">Bukti Resi Transfer</th>
                        <th class="px-4 py-3.5">Kuitansi PDF & QR</th>
                        <th class="px-4 py-3.5">Catatan / Keterangan</th>
                        <th class="px-4 py-3.5">Dicatat Oleh</th>
                        <th class="px-4 py-3.5 text-right">Jumlah Dibayar (Rp)</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projectPaymentsList as $index => $pay)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3.5 font-mono text-slate-500 font-semibold">{{ $index + 1 }}</td>
                            <td class="px-4 py-3.5 font-mono font-bold text-slate-800">
                                {{ $pay->payment_date ? $pay->payment_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-slate-700">
                                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-[10px]">
                                    {{ $pay->payment_method }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($pay->receipt_photo_url)
                                    <a href="{{ $pay->receipt_photo_url }}" target="_blank" class="btn-action-pdf">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Foto Resi</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                @if($pay->uuid)
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('land-payment.receipt', $pay->uuid) }}" target="_blank" class="btn-action-pdf">
                                            <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Kuitansi PDF</span>
                                        </a>

                                        <a href="{{ route('verify.land-payment', $pay->uuid) }}" target="_blank" class="btn-action-qr" title="Verifikasi QR Keabsahan">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                            <span>Scan QR</span>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-600 max-w-xs leading-relaxed break-words">
                                {{ $pay->notes ?: '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-slate-600 font-medium">
                                {{ $pay->creator->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono font-extrabold text-rose-700 text-sm">
                                - Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    @if($pay->cashflowTransaction)
                                        <button wire:click="openDetailModal({{ $pay->cashflowTransaction->id }})" class="btn-action-detail" title="Detail Audit Trail Transaksi">
                                            <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Detail</span>
                                        </button>
                                    @endif

                                    @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                        <button wire:click="editProjectPayment({{ $pay->id }})" class="btn-action-edit" title="Edit Pembayaran Lahan">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button onclick="confirm('Hapus pencatatan pembayaran lahan ini?') || event.stopImmediatePropagation()" wire:click="deleteProjectPayment({{ $pay->id }})" class="btn-action-delete" title="Hapus Pembayaran">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="font-semibold text-slate-600">Belum ada riwayat pembayaran lahan proyek ke penjual</p>
                                <p class="text-xs text-slate-400 mt-1">Klik tombol "Catat Bayar Lahan ke Penjual" untuk memasukkan setoran pelunasan tanah.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
