<!-- Physical Specifications Card -->
<x-card padding="p-5" :title="$unit->category === 'infrastruktur' ? 'Spesifikasi & Luas Pengerjaan Fasum' : 'Spesifikasi Fisik & Dimensi'">
    <div class="space-y-2.5 text-xs">
        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
            <span class="text-slate-500 font-medium">Proyek Properti:</span>
            <span class="font-bold text-slate-800 text-right">{{ $unit->project->name }}</span>
        </div>

        @if($unit->category === 'infrastruktur')
            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                <span class="text-slate-500 font-medium">Jenis Fasum / Infrastruktur:</span>
                <span class="font-bold text-sky-800 uppercase">{{ $unit->type }}</span>
            </div>
            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                <span class="text-slate-500 font-medium">Luas Pengerjaan:</span>
                <span class="font-mono font-extrabold text-sky-900 text-xs">{{ number_format($unit->land_area, 0, ',', '.') }} m²</span>
            </div>

            @if($unit->specifications)
                <div class="pt-2 mt-2 border-t border-sky-100 space-y-1.5">
                    <p class="font-extrabold text-sky-900 text-[11px] uppercase tracking-wider">Catatan Teknis Fasum:</p>
                    <div class="text-slate-700 text-xs bg-sky-50/60 p-2.5 rounded-xl border border-sky-200/70 leading-relaxed">
                        {{ $unit->specifications }}
                    </div>
                </div>
            @endif
        @else
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

            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                <span class="text-slate-500 font-medium">Harga Jual Standar:</span>
                <span class="font-mono font-bold text-slate-800">Rp {{ number_format($unit->project->base_price ?? $unit->base_price, 0, ',', '.') }}</span>
            </div>

            @if($unit->excess_land_area > 0)
                <div class="flex items-center justify-between py-2 bg-amber-50/90 px-3 rounded-xl border border-amber-200/80 text-amber-900 font-semibold shadow-2xs">
                    <span class="text-[11px]">Kelebihan Tanah:</span>
                    <span class="font-mono font-extrabold text-xs text-amber-800">+{{ number_format($unit->excess_land_area, 0, ',', '.') }} m² (+Rp {{ number_format($unit->excess_cost, 0, ',', '.') }})</span>
                </div>
            @endif

            <div class="flex items-center justify-between py-1.5 border-b border-slate-50 font-bold">
                <span class="text-slate-700">Harga Total Unit:</span>
                <span class="font-mono font-extrabold text-emerald-700 text-xs">Rp {{ number_format($unit->total_price, 0, ',', '.') }}</span>
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
</x-card>
