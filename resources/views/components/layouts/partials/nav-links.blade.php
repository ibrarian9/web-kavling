@php 
    $u = auth()->user();
    $isF = $u?->isFounder();
    $isS = $u?->isSupervisor();
    $isP = $u?->isPengawasProject();
    $isFI = $u?->isFinance();
    $isM = $u?->isMarketing();
@endphp

<a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    <span>Dashboard</span>
</a>

<a href="{{ route('tutorial.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('tutorial.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    <span>Tutorial System</span>
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

    <a href="{{ route('installments.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('installments.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Cicilan Pembeli</span>
    </a>

    @if($isF || $isFI)
        <a href="{{ route('cashflow.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('cashflow.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span>Arus Kas & Global</span>
        </a>

        <a href="{{ route('manual-invoices.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('manual-invoices.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Invoice Manual</span>
        </a>
    @endif
@endif

@if($isF)
    <div class="pt-3 pb-1 px-3 text-[10px] uppercase font-bold tracking-wider text-slate-500">Pengaturan Founder</div>

    <a href="{{ route('profile.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('profile.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span>Profil Akun & Legalitas</span>
    </a>

    <a href="{{ route('employee-salaries.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('employee-salaries.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <span>Gaji Karyawan</span>
    </a>

    <a href="{{ route('users.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('users.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        <span>Manajemen User</span>
    </a>

    <a href="{{ route('activity-logs.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('activity-logs.*') ? 'bg-emerald-500/10 text-emerald-400 border-r-2 border-emerald-500' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span>Log System & Audit</span>
    </a>
@endif
