<!-- Section Komisi Penjual Unit & Skema Cicilan Komisi Perusahaan -->
@php
    $commissionsList = $unitCommissions ?? collect();
@endphp

<x-card padding="p-6">
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
            <x-button variant="purple" size="sm" wire:click="openCommissionModal" icon="plus">
                <span>Catat Komisi Penjual</span>
            </x-button>
        @endif
    </div>

    @if($commissionsList->isEmpty())
        <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200 mt-4">
            <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="text-xs font-semibold text-slate-500">Belum ada catatan hutang komisi penjual untuk unit ini.</p>
            <p class="text-[11px] text-slate-400">Klik tombol "Catat Komisi Penjual" di atas untuk menambahkan persenan / fee marketing.</p>
        </div>
    @else
        <div class="space-y-4 mt-4">
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
                                
                                <x-status-badge :status="$comm->status" />
                            </div>
                            @if($comm->notes)
                                <p class="text-xs text-slate-500 italic mt-0.5">{{ $comm->notes }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 whitespace-nowrap flex-nowrap">
                            @if(!$isLunas && (auth()->user()->isFounder() || auth()->user()->isFinance()))
                                <x-button variant="payment" size="xs" wire:click="openCommissionPaymentModal({{ $comm->id }})" icon="plus">
                                    <span>Bayar Cicilan</span>
                                </x-button>
                            @endif

                            @if(auth()->user()->isFounder())
                                <x-action-dropdown title="Menu Opsi Komisi" size="xs">
                                    <div class="py-1">
                                        <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                            title: 'Hapus Catatan Komisi',
                                            message: 'Yakin ingin menghapus catatan komisi {{ $comm->seller_name }}?',
                                            confirmText: 'Hapus Komisi',
                                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                            onConfirm: () => $wire.deleteCommission({{ $comm->id }})
                                        })">
                                            Hapus Komisi
                                        </x-dropdown-item>
                                    </div>
                                </x-action-dropdown>
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
                                            <span class="font-semibold text-slate-700">{{ format_id_date($p->payment_date) }}</span>
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
</x-card>
