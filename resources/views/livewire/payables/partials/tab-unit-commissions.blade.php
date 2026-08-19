<!-- TAB 3: HUTANG KOMISI PENJUAL UNIT -->
@if($activeTab === 'unit_commissions')
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <span>Daftar Hutang Komisi / Fee Penjual per Unit</span>
                <span class="text-xs text-slate-500 font-normal">({{ $unitCommissions->total() }} Komisi)</span>
            </h3>
            <div class="text-xs font-mono font-bold text-purple-700 bg-purple-50 border border-purple-200 px-3 py-1 rounded-xl">
                Subtotal Hutang Komisi: Rp {{ number_format($totalUnpaidCommissions, 0, ',', '.') }}
            </div>
        </div>

        <x-table :headers="['Tgl Catat', 'Proyek & Unit', 'Penjual / Marketing', 'Persenan (%)', 'Nominal Komisi', 'Status', ['label' => 'Aksi', 'class' => 'p-3 text-center']]" loadingTarget="setTab, filter_project_id, filter_status, search, com_page">
            @forelse($unitCommissions as $c)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="p-3 font-mono font-bold text-slate-700 whitespace-nowrap">{{ format_id_date($c->created_at) }}</td>
                    <td class="p-3">
                        <span class="font-bold text-slate-800 block">{{ $c->project->name ?? 'Non-Proyek' }}</span>
                        <span class="text-[10px] text-slate-500 font-mono">Unit: {{ $c->unit ? $c->unit->code : '-' }}</span>
                    </td>
                    <td class="p-3 font-bold text-purple-900">
                        {{ $c->seller_name }}
                        @if($c->seller_phone)
                            <span class="block text-[10px] text-slate-500 font-mono font-normal">{{ $c->seller_phone }}</span>
                        @endif
                    </td>
                    <td class="p-3 font-mono font-bold text-slate-700">{{ $c->percentage > 0 ? $c->percentage . '%' : '-' }}</td>
                    <td class="p-3 font-mono font-bold text-purple-700 text-sm">Rp {{ number_format($c->commission_amount, 0, ',', '.') }}</td>
                    <td class="p-3">
                        <x-status-badge :status="$c->status" />
                    </td>
                    <td class="p-3 text-center">
                        <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                            @if($c->status !== 'lunas')
                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                    <x-button variant="purple" size="xs" wire:click="openSettleCommissionModal({{ $c->id }})">
                                        <span>Bayar Komisi</span>
                                    </x-button>
                                @endif
                            @else
                                <span class="text-[11px] text-emerald-600 font-semibold whitespace-nowrap">Lunas</span>
                            @endif

                            @if(auth()->user()->isSuperAdmin())
                                <x-button variant="delete" size="xs" @click="confirmModalAction({ title: 'Hapus Komisi Penjual', message: 'Apakah Anda yakin ingin menghapus catatan komisi penjual ini secara permanen?', confirmText: 'Ya, Hapus Komisi', btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5', onConfirm: () => $wire.deleteCommission({{ $c->id }}) })" title="Hapus Komisi">
                                    <span>Hapus</span>
                                </x-button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada catatan hutang komisi penjual.</td>
                </tr>
            @endforelse
        </x-table>
        <div>{{ $unitCommissions->links() }}</div>
    </div>
@endif
