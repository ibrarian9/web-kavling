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
        <div class="h-16 px-5 flex items-center justify-between border-b border-slate-800/80 bg-slate-950/40">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-black text-lg flex items-center justify-center shadow-md shadow-emerald-900/30 ring-2 ring-emerald-400/20">
                    A
                </div>
                <div>
                    <h2 class="font-extrabold text-white text-xs tracking-wider uppercase">ATLANTIK PERKASA</h2>
                    <p class="text-[9px] text-emerald-400 font-bold tracking-widest uppercase">SIM Proyek Kavling</p>
                </div>
            </div>
            <button @click="mobileMenuOpen = false" class="text-slate-400 hover:text-white p-2 text-lg focus:outline-none" aria-label="Tutup Menu">✕</button>
        </div>

        <nav class="p-3.5 space-y-1 text-sm">
            @include('components.layouts.partials.nav-links')
        </nav>
    </div>

    <!-- Mobile Footer with Active User, Role Badge & Logout -->
    <div class="p-3.5 border-t border-slate-800/80 bg-slate-950/50 space-y-2.5">
        <div class="bg-slate-800/60 rounded-xl p-3 border border-slate-700/50 backdrop-blur-xs flex items-center justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[9px] uppercase tracking-widest font-extrabold text-slate-400">Pengguna Aktif</p>
                <p class="font-extrabold text-white text-xs truncate mt-0.5">{{ auth()->user()->name ?? 'Guest' }}</p>
            </div>
            
            <div class="shrink-0">
                @php $role = auth()->user()->role ?? 'guest'; @endphp
                @if($role === 'founder')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-purple-500/20 text-purple-300 border border-purple-500/40">FOUNDER</span>
                @elseif($role === 'pengawas_project')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-amber-500/20 text-amber-300 border border-amber-500/40">PENGAWAS</span>
                @elseif($role === 'supervisor')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-cyan-500/20 text-cyan-300 border border-cyan-500/40">SUPERVISOR</span>
                @elseif($role === 'finance')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">FINANCE</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-blue-500/20 text-blue-300 border border-blue-500/40">MARKETING</span>
                @endif
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left text-xs font-bold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-lg transition-colors flex items-center gap-2 py-2 px-2.5">
                <svg class="w-4 h-4 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Keluar dari Sistem</span>
            </button>
        </form>
    </div>
</aside>
