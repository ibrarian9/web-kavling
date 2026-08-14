<!-- Section Komisi Penjual Unit & Skema Cicilan Komisi Perusahaan -->
@php
    $commissionsList = $unitCommissions ?? collect();
@endphp

<div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 space-y-4">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-800 text-base">Komisi Penjual Unit & Skema Cicilan</h3>
                <p class="text-xs text-slate-500">Hutang komisi perusahaan ke agen / marketing yang dapat dicicil bertahap</p>
            </div>
        </div>

        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
            <button type="button" wire:click="openCommissionModal" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Catat Komisi Penjual</span>
            </button>
        @endif
    </div>

    @if($commissionsList->isEmpty())
        <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
            <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="text-xs font-semibold text-slate-500">Belum ada catatan hutang komisi penjual untuk unit ini.</p>
            <p class="text-[11px] text-slate-400">Klik tombol "Catat Komisi Penjual" di atas untuk menambahkan persenan / fee marketing.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($commissionsList as $comm)
                @php
                    $isLunas = $comm->status === 'lunas';
                    $isBerjalan = $comm->status === 'berjalan';
                    $paid = (float)($comm->paid_amount ?? 0);
                    $total = (float)$comm->commission_amount;
                    $remaining = max(0, $total - $paid);
                    $percentPaid = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;
                @endphp

                <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50 space-y-3">
                    <div class="flex items-start justify-between flex-wrap gap-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-slate-800 text-sm">{{ $comm->seller_name }}</span>
                                @if($comm->seller_phone)
                                    <span class="text-xs text-slate-500 font-mono">({{ $comm->seller_phone }})</span>
                                @endif
                                
                                @if($isLunas)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">LUNAS 100%</span>
                                @elseif($isBerjalan)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">DICICIL ({{ $percentPaid }}%)</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">BELUM DIBAYAR</span>
                                @endif
                            </div>
                            @if($comm->notes)
                                <p class="text-xs text-slate-500 italic mt-0.5">{{ $comm->notes }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            @if(!$isLunas && (auth()->user()->isFounder() || auth()->user()->isFinance()))
                                <button type="button" wire:click="openCommissionPaymentModal({{ $comm->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span>Bayar Cicilan</span>
                                </button>
                            @endif

                            @if(auth()->user()->isFounder())
                                <button type="button" wire:confirm="Hapus catatan komisi ini?" wire:click="deleteCommission({{ $comm->id }})" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus Komisi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Progress Bar Cicilan Komisi -->
                    <div class="grid grid-cols-3 gap-2 bg-white p-3 rounded-xl border border-slate-200 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[10px] font-bold uppercase">Total Komisi ({{ (float)$comm->percentage }}%)</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] font-bold uppercase">Terbayar (Cicilan)</span>
                            <span class="font-bold text-emerald-600">Rp {{ number_format($paid, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] font-bold uppercase">Sisa Terutang</span>
                            <span class="font-bold text-red-600">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Visual Progress Bar -->
                    <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-purple-600 h-full rounded-full transition-all duration-500" style="width: {{ $percentPaid }}%"></div>
                    </div>

                    <!-- Riwayat Pembayaran Cicilan Komisi -->
                    @if($comm->payments && $comm->payments->isNotEmpty())
                        <div class="pt-2 border-t border-slate-200/80">
                            <span class="text-[11px] font-extrabold text-slate-700 block mb-1.5 uppercase tracking-wider">Riwayat Cicilan Terbayar ({{ $comm->payments->count() }} Setoran):</span>
                            <div class="space-y-1.5">
                                @foreach($comm->payments as $p)
                                    <div class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-slate-100 text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-slate-700">{{ $p->payment_date ? $p->payment_date->format('d/m/Y') : '-' }}</span>
                                            <span class="text-slate-400">•</span>
                                            <span class="text-slate-600">{{ $p->payment_method }}</span>
                                            @if($p->notes)
                                                <span class="text-slate-400 italic">({{ $p->notes }})</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 font-mono font-bold text-emerald-700">
                                            <span>+ Rp {{ number_format($p->amount, 0, ',', '.') }}</span>
                                            @if($p->receipt_photo_path)
                                                <button type="button" wire:click="openViewerModal('image', '{{ asset('storage/' . $p->receipt_photo_path) }}', 'Bukti Resi Transfer Komisi')" class="text-purple-600 hover:text-purple-800 text-[11px] underline">Resi</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
