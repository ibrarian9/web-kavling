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
                $cardStyle = 'bg-indigo-100/80 hover:bg-indigo-200/90 border-2 border-indigo-300 hover:border-indigo-500 text-indigo-950 shadow-xs';
                $badgeBg = 'bg-indigo-600 text-white border-indigo-600';
                $dotColor = 'bg-white';
                $statusLabel = 'Fasum';
                $areaBadge = 'bg-white/90 text-indigo-900 border-indigo-200/80';
                $codeColor = 'text-indigo-950 group-hover:text-indigo-700';
                $descColor = 'text-indigo-800/80';
                $priceColor = 'text-indigo-950';
                $footerBorder = 'border-indigo-200/80';
                $actionIcon = 'bg-white/90 text-indigo-700 border-indigo-200/80 group-hover:bg-indigo-600 group-hover:text-white';
            } elseif ($isSold) {
                $cardStyle = 'bg-rose-100/80 hover:bg-rose-200/90 border-2 border-rose-300 hover:border-rose-500 text-rose-950 shadow-xs';
                $badgeBg = 'bg-rose-600 text-white border-rose-600';
                $dotColor = 'bg-white';
                $statusLabel = 'Terjual';
                $areaBadge = 'bg-white/90 text-rose-900 border-rose-200/80';
                $codeColor = 'text-rose-950 group-hover:text-rose-700';
                $descColor = 'text-rose-800/80';
                $priceColor = 'text-rose-950';
                $footerBorder = 'border-rose-200/80';
                $actionIcon = 'bg-white/90 text-rose-700 border-rose-200/80 group-hover:bg-rose-600 group-hover:text-white';
            } elseif ($isBooked) {
                $cardStyle = 'bg-amber-100/80 hover:bg-amber-200/90 border-2 border-amber-300 hover:border-amber-500 text-amber-950 shadow-xs';
                $badgeBg = 'bg-amber-500 text-white border-amber-600';
                $dotColor = 'bg-white';
                $statusLabel = 'Booked';
                $areaBadge = 'bg-white/90 text-amber-900 border-amber-200/80';
                $codeColor = 'text-amber-950 group-hover:text-amber-700';
                $descColor = 'text-amber-800/80';
                $priceColor = 'text-amber-950';
                $footerBorder = 'border-amber-200/80';
                $actionIcon = 'bg-white/90 text-amber-700 border-amber-200/80 group-hover:bg-amber-600 group-hover:text-white';
            } else {
                $cardStyle = 'bg-emerald-100/80 hover:bg-emerald-200/90 border-2 border-emerald-300 hover:border-emerald-500 text-emerald-950 shadow-xs';
                $badgeBg = 'bg-emerald-600 text-white border-emerald-600';
                $dotColor = 'bg-white animate-pulse';
                $statusLabel = 'Tersedia';
                $areaBadge = 'bg-white/90 text-emerald-900 border-emerald-200/80';
                $codeColor = 'text-emerald-950 group-hover:text-emerald-700';
                $descColor = 'text-emerald-800/80';
                $priceColor = 'text-emerald-950';
                $footerBorder = 'border-emerald-200/80';
                $actionIcon = 'bg-white/90 text-emerald-700 border-emerald-200/80 group-hover:bg-emerald-600 group-hover:text-white';
            }
        @endphp

        @if($interactiveModal)
            <div wire:click="{{ $modalAction }}({{ $u->id }})" 
                 class="rounded-2xl {{ $cardStyle }} p-4 flex flex-col justify-between transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md cursor-pointer group relative overflow-hidden min-h-[145px]">
        @else
            <a href="{{ route('units.show', $u->id) }}" 
               wire:navigate.hover
               class="rounded-2xl {{ $cardStyle }} p-4 flex flex-col justify-between transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md cursor-pointer group relative overflow-hidden min-h-[145px]">
        @endif
            
            <!-- Top Ribbon Header -->
            <div class="flex items-center justify-between gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border {{ $badgeBg }} shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                    <span>{{ $statusLabel }}</span>
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono text-[11px] font-bold border whitespace-nowrap {{ $areaBadge }}">
                    {{ (float)$u->land_area }} m²
                </span>
            </div>

            <!-- Unit Code & Category -->
            <div class="my-2.5">
                <div class="flex items-center justify-between gap-1 flex-wrap">
                    <p class="text-lg sm:text-xl font-black font-mono tracking-tight {{ $codeColor }} transition-colors">
                        Unit {{ $u->code }}
                    </p>
                    @if(auth()->user()->canViewUnitExpenses() && $u->has_expenses)
                        <span class="inline-flex items-center gap-1 text-[9px] font-black px-1.5 py-0.5 rounded bg-amber-200/90 text-amber-950 border border-amber-400/80 shadow-2xs" title="Terdapat catatan biaya unit">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-700 animate-pulse"></span>
                            <span>Ada Biaya</span>
                        </span>
                    @endif
                </div>
                <div class="text-[11px] {{ $descColor }} font-semibold capitalize mt-0.5">
                    @if($showProjectName && $u->project)
                        <p class="truncate font-bold">{{ $u->project->name }}</p>
                    @endif
                    <p class="truncate">
                        {{ $u->category === 'rumah' ? ($u->building_area ? 'Rumah Tipe ' . (int)$u->building_area : 'Rumah') : 'Kavling Standar' }}
                        @if($u->land_width && $u->land_length)
                            <span class="font-mono opacity-80">({{ (float)$u->land_width }}×{{ (float)$u->land_length }}m)</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Bottom Tag -->
            <div class="pt-2 border-t {{ $footerBorder }} flex items-center justify-between text-xs">
                @if(auth()->user()->canViewSalesPrices())
                    <div class="min-w-0">
                        <span class="font-mono font-black {{ $priceColor }} block truncate text-xs sm:text-sm">
                            Rp {{ number_format($u->final_selling_price ?? $u->hpp ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-6 h-6 rounded-lg {{ $actionIcon }} border flex items-center justify-center transition-colors shrink-0 shadow-2xs">
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                @else
                    <span class="font-mono text-[11px] font-bold {{ $descColor }} truncate">
                        {{ (float)$u->land_width }}m × {{ (float)$u->land_length }}m
                    </span>
                    <span class="text-[11px] font-extrabold {{ $codeColor }} group-hover:underline flex items-center gap-0.5 shrink-0">
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
