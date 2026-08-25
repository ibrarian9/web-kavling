<!-- Unit Status & Financial KPI Dashboard Cards -->
<div class="space-y-4">
    <!-- Unit Status Summary Grid (2 Baris di iPad / Tablet, 4 Kolom di Layar Lebar) -->
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- Unit Terjual -->
        <div class="kpi-card-emerald p-4 sm:p-5 min-w-0 flex flex-col justify-between shadow-2xs rounded-2xl">
            <div class="flex items-center justify-between gap-1.5">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 truncate">Unit Terjual</span>
                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3 min-w-0">
                <div class="flex items-baseline gap-1.5 flex-wrap">
                    <span class="text-2xl sm:text-3xl font-black font-mono text-emerald-700 tracking-tight">{{ $soldUnits }}</span>
                    <span class="text-xs font-bold text-slate-400">Unit</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-400 mt-1">Booked & Terjual</p>
            </div>
        </div>

        <!-- Unit Belum Terjual / Tersedia -->
        <div class="kpi-card-blue p-4 sm:p-5 min-w-0 flex flex-col justify-between shadow-2xs rounded-2xl">
            <div class="flex items-center justify-between gap-1.5">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 truncate">Belum Terjual</span>
                <div class="p-2 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
            </div>
            <div class="mt-3 min-w-0">
                <div class="flex items-baseline gap-1.5 flex-wrap">
                    <span class="text-2xl sm:text-3xl font-black font-mono text-slate-900 tracking-tight">{{ $availableUnits }}</span>
                    <span class="text-xs font-bold text-slate-400">Unit</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-400 mt-1">Tersedia Ditawarkan</p>
            </div>
        </div>

        <!-- Unit Lunas -->
        <div class="kpi-card-emerald p-4 sm:p-5 min-w-0 flex flex-col justify-between shadow-2xs rounded-2xl">
            <div class="flex items-center justify-between gap-1.5">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 truncate">Sudah Lunas</span>
                <div class="p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
            </div>
            <div class="mt-3 min-w-0">
                <div class="flex items-baseline gap-1.5 flex-wrap">
                    <span class="text-2xl sm:text-3xl font-black font-mono text-emerald-700 tracking-tight">{{ $fullyPaidUnitsCount ?? 0 }}</span>
                    <span class="text-xs font-bold text-slate-400">Unit</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-400 mt-1">Pelunasan 100%</p>
            </div>
        </div>

        <!-- Unit Cicilan / Belum Lunas -->
        <div class="kpi-card-amber p-4 sm:p-5 min-w-0 flex flex-col justify-between shadow-2xs rounded-2xl">
            <div class="flex items-center justify-between gap-1.5">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 truncate">Masih Cicilan</span>
                <div class="p-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3 min-w-0">
                <div class="flex items-baseline gap-1.5 flex-wrap">
                    <span class="text-2xl sm:text-3xl font-black font-mono text-amber-700 tracking-tight">{{ $installmentUnitsCount ?? 0 }}</span>
                    <span class="text-xs font-bold text-slate-400">Unit</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-400 mt-1">Dalam Masa Cicilan</p>
            </div>
        </div>
    </div>

    <!-- Financial KPI Dashboard Cards (2 Baris di iPad / Tablet, 4 Kolom di Layar Lebar) -->
    @if(!auth()->user()->isMarketing() && !auth()->user()->isPengawasProject() && auth()->user()->canViewSalesPrices())
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3.5 sm:gap-4">
            <!-- Total Nilai Deal Penjualan -->
            <div class="kpi-card-emerald p-4 sm:p-5 min-w-0 flex flex-col justify-between shadow-2xs rounded-2xl">
                <div class="flex items-center justify-between gap-1.5">
                    <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 truncate">Nilai Deal Proyek</span>
                    <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 min-w-0">
                    <p class="text-base sm:text-lg xl:text-2xl font-black text-emerald-700 font-mono tracking-tight whitespace-normal break-words" title="Rp {{ number_format($totalSalesRevenue, 0, ',', '.') }}">
                        Rp {{ number_format($totalSalesRevenue, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-1">{{ $soldUnits }} Unit Deal / Terjual</p>
                </div>
            </div>

            <!-- Total Terbayar Masuk -->
            <div class="kpi-card-emerald p-4 sm:p-5 min-w-0 flex flex-col justify-between shadow-2xs rounded-2xl">
                <div class="flex items-center justify-between gap-1.5">
                    <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 truncate">Kas Masuk Terbayar</span>
                    <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 min-w-0">
                    <p class="text-base sm:text-lg xl:text-2xl font-black text-emerald-700 font-mono tracking-tight whitespace-normal break-words" title="Rp {{ number_format($totalPaidRevenue, 0, ',', '.') }}">
                        Rp {{ number_format($totalPaidRevenue, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-1">Booking, DP, & Cicilan</p>
                </div>
            </div>

            <!-- Sisa Piutang Penjualan -->
            <div class="kpi-card-amber p-4 sm:p-5 min-w-0 flex flex-col justify-between shadow-2xs rounded-2xl">
                <div class="flex items-center justify-between gap-1.5">
                    <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 truncate">Sisa Tagihan</span>
                    <div class="p-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 min-w-0">
                    <p class="text-base sm:text-lg xl:text-2xl font-black text-amber-700 font-mono tracking-tight whitespace-normal break-words" title="Rp {{ number_format($totalOutstandingReceivable, 0, ',', '.') }}">
                        Rp {{ number_format($totalOutstandingReceivable, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-1">Piutang Belum Lunas</p>
                </div>
            </div>

            <!-- Total Biaya & Profit -->
            <div class="kpi-card-blue p-4 sm:p-5 min-w-0 flex flex-col justify-between shadow-2xs rounded-2xl">
                <div class="flex items-center justify-between gap-1.5">
                    <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 truncate">Profit Bersih</span>
                    <div class="p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <div class="mt-3 min-w-0">
                    <p class="text-base sm:text-lg xl:text-2xl font-black font-mono tracking-tight whitespace-normal break-words {{ $totalProjectProfit >= 0 ? 'text-emerald-700' : 'text-rose-700' }}" title="Rp {{ number_format($totalProjectProfit, 0, ',', '.') }}">
                        Rp {{ number_format($totalProjectProfit, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-1" title="Pengeluaran: Rp {{ number_format($totalProjectExpenses, 0, ',', '.') }}">Biaya: Rp {{ number_format($totalProjectExpenses, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    @endif
</div>
