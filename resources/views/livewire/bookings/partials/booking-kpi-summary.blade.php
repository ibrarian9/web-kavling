<!-- KPI Summary Metric Cards -->
<div class="grid grid-cols-1 {{ auth()->user()->canViewSalesPrices() ? 'sm:grid-cols-2' : '' }} gap-4 sm:gap-5">
    @if(auth()->user()->canViewSalesPrices())
        <div class="kpi-card-teal p-3.5 sm:p-4 xl:p-5 min-w-0 flex flex-col justify-between">
            <div class="flex items-center justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Booking Fee & DP Aktif</span>
                <div class="p-1.5 sm:p-2 rounded-xl bg-teal-50 text-teal-600 border border-teal-100 shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
            </div>
            <div class="mt-2 min-w-0">
                <p class="text-sm sm:text-base xl:text-xl 2xl:text-2xl font-extrabold text-teal-600 font-mono tracking-tight truncate" title="Rp {{ number_format($totalBookingDpAmount, 0, ',', '.') }}">Rp {{ number_format($totalBookingDpAmount, 0, ',', '.') }}</p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Tanda jadi & uang muka aktif</p>
            </div>
        </div>
    @endif

    <div class="kpi-card-emerald p-3.5 sm:p-4 xl:p-5 min-w-0 flex flex-col justify-between">
        <div class="flex items-center justify-between gap-1">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Total Pembeli Booking / DP</span>
            <div class="p-1.5 sm:p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
        <div class="mt-2 min-w-0">
            <p class="text-base sm:text-lg xl:text-2xl font-extrabold text-emerald-600 tracking-tight truncate">{{ $totalBookingCount }} <span class="text-[10px] sm:text-xs font-normal font-sans text-slate-400">Orang</span></p>
            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Pembeli booking atau DP aktif</p>
        </div>
    </div>
</div>
