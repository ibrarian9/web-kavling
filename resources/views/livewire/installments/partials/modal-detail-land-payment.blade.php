<!-- Modal Detail Lengkap Pembayaran Lahan Proyek -->
@if($showLandPaymentDetailModal && $selectedLandPayment)
    <x-modal-dialog show="showLandPaymentDetailModal" 
                    closeAction="closeLandPaymentDetailModal"
                    title="Detail Pembayaran Lahan Proyek" 
                    subTitle="Kuitansi No: #LPAY-{{ $selectedLandPayment->id }} ({{ $selectedLandPayment->project->name ?? 'Proyek' }})" 
                    maxWidth="max-w-xl">
        <div class="space-y-4 text-xs sm:text-sm">
            <!-- Project Header Card -->
            <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2 shadow-2xs">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kawasan Proyek:</span>
                    <h4 class="font-extrabold text-slate-900 text-sm sm:text-base">
                        {{ $selectedLandPayment->project->name ?? '-' }}
                    </h4>
                    <p class="text-[11px] text-slate-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $selectedLandPayment->project->location ?? 'Lokasi belum ditentukan' }}</span>
                    </p>
                </div>
                @if($selectedLandPayment->uuid)
                    <div class="shrink-0 flex items-center gap-1.5">
                        <x-button variant="pdf" size="xs" wire:click="openViewerModal('pdf', '{{ route('land-payment.receipt', $selectedLandPayment->uuid) }}', 'Kuitansi Pembayaran Lahan - {{ $selectedLandPayment->project->name ?? '' }}')" title="Pratinjau Kuitansi PDF" icon="pdf">
                            <span>Kuitansi PDF</span>
                        </x-button>
                        <x-button variant="qr" size="xs" wire:click="openViewerModal('qr', '{{ route('verify.land-payment', $selectedLandPayment->uuid) }}', 'Verifikasi Keabsahan Kuitansi Lahan - {{ $selectedLandPayment->project->name ?? '' }}')" title="Scan QR Verifikasi Publik" icon="qr">
                            <span>QR</span>
                        </x-button>
                    </div>
                @endif
            </div>

            <!-- Financial Highlight Box -->
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-950 flex items-center justify-between shadow-2xs">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block opacity-75">Nominal Pembayaran Termin Lahan</span>
                    <strong class="text-xl sm:text-2xl font-mono font-extrabold text-rose-700">
                        Rp {{ number_format($selectedLandPayment->amount_paid, 0, ',', '.') }}
                    </strong>
                </div>
                <div class="text-right space-y-1">
                    <span class="px-2.5 py-1 rounded-full font-bold text-[10px] bg-rose-100 text-rose-800 border border-rose-200 block text-center">
                        KAS KELUAR
                    </span>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-white text-slate-700 border border-rose-200 block text-center">
                        {{ $selectedLandPayment->payment_method }}
                    </span>
                </div>
            </div>

            <!-- Project Land Summary Info -->
            @if($selectedLandPayment->project)
                @php
                    $proj = $selectedLandPayment->project;
                    $targetCost = (float)$proj->total_project_price;
                    $totalPaid = (float)$proj->payments->sum('amount_paid');
                    $remaining = $targetCost > 0 ? max(0, $targetCost - $totalPaid) : 0;
                    $pct = $targetCost > 0 ? min(100, round(($totalPaid / $targetCost) * 100, 1)) : 0;
                @endphp
                <div class="p-3.5 bg-slate-50/70 border border-slate-200 rounded-2xl space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Status Pelunasan Lahan Proyek Ini:</span>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Total Nilai Lahan</span>
                            <span class="font-bold font-mono text-slate-900">Rp {{ number_format($targetCost, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Total Terbayar</span>
                            <span class="font-bold font-mono text-emerald-700">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Sisa Hutang Lahan</span>
                            <span class="font-bold font-mono text-amber-700">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endif

            <!-- Notes / Catatan Detail Box (Full Un-truncated) -->
            <div class="space-y-1.5">
                <label class="block font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Keterangan / Catatan Pembayaran Lengkap</span>
                </label>
                <div class="p-3.5 bg-white border border-slate-200 rounded-2xl text-slate-800 leading-relaxed font-medium whitespace-pre-line shadow-2xs">
                    {{ $selectedLandPayment->notes ?: 'Tidak ada catatan khusus yang dilampirkan.' }}
                </div>
            </div>

            <!-- Receipt Photo Preview (if available) -->
            @if($selectedLandPayment->receipt_photo_url)
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Foto Resi / Bukti Transfer Pembayaran</span>
                    </label>
                    <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col sm:flex-row items-center gap-3">
                        <img src="{{ $selectedLandPayment->receipt_photo_url }}" alt="Struk Pembayaran" class="max-h-32 object-contain rounded-xl border border-slate-200 bg-white p-1">
                        <div class="space-y-1 text-center sm:text-left">
                            <span class="text-xs font-bold text-slate-800 block">Struk Pembayaran Terlampir</span>
                            <span class="text-[10px] text-slate-400 block">Klik tombol di bawah untuk melihat ukuran penuh</span>
                            <x-button variant="outline" size="xs" wire:click="openViewerModal('image', '{{ $selectedLandPayment->receipt_photo_url }}', 'Foto Bukti Pembayaran Lahan - {{ $selectedLandPayment->project->name ?? '' }}')" class="bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100">
                                <span>Buka Foto Penuh</span>
                            </x-button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Audit & Metadata -->
            <div class="pt-2 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-[11px] text-slate-500 font-medium">
                <div>
                    <span>Dicatat Oleh: <strong class="text-slate-700">{{ $selectedLandPayment->creator->name ?? 'Sistem' }}</strong></span>
                </div>
                <div>
                    <span>Tanggal Entri: <strong class="text-slate-700 font-mono">{{ format_id_datetime($selectedLandPayment->created_at) }}</strong></span>
                </div>
            </div>

            <!-- Footer Close Button -->
            <div class="flex items-center justify-end pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="outline" size="sm" wire:click="closeLandPaymentDetailModal">
                    Tutup Detail
                </x-button>
            </div>
        </div>
    </x-modal-dialog>
@endif
