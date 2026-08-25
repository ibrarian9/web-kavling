<!-- KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
    <div class="kpi-card-emerald p-3.5 sm:p-4 xl:p-5 min-w-0 flex flex-col justify-between">
        <div class="flex items-center justify-between gap-1">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Total Pengeluaran Lapangan</span>
            <div class="p-1.5 sm:p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="mt-2 min-w-0">
            <p class="text-sm sm:text-base xl:text-xl 2xl:text-2xl font-extrabold text-emerald-600 font-mono tracking-tight truncate" title="Rp {{ number_format($totalExpenses, 0, ',', '.') }}">
                Rp {{ number_format($totalExpenses, 0, ',', '.') }}
            </p>
            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Gaji worker + belanja material</p>
        </div>
    </div>

    <div class="kpi-card-amber p-3.5 sm:p-4 xl:p-5 min-w-0 flex flex-col justify-between">
        <div class="flex items-center justify-between gap-1">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Total Gaji Worker</span>
            <div class="p-1.5 sm:p-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
        <div class="mt-2 min-w-0">
            <p class="text-sm sm:text-base xl:text-xl 2xl:text-2xl font-extrabold text-amber-600 font-mono tracking-tight truncate" title="Rp {{ number_format($totalSalary, 0, ',', '.') }}">
                Rp {{ number_format($totalSalary, 0, ',', '.') }}
            </p>
            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Upah & borongan unit tukang</p>
        </div>
    </div>

    <div class="kpi-card-rose p-3.5 sm:p-4 xl:p-5 min-w-0 flex flex-col justify-between col-span-1 sm:col-span-2 lg:col-span-1">
        <div class="flex items-center justify-between gap-1">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Belanja Material</span>
            <div class="p-1.5 sm:p-2 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
        <div class="mt-2 min-w-0">
            <p class="text-sm sm:text-base xl:text-xl 2xl:text-2xl font-extrabold text-rose-600 font-mono tracking-tight truncate" title="Rp {{ number_format($totalMaterial, 0, ',', '.') }}">
                Rp {{ number_format($totalMaterial, 0, ',', '.') }}
            </p>
            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Pengadaan bahan bangunan</p>
        </div>
    </div>
</div>
