<!-- Physical Specifications Card -->
<div class="card-clean p-5 space-y-4">
    <h3 class="font-extrabold text-slate-900 text-sm border-b border-slate-100 pb-3 flex items-center gap-2">
        <div class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <span>Spesifikasi Fisik & Dimensi</span>
    </h3>

    <div class="space-y-2.5 text-xs">
        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
            <span class="text-slate-500 font-medium">Proyek Properti:</span>
            <span class="font-bold text-slate-800 text-right">{{ $unit->project->name }}</span>
        </div>
        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
            <span class="text-slate-500 font-medium">Dimensi Tanah (P &times; L):</span>
            <span class="font-mono font-bold text-slate-800">{{ $unit->land_length }}m &times; {{ $unit->land_width }}m</span>
        </div>
        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
            <span class="text-slate-500 font-medium">Luas Tanah Aktual:</span>
            <span class="font-mono font-extrabold text-slate-900 text-xs">{{ number_format($unit->land_area, 0, ',', '.') }} m²</span>
        </div>
        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
            <span class="text-slate-500 font-medium">Standar Proyek:</span>
            <span class="font-mono text-slate-700">{{ number_format($unit->project->standard_land_area, 0, ',', '.') }} m²</span>
        </div>

        @if($unit->excess_land_area > 0)
            <div class="flex items-center justify-between py-2 bg-amber-50/90 px-3 rounded-xl border border-amber-200/80 text-amber-900 font-semibold shadow-2xs">
                <span class="text-[11px]">Kelebihan Tanah:</span>
                <span class="font-mono font-extrabold text-xs text-amber-800">+{{ number_format($unit->excess_land_area, 0, ',', '.') }} m² (+Rp {{ number_format($unit->excess_cost, 0, ',', '.') }})</span>
            </div>
        @endif

        @if($unit->category === 'rumah')
            <div class="pt-2 mt-2 border-t border-purple-100 space-y-2">
                <p class="font-extrabold text-purple-900 text-[11px] uppercase tracking-wider">Detail Bangunan Rumah:</p>
                <div class="flex items-center justify-between py-1.5 border-b border-purple-50">
                    <span class="text-slate-500 font-medium">Luas Bangunan:</span>
                    <span class="font-mono font-extrabold text-purple-900 text-xs">{{ number_format($unit->building_area, 0, ',', '.') }} m²</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-purple-50">
                    <span class="text-slate-500 font-medium">Jumlah Lantai:</span>
                    <span class="font-bold text-slate-800">{{ $unit->floors_count ?? 1 }} Lantai</span>
                </div>
                @if($unit->specifications)
                    <div class="pt-1.5 text-slate-600 text-[11px] italic bg-purple-50/50 p-2.5 rounded-xl border border-purple-100">
                        "{{ $unit->specifications }}"
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
