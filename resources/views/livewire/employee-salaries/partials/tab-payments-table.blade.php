<!-- TAB 2: HISTORI PEMBAYARAN GAJI & SLIP PDF -->
<div class="card-clean p-6 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
        <div>
            <h2 class="font-black text-slate-900 text-base">Histori Transaksi Pembayaran Gaji Karyawan</h2>
            <p class="text-xs text-slate-500">Riwayat penggajian bulanan yang telah diproses dan penerbitan Slip Gaji PDF resmi.</p>
        </div>

        <div class="flex items-center gap-2">
            <select wire:model.live="selected_month" class="input-clean text-xs font-semibold">
                <option value="">Semua Bulan</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>

            <select wire:model.live="selected_year" class="input-clean text-xs font-semibold">
                <option value="">Semua Tahun</option>
                <option value="2026">2026</option>
                <option value="2025">2025</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
            <thead class="bg-slate-900 text-white font-bold uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="p-3.5">No. Slip Gaji</th>
                    <th class="p-3.5">Nama Karyawan</th>
                    <th class="p-3.5">Periode Gaji</th>
                    <th class="p-3.5">Tgl Bayar</th>
                    <th class="p-3.5 text-right">Total Gaji Dibayar</th>
                    <th class="p-3.5">Metode Bayar</th>
                    <th class="p-3.5 text-center">Cetak Slip & Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 font-medium text-slate-700 bg-white">
                @forelse($payments as $pay)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5 font-mono font-bold text-emerald-800">
                            SLIP/PAY/{{ strtoupper(substr($pay->uuid, 0, 8)) }}
                        </td>
                        <td class="p-3.5">
                            <div class="font-bold text-slate-900 text-sm">{{ $pay->employeeSalary->employee_name ?? 'Karyawan' }}</div>
                            <div class="text-[11px] text-slate-500">{{ $pay->employeeSalary->position ?? '-' }}</div>
                        </td>
                        <td class="p-3.5 font-bold text-slate-800">
                            {{ $pay->employeeSalary->getIndonesianMonth($pay->payroll_month) }} {{ $pay->payroll_year }}
                        </td>
                        <td class="p-3.5 font-mono">{{ \Carbon\Carbon::parse($pay->payment_date)->isoFormat('DD/MM/YYYY') }}</td>
                        <td class="p-3.5 text-right font-mono text-emerald-800 font-black text-sm">
                            Rp {{ number_format($pay->net_salary, 0, ',', '.') }}
                        </td>
                        <td class="p-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-800 border border-slate-200">
                                {{ strtoupper($pay->payment_method) }}
                            </span>
                        </td>
                        <td class="p-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('employee-salary.slip-pdf', $pay->uuid) }}" target="_blank" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold shadow-2xs transition flex items-center gap-1" title="Buka / Stream Slip Gaji PDF Resmi">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span>Slip Gaji PDF</span>
                                </a>
                                <button type="button" @click="confirmModalAction({
                                    title: 'Hapus Histori Penggajian',
                                    message: 'Yakin ingin menghapus berkas penggajian ini dari sistem?',
                                    confirmText: 'Hapus Berkas',
                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                    onConfirm: () => $wire.deletePaymentRecord({{ $pay->id }})
                                })" class="btn-action-delete" title="Hapus Berkas">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="font-bold text-slate-600">Belum Ada Histori Transaksi Pembayaran Gaji</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
