<!-- TAB 2: Skema & Riwayat Pembayaran Lahan Proyek -->
<div class="space-y-4">
    <x-card padding="p-4" class="bg-purple-950/5 border-purple-200/80">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div>
                <h3 class="font-bold text-purple-950 text-sm">Riwayat Pembayaran Lahan Proyek ke Penjual</h3>
                <p class="text-xs text-purple-700">Daftar setoran termin / pembayaran yang telah diserahkan ke pemilik/penjual tanah proyek {{ $project->name }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if(count($projectPaymentsList) > 0)
                    <x-button variant="pdf" size="sm" wire:click="openViewerModal('pdf', '{{ route('projects.land-payments-pdf', $project->id) }}', 'Rekapitulasi Pembayaran Lahan - {{ $project->name }}')" icon="pdf">
                        <span>Lihat PDF Rekap</span>
                    </x-button>
                @else
                    <x-button variant="pdf" size="sm" disabled icon="pdf" title="Belum ada data pembayaran lahan untuk digenerate PDF" class="opacity-50 cursor-not-allowed">
                        <span>PDF Rekap (Kosong)</span>
                    </x-button>
                @endif

                @if(auth()->user()->isAdminOrFounder())
                    <x-button variant="primary" size="sm" wire:click="openPaymentModal">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Catat Bayar Lahan ke Penjual</span>
                    </x-button>
                @endif
            </div>
        </div>
    </x-card>

    <!-- 1. MOBILE CARDS LAYOUT (Active on screens < 768px with clear boundaries) -->
    <div class="block md:hidden space-y-3.5">
        @forelse($projectPaymentsList as $index => $pay)
            <div class="bg-white border-2 border-slate-200/90 hover:border-purple-300 rounded-2xl p-4 space-y-3.5 shadow-xs transition-all">
                <!-- Header: # + Date + Amount -->
                <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 text-[10px] font-extrabold flex items-center justify-center">{{ $index + 1 }}</span>
                        <div class="flex items-center gap-1.5 text-xs text-slate-700 font-semibold font-mono">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ format_id_date($pay->payment_date) }}</span>
                        </div>
                    </div>
                    <span class="font-mono font-black text-rose-700 text-sm whitespace-nowrap">- Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</span>
                </div>

                <!-- Payment details -->
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="px-2.5 py-0.5 rounded-full bg-purple-50 border border-purple-200 text-[10px] font-bold text-purple-700">{{ $pay->payment_method }}</span>
                    <span class="text-slate-400">•</span>
                    <span class="text-slate-600 text-[11px] font-medium">Dicatat oleh: <strong class="text-slate-800">{{ $pay->creator->name ?? 'System' }}</strong></span>
                </div>

                <!-- Keterangan / Catatan -->
                <div class="text-xs bg-slate-50/90 p-3 rounded-xl border border-slate-100">
                    <span class="font-bold block text-[10px] uppercase tracking-wider text-purple-900">Keterangan:</span>
                    <p class="text-slate-700 leading-relaxed mt-0.5">{{ $pay->notes ?: '-' }}</p>
                </div>

                <!-- Action buttons -->
                <div class="flex flex-wrap items-center justify-end gap-1.5 pt-2.5 border-t border-slate-100">
                    @if($pay->cashflowTransaction)
                        <x-button variant="detail" size="xs" wire:click="openDetailModal({{ $pay->cashflowTransaction->id }})" title="Detail Audit Trail & Log Transaksi">
                            <span>Detail</span>
                        </x-button>
                    @endif
                    @if($pay->receipt_photo_url)
                        <x-button variant="amber" size="xs" type="button" wire:click="openViewerModal('image', '{{ $pay->receipt_photo_url }}', 'Foto Bukti Pembayaran Lahan - {{ $project->name }}')">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Resi</span>
                        </x-button>
                    @endif
                    @if($pay->uuid)
                        <x-button variant="pdf" size="xs" type="button" wire:click="openViewerModal('pdf', '{{ route('land-payment.receipt', $pay->uuid) }}', 'Kuitansi Pembayaran Lahan - {{ $project->name }}')" icon="pdf">
                            <span>PDF</span>
                        </x-button>
                        <x-button variant="qr" size="xs" type="button" wire:click="openViewerModal('qr', '{{ route('verify.land-payment', $pay->uuid) }}', 'Verifikasi Keabsahan Kuitansi Lahan - {{ $project->name }}')" icon="qr">
                            <span>QR</span>
                        </x-button>
                    @endif
                    @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                        <x-button variant="edit" size="xs" wire:click="editProjectPayment({{ $pay->id }})" title="Edit">
                            <span>Edit</span>
                        </x-button>
                    @endif
                    @if(auth()->user()->isSuperAdmin())
                        <x-button variant="delete" size="xs" @click="confirmModalAction({
                            title: 'Hapus Pembayaran Lahan',
                            message: 'Hapus pencatatan pembayaran lahan ini?',
                            confirmText: 'Hapus Pembayaran',
                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                            onConfirm: () => $wire.deleteProjectPayment({{ $pay->id }})
                        })" title="Hapus">
                            <span>Hapus</span>
                        </x-button>
                    @endif
                </div>
            </div>
        @empty
            <div class="card-clean p-8 text-center text-slate-400 bg-white border border-slate-200/80 rounded-2xl">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="font-semibold text-slate-600">Belum ada riwayat pembayaran lahan proyek ke penjual</p>
                <p class="text-xs text-slate-400 mt-1">Klik tombol "Catat Bayar Lahan ke Penjual" untuk memasukkan setoran pelunasan tanah.</p>
            </div>
        @endforelse
    </div>

    <!-- 2. DESKTOP TABLE LAYOUT (Active on screens >= 768px) -->
    <div class="hidden md:block">
        <x-card padding="p-0" class="overflow-hidden">
            <div class="overflow-x-auto">
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
                    <tbody class="divide-y divide-purple-50/50">
                        @forelse($projectPaymentsList as $index => $pay)
                            <tr class="hover:bg-purple-50/30 transition-colors">
                                <td class="px-4 py-3.5 text-slate-500 font-mono">{{ $index + 1 }}</td>
                                <td class="px-4 py-3.5 font-mono text-slate-800 font-medium whitespace-nowrap">{{ format_id_date($pay->payment_date) }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-[10px] font-semibold text-slate-700 whitespace-nowrap">{{ $pay->payment_method }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($pay->receipt_photo_url)
                                        <button type="button" wire:click="openViewerModal('image', '{{ $pay->receipt_photo_url }}', 'Foto Bukti Pembayaran Lahan - {{ $project->name }}')" class="group relative inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200/80 text-amber-800 hover:bg-amber-100 text-xs font-semibold shadow-2xs transition">
                                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Lihat Resi</span>
                                        </button>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($pay->uuid)
                                        <div class="flex items-center gap-1.5 whitespace-nowrap">
                                            <x-button variant="pdf" size="xs" type="button" wire:click="openViewerModal('pdf', '{{ route('land-payment.receipt', $pay->uuid) }}', 'Kuitansi Pembayaran Lahan - {{ $project->name }}')" icon="pdf">
                                                <span>PDF</span>
                                            </x-button>
                                            <x-button variant="qr" size="xs" type="button" wire:click="openViewerModal('qr', '{{ route('verify.land-payment', $pay->uuid) }}', 'Verifikasi Keabsahan Kuitansi Lahan - {{ $project->name }}')" icon="qr">
                                                <span>QR</span>
                                            </x-button>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-slate-600 max-w-xs truncate" title="{{ $pay->notes }}">
                                    {{ $pay->notes ?: '-' }}
                                </td>
                                <td class="px-4 py-3.5 text-slate-500 whitespace-nowrap text-[11px]">
                                    {{ $pay->creator->name ?? 'System' }}
                                </td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-rose-700 whitespace-nowrap">
                                    - Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        @if($pay->cashflowTransaction)
                                            <x-button variant="detail" size="xs" wire:click="openDetailModal({{ $pay->cashflowTransaction->id }})" title="Detail Audit Trail & Log Transaksi">
                                                <span>Detail</span>
                                            </x-button>
                                        @endif

                                        @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                                            <x-action-dropdown title="Menu Opsi" size="xs">
                                                <div class="py-1">
                                                    <x-dropdown-item icon="edit" variant="warning" wire:click="editProjectPayment({{ $pay->id }})">
                                                        Edit Pembayaran
                                                    </x-dropdown-item>
                                                </div>

                                                @if(auth()->user()->isSuperAdmin())
                                                    <div class="py-1">
                                                        <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                                            title: 'Hapus Pembayaran Lahan',
                                                            message: 'Yakin ingin menghapus data setoran pembayaran lahan proyek ini?',
                                                            confirmText: 'Hapus Pembayaran',
                                                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                            onConfirm: () => $wire.deleteProjectPayment({{ $pay->id }})
                                                        })">
                                                            Hapus Pembayaran
                                                        </x-dropdown-item>
                                                    </div>
                                                @endif
                                            </x-action-dropdown>
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
        </x-card>
    </div>
</div>
