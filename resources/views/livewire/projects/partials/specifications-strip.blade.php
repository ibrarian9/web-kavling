<!-- Project Specifications & Workers Strip -->
<x-card padding="p-4 sm:p-5" class="bg-white border border-slate-200/80 shadow-2xs rounded-2xl sm:rounded-3xl">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs flex-1 w-full">
            <div>
                <span class="text-slate-400 block text-[10px] sm:text-[11px] uppercase font-bold tracking-wider">Luas Standar</span>
                <span class="font-mono font-black text-sm sm:text-base text-emerald-600 whitespace-nowrap mt-0.5 block">{{ number_format($project->standard_land_area, 0, ',', '.') }} m²</span>
            </div>
            @if(auth()->user()->canViewSalesPrices())
                <div>
                    <span class="text-slate-400 block text-[10px] sm:text-[11px] uppercase font-bold tracking-wider">Harga Dasar Standar</span>
                    <span class="font-mono font-black text-sm sm:text-base text-emerald-600 whitespace-nowrap mt-0.5 block">Rp {{ number_format($project->base_price, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] sm:text-[11px] uppercase font-bold tracking-wider">Kelebihan / m²</span>
                    <span class="font-mono font-black text-sm sm:text-base text-emerald-600 whitespace-nowrap mt-0.5 block">Rp {{ number_format($project->excess_price_per_sqm, 0, ',', '.') }}</span>
                </div>
            @endif
            <div>
                <span class="text-slate-400 block text-[10px] sm:text-[11px] uppercase font-bold tracking-wider">Total Unit Kavling</span>
                <span class="font-mono font-black text-sm sm:text-base text-emerald-600 whitespace-nowrap mt-0.5 block">{{ $totalUnits }} Unit</span>
            </div>
        </div>

        <div class="text-xs border-t md:border-t-0 md:border-l border-slate-100 pt-3 md:pt-0 md:pl-4 min-w-[200px] w-full md:w-auto">
            <span class="text-slate-400 block text-[10px] sm:text-[11px] uppercase font-bold tracking-wider mb-1.5">Pengawas Proyek</span>
            @php
                $projActivePengawas = $project->assignments->where('status', 'active')->filter(fn($a) => $a->user_id !== null || $a->user !== null);
            @endphp
            <div class="flex flex-wrap items-center gap-1.5">
                @forelse($projActivePengawas as $pa)
                    @if($pa->user)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-50 text-purple-800 border border-purple-200/80 shadow-2xs" title="Pengawas Project: {{ $pa->user->name }}">
                            <svg class="w-3.5 h-3.5 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>{{ $pa->user->name }}</span>
                        </span>
                    @endif
                @empty
                    <span class="text-slate-400 text-xs italic">Belum ada pengawas ditugaskan</span>
                @endforelse
            </div>
        </div>
    </div>
</x-card>
