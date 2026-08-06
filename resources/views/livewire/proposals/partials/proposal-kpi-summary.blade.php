<!-- Summary KPI Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
    <div class="kpi-card-blue">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Proposal Usulan</span>
            <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">{{ $proposals->total() }} Proposal</p>
        <p class="text-[11px] text-slate-400 mt-1">Usulan penawaran harga dari marketing</p>
    </div>

    <div class="kpi-card-amber">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Menunggu Approval</span>
            <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-amber-600 font-mono mt-2">
            {{ \App\Models\PriceProposal::where('status', 'menunggu')->count() }} Menunggu
        </p>
        <p class="text-[11px] text-slate-400 mt-1">Memerlukan keputusannya Founder & Supervisor</p>
    </div>

    <div class="kpi-card-emerald">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Proposal Disetujui (ACC)</span>
            <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">
            {{ \App\Models\PriceProposal::where('status', 'disetujui')->count() }} Disetujui
        </p>
        <p class="text-[11px] text-slate-400 mt-1">Harga disetujui & siap cetak SPP</p>
    </div>
</div>
