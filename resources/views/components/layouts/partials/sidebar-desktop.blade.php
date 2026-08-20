<!-- Desktop Sidebar Navigation (Sticky & Mini/Expanded Collapsible) -->
<aside :class="sidebarExpanded ? 'w-64' : 'w-20'"
       class="sticky top-0 h-screen bg-slate-900 border-r border-slate-800/80 text-slate-300 flex flex-col justify-between shrink-0 hidden lg:flex shadow-xl z-30 transition-[width] duration-200 ease-in-out select-none">
    
    <!-- Brand Top Header (Fixed at top of sidebar) -->
    <div class="h-16 px-4 flex items-center justify-between border-b border-slate-800/80 bg-slate-950/40 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0 group" :class="!sidebarExpanded && 'mx-auto'">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-black text-lg flex items-center justify-center shadow-md shadow-emerald-900/30 ring-2 ring-emerald-400/20 shrink-0 transition-transform group-hover:scale-105">
                A
            </div>
            <div x-show="sidebarExpanded" class="min-w-0">
                <h2 class="font-extrabold text-white text-xs tracking-wider uppercase truncate">ATLANTIK PERKASA</h2>
                <p class="text-[9px] text-emerald-400 font-bold tracking-widest uppercase truncate">SIM Proyek Kavling</p>
            </div>
        </a>

        <!-- Quick Toggle Button on Desktop Sidebar -->
        <button x-show="sidebarExpanded" 
                @click="toggleSidebar()" 
                class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800/80 transition focus:outline-none shrink-0" 
                title="Ciutkan Sidebar (Mode Mini)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <!-- Nav Menu with Native Independent Smooth Scrolling -->
    <nav id="desktop-sidebar-nav" 
         @scroll.passive="sessionStorage.setItem('sidebar_nav_scroll', $el.scrollTop)" 
         class="flex-1 p-3 text-sm overflow-y-auto custom-scrollbar space-y-3">
        @include('components.layouts.partials.nav-links')
    </nav>

    <!-- User Footer & Logout (Fixed at bottom of sidebar) -->
    <div class="p-3 border-t border-slate-800/80 bg-slate-950/60 shrink-0 space-y-2">
        <!-- Expanded User Profile Box -->
        <div x-show="sidebarExpanded" class="bg-slate-800/60 rounded-xl p-2.5 border border-slate-700/50 backdrop-blur-xs flex items-center justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[9px] uppercase tracking-widest font-extrabold text-slate-400">Pengguna</p>
                <p class="font-extrabold text-white text-xs truncate mt-0.5">{{ auth()->user()->name ?? 'Guest' }}</p>
            </div>
            
            <div class="shrink-0">
                @php $role = auth()->user()->role ?? 'guest'; @endphp
                @if($role === 'founder')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-purple-500/20 text-purple-300 border border-purple-500/40">FOUNDER</span>
                @elseif($role === 'admin')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-indigo-500/20 text-indigo-300 border border-indigo-500/40">ADMIN</span>
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

        <!-- Mini Compact Avatar (When Sidebar is Collapsed) -->
        <div x-show="!sidebarExpanded" class="flex flex-col items-center justify-center py-1">
            <div class="w-8 h-8 rounded-full bg-slate-800 text-slate-200 border border-slate-700 flex items-center justify-center text-xs font-bold shadow-xs" title="{{ auth()->user()->name ?? 'User' }} ({{ strtoupper(auth()->user()->role ?? '') }})">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
        </div>

        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    :class="sidebarExpanded ? 'w-full justify-start px-2.5 py-2' : 'w-full justify-center p-2'"
                    class="text-xs font-bold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-xl transition-colors flex items-center gap-2"
                    :title="!sidebarExpanded ? 'Keluar dari Sistem' : ''">
                <svg class="w-4 h-4 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-show="sidebarExpanded" class="truncate">Keluar dari Sistem</span>
            </button>
        </form>
    </div>
</aside>
