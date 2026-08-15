<!-- TAB 2: SISA UPAH PEKERJA -->
@if($activeTab === 'worker_payrolls')
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <span>Daftar Sisa Upah Terutang Pekerja Lapangan</span>
                <span class="text-xs text-slate-500 font-normal">({{ $workerPayrolls->total() }} Kontrak)</span>
            </h3>
            <div class="text-xs font-mono font-bold text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1 rounded-xl">
                Subtotal Sisa Upah Worker: Rp {{ number_format($totalUnpaidWorkerWages, 0, ',', '.') }}
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs text-slate-600 min-w-[750px]">
                <thead class="bg-slate-50 font-bold text-slate-700 uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="p-3">Nama Pekerja / Tukang</th>
                        <th class="p-3">Proyek & Unit</th>
                        <th class="p-3">Uraian Pekerjaan</th>
                        <th class="p-3">Total Kontrak</th>
                        <th class="p-3">Sudah Dibayar</th>
                        <th class="p-3">Sisa Terutang</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($workerPayrolls as $w)
                        @php $sisaUpah = max(0, (float)$w->agreed_salary - (float)$w->paid_amount); @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3 font-bold text-slate-900">
                                {{ $w->worker->name ?? '-' }}
                                <span class="block text-[10px] text-slate-500 font-normal capitalize">{{ $w->worker->type ?? 'Tukang' }}</span>
                            </td>
                            <td class="p-3">
                                <span class="font-bold text-slate-800 block">{{ $w->project->name ?? '-' }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">Unit: {{ $w->unit ? $w->unit->code : '-' }}</span>
                            </td>
                            <td class="p-3 text-slate-700">{{ $w->notes ?: 'Pekerjaan Borongan Unit' }}</td>
                            <td class="p-3 font-mono font-bold text-slate-800">Rp {{ number_format($w->agreed_salary, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono font-bold text-emerald-600">Rp {{ number_format($w->paid_amount, 0, ',', '.') }}</td>
                            <td class="p-3 font-mono font-bold text-rose-700 text-sm">Rp {{ number_format($sisaUpah, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($sisaUpah > 0)
                                        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                            <button type="button" wire:click="openWorkerPaymentModal({{ $w->id }})" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition shadow-xs whitespace-nowrap">
                                                Bayar Upah
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-[11px] text-emerald-600 font-semibold whitespace-nowrap">Lunas</span>
                                    @endif

                                    @if(auth()->user()->isFounder())
                                        <button type="button" @click="confirmModalAction({ title: 'Hapus Upah Pekerja', message: 'Apakah Anda yakin ingin menghapus kontrak upah worker ini secara permanen?', confirmText: 'Ya, Hapus Upah', onConfirm: () => $wire.deleteWorkerPayroll({{ $w->id }}) })" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus Upah">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada sisa upah terutang pekerja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $workerPayrolls->links() }}</div>
    </div>
@endif
