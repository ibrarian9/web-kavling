<!-- KPI Summary Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="kpi-card-teal">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Booking Fee Aktif</span>
            <div class="p-2.5 rounded-xl bg-teal-50 text-teal-600 border border-teal-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-teal-600 font-mono tracking-tight mt-2">Rp {{ number_format($totalBookingAmount, 0, ',', '.') }}</p>
        <p class="text-[11px] text-slate-400 mt-1">Akumulasi tanda jadi pemesanan unit/proyek</p>
    </div>

    <div class="kpi-card-emerald">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total DP (Uang Muka) Aktif</span>
            <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-emerald-600 font-mono tracking-tight mt-2">Rp {{ number_format($totalDpAmount, 0, ',', '.') }}</p>
        <p class="text-[11px] text-slate-400 mt-1">Total pembayaran uang muka yang disetujui</p>
    </div>
</div>
