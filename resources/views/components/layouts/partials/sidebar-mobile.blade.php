<!-- Mobile Sidebar Drawer Overlay -->
<div x-show="mobileMenuOpen" 
     x-transition:enter="transition ease-out duration-200" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition ease-in duration-150" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0" 
     @click="mobileMenuOpen = false" 
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 md:hidden"></div>

<!-- Mobile Sidebar Drawer Content -->
<aside x-show="mobileMenuOpen" 
       x-transition:enter="transition ease-out duration-300 transform" 
       x-transition:enter-start="-translate-x-full" 
       x-transition:enter-end="translate-x-0" 
       x-transition:leave="transition ease-in duration-200 transform" 
       x-transition:leave-start="translate-x-0" 
       x-transition:leave-end="-translate-x-full" 
       class="fixed inset-y-0 left-0 w-72 bg-slate-900 text-slate-300 z-50 flex flex-col justify-between md:hidden shadow-2xl overflow-y-auto">
    <div>
        <div class="h-16 px-6 flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 font-bold text-lg shadow-sm">
                    A
                </div>
                <div>
                    <h2 class="font-bold text-white text-xs tracking-wide">ATLANTIK PERKASA</h2>
                    <p class="text-[10px] text-emerald-400 font-bold tracking-wider uppercase">PT. Atlantik Perkasa Abadi</p>
                </div>
            </div>
            <button @click="mobileMenuOpen = false" class="text-slate-400 hover:text-white p-2 text-lg focus:outline-none" aria-label="Tutup Menu">✕</button>
        </div>

        <nav class="p-4 space-y-1 text-sm">
            @include('components.layouts.partials.nav-links')
        </nav>
    </div>

    <!-- Mobile Footer with Active User, Role Badge, Logout & Demo Switcher -->
    <div class="p-4 border-t border-slate-800 space-y-3">
        <div class="bg-slate-800/80 rounded-xl p-3 border border-slate-700/60">
            <p class="text-[10px] uppercase tracking-wider font-semibold text-slate-400">Pengguna Aktif</p>
            <p class="font-bold text-white text-xs truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
            
            <div class="mt-1.5 flex items-center justify-between">
                @php $role = auth()->user()->role ?? 'guest'; @endphp
                @if($role === 'founder')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-900/60 text-purple-300 border border-purple-500/30">FOUNDER</span>
                @elseif($role === 'pengawas_project')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-900/60 text-amber-300 border border-amber-500/30">PENGAWAS</span>
                @elseif($role === 'supervisor')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-900/60 text-cyan-300 border border-cyan-500/30">SUPERVISOR</span>
                @elseif($role === 'finance')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-900/60 text-emerald-300 border border-emerald-500/30">FINANCE</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-900/60 text-blue-300 border border-blue-500/30">MARKETING</span>
                @endif
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left text-xs font-semibold text-rose-400 hover:text-rose-300 flex items-center gap-2 py-1 px-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Keluar System</span>
            </button>
        </form>
    </div>
</aside>
