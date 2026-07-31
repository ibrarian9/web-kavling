<!-- Project Specifications & Workers Strip -->
<div class="card-clean p-4 bg-slate-900 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
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

    <div class="text-xs border-t md:border-t-0 md:border-l border-slate-800 pt-2 md:pt-0 md:pl-4">
        <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1">Mandor / Pekerja Bertugas</span>
        @php
            $projActiveAssignments = $project->assignments->where('status', 'active');
            $firstProjWorker = $projActiveAssignments->first();
            $projWorkerCount = $projActiveAssignments->count();
        @endphp
        <div class="flex items-center gap-1">
            @if($firstProjWorker && $firstProjWorker->worker)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-white border border-emerald-500/30 max-w-[200px] truncate" title="{{ $firstProjWorker->worker->name }} ({{ ucfirst($firstProjWorker->worker->type) }})">
                    <span class="truncate">{{ $firstProjWorker->worker->name }} ({{ ucfirst($firstProjWorker->worker->type) }})</span>
                    @if($projWorkerCount > 1)
                        <span class="font-bold shrink-0 text-emerald-200">...</span>
                    @endif
                </span>
            @else
                <span class="text-slate-500 text-[11px] italic">Belum ada pekerja ditugaskan</span>
            @endif
        </div>
    </div>
</div>
