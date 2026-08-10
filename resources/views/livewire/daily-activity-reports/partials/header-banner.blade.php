<!-- Header Banner & Stats Section -->
<div class="bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900 rounded-3xl p-5 sm:p-7 text-white shadow-xl relative overflow-hidden border border-slate-800">
    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 text-teal-300 border border-teal-500/30 text-xs font-bold mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Laporan Aktivitas Harian Sales Marketing</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Daily Activity Report</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl">
                Pencatatan aktivitas prospek harian tim sales, pelacakan efektivitas sumber lead, hingga status transaksi DP / Beli Cash.
            </p>
        </div>

        <button wire:click="openCreateModal" class="px-4 py-2.5 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-teal-900/40 text-xs sm:text-sm transition flex items-center justify-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Catat Laporan Harian</span>
        </button>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-5 border-t border-slate-800/80">
        <div class="bg-slate-800/50 backdrop-blur-xs p-3.5 rounded-2xl border border-slate-700/60">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Aktivitas Hari Ini</span>
            </div>
            <span class="text-lg sm:text-xl font-black text-emerald-400 font-mono">{{ number_format($todayReportsCount, 0, ',', '.') }}</span>
            <span class="text-[10px] text-slate-400 block mt-0.5">Total Keseluruhan: {{ number_format($totalReportsCount, 0, ',', '.') }}</span>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-xs p-3.5 rounded-2xl border border-slate-700/60">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Hot Deals & Closing</span>
            </div>
            <span class="text-lg sm:text-xl font-black text-amber-400 font-mono">{{ number_format($hotDealsCount, 0, ',', '.') }}</span>
            <span class="text-[10px] text-amber-300/80 block mt-0.5">Prospek Siap Closing</span>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-xs p-3.5 rounded-2xl border border-slate-700/60">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-3.5 h-3.5 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Volume Closing</span>
            </div>
            <span class="text-xs sm:text-sm font-black text-teal-300 font-mono block truncate">Rp {{ number_format($totalDealVolume, 0, ',', '.') }}</span>
            <span class="text-[10px] text-teal-400/80 block mt-0.5">Nominal DP / Cash Masuk</span>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-xs p-3.5 rounded-2xl border border-slate-700/60">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Lead Source Teratas</span>
            </div>
            @if($topLeadSources->count() > 0)
                <span class="text-xs font-bold text-slate-200 block truncate">
                    {{ \App\Models\DailyActivityReport::leadSources()[$topLeadSources->first()->lead_source] ?? $topLeadSources->first()->lead_source }} ({{ $topLeadSources->first()->count }})
                </span>
            @else
                <span class="text-xs text-slate-400 block">-</span>
            @endif
            <span class="text-[10px] text-slate-400 block mt-0.5">Sumber Paling Efektif</span>
        </div>
    </div>
</div>
