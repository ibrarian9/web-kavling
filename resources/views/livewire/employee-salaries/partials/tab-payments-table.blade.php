<!-- TAB 2: HISTORI PEMBAYARAN GAJI & SLIP PDF -->
<div class="space-y-4">
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="font-bold text-slate-900 text-sm">Histori Transaksi Pembayaran Gaji Karyawan</h2>
            <p class="text-xs text-slate-500">Riwayat penggajian bulanan yang telah diproses dan penerbitan Slip Gaji PDF resmi.</p>
        </div>

        <div class="flex items-center gap-2">
            <select wire:model.live="selected_month" class="select-clean text-xs font-semibold">
                <option value="">Semua Bulan</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>

            <select wire:model.live="selected_year" class="select-clean text-xs font-semibold">
                <option value="">Semua Tahun</option>
                <option value="2026">2026</option>
                <option value="2025">2025</option>
            </select>
        </div>
    </div>

    <x-table :headers="['No. Slip Gaji', 'Nama Karyawan', 'Periode Gaji', 'Tgl Bayar', ['label' => 'Total Gaji Dibayar', 'class' => 'p-3.5 text-right'], 'Metode Bayar', ['label' => 'Cetak Slip & Aksi', 'class' => 'p-3.5 text-center']]" loadingTarget="selected_month, selected_year, gotoPage, nextPage, previousPage">
        @forelse($payments as $pay)
            <tr class="hover:bg-slate-50 transition">
                <td class="p-3.5 font-mono font-bold text-emerald-800 text-xs">
                    SLIP/PAY/{{ strtoupper(substr($pay->uuid, 0, 8)) }}
                </td>
                <td class="p-3.5">
                    <div class="font-bold text-slate-900 text-sm">{{ $pay->employeeSalary->employee_name ?? 'Karyawan' }}</div>
                    <div class="text-[11px] text-slate-500">{{ $pay->employeeSalary->position ?? '-' }}</div>
                </td>
                <td class="p-3.5 font-bold text-slate-800 text-xs">
                    {{ $pay->employeeSalary->getIndonesianMonth($pay->payroll_month) }} {{ $pay->payroll_year }}
                </td>
                <td class="p-3.5 font-mono text-xs">{{ \Carbon\Carbon::parse($pay->payment_date)->isoFormat('DD/MM/YYYY') }}</td>
                <td class="p-3.5 text-right font-mono text-emerald-800 font-black text-sm">
                    Rp {{ number_format($pay->net_salary, 0, ',', '.') }}
                </td>
                <td class="p-3.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-800 border border-slate-200">
                        {{ strtoupper($pay->payment_method) }}
                    </span>
                </td>
                <td class="p-3.5 text-center">
                    <div class="inline-flex items-center justify-center gap-1.5">
                        <a href="{{ route('employee-salary.slip-pdf', $pay->uuid) }}" target="_blank">
                            <x-button variant="emerald" size="xs" title="Buka / Stream Slip Gaji PDF Resmi">
                                Slip Gaji PDF
                            </x-button>
                        </a>
                        <button type="button" @click="confirmModalAction({
                            title: 'Hapus Histori Penggajian',
                            message: 'Yakin ingin menghapus berkas penggajian ini dari sistem?',
                            confirmText: 'Hapus Berkas',
                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                            onConfirm: () => $wire.deletePaymentRecord({{ $pay->id }})
                        })" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus Berkas">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="font-bold text-slate-600">Belum Ada Histori Transaksi Pembayaran Gaji</p>
                </td>
            </tr>
        @endforelse
    </x-table>

    <div>{{ $payments->links() }}</div>
</div>
