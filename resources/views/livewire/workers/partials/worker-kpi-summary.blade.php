<!-- Summary KPI Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
    <div class="kpi-card-blue">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pekerja Terdaftar</span>
            <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">{{ $workers->total() }} Orang</p>
        <p class="text-[11px] text-slate-400 mt-1">Direktori mandor, tukang & kontraktor</p>
    </div>

    <div class="kpi-card-amber">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Mandor & Kontraktor</span>
            <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-amber-600 font-mono mt-2">
            {{ \App\Models\Worker::whereIn('type', ['mandor', 'kontraktor'])->count() }} Orang
        </p>
        <p class="text-[11px] text-slate-400 mt-1">Penanggung jawab pekerjaan</p>
    </div>

    <div class="kpi-card-emerald">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Tukang Lapangan</span>
            <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">
            {{ \App\Models\Worker::where('type', 'tukang')->count() }} Orang
        </p>
        <p class="text-[11px] text-slate-400 mt-1">Tenaga ahli & pembantu tukang</p>
    </div>
</div>
