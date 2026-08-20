<!-- TAB 4: PIUTANG & KASBON STAF / WORKERS -->
@if($activeTab === 'company_receivables')
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <span>Daftar Piutang / Uang Dipinjam Staf & Workers (Kasbon)</span>
                <span class="text-xs text-slate-500 font-normal">({{ $companyReceivables->total() }} Peminjam)</span>
            </h3>
            <div class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-xl">
                Subtotal Total Piutang: Rp {{ number_format($totalCompanyReceivables, 0, ',', '.') }}
            </div>
        </div>

        <!-- Unified Table of Company Receivables with CSS Table-to-Card Transformation -->
        <x-table :headers="['Tanggal Pinjam', 'Peminjam (Mandor/Tukang/Marketing)', 'Total Pinjaman', 'Sudah Dikembalikan', 'Sisa Piutang', 'Status', ['label' => 'Aksi', 'class' => 'p-3 text-center']]" loadingTarget="setTab, filter_project_id, filter_status, search, rec_page">
            @forelse($companyReceivables as $r)
                @php $sisaRec = max(0, (float)$r->amount - (float)$r->paid_amount); @endphp
                <tr class="hover:bg-slate-50/80 transition">
                    <td data-label="Tanggal Pinjam" class="p-3 font-mono font-bold text-slate-700 whitespace-nowrap">{{ format_id_date($r->loan_date) }}</td>
                    <td data-label="Peminjam" class="p-3 font-bold text-slate-900">
                        {{ $r->debtor_name }}
                        @if($r->notes)
                            <span class="block text-[10px] text-slate-500 font-normal">{{ $r->notes }}</span>
                        @endif
                    </td>
                    <td data-label="Total Pinjaman" class="p-3 font-mono font-bold text-slate-800">Rp {{ number_format($r->amount, 0, ',', '.') }}</td>
                    <td data-label="Sudah Dikembalikan" class="p-3 font-mono font-bold text-emerald-600">Rp {{ number_format($r->paid_amount, 0, ',', '.') }}</td>
                    <td data-label="Sisa Piutang" class="p-3 font-mono font-bold text-amber-700 text-sm">Rp {{ number_format($sisaRec, 0, ',', '.') }}</td>
                    <td data-label="Status" class="p-3">
                        <x-status-badge :status="$r->status" />
                    </td>
                    <td data-card-action class="p-3 text-center">
                        <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                            @if($sisaRec > 0)
                                 @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                     <x-button variant="payment" size="xs" wire:click="openPayReceivableModal({{ $r->id }})">
                                         <span>Terima Pengembalian</span>
                                     </x-button>
                                 @endif
                            @else
                                <span class="text-[11px] text-emerald-600 font-semibold whitespace-nowrap">Lunas</span>
                            @endif

                            @if(auth()->user()->isSuperAdmin())
                                <x-button variant="delete" size="xs" @click="confirmModalAction({ title: 'Hapus Piutang Staf', message: 'Apakah Anda yakin ingin menghapus catatan piutang / kasbon staf ini secara permanen?', confirmText: 'Ya, Hapus Piutang', btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5', onConfirm: () => $wire.deleteReceivable({{ $r->id }}) })" title="Hapus Piutang">
                                    <span>Hapus</span>
                                </x-button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <x-table-empty colspan="7" title="Tidak Ada Data Piutang" message="Tidak ada catatan piutang / kasbon staf." />
            @endforelse
        </x-table>
        <div>{{ $companyReceivables->links() }}</div>
    </div>
@endif
