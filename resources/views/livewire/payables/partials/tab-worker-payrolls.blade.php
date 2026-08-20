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

        <!-- Unified Table of Worker Payrolls with CSS Table-to-Card Transformation -->
        <x-table :headers="['Nama Pekerja / Tukang', 'Proyek & Unit', 'Uraian Pekerjaan', 'Total Kontrak', 'Sudah Dibayar', 'Sisa Terutang', ['label' => 'Aksi', 'class' => 'p-3 text-center']]" loadingTarget="setTab, filter_project_id, filter_status, search, wrk_page">
            @forelse($workerPayrolls as $w)
                @php $sisaUpah = max(0, (float)$w->agreed_salary - (float)$w->paid_amount); @endphp
                <tr class="hover:bg-slate-50/80 transition">
                    <td data-label="Nama Pekerja" class="p-3 font-bold text-slate-900">
                        {{ $w->worker->name ?? '-' }}
                        <span class="block text-[10px] text-slate-500 font-normal capitalize">{{ $w->worker->type ?? 'Tukang' }}</span>
                    </td>
                    <td data-label="Proyek & Unit" class="p-3">
                        <span class="font-bold text-slate-800 block">{{ $w->project->name ?? '-' }}</span>
                        <span class="text-[10px] text-slate-500 font-mono">Unit: {{ $w->unit ? $w->unit->code : '-' }}</span>
                    </td>
                    <td data-label="Uraian" class="p-3 text-slate-700">{{ $w->notes ?: 'Pekerjaan Borongan Unit' }}</td>
                    <td data-label="Total Kontrak" class="p-3 font-mono font-bold text-slate-800">Rp {{ number_format($w->agreed_salary, 0, ',', '.') }}</td>
                    <td data-label="Sudah Dibayar" class="p-3 font-mono font-bold text-emerald-600">Rp {{ number_format($w->paid_amount, 0, ',', '.') }}</td>
                    <td data-label="Sisa Terutang" class="p-3 font-mono font-bold text-rose-700 text-sm">Rp {{ number_format($sisaUpah, 0, ',', '.') }}</td>
                    <td data-card-action class="p-3 text-center">
                        <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                            @if($sisaUpah > 0)
                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                    <x-button variant="payment" size="xs" wire:click="openWorkerPaymentModal({{ $w->id }})">
                                        <span>Bayar Upah</span>
                                    </x-button>
                                @endif
                            @else
                                <span class="text-[11px] text-emerald-600 font-semibold whitespace-nowrap">Lunas</span>
                            @endif

                            @if(auth()->user()->isSuperAdmin())
                                <x-button variant="delete" size="xs" @click="confirmModalAction({ title: 'Hapus Upah Pekerja', message: 'Apakah Anda yakin ingin menghapus kontrak upah worker ini secara permanen?', confirmText: 'Ya, Hapus Upah', btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5', onConfirm: () => $wire.deleteWorkerPayroll({{ $w->id }}) })" title="Hapus Upah">
                                    <span>Hapus</span>
                                </x-button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <x-table-empty colspan="7" title="Tidak Ada Data Upah" message="Tidak ada sisa upah terutang pekerja." />
            @endforelse
        </x-table>
        <div>{{ $workerPayrolls->links() }}</div>
    </div>
@endif
