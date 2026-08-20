<!-- Project Specifications & Workers Strip -->
<x-card padding="p-4" class="bg-slate-900 text-white">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 text-xs">
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Harga Beli Lahan</span>
                <span class="font-mono font-bold text-sm text-purple-400">
                    @if($project->total_project_price > 0)
                        Rp {{ number_format($project->total_project_price, 0, ',', '.') }}
                    @else
                        <span class="text-slate-400 font-normal italic">-</span>
                    @endif
                </span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Luas Standar</span>
                <span class="font-mono font-bold text-sm text-emerald-400">{{ number_format($project->standard_land_area, 0, ',', '.') }} m²</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Harga Dasar Standar</span>
                <span class="font-mono font-bold text-sm text-emerald-400">Rp {{ number_format($project->base_price, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Kelebihan Tanah / m²</span>
                <span class="font-mono font-bold text-sm text-emerald-400">Rp {{ number_format($project->excess_price_per_sqm, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Total Unit Kavling</span>
                <span class="font-mono font-bold text-sm text-emerald-400">{{ $totalUnits }} Unit</span>
            </div>
        </div>

        <div class="text-xs border-t md:border-t-0 md:border-l border-slate-800 pt-2 md:pt-0 md:pl-4 min-w-[200px]">
            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1.5">Pengawas & Mandor Bertugas</span>
            @php
                $projActiveAssignments = $project->assignments->where('status', 'active');
            @endphp
            <div class="flex flex-wrap items-center gap-1.5">
                @forelse($projActiveAssignments as $pa)
                    @if($pa->user)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-purple-900/80 text-purple-200 border border-purple-700/80 shadow-2xs" title="Pengawas Project: {{ $pa->user->name }}">
                            <svg class="w-3.5 h-3.5 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>{{ $pa->user->name }} (Pengawas)</span>
                        </span>
                    @elseif($pa->worker)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-emerald-900/80 text-emerald-200 border border-emerald-700/80 shadow-2xs" title="{{ ucfirst($pa->worker->type) }}: {{ $pa->worker->name }}">
                            <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>{{ $pa->worker->name }} ({{ ucfirst($pa->worker->type) }})</span>
                        </span>
                    @endif
                @empty
                    <span class="text-slate-500 text-[11px] italic">Belum ada pekerja / pengawas ditugaskan</span>
                @endforelse
            </div>
        </div>
    </div>
</x-card>
