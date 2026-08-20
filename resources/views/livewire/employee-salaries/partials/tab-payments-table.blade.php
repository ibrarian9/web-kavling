<!-- TAB 2: HISTORI PEMBAYARAN GAJI & SLIP PDF -->
<div class="space-y-4">
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="font-bold text-slate-900 text-sm">Histori Transaksi Pembayaran Gaji Karyawan</h2>
            <p class="text-xs text-slate-500">Riwayat penggajian bulanan yang telah diproses dan penerbitan Slip Gaji PDF resmi.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <!-- Filter Periode Waktu Tanggal Bayar -->
            <x-date-period-filter periodModel="datePeriod" startModel="startDate" endModel="endDate" :periodValue="$datePeriod" />

            <select wire:model.live="selected_month" class="select-clean text-xs font-semibold">
                <option value="">Semua Bulan</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->isoFormat('MMMM') }}</option>
                @endfor
            </select>

            <select wire:model.live="selected_year" class="select-clean text-xs font-semibold">
                <option value="">Semua Tahun</option>
                <option value="2026">2026</option>
                <option value="2025">2025</option>
            </select>

            @if($selected_month || $selected_year || $datePeriod !== 'all' || $startDate || $endDate)
                <x-reset-filter-button wire:click="$set('selected_month', ''); $set('selected_year', ''); $set('datePeriod', 'all'); $set('startDate', ''); $set('endDate', '');" />
            @endif
        </div>
    </div>

    <!-- Unified Table of Payments with CSS Table-to-Card Transformation -->
    <x-table :headers="['No. Slip Gaji', 'Nama Karyawan', 'Periode Gaji', 'Tgl Bayar', ['label' => 'Total Gaji Dibayar', 'class' => 'p-3.5 text-right'], 'Metode Bayar', ['label' => 'Cetak Slip & Aksi', 'class' => 'p-3.5 text-center']]" loadingTarget="selected_month, selected_year, datePeriod, startDate, endDate, gotoPage, nextPage, previousPage">
        @forelse($payments as $pay)
            <tr class="hover:bg-slate-50 transition">
                <td data-label="No. Slip Gaji" class="p-3.5 font-mono font-bold text-emerald-800 text-xs">
                    SLIP/PAY/{{ strtoupper(substr($pay->uuid, 0, 8)) }}
                </td>
                <td data-label="Nama Karyawan" class="p-3.5">
                    <div class="font-bold text-slate-900 text-sm">{{ $pay->employeeSalary->employee_name ?? 'Karyawan' }}</div>
                    <div class="text-[11px] text-slate-500">{{ $pay->employeeSalary->position ?? '-' }}</div>
                </td>
                <td data-label="Periode Gaji" class="p-3.5 font-bold text-slate-800 text-xs">
                    {{ $pay->employeeSalary->getIndonesianMonth($pay->payroll_month) }} {{ $pay->payroll_year }}
                </td>
                <td data-label="Tgl Bayar" class="p-3.5 font-mono text-xs">{{ format_id_date($pay->payment_date) }}</td>
                <td data-label="Total Gaji Dibayar" class="p-3.5 text-right font-mono text-emerald-800 font-black text-sm">
                    Rp {{ number_format($pay->net_salary, 0, ',', '.') }}
                </td>
                <td data-label="Metode Bayar" class="p-3.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-800 border border-slate-200">
                        {{ strtoupper($pay->payment_method) }}
                    </span>
                </td>
                <td data-card-action class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <a href="{{ route('employee-salary.slip-pdf', $pay->uuid) }}" target="_blank">
                            <x-button variant="emerald" size="xs" title="Buka / Stream Slip Gaji PDF Resmi">
                                Slip Gaji PDF
                            </x-button>
                        </a>

                        @if(auth()->user()->isSuperAdmin())
                            <x-action-dropdown title="Menu Opsi Berkas" size="xs">
                                <div class="py-1">
                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Histori Penggajian',
                                        message: 'Yakin ingin menghapus berkas penggajian ini dari sistem?',
                                        confirmText: 'Hapus Berkas',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deletePaymentRecord({{ $pay->id }})
                                    })">
                                        Hapus Berkas
                                    </x-dropdown-item>
                                </div>
                            </x-action-dropdown>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <x-table-empty colspan="7" title="Belum Ada Histori Transaksi Pembayaran Gaji" message="Riwayat penggajian bulanan yang telah diproses akan ditampilkan di sini." />
        @endforelse
    </x-table>

    <div>{{ $payments->links() }}</div>
</div>
