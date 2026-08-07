<div>
    <!-- Command Palette Trigger Button (Header Search Bar) -->
    <button type="button" 
            @click="$wire.openModal()" 
            class="hidden sm:flex items-center justify-between w-64 lg:w-80 px-3.5 py-1.5 bg-slate-100/80 hover:bg-slate-100 text-slate-500 rounded-xl border border-slate-200/80 text-xs transition duration-150 shadow-2xs group">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span class="text-slate-400 group-hover:text-slate-600">Cari unit, proyek, menu...</span>
        </div>
        <kbd class="px-1.5 py-0.5 text-[10px] font-mono font-bold text-slate-400 bg-white border border-slate-200 rounded-md shadow-2xs">Ctrl K</kbd>
    </button>

    <!-- Mobile Search Button -->
    <button type="button" 
            @click="$wire.openModal()" 
            class="sm:hidden p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition" 
            title="Cari Sistem (Ctrl + K)">
        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    </button>

    <!-- Command Palette Modal Backdrop & Dialog -->
    <div x-data="{ 
             isOpen: @entangle('isOpen'),
             initKeybindings() {
                 window.addEventListener('keydown', (e) => {
                     if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                         e.preventDefault();
                         $wire.openModal();
                     }
                     if (e.key === 'Escape' && this.isOpen) {
                         $wire.closeModal();
                     }
                 });
             }
         }"
         x-init="initKeybindings()"
         x-show="isOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20 flex items-start justify-center" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Dark Overlay (Crisp Non-Blur Backdrop) -->
        <div x-show="isOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="$wire.closeModal()"
             class="fixed inset-0 bg-slate-900/70 transition-opacity"></div>

        <!-- Modal Container (Sharp & Crystal Clear Text) -->
        <div x-show="isOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative z-10 w-full max-w-2xl transform divide-y divide-slate-100 overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/10 transition-all text-slate-800">
            
            <!-- Input Field Bar -->
            <div class="relative flex items-center px-4 py-3 bg-slate-50/50">
                <svg class="pointer-events-none w-5 h-5 text-emerald-600 shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" 
                       wire:model.live.debounce.150ms="query" 
                       x-ref="searchInput"
                       x-effect="if (isOpen) setTimeout(() => $refs.searchInput.focus(), 100)"
                       class="w-full bg-transparent text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none" 
                       placeholder="Ketik kode unit (A-01), nama proyek, konsumen, mandor, atau menu..." />
                <button type="button" @click="$wire.closeModal()" class="text-xs font-semibold text-slate-400 hover:text-slate-700 bg-slate-200/60 px-2 py-1 rounded-md">
                    ESC
                </button>
            </div>

            <!-- Search Results Content Area -->
            <div class="max-h-[60vh] overflow-y-auto p-4 space-y-4 divide-y divide-slate-100 text-xs">
                
                <!-- Section 1: Units Results -->
                @if(!empty($results['units']) && count($results['units']) > 0)
                    <div class="space-y-2 pt-2 first:pt-0">
                        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>Unit Kavling & Rumah ({{ count($results['units']) }})</span>
                        </div>
                        <div class="grid grid-cols-1 gap-1.5">
                            @foreach($results['units'] as $u)
                                <a href="{{ route('units.show', $u->id) }}" 
                                   wire:navigate.hover 
                                   @click="$wire.closeModal()" 
                                   class="flex items-center justify-between p-2.5 rounded-xl hover:bg-emerald-50/70 border border-transparent hover:border-emerald-200/60 transition group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ $u->code }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 group-hover:text-emerald-700">Unit {{ $u->code }} - {{ $u->project->name }}</p>
                                            <p class="text-[11px] text-slate-500 font-mono">
                                                Tipe {{ $u->type }} | Kategori: {{ ucfirst($u->category ?? 'Kavling') }} | Harga: Rp {{ number_format($u->final_selling_price ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $u->status === 'terjual' ? 'bg-rose-100 text-rose-700' : ($u->status === 'booking' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                        {{ $u->status }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Section 2: Projects Results -->
                @if(!empty($results['projects']) && count($results['projects']) > 0)
                    <div class="space-y-2 pt-3">
                        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            <span>Proyek Properti</span>
                        </div>
                        <div class="grid grid-cols-1 gap-1.5">
                            @foreach($results['projects'] as $p)
                                <a href="{{ route('projects.show', $p->id) }}" 
                                   wire:navigate.hover 
                                   @click="$wire.closeModal()" 
                                   class="flex items-center justify-between p-2.5 rounded-xl hover:bg-blue-50/70 border border-transparent hover:border-blue-200/60 transition group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                                            🏢
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 group-hover:text-blue-700">{{ $p->name }}</p>
                                            <p class="text-[11px] text-slate-500 font-mono">{{ $p->location }} | ID: #{{ $p->id }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-blue-600 font-bold group-hover:underline">Buka Proyek ➔</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Section 3: Bookings / Customers Results -->
                @if(!empty($results['bookings']) && count($results['bookings']) > 0)
                    <div class="space-y-2 pt-3">
                        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Booking Fee & Konsumen</span>
                        </div>
                        <div class="grid grid-cols-1 gap-1.5">
                            @foreach($results['bookings'] as $b)
                                <a href="{{ route('bookings.index') }}?search={{ urlencode($b->buyer_name) }}" 
                                   wire:navigate.hover 
                                   @click="$wire.closeModal()" 
                                   class="flex items-center justify-between p-2.5 rounded-xl hover:bg-amber-50/70 border border-transparent hover:border-amber-200/60 transition group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 font-bold text-xs flex items-center justify-center shrink-0">
                                            👤
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 group-hover:text-amber-700">{{ $b->buyer_name }}</p>
                                            <p class="text-[11px] text-slate-500 font-mono">Unit: {{ $b->unit->code ?? '-' }} | Telp: {{ $b->buyer_phone ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <span class="font-mono text-emerald-700 font-bold">Rp {{ number_format($b->booking_amount, 0, ',', '.') }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Section 4: Workers / Mandor Results -->
                @if(!empty($results['workers']) && count($results['workers']) > 0)
                    <div class="space-y-2 pt-3">
                        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                            <span>Pekerja Lapangan & Mandor</span>
                        </div>
                        <div class="grid grid-cols-1 gap-1.5">
                            @foreach($results['workers'] as $w)
                                <a href="{{ route('workers.index') }}?search={{ urlencode($w->name) }}" 
                                   wire:navigate.hover 
                                   @click="$wire.closeModal()" 
                                   class="flex items-center justify-between p-2.5 rounded-xl hover:bg-teal-50/70 border border-transparent hover:border-teal-200/60 transition group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 font-bold text-xs flex items-center justify-center shrink-0">
                                            👷
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 group-hover:text-teal-700">{{ $w->name }} ({{ ucfirst($w->type) }})</p>
                                            <p class="text-[11px] text-slate-500 font-mono">{{ $w->specialty }} | Telp: {{ $w->phone }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-teal-600 font-bold group-hover:underline">Lihat Pekerja ➔</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Section 5: Menu Navigasi Links -->
                @if(!empty($results['menus']) && count($results['menus']) > 0)
                    <div class="space-y-2 pt-3">
                        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                            <span>Menu Aplikasi</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                            @foreach($results['menus'] as $m)
                                <a href="{{ $m['url'] }}" 
                                   wire:navigate.hover 
                                   @click="$wire.closeModal()" 
                                   class="flex items-center gap-2.5 p-2 rounded-xl hover:bg-purple-50/70 border border-slate-100 hover:border-purple-200/60 transition group">
                                    <div class="p-1.5 rounded-lg bg-slate-100 text-slate-600 group-hover:bg-purple-100 group-hover:text-purple-700 transition">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                    </div>
                                    <span class="font-semibold text-slate-800 group-hover:text-purple-700 truncate">{{ $m['title'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Empty State -->
                @if(empty($results['units']) && empty($results['projects']) && empty($results['bookings']) && empty($results['workers']) && empty($results['menus']))
                    <div class="text-center py-10 space-y-2 text-slate-400">
                        <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <p class="font-semibold text-slate-600">Tidak ada hasil pencarian untuk "{{ $query }}"</p>
                        <p class="text-xs text-slate-400">Coba cari dengan kata kunci lain seperti kode unit A-01, nama konsumen, atau nama proyek.</p>
                    </div>
                @endif

            </div>

            <!-- Modal Footer Controls Tips -->
            <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1"><kbd class="px-1 py-0.5 bg-white border rounded font-mono text-[9px]">ESC</kbd> untuk tutup</span>
                    <span class="flex items-center gap-1"><kbd class="px-1 py-0.5 bg-white border rounded font-mono text-[9px]">Ctrl K</kbd> untuk buka kapan saja</span>
                </div>
                <span class="font-bold text-emerald-600">SIM Kavling Search</span>
            </div>
        </div>
    </div>
</div>
