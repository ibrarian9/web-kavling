<!-- Modal Detail Rincian Skema Cicilan & Riwayat Setoran -->
@if(!empty($showDetailModal) && !empty($selectedDetailInstallment))
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg sm:max-w-xl w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="p-2 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base truncate">Detail Skema Cicilan Unit {{ $selectedDetailInstallment->unit->code }}</h3>
                        <p class="text-[11px] text-slate-500 truncate">Proyek: <strong class="text-slate-800">{{ $selectedDetailInstallment->unit->project->name ?? '-' }}</strong> | Pembeli: <strong class="text-slate-800">{{ $selectedDetailInstallment->buyer_name }}</strong></p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                        <button type="button" wire:click="openSetupModal({{ $selectedDetailInstallment->id }})" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/90 rounded-lg text-[11px] font-bold transition flex items-center gap-1 shadow-2xs" title="Edit Nilai Deal, DP, & Tenor Skema">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit Skema</span>
                        </button>
                    @endif
                    <button wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600 text-sm font-bold p-1">✕</button>
                </div>
            </div>

            <!-- Scrollable Body Content -->
            <div class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <!-- Status & Progress Bar -->
                @php
                    $pctPaid = $selectedDetailInstallment->total_price > 0 ? min(100, round(($selectedDetailInstallment->total_paid / $selectedDetailInstallment->total_price) * 100, 1)) : 0;
                @endphp
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Progress Pelunasan Piutang</span>
                        <div>
                            @if($selectedDetailInstallment->status === 'lunas')
                                <span class="status-disetujui">LUNAS</span>
                            @elseif($selectedDetailInstallment->status === 'konversi_cash')
                                <span class="bg-purple-100 text-purple-900 border border-purple-300 font-extrabold px-2.5 py-0.5 rounded-full text-[10px]">LUNAS CASH</span>
                            @elseif($selectedDetailInstallment->status === 'menunggak')
                                <span class="status-ditolak">MENUNGGAK</span>
                            @else
                                <span class="status-menunggu">BERJALAN</span>
                            @endif
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $pctPaid }}%"></div>
                    </div>
                    <div class="flex justify-between text-[11px] font-mono text-slate-600 pt-0.5">
                        <span>Terbayar: {{ $pctPaid }}%</span>
                        <span>Sisa: Rp {{ number_format($selectedDetailInstallment->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Breakdown Financial Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px] sm:text-xs">
                    <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl space-y-0.5">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Total Deal:</span>
                        <strong class="font-mono text-slate-800 font-extrabold text-xs sm:text-sm">Rp {{ number_format($selectedDetailInstallment->total_price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="p-3 bg-emerald-50/70 border border-emerald-200/70 rounded-xl space-y-0.5">
                        <span class="text-emerald-700 block text-[10px] uppercase font-bold">Uang Muka (DP):</span>
                        <strong class="font-mono text-emerald-800 font-bold text-xs sm:text-sm">Rp {{ number_format($selectedDetailInstallment->down_payment, 0, ',', '.') }}</strong>
                    </div>
                    <div class="p-3 bg-purple-50/70 border border-purple-200/70 rounded-xl space-y-0.5">
                        <span class="text-purple-700 block text-[10px] uppercase font-bold">Termin Cicilan:</span>
                        <strong class="font-mono text-purple-800 font-bold text-xs sm:text-sm">{{ $selectedDetailInstallment->installment_count }}x Bulan</strong>
                    </div>
                    <div class="p-3 bg-rose-50/70 border border-rose-200/70 rounded-xl space-y-0.5">
                        <span class="text-rose-700 block text-[10px] uppercase font-bold">Sisa Piutang:</span>
                        <strong class="font-mono text-rose-800 font-extrabold text-xs sm:text-sm">Rp {{ number_format($selectedDetailInstallment->remaining_balance, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <!-- Riwayat Setoran Pembayaran -->
                <div class="space-y-2">
                    <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] border-b border-slate-100 pb-1.5 flex items-center justify-between">
                        <span>Riwayat Setoran Pembayaran Pembeli</span>
                        <span class="text-[10px] text-slate-400 font-normal">Total {{ count($selectedDetailInstallment->payments) }} Trx</span>
                    </h4>

                    @forelse($selectedDetailInstallment->payments as $pay)
                        <div class="p-3 bg-white border border-slate-200/80 rounded-xl flex items-center justify-between gap-3 text-xs shadow-2xs hover:bg-slate-50/50 transition">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono font-extrabold text-emerald-700 text-sm">Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</span>
                                    <span class="bg-slate-100 text-slate-700 border border-slate-200 text-[10px] px-2 py-0.5 rounded-md font-semibold">{{ $pay->payment_method }}</span>
                                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 inline-block">TERBAYAR</span>
                                </div>
                                <p class="text-[11px] text-slate-500">{{ $pay->notes ?: 'Setoran cicilan berkala' }}</p>
                                <p class="text-[10px] text-slate-400 font-mono">Pencatat: {{ $pay->creator->name ?? 'Finance' }}</p>
                            </div>
                            <div class="text-right shrink-0 space-y-1">
                                <span class="font-mono text-slate-600 font-semibold block text-[11px]">{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->locale('id')->isoFormat('D MMM YYYY') : '-' }}</span>
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    @if($pay->uuid)
                                        <button type="button" wire:click="openViewerModal('pdf', '{{ route('installment.invoice', $pay->uuid) }}', 'Invoice Setoran Unit {{ $selectedDetailInstallment->unit->code }}')" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/90 rounded-lg text-[10px] font-bold transition flex items-center gap-1 shadow-2xs group" title="Buka / Cetak Invoice Resmi PDF Setoran">
                                            <svg class="w-3.5 h-3.5 text-blue-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <span>Invoice PDF</span>
                                        </button>
                                    @endif

                                    @if($pay->receipt_photo_path)
                                        <button type="button" wire:click="openViewerModal('image', '{{ asset('storage/' . $pay->receipt_photo_path) }}', 'Foto Resi Transfer Bank - Setoran Unit {{ $selectedDetailInstallment->unit->code }}')" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/90 rounded-lg text-[10px] font-bold transition flex items-center gap-1 shadow-2xs group" title="Lihat Foto Struk / Resi Bukti Transfer">
                                            <svg class="w-3.5 h-3.5 text-amber-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Struk TF</span>
                                        </button>
                                    @endif

                                    @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                                         <button type="button" wire:click="editInstallmentPayment({{ $pay->id }})" class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/90 rounded-lg text-[10px] font-bold transition flex items-center gap-1 shadow-2xs" title="Edit Setoran Ini">
                                             <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                             <span>Edit</span>
                                         </button>
                                         <button type="button" @click="confirmModalAction({
                                             title: 'Hapus Setoran Cicilan',
                                             message: 'Hapus pencatatan setoran cicilan ini?',
                                             confirmText: 'Hapus Setoran',
                                             btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                             onConfirm: () => $wire.deleteInstallmentPayment({{ $pay->id }})
                                         })" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/90 rounded-lg text-[10px] font-bold transition flex items-center gap-1 shadow-2xs" title="Hapus Setoran">
                                             <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                             <span>Hapus</span>
                                         </button>
                                     @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 bg-slate-50 rounded-xl text-center text-slate-400 text-xs italic">
                            Belum ada riwayat setoran cicilan yang tercatat.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer -->
            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end pt-3 border-t border-slate-100 shrink-0">
                <x-button variant="secondary" size="md" type="button" wire:click="closeDetailModal">Tutup Detail</x-button>
            </div>
        </div>
    </div>
@endif
