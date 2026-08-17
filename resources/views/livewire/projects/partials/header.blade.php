<!-- Top Navigation & Header -->
<x-card padding="p-5">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('projects.index') }}" class="hover:text-emerald-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Daftar Proyek</span>
                </a>
                <span>/</span>
                <span class="text-slate-600 font-semibold">Dashboard Proyek</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">{{ $project->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ $project->location }}
            </p>
        </div>

        <div class="flex items-center gap-2 whitespace-nowrap overflow-x-auto flex-nowrap shrink-0">
            <x-button variant="secondary" size="sm" href="{{ route('units.index', ['project_id' => $project->id]) }}">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                <span>Stok Unit</span>
            </x-button>

            @if(auth()->user()->isAdminOrFounder())
                <x-button variant="purple" size="sm" wire:click="openLegacyModal" title="Catat Penjualan Unit Masa Lalu / Sebelum System">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Penjualan Lalu</span>
                </x-button>
            @endif

            @if(!auth()->user()->isPengawasProject() && !auth()->user()->isMarketing())
                <x-button variant="primary" size="sm" href="{{ route('cashflow.index') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Arus Kas</span>
                </x-button>
            @endif
        </div>
    </div>
</x-card>
