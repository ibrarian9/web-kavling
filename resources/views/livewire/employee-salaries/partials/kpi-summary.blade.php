<!-- KPI Metrics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="card-clean p-5 space-y-1 bg-white border border-slate-200/80">
        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Beban Gaji Terbayar (Bulan Ini)</span>
        <p class="text-2xl font-black text-emerald-700 font-mono">Rp {{ number_format($totalMonthlySalaryPaid, 0, ',', '.') }}</p>
        <p class="text-[10px] text-slate-400">Pencatatan penggajian periode {{ date('F Y') }}</p>
    </div>

    <div class="card-clean p-5 space-y-1 bg-white border border-slate-200/80">
        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Jumlah Karyawan Ditetapkan</span>
        <p class="text-2xl font-black text-slate-900 font-mono">{{ $totalEmployeesCount }} Orang</p>
        <p class="text-[10px] text-slate-400">Staf kantor & pekerja proyek</p>
    </div>

    <div class="card-clean p-5 space-y-1 bg-white border border-slate-200/80">
        <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Transaksi Penggajian</span>
        <p class="text-2xl font-black text-slate-900 font-mono">{{ $totalPaymentsCount }} Berkas</p>
        <p class="text-[10px] text-slate-400">Slip gaji PDF yang telah diproses</p>
    </div>
</div>
