<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - SIM Proyek Properti & Keuangan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body x-data="{ mobileMenuOpen: false }" x-effect="document.body.classList.toggle('overflow-hidden', mobileMenuOpen)" @keydown.window.escape="mobileMenuOpen = false" class="bg-slate-50 font-sans text-slate-800 min-h-screen flex flex-col antialiased">

    <div class="flex flex-1 min-h-screen">
        <!-- Desktop Sidebar Navigation -->
        <aside class="w-64 bg-slate-900 border-r border-slate-800 text-slate-300 flex flex-col justify-between shrink-0 hidden md:flex">
            <div>
                <!-- Brand Header -->
                <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-800">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 font-bold text-lg shadow-sm">
                        A
                    </div>
                    <div>
                        <h2 class="font-bold text-white text-xs tracking-wide">ATLANTIK PERKASA</h2>
                        <p class="text-[10px] text-emerald-400 font-bold tracking-wider uppercase">PT. Atlantik Perkasa Abadi</p>
                    </div>
                </div>

                @php 
                    $u = auth()->user();
                    $isF = $u?->isFounder();
                    $isS = $u?->isSupervisor();
                    $isP = $u?->isPengawasProject();
                    $isFI = $u?->isFinance();
                    $isM = $u?->isMarketing();
                @endphp

                <!-- Nav Menu -->
                <nav class="p-4 space-y-1 text-sm overflow-y-auto max-h-[calc(100vh-220px)]">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Section: Master Data -->
                    <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Master Data</div>

                    <a href="{{ route('projects.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('projects.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Proyek Properti</span>
                    </a>

                    <a href="{{ route('units.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('units.index') || request()->routeIs('units.show') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Unit Kavling / Rumah</span>
                    </a>

                    @if($isF)
                        <a href="{{ route('units.legacy-sale') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('units.legacy-sale') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Input Penjualan Lalu</span>
                        </a>
                    @endif

                    <!-- Section: Pekerja Lapangan (Hidden from Marketing) -->
                    @if($isF || $isS || $isP || $isFI)
                        <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Pekerja Lapangan</div>

                        <a href="{{ route('workers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('workers.index') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Mandor & Tukang</span>
                        </a>

                        <a href="{{ route('field-expenses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('field-expenses.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span>Laporan Belanja & Gaji</span>
                        </a>
                    @endif

                    <!-- Section: Penjualan & Approval (Hidden from Pengawas Project) -->
                    @if($isF || $isS || $isFI || $isM)
                        <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Penjualan & Approval</div>

                        <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('bookings.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Booking Fee & DP</span>
                        </a>

                        <a href="{{ route('proposals.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('proposals.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pengajuan & Approval</span>
                        </a>

                        <a href="{{ route('documents.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('documents.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span>Surat Resmi PDF</span>
                        </a>
                    @endif

                    <!-- Section: Keuangan & Pembayaran (Hidden from Pengawas Project) -->
                    @if($isF || $isFI || $isM)
                        <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Keuangan & Pembayaran</div>

                        <a href="{{ route('installments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('installments.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Cicilan Pembeli</span>
                        </a>

                        @if($isF || $isFI)
                            <a href="{{ route('cashflow.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('cashflow.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>Arus Kas & Global</span>
                            </a>

                            <a href="{{ route('manual-invoices.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('manual-invoices.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Invoice Manual</span>
                            </a>
                        @endif
                    @endif

                    @if($isF)
                        <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Pengaturan Akses</div>

                        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('users.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Manajemen User</span>
                        </a>

                        <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('activity-logs.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Log System & Audit</span>
                        </a>
                    @endif
                </nav>
            </div>

            <!-- User Footer & Role Switcher -->
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
                    <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Section: Master Data -->
                    <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Master Data</div>

                    <a href="{{ route('projects.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('projects.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Proyek Properti</span>
                    </a>

                    <a href="{{ route('units.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('units.index') || request()->routeIs('units.show') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Unit Kavling / Rumah</span>
                    </a>

                    @if($isF)
                        <a href="{{ route('units.legacy-sale') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('units.legacy-sale') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Input Penjualan Lalu</span>
                        </a>
                    @endif

                    <!-- Section: Pekerja Lapangan -->
                    @if($isF || $isS || $isP || $isFI)
                        <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Pekerja Lapangan</div>

                        <a href="{{ route('workers.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('workers.index') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Mandor & Tukang</span>
                        </a>

                        <a href="{{ route('field-expenses.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('field-expenses.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span>Laporan Belanja & Gaji</span>
                        </a>
                    @endif

                    <!-- Section: Penjualan & Approval -->
                    @if($isF || $isS || $isFI || $isM)
                        <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Penjualan & Approval</div>

                        <a href="{{ route('bookings.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('bookings.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Booking Fee & DP</span>
                        </a>

                        <a href="{{ route('proposals.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('proposals.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pengajuan & Approval</span>
                        </a>

                        <a href="{{ route('documents.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('documents.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span>Surat Resmi PDF</span>
                        </a>
                    @endif

                    <!-- Section: Keuangan & Pembayaran -->
                    @if($isF || $isFI || $isM)
                        <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Keuangan & Pembayaran</div>

                        @if($isF || $isFI || $isM)
                            <a href="{{ route('installments.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('installments.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Cicilan Pembeli</span>
                            </a>
                        @endif

                        @if($isF || $isFI)
                            <a href="{{ route('cashflow.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('cashflow.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>Arus Kas & Global</span>
                            </a>

                            <a href="{{ route('manual-invoices.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('manual-invoices.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Invoice Manual</span>
                            </a>
                        @endif
                    @endif

                    @if($isF)
                        <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Pengaturan Akses</div>

                        <a href="{{ route('users.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('users.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Manajemen User</span>
                        </a>

                        <a href="{{ route('activity-logs.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('activity-logs.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Log System & Audit</span>
                        </a>
                    @endif
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

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navigation Bar -->
            <header class="min-h-16 py-2 bg-white border-b border-slate-200 px-3 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1 mr-2">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 md:hidden focus:outline-none shrink-0" aria-label="Buka Menu Mobile">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="font-bold text-xs sm:text-lg text-slate-900 tracking-tight leading-tight line-clamp-2 break-words">{{ $header ?? $title ?? 'Dashboard' }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-500 font-mono">{{ auth()->user()->email }}</p>
                    </div>

                    @php $role = auth()->user()->role ?? 'guest'; @endphp
                    @if($role === 'founder')
                        <span class="badge-role-founder">Founder</span>
                    @elseif($role === 'supervisor')
                        <span class="badge-role-supervisor">Supervisor</span>
                    @elseif($role === 'finance')
                        <span class="badge-role-finance">Finance</span>
                    @elseif($role === 'pengawas_project')
                        <span class="badge-role-pengawas">Pengawas</span>
                    @else
                        <span class="badge-role-marketing">Marketing</span>
                    @endif
                </div>
            </header>

            <!-- Alerts Banner -->
            @if (session()->has('success'))
                <div class="mx-6 mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mx-6 mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Page Content -->
            <main class="p-6 lg:p-8 flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
