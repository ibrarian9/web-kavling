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

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs text-slate-600 min-w-[750px]">
                <thead class="bg-slate-50 font-bold text-slate-700 uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="p-3">Tanggal Pinjam</th>
                        <th class="p-3">Peminjam (Mandor/Tukang/Marketing)</th>
                        <th class="p-3">Total Pinjaman</th>
                        <th class="p-3">Sudah Dikembalikan</th>
                        <th class="p-3">Sisa Piutang</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($companyReceivables as $r)
                        @php $sisaRec = max(0, (float)$r->amount - (float)$r->paid_amount); @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3 font-mono font-medium">{{ \Carbon\Carbon::parse($r->loan_date)->format('d/m/Y') }}</td>
                            <td class="p-3 font-bold text-slate-900">
                                {{ $r->debtor_name }}
                                @if($r->notes)
                                    <span class="block text-[10px] text-slate-500 font-normal">{{ $r->notes }}</span>
                                @endif
                            </td>
                            <td class="p-3 font-mono font-bold text-slate-800">Rp {{ number_format($r->amount, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono font-bold text-emerald-600">Rp {{ number_format($r->paid_amount, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono font-bold text-amber-700 text-sm">Rp {{ number_format($sisaRec, 0, ',', '.') }}</td>
                            <td class="p-3">
                                @if($r->status === 'lunas')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1 whitespace-nowrap shrink-0">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        LUNAS
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1 whitespace-nowrap shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                        BELUM LUNAS
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($sisaRec > 0)
                                        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                            <button type="button" wire:click="openPayReceivableModal({{ $r->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-xs whitespace-nowrap">
                                                Terima Pengembalian
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-[11px] text-emerald-600 font-semibold whitespace-nowrap">Lunas</span>
                                    @endif

                                    @if(auth()->user()->isFounder())
                                        <button type="button" @click="confirmModalAction({ title: 'Hapus Piutang Staf', message: 'Apakah Anda yakin ingin menghapus catatan piutang / kasbon staf ini secara permanen?', confirmText: 'Ya, Hapus Piutang', onConfirm: () => $wire.deleteReceivable({{ $r->id }}) })" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus Piutang">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada catatan piutang / kasbon staf.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $companyReceivables->links() }}</div>
    </div>
@endif
