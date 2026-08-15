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

        <x-table :headers="['Tanggal Beli', 'Proyek & Unit', 'Toko / Supplier', 'Barang / Uraian', 'Total Nominal', 'Status', ['label' => 'Aksi', 'class' => 'p-3 text-center']]" loadingTarget="setTab, filter_project_id, filter_status, search, page">
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
                        <x-status-badge :status="$m->payment_status" />
                    </td>
                    <td class="p-3 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            @if($m->payment_status !== 'lunas')
                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                    <x-button variant="emerald" size="xs" wire:click="openSettleModal({{ $m->id }})">
                                        Bayar Lunas
                                    </x-button>
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
        </x-table>
        <div>{{ $materialBills->links() }}</div>
    </div>
@endif
