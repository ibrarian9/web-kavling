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
                    <td class="p-3 font-mono font-medium">{{ $c->created_at->format('d/m/Y') }}</td>
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
                        <div class="flex items-center justify-center gap-1.5">
                            @if($c->status !== 'lunas')
                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                    <x-button variant="purple" size="xs" wire:click="openSettleCommissionModal({{ $c->id }})">
                                        Bayar Komisi
                                    </x-button>
                                @endif
                            @else
                                <span class="text-[11px] text-emerald-600 font-semibold whitespace-nowrap">Lunas</span>
                            @endif

                             @if(auth()->user()->isFounder())
                                 <button type="button" @click="confirmModalAction({ title: 'Hapus Komisi Penjual', message: 'Apakah Anda yakin ingin menghapus catatan komisi penjual ini secara permanen?', confirmText: 'Ya, Hapus Komisi', onConfirm: () => $wire.deleteCommission({{ $c->id }}) })" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus Komisi">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                 </button>
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
