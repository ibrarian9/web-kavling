<!-- TAB 1: TAGIHAN MATERIAL TOKO -->
@if($activeTab === 'material_bills')
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <span>Daftar Tagihan Belanja Material & Operasional</span>
                <span class="text-xs text-slate-500 font-normal">({{ $materialBills->total() }} Item)</span>
            </h3>
            <div class="text-xs font-mono font-bold text-rose-700 bg-rose-50 border border-rose-200 px-3 py-1 rounded-xl">
                Subtotal Hutang Toko: Rp {{ number_format($totalUnpaidMaterialBills, 0, ',', '.') }}
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs text-slate-600 min-w-[750px]">
                <thead class="bg-slate-50 font-bold text-slate-700 uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="p-3">Tanggal Beli</th>
                        <th class="p-3">Proyek & Unit</th>
                        <th class="p-3">Toko / Supplier</th>
                        <th class="p-3">Barang / Uraian</th>
                        <th class="p-3">Total Nominal</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($materialBills as $m)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3 font-mono font-medium">{{ \Carbon\Carbon::parse($m->purchase_date)->format('d/m/Y') }}</td>
                            <td class="p-3">
                                <span class="font-bold text-slate-800 block">{{ $m->project->name ?? 'Operasional Umum' }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">Unit: {{ $m->unit ? $m->unit->code : '-' }}</span>
                            </td>
                            <td class="p-3 font-semibold text-slate-700">{{ $m->store_name ?: '-' }}</td>
                            <td class="p-3 font-bold text-slate-900">
                                {{ $m->item_name }}
                                <span class="block text-[10px] font-normal text-slate-500 font-mono">{{ $m->quantity }} {{ $m->unit_measure }} @ Rp {{ number_format($m->unit_price, 0, ',', '.') }}</span>
                            </td>
                            <td class="p-3 font-mono font-bold text-rose-700 text-sm">Rp {{ number_format($m->total_price, 0, ',', '.') }}</td>
                            <td class="p-3">
                                @if($m->payment_status === 'lunas')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1 whitespace-nowrap shrink-0">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        LUNAS
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1 whitespace-nowrap shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                                        HUTANG TOKO
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($m->payment_status !== 'lunas')
                                        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                            <button type="button" wire:click="openSettleModal({{ $m->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-xs whitespace-nowrap">
                                                Bayar Lunas
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-[11px] text-emerald-600 font-semibold whitespace-nowrap">Terbayar</span>
                                    @endif

                                    @if(auth()->user()->isFounder())
                                        <button type="button" @click="confirmModalAction({ title: 'Hapus Tagihan Material', message: 'Apakah Anda yakin ingin menghapus catatan tagihan material toko ini secara permanen?', confirmText: 'Ya, Hapus Tagihan', onConfirm: () => $wire.deleteMaterialPurchase({{ $m->id }}) })" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus Tagihan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada data tagihan toko material.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $materialBills->links() }}</div>
    </div>
@endif
