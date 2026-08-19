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
                    <td class="p-3 font-mono font-bold text-slate-700 whitespace-nowrap">{{ format_id_date($m->purchase_date) }}</td>
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
                        <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                            @if($m->payment_status !== 'lunas')
                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                    <x-button variant="payment" size="xs" wire:click="openSettleModal({{ $m->id }})">
                                        <span>Bayar Lunas</span>
                                    </x-button>
                                @endif
                            @else
                                <span class="text-[11px] text-emerald-600 font-semibold whitespace-nowrap">Terbayar</span>
                            @endif

                            @if(auth()->user()->isSuperAdmin())
                                <x-button variant="delete" size="xs" @click="confirmModalAction({ title: 'Hapus Tagihan Material', message: 'Apakah Anda yakin ingin menghapus catatan tagihan material toko ini secara permanen?', confirmText: 'Ya, Hapus Tagihan', btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5', onConfirm: () => $wire.deleteMaterialPurchase({{ $m->id }}) })" title="Hapus Tagihan">
                                    <span>Hapus</span>
                                </x-button>
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
