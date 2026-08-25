@props([
    'units' => [],
    'interactiveModal' => false,
    'modalAction' => 'openSiteplanUnitModal',
    'showProjectName' => false,
    'emptyMessage' => 'Tidak ada unit yang sesuai dengan filter site plan',
])

<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3.5 sm:gap-4">
    @forelse($units as $u)
        @php
            $isInfra = ($u->category === 'infrastruktur' || $u->type === 'infrastruktur' || $u->status === 'infrastruktur');
            $isSold = in_array($u->status, ['terjual', 'disetujui']);
            $isBooked = in_array($u->status, ['booked', 'menunggu_persetujuan']);
            $isAvailable = in_array($u->status, ['tersedia', 'draft']);

            if ($isInfra) {
                $cardBorder = 'border-indigo-200/80 hover:border-indigo-400 hover:ring-2 hover:ring-indigo-500/10 bg-indigo-50/20';
                $badgeBg = 'bg-indigo-50 text-indigo-700 border-indigo-200/80';
                $dotColor = 'bg-indigo-500';
                $statusLabel = 'Fasum';
            } elseif ($isSold) {
                $cardBorder = 'border-rose-200/80 hover:border-rose-400 hover:ring-2 hover:ring-rose-500/10 bg-rose-50/20';
                $badgeBg = 'bg-rose-50 text-rose-700 border-rose-200/80';
                $dotColor = 'bg-rose-500';
                $statusLabel = 'Terjual';
            } elseif ($isBooked) {
                $cardBorder = 'border-amber-200/80 hover:border-amber-400 hover:ring-2 hover:ring-amber-500/10 bg-amber-50/20';
                $badgeBg = 'bg-amber-50 text-amber-700 border-amber-200/80';
                $dotColor = 'bg-amber-500';
                $statusLabel = 'Booked';
            } else {
                $cardBorder = 'border-emerald-200/80 hover:border-emerald-400 hover:ring-2 hover:ring-emerald-500/10 bg-emerald-50/20';
                $badgeBg = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
                $dotColor = 'bg-emerald-500 animate-pulse';
                $statusLabel = 'Tersedia';
            }
        @endphp

        @if($interactiveModal)
            <div wire:click="{{ $modalAction }}({{ $u->id }})" 
                 class="bg-white rounded-2xl border {{ $cardBorder }} p-4 flex flex-col justify-between transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md cursor-pointer group relative overflow-hidden shadow-2xs min-h-[140px]">
        @else
            <a href="{{ route('units.show', $u->id) }}" 
               wire:navigate.hover
               class="bg-white rounded-2xl border {{ $cardBorder }} p-4 flex flex-col justify-between transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md cursor-pointer group relative overflow-hidden shadow-2xs min-h-[140px]">
        @endif
            
            <!-- Top Ribbon Header -->
            <div class="flex items-center justify-between gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $badgeBg }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                    <span>{{ $statusLabel }}</span>
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100/80 text-slate-600 font-mono text-[11px] font-bold border border-slate-200/60 whitespace-nowrap">
                    {{ (float)$u->land_area }} m²
                </span>
            </div>

            <!-- Unit Code & Category -->
            <div class="my-2.5">
                <p class="text-lg sm:text-xl font-black font-mono tracking-tight text-slate-900 group-hover:text-emerald-600 transition-colors">
                    Unit {{ $u->code }}
                </p>
                <div class="text-[11px] text-slate-500 font-medium capitalize mt-0.5">
                    @if($showProjectName && $u->project)
                        <p class="truncate text-slate-700 font-semibold">{{ $u->project->name }}</p>
                    @endif
                    <p class="truncate text-slate-500">
                        {{ $u->category === 'rumah' ? ($u->building_area ? 'Rumah Tipe ' . (int)$u->building_area : 'Rumah') : 'Kavling Standar' }}
                        @if($u->land_width && $u->land_length)
                            <span class="font-mono text-slate-400">({{ (float)$u->land_width }}×{{ (float)$u->land_length }}m)</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Bottom Tag -->
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                @if(auth()->user()->canViewSalesPrices())
                    <div class="min-w-0">
                        <span class="font-mono font-bold text-slate-900 block truncate">
                            Rp {{ number_format($u->final_selling_price ?? $u->hpp ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-6 h-6 rounded-lg bg-slate-50 group-hover:bg-emerald-50 text-slate-400 group-hover:text-emerald-600 flex items-center justify-center transition-colors shrink-0">
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                @else
                    <span class="font-mono text-[11px] font-bold text-slate-600 truncate">
                        {{ (float)$u->land_width }}m × {{ (float)$u->land_length }}m
                    </span>
                    <span class="text-[11px] font-bold text-emerald-600 group-hover:underline flex items-center gap-0.5 shrink-0">
                        Detail &rarr;
                    </span>
                @endif
            </div>

        @if($interactiveModal)
            </div>
        @else
            </a>
        @endif
    @empty
        <div class="col-span-full py-12 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
            <p class="font-bold text-slate-600">{{ $emptyMessage }}</p>
            <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau filter status unit.</p>
        </div>
    @endforelse
</div>
