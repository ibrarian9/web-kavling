<!-- Unit Status & Financial KPI Dashboard Cards -->
<div class="space-y-4">
    <!-- Unit Status Summary Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Unit Terjual -->
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unit Terjual / Deal</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-emerald-700 mt-2">{{ $soldUnits }} <span class="text-xs font-normal font-sans text-slate-400">Unit</span></p>
            <p class="text-[11px] text-slate-400 mt-1">Disetujui / Booked / Terjual</p>
        </div>

        <!-- Unit Belum Terjual / Tersedia -->
        <div class="kpi-card-blue">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unit Belum Terjual</span>
                <div class="p-2.5 rounded-xl bg-sky-50 text-sky-600 border border-sky-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-slate-900 mt-2">{{ $availableUnits }} <span class="text-xs font-normal font-sans text-slate-400">Unit</span></p>
            <p class="text-[11px] text-slate-400 mt-1">Masih Tersedia Ditawarkan</p>
        </div>

        <!-- Unit Lunas -->
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unit Sudah Lunas</span>
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-emerald-700 mt-2">{{ $fullyPaidUnitsCount ?? 0 }} <span class="text-xs font-normal font-sans text-slate-400">Unit</span></p>
            <p class="text-[11px] text-slate-400 mt-1">Pembayaran Selesai 100%</p>
        </div>

        <!-- Unit Cicilan / Belum Lunas -->
        <div class="kpi-card-amber">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unit Masih Cicilan</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-amber-700 mt-2">{{ $installmentUnitsCount ?? 0 }} <span class="text-xs font-normal font-sans text-slate-400">Unit</span></p>
            <p class="text-[11px] text-slate-400 mt-1">Dalam Masa Cicilan</p>
        </div>
    </div>

    <!-- Financial KPI Dashboard Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Nilai Deal Penjualan -->
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Nilai Deal Proyek</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">Rp {{ number_format($totalSalesRevenue, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">{{ $soldUnits }} Unit Deal / Terjual / Booked</p>
        </div>

        <!-- Total Terbayar Masuk -->
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kas Masuk Terbayar</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">Rp {{ number_format($totalPaidRevenue, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Setoran Booking, DP, & Cicilan Masuk</p>
        </div>

        <!-- Sisa Piutang Penjualan -->
        <div class="kpi-card-amber">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sisa Tagihan / Piutang</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 font-mono mt-2">Rp {{ number_format($totalOutstandingReceivable, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Piutang Pembeli Belum Lunas</p>
        </div>

        <!-- Total Biaya & Profit -->
        <div class="kpi-card-blue">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Profit Proyek Bersih</span>
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono mt-2 {{ $totalProjectProfit >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                Rp {{ number_format($totalProjectProfit, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Pengeluaran: Rp {{ number_format($totalProjectExpenses, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
