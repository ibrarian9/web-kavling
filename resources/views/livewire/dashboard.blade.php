<div class="space-y-6">

    <!-- Role Welcome Banner Executive Header -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 border border-slate-800/80 text-white rounded-3xl p-6 sm:p-7 shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-80 h-80 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>

        <div class="relative z-10 space-y-3">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-[10px] uppercase tracking-widest font-extrabold px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1.5 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>SIM Kavling & Properti Active</span>
                </span>
                <span class="text-xs text-slate-400 font-mono flex items-center gap-1.5 bg-slate-800/60 px-3 py-1 rounded-full border border-slate-700/50">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ format_id_day_date(now()) }}</span>
                </span>
            </div>

            <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-white flex items-center gap-2">
                <span>Selamat Datang, {{ $user->name }}</span>
                <span class="text-xl">👋</span>
            </h2>

            <p class="text-slate-300 text-xs sm:text-sm max-w-3xl leading-relaxed">
                @if($user->isAdmin() || $user->isPengawasProject())
                    Akses Operasional & Lapangan: Pemantauan data proyek perumahan, ketersediaan stok fisik unit, penugasan mandor & tukang, serta pencatatan belanja material.
                @elseif($user->isFounder())
                    Akses Executive Founder: Pemantauan arus kas global, rincian ketersediaan unit kavling, dan persetujuan pengajuan harga.
                @elseif($user->isSupervisor())
                    Supervisi Lapangan: Validasi fisik proyek perumahan, monitoring unit, dan persetujuan proposal penawaran.
                @elseif($user->isFinance())
                    Manajemen Keuangan: Monitor arus kas proyek & global, slip gaji karyawan, pencatatan transaksi kas, dan cicilan konsumen.
                @else
                    Tim Penjualan: Pencatatan booking fee & DP, pengajuan penawaran harga, dan pendaftaran pembeli unit kavling.
                @endif
            </p>

            <!-- Quick Action Shortcut Pills -->
            <div class="pt-3 border-t border-slate-800/80 flex flex-wrap items-center gap-2">
                @if($user->isAdmin() || $user->isPengawasProject())
                    <a href="{{ route('projects.index') }}" wire:navigate.hover class="px-3.5 py-1.5 bg-purple-600/90 hover:bg-purple-600 text-white font-bold rounded-xl text-xs shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Proyek Properti</span>
                    </a>
                    <a href="{{ route('workers.index') }}" wire:navigate.hover class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold border border-slate-700 rounded-xl text-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Mandor & Tukang</span>
                    </a>
                    <a href="{{ route('field-expenses.index') }}" wire:navigate.hover class="px-3.5 py-1.5 bg-emerald-600/90 hover:bg-emerald-600 text-white font-bold rounded-xl text-xs shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Belanja Material</span>
                    </a>
                @elseif($user->isFounder() || $user->isFinance())
                    <a href="{{ route('cashflow.index') }}" wire:navigate.hover class="px-3.5 py-1.5 bg-emerald-600/90 hover:bg-emerald-600 text-white font-bold rounded-xl text-xs shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Arus Kas</span>
                    </a>
                    <a href="{{ route('bookings.index') }}" wire:navigate.hover class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold border border-slate-700 rounded-xl text-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Booking Unit</span>
                    </a>
                @elseif($user->isMarketing())
                    <a href="{{ route('daily-activity-reports.index') }}" wire:navigate.hover class="px-3.5 py-1.5 bg-teal-600/90 hover:bg-teal-600 text-white font-bold rounded-xl text-xs shadow-sm transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>Daily Activity</span>
                    </a>
                    <a href="{{ route('bookings.index') }}" wire:navigate.hover class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold border border-slate-700 rounded-xl text-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Booking Unit</span>
                    </a>
                @endif

                <a href="{{ route('units.index') }}" wire:navigate.hover class="px-3.5 py-1.5 bg-slate-800/80 hover:bg-slate-700/80 text-slate-300 font-bold border border-slate-700/80 rounded-xl text-xs transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                    <span>Daftar Unit</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Banner Notifikasi Pengajuan Proposal Menunggu Approval (Founder / Supervisor Only) -->
    @if(($user->isFounder() || $user->isSupervisor()) && $pendingProposalsCount > 0)
        <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-gradient-to-r from-amber-500/15 via-amber-50 to-orange-500/10 border border-amber-300/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20">
                    <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm text-amber-900">Perhatian: Ada {{ $pendingProposalsCount }} Pengajuan Harga Menunggu Persetujuan Anda!</h4>
                    <p class="text-xs text-amber-700">Persetujuan pengajuan harga membutuhkan keputusan bertingkat dari Founder & Supervisor.</p>
                </div>
            </div>
            <a href="{{ route('proposals.index') }}" wire:navigate.hover class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-sm transition whitespace-nowrap self-start sm:self-auto flex items-center gap-1.5">
                <span>Review Proposal Sekarang</span>
                <span>&rarr;</span>
            </a>
        </div>
    @endif

    <!-- KPI Metric Cards Grid (Primary Focus: 2 Baris di iPad / Tablet, 4 Kolom di Layar Lebar) -->
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- Stat Card 1: Proyek Aktif -->
        <div class="kpi-card-blue p-4 sm:p-5 flex flex-col justify-between min-w-0 shadow-2xs rounded-2xl">
            <div class="flex items-center justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Proyek Properti</span>
                <div class="p-1.5 sm:p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <div class="mt-3 min-w-0">
                <p class="text-base sm:text-lg xl:text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $totalProjects }} Proyek</p>
                <p class="text-[10px] sm:text-[11px] text-slate-500 mt-1 flex items-center gap-1 truncate">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500 shrink-0"></span>
                    <span class="truncate">Kawasan perumahan aktif</span>
                </p>
            </div>
        </div>

        <!-- Stat Card 2: Stok Unit & Accessibility Breakdown -->
        <div class="kpi-card-emerald p-4 sm:p-5 flex flex-col justify-between min-w-0 shadow-2xs rounded-2xl">
            <div class="flex items-center justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Stok Unit Fisik</span>
                <div class="p-1.5 sm:p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
            </div>
            <div class="mt-3 min-w-0">
                <div class="flex items-baseline justify-between gap-1.5 flex-wrap">
                    <span class="text-base sm:text-lg xl:text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $totalUnits }} Unit</span>
                    <span class="status-tersedia text-[9px] sm:text-[10px] shrink-0">
                        {{ $availableUnits }} Tersedia
                    </span>
                </div>
                <div class="flex items-center gap-1.5 text-[9px] sm:text-[10px] text-slate-500 mt-1.5 font-mono truncate">
                    <span class="text-amber-700 font-bold shrink-0">{{ $bookedUnits }} Booked</span>
                    <span>•</span>
                    <span class="text-rose-700 font-bold shrink-0">{{ $soldUnits }} Terjual</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 3: Mandor & Tukang (Admin / Pengawas) vs Booking DP (Founder / Marketing / Finance) -->
        @if($user->isAdmin() || $user->isPengawasProject())
            <div class="kpi-card-amber p-4 sm:p-5 flex flex-col justify-between min-w-0 shadow-2xs rounded-2xl">
                <div class="flex items-center justify-between gap-1">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Pekerja Lapangan</span>
                    <div class="p-1.5 sm:p-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 min-w-0">
                    <p class="text-base sm:text-lg xl:text-2xl font-black text-amber-700 font-mono tracking-tight">{{ $activeWorkersCount }} Pekerja</p>
                    <p class="text-[10px] sm:text-[11px] text-slate-500 mt-1 flex items-center gap-1 truncate">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                        <span class="truncate">Mandor & tukang aktif</span>
                    </p>
                </div>
            </div>
        @else
            <div class="kpi-card-amber p-4 sm:p-5 flex flex-col justify-between min-w-0 shadow-2xs rounded-2xl">
                <div class="flex items-center justify-between gap-1">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Booking Fee & DP</span>
                    <div class="p-1.5 sm:p-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 min-w-0">
                    <p class="text-base sm:text-lg xl:text-2xl font-black text-amber-700 font-mono tracking-tight whitespace-normal break-words" title="Rp {{ number_format($totalBookingAmount, 0, ',', '.') }}">Rp {{ number_format($totalBookingAmount, 0, ',', '.') }}</p>
                    <p class="text-[10px] sm:text-[11px] text-slate-500 mt-1 truncate">Dari {{ $totalBookingsCount }} pemesanan</p>
                </div>
            </div>
        @endif

        <!-- Stat Card 4: Belanja Material Bulan Ini (Admin / Pengawas) vs Saldo Kas (Founder/Finance) vs Daily Activity (Marketing) -->
        @if($user->isAdmin() || $user->isPengawasProject())
            <div class="kpi-card-dark p-4 sm:p-5 flex flex-col justify-between min-w-0 shadow-2xs rounded-2xl">
                <div class="flex items-center justify-between gap-1">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Belanja Material</span>
                    <div class="p-1.5 sm:p-2 rounded-xl bg-slate-800 text-emerald-400 border border-slate-700 shadow-2xs shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </div>
                <div class="mt-3 min-w-0">
                    <p class="text-base sm:text-lg xl:text-2xl font-black text-emerald-400 font-mono tracking-tight whitespace-normal break-words" title="Rp {{ number_format($totalMaterialPurchasesThisMonth, 0, ',', '.') }}">
                        Rp {{ number_format($totalMaterialPurchasesThisMonth, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] sm:text-[11px] text-slate-400 mt-1 flex items-center gap-1 truncate">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                        <span class="truncate">Belanja bulan ini</span>
                    </p>
                </div>
            </div>
        @elseif($user->isMarketing())
            <div class="kpi-card-dark p-4 sm:p-5 flex flex-col justify-between min-w-0 shadow-2xs rounded-2xl">
                <div class="flex items-center justify-between gap-1">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Daily Activity</span>
                    <div class="p-1.5 sm:p-2 rounded-xl bg-teal-500/20 text-teal-300 border border-teal-500/30 shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
                <div class="mt-3 min-w-0">
                    <p class="text-base sm:text-lg xl:text-2xl font-black text-white font-mono tracking-tight">{{ $marketingDailyReportCount ?? 0 }} Laporan</p>
                    <p class="text-[10px] sm:text-[11px] text-teal-300 mt-1 font-semibold truncate">{{ $marketingHotDealsCount ?? 0 }} Prospek Hot Deal</p>
                </div>
            </div>
        @else
            <div class="kpi-card-dark p-4 sm:p-5 flex flex-col justify-between min-w-0 shadow-2xs rounded-2xl">
                <div class="flex items-center justify-between gap-1">
                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">Saldo Kas Bersih</span>
                    <div class="p-1.5 sm:p-2 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
                <div class="mt-3 min-w-0">
                    <p class="text-base sm:text-lg xl:text-2xl font-black text-white font-mono tracking-tight whitespace-normal break-words" title="Rp {{ number_format($netCashflow, 0, ',', '.') }}">Rp {{ number_format($netCashflow, 0, ',', '.') }}</p>
                    <p class="text-[10px] sm:text-[11px] text-emerald-400 mt-1 font-mono tracking-tight truncate" title="Pemasukan: Rp {{ number_format($totalCashIn, 0, ',', '.') }}">Pemasukan: Rp {{ number_format($totalCashIn, 0, ',', '.') }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Visual Analytics: Grafik Real-Time Tren Arus Kas (Non-Marketing, Non-Admin, & Non-Pengawas Only) -->
    @if(!$user->isMarketing() && !$user->isAdmin() && !$user->isPengawasProject())
        <div class="card-clean p-5 sm:p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18"/></svg>
                        <span>Grafik Tren Keuangan Arus Kas (6 Bulan Terakhir)</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Perbandingan pemasukan penjualan unit & booking fee vs pengeluaran operasional & belanja material</p>
                </div>
                <a href="{{ route('cashflow.index') }}" wire:navigate.hover class="text-xs font-bold text-emerald-700 hover:text-emerald-900 flex items-center gap-1 self-start sm:self-auto">
                    <span>Rincian Jurnal Kas</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div id="dashboardTrendChart" class="w-full min-h-[300px]"></div>
        </div>
    @endif

    <!-- Quick Module Navigation Shortcuts -->
    <div class="card-clean p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Pintasan Modul Utama</span>
            </h3>
            <span class="text-[10px] font-bold text-slate-400">Klik untuk langsung loncat halaman</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
            <!-- Shortcut 1: Proyek -->
            <a href="{{ route('projects.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-purple-300 hover:bg-purple-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-xs text-slate-800 group-hover:text-purple-700">Proyek Properti</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Kelola kawasan perumahan</p>
                </div>
            </a>

            <!-- Shortcut 2: Unit Kavling -->
            <a href="{{ route('units.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-xs text-slate-800 group-hover:text-emerald-700">Stok Unit & Map</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Siteplan & spesifikasi</p>
                </div>
            </a>

            <!-- Shortcut 3: Mandor & Tukang (Admin / Pengawas) vs Booking (Founder / Marketing / Finance) -->
            @if($user->isAdmin() || $user->isPengawasProject())
                <a href="{{ route('workers.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-amber-300 hover:bg-amber-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-slate-800 group-hover:text-amber-700">Mandor & Tukang</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Pekerja & penugasan</p>
                    </div>
                </a>
            @else
                <a href="{{ route('bookings.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-amber-300 hover:bg-amber-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-slate-800 group-hover:text-amber-700">Booking Fee & DP</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Pemesanan konsumen</p>
                    </div>
                </a>
            @endif

            <!-- Shortcut 4: Panduan Sistem (Admin / Pengawas) vs Surat SPP (Lainnya) -->
            @if($user->isAdmin() || $user->isPengawasProject())
                <a href="{{ route('tutorial.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-teal-300 hover:bg-teal-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-slate-800 group-hover:text-teal-700">Panduan Sistem</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Tutorial & SOP</p>
                    </div>
                </a>
            @else
                <a href="{{ route('documents.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-teal-300 hover:bg-teal-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-slate-800 group-hover:text-teal-700">Surat SPP (PDF)</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Dokumen surat pesanan</p>
                    </div>
                </a>
            @endif

            <!-- Shortcut 5: Belanja Material (Admin / Pengawas) vs Daily Activity (Marketing) vs Mandor (Founder/Finance) -->
            @if($user->isAdmin() || $user->isPengawasProject())
                <a href="{{ route('field-expenses.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-slate-800 group-hover:text-emerald-700">Belanja Material</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Log belanja lapangan</p>
                    </div>
                </a>
            @elseif($user->isMarketing())
                <a href="{{ route('daily-activity-reports.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-teal-300 hover:bg-teal-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-slate-800 group-hover:text-teal-700">Daily Activity Report</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Aktivitas & prospek sales</p>
                    </div>
                </a>
            @else
                <a href="{{ route('workers.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-blue-300 hover:bg-blue-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-slate-800 group-hover:text-blue-700">Mandor & Tukang</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Penugasan & upah kerja</p>
                    </div>
                </a>
            @endif

            <!-- Shortcut 6: Proyek Eksternal (Admin / Pengawas) vs Cicilan (Lainnya) -->
            @if($user->isAdmin() || $user->isPengawasProject())
                <a href="{{ route('external-projects.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-sky-300 hover:bg-sky-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-slate-800 group-hover:text-sky-700">Proyek Luar</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Pekerjaan eksternal</p>
                    </div>
                </a>
            @else
                <a href="{{ route('installments.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-sky-300 hover:bg-sky-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                    <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-xs text-slate-800 group-hover:text-sky-700">Cicilan Pembeli</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Skema & pelunasan</p>
                    </div>
                </a>
            @endif
        </div>
    </div>

    <!-- Informasi Penetapan Gaji Karyawan dari Founder (Khusus Karyawan / Non-Founder) -->
    @if(!$user->isFounder())
        <div class="card-clean p-5 sm:p-6 border border-slate-200/90 shadow-2xs relative overflow-hidden bg-gradient-to-br from-white via-slate-50/40 to-emerald-50/20">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-emerald-600 text-white rounded-2xl shadow-md shadow-emerald-600/20 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-extrabold text-slate-900 text-base sm:text-lg">Informasi Penetapan Gaji Anda</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Ditetapkan Founder
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Rincian gaji pokok, tunjangan, dan hak keuangan bulanan yang terdaftar pada akun Anda</p>
                    </div>
                </div>

                @if($latestSalaryPayment)
                    <a href="{{ route('employee-salary.slip-pdf', $latestSalaryPayment->uuid) }}" 
                       target="_blank" 
                       class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-2 self-start sm:self-auto shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Cetak Slip Gaji ({{ $userSalary?->getIndonesianMonth($latestSalaryPayment->payroll_month) }} {{ $latestSalaryPayment->payroll_year }})</span>
                    </a>
                @endif
            </div>

            @if($userSalary)
                <!-- Salary Breakdown Grid (iPad Optimized) -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3.5 mt-5">
                    <!-- Gaji Pokok -->
                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs min-w-0">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Gaji Pokok</span>
                        <p class="text-sm sm:text-base xl:text-lg font-black text-slate-800 font-mono tracking-tight truncate" title="Rp {{ number_format($userSalary->basic_salary, 0, ',', '.') }}">Rp {{ number_format($userSalary->basic_salary, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-slate-400">Dasar ketetapan</span>
                    </div>

                    <!-- Tunjangan -->
                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs min-w-0">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600 block mb-1">Tunjangan (+)</span>
                        <p class="text-sm sm:text-base xl:text-lg font-black text-teal-700 font-mono tracking-tight truncate" title="Rp {{ number_format($userSalary->allowance, 0, ',', '.') }}">Rp {{ number_format($userSalary->allowance, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-teal-600/80">Transport & fungsional</span>
                    </div>

                    <!-- Bonus / Insentif -->
                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs min-w-0">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 block mb-1">Bonus/Insentif (+)</span>
                        <p class="text-sm sm:text-base xl:text-lg font-black text-blue-700 font-mono tracking-tight truncate" title="Rp {{ number_format($userSalary->bonus, 0, ',', '.') }}">Rp {{ number_format($userSalary->bonus, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-blue-600/80">Kinerja operasional</span>
                    </div>

                    <!-- Potongan -->
                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs min-w-0">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rose-500 block mb-1">Potongan (-)</span>
                        <p class="text-sm sm:text-base xl:text-lg font-black text-rose-600 font-mono tracking-tight truncate" title="Rp {{ number_format($userSalary->deductions, 0, ',', '.') }}">Rp {{ number_format($userSalary->deductions, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-rose-500/80">PPh / administrasi</span>
                    </div>

                    <!-- Total Gaji Bersih (THP) -->
                    <div class="col-span-2 sm:col-span-2 md:col-span-1 p-3.5 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white shadow-md shadow-emerald-700/20 min-w-0">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-100 block mb-1">Take Home Pay</span>
                        <p class="text-sm sm:text-base xl:text-lg font-black font-mono tracking-tight truncate" title="Rp {{ number_format($userSalary->net_salary, 0, ',', '.') }}">Rp {{ number_format($userSalary->net_salary, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-emerald-100/90 font-medium">Gaji Bersih / Bulan</span>
                    </div>
                </div>

                <!-- Account & Metadata Information -->
                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                    <div class="flex items-center gap-2 text-slate-600 bg-white p-2.5 rounded-xl border border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Posisi / Jabatan</span>
                            <span class="font-bold text-slate-800 truncate block">{{ $userSalary->position ?: ($user->position ?: ucfirst($user->role)) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-slate-600 bg-white p-2.5 rounded-xl border border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Rekening Payroll</span>
                            <span class="font-bold text-slate-800 font-mono truncate block">
                                {{ $userSalary->bank_name ? $userSalary->bank_name . ' - ' . $userSalary->bank_account_number : 'Belum Didaftarkan' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-slate-600 bg-white p-2.5 rounded-xl border border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Status Payroll</span>
                            @if($latestSalaryPayment)
                                <span class="font-bold text-emerald-700 truncate block">Terbayar: Periode {{ $userSalary->getIndonesianMonth($latestSalaryPayment->payroll_month) }} {{ $latestSalaryPayment->payroll_year }}</span>
                            @else
                                <span class="font-bold text-amber-700 truncate block">Aktif Terdaftar (Menunggu Payroll)</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($userSalary->notes)
                    <div class="mt-3 p-3 bg-slate-100/70 border border-slate-200/60 rounded-xl text-[11px] text-slate-600 flex items-start gap-2">
                        <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <span class="font-bold text-slate-700">Catatan Founder:</span> {{ $userSalary->notes }}
                        </div>
                    </div>
                @endif
            @else
                <div class="mt-4 p-4 bg-amber-50/80 border border-amber-200/80 rounded-2xl text-amber-900 text-xs flex items-center gap-3">
                    <div class="p-2 bg-amber-500 text-white rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold">Informasi Gaji Belum Ditetapkan</p>
                        <p class="text-amber-700 text-[11px] mt-0.5">Penetapan gaji pokok dan tunjangan untuk akun Anda belum dikonfigurasi di sistem oleh Founder. Silakan hubungi Founder atau Manajemen Keuangan.</p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Data Tables Grid (Recent Feeds) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @if($user->isAdmin() || $user->isPengawasProject())
            <!-- Table 1 (Admin/Pengawas): Status Stok & Fisik Unit Properti -->
            <div class="card-clean overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Status Stok & Unit Properti</h3>
                        <a href="{{ route('units.index') }}" wire:navigate.hover class="text-xs text-emerald-700 font-bold hover:underline">Lihat Semua &rarr;</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4 whitespace-nowrap">Kode Unit</th>
                                    <th class="py-3 px-4">Proyek</th>
                                    <th class="py-3 px-4 whitespace-nowrap">Tipe / Dimensi</th>
                                    <th class="py-3 px-4 whitespace-nowrap">Status Unit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentUnits as $unit)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="py-3.5 px-4 font-extrabold text-slate-800 whitespace-nowrap">
                                            <a href="{{ route('units.show', $unit->id) }}" wire:navigate.hover class="hover:text-emerald-600 transition">
                                                Unit {{ $unit->code }}
                                            </a>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase block">({{ $unit->category ?? $unit->type }})</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-600 font-medium">{{ $unit->project?->name ?? 'Proyek' }}</td>
                                        <td class="py-3.5 px-4 font-mono text-slate-700 text-xs whitespace-nowrap">
                                            @if($unit->category === 'fasum')
                                                {{ $unit->work_area ? $unit->work_area . ' m²' : '-' }}
                                            @else
                                                {{ $unit->land_area }} m²
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if($unit->status === 'tersedia')
                                                <span class="status-tersedia">Tersedia</span>
                                            @elseif($unit->status === 'booked' || $unit->status === 'booking')
                                                <span class="status-booked">Booked</span>
                                            @elseif($unit->status === 'menunggu_persetujuan')
                                                <span class="status-menunggu">Pending</span>
                                            @elseif($unit->status === 'disetujui' || $unit->status === 'terjual')
                                                <span class="status-terjual">Terjual</span>
                                            @else
                                                <span class="status-draft">{{ ucfirst($unit->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400 italic">Belum ada unit properti tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Table 2 (Admin/Pengawas): Log Belanja Material Lapangan Terbaru -->
            <div class="card-clean overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Aktivitas Belanja Material Terbaru</h3>
                        <a href="{{ route('field-expenses.index') }}" wire:navigate.hover class="text-xs text-emerald-700 font-bold hover:underline">Lihat Log &rarr;</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4 whitespace-nowrap">Tanggal</th>
                                    <th class="py-3 px-4">Proyek & Unit</th>
                                    <th class="py-3 px-4">Barang / Toko</th>
                                    <th class="py-3 px-4 text-right whitespace-nowrap">Total Belanja</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentMaterialPurchases as $mat)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="py-3.5 px-4 font-mono text-slate-600 text-xs whitespace-nowrap">
                                            {{ format_id_date($mat->purchase_date) }}
                                        </td>
                                        <td class="py-3.5 px-4 font-extrabold text-slate-800">
                                            <span>{{ $mat->unit?->project?->name ?? $mat->project?->name ?? 'Proyek' }}</span>
                                            <span class="text-[10px] text-slate-500 font-mono block">Unit {{ $mat->unit?->code ?? '-' }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-700 text-xs">
                                            <p class="font-bold text-slate-800">{{ $mat->item_name }}</p>
                                            <p class="text-[10px] text-amber-700">{{ $mat->store_name ?: '-' }}</p>
                                        </td>
                                        <td class="py-3.5 px-4 font-mono font-extrabold text-emerald-700 text-right whitespace-nowrap">
                                            Rp {{ number_format($mat->total_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400 italic">Belum ada catatan belanja material.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <!-- Table 1 (Founder/Finance/Marketing): Recent Proposals -->
            <div class="card-clean overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Pengajuan Harga Terbaru</h3>
                        <a href="{{ route('proposals.index') }}" wire:navigate.hover class="text-xs text-emerald-700 font-bold hover:underline">Lihat Semua &rarr;</a>
                    </div>

                    @if($recentProposals->isEmpty())
                        <p class="text-xs text-slate-400 py-10 text-center">Belum ada pengajuan harga yang dibuat.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                                    <tr>
                                        <th class="py-3 px-4 whitespace-nowrap">Unit / Proyek</th>
                                        @if(auth()->user()->canViewHpp())
                                            <th class="py-3 px-4 whitespace-nowrap">HPP</th>
                                        @endif
                                        <th class="py-3 px-4 whitespace-nowrap">Usulan Harga</th>
                                        <th class="py-3 px-4 whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($recentProposals as $prop)
                                        <tr class="hover:bg-slate-50/60 transition">
                                            <td class="py-3.5 px-4 font-extrabold text-slate-800">
                                                <span>Unit {{ $prop->unit->code }}</span>
                                                <span class="text-slate-400 font-normal text-[11px] block">({{ $prop->unit->project->name }})</span>
                                            </td>
                                            @if(auth()->user()->canViewHpp())
                                                <td class="py-3.5 px-4 font-mono text-slate-600 whitespace-nowrap">Rp {{ number_format($prop->hpp_price, 0, ',', '.') }}</td>
                                            @endif
                                            <td class="py-3.5 px-4 font-mono font-extrabold text-emerald-700 whitespace-nowrap">Rp {{ number_format($prop->proposed_price, 0, ',', '.') }}</td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                @if($prop->status === 'menunggu')
                                                    <span class="status-menunggu">Menunggu</span>
                                                @elseif($prop->status === 'disetujui')
                                                    <span class="status-disetujui">Disetujui</span>
                                                @else
                                                    <span class="status-ditolak">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Table 2 (Founder/Finance/Marketing): Recent Unit Availability -->
            <div class="card-clean overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Status Availability Unit Kavling</h3>
                        <a href="{{ route('units.index') }}" wire:navigate.hover class="text-xs text-emerald-700 font-bold hover:underline">Lihat Semua &rarr;</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4 whitespace-nowrap">Kode Unit</th>
                                    <th class="py-3 px-4">Proyek</th>
                                    @if(auth()->user()->canViewSalesPrices())
                                        <th class="py-3 px-4 whitespace-nowrap">Harga Jual</th>
                                    @endif
                                    <th class="py-3 px-4 whitespace-nowrap">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentUnits as $unit)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="py-3.5 px-4 font-extrabold text-slate-800">
                                            <a href="{{ route('units.show', $unit->id) }}" wire:navigate.hover class="hover:text-emerald-600 transition">
                                                Unit {{ $unit->code }}
                                            </a>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase block">({{ $unit->category ?? $unit->type }})</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-600 font-medium">{{ $unit->project->name }}</td>
                                        @if(auth()->user()->canViewSalesPrices())
                                            <td class="py-3.5 px-4 font-mono text-slate-800 font-bold whitespace-nowrap">
                                                Rp {{ number_format($unit->final_selling_price ?? 0, 0, ',', '.') }}
                                            </td>
                                        @endif
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if($unit->status === 'tersedia')
                                                <span class="status-tersedia">Tersedia</span>
                                            @elseif($unit->status === 'booked' || $unit->status === 'booking')
                                                <span class="status-booked">Booked</span>
                                            @elseif($unit->status === 'menunggu_persetujuan')
                                                <span class="status-menunggu">Pending</span>
                                            @elseif($unit->status === 'disetujui' || $unit->status === 'terjual')
                                                <span class="status-terjual">Terjual</span>
                                            @else
                                                <span class="status-draft">{{ ucfirst($unit->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Script Chart Real-time Trend ApexCharts (Non-Marketing, Non-Admin, & Non-Pengawas Only) -->
    @if(!$user->isMarketing() && !$user->isAdmin() && !$user->isPengawasProject())
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                initDashboardChart();
            });

            document.addEventListener('livewire:navigated', function () {
                initDashboardChart();
            });

            Livewire.hook('commit', ({ succeed }) => {
                succeed(() => {
                    setTimeout(() => {
                        initDashboardChart();
                    }, 100);
                });
            });

            function initDashboardChart() {
                const chartEl = document.getElementById('dashboardTrendChart');
                if (!chartEl) return;

                chartEl.innerHTML = '';

                const options = {
                    series: [{
                        name: 'Kas Masuk (Penjualan & Booking)',
                        data: @json($chartMasuk)
                    }, {
                        name: 'Kas Keluar (Operasional & Material)',
                        data: @json($chartKeluar)
                    }],
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: { show: false },
                        fontFamily: 'Inter, sans-serif'
                    },
                    colors: ['#10b981', '#f43f5e'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3 },
                    xaxis: {
                        categories: @json($chartLabels),
                        labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#64748b', fontSize: '11px' },
                            formatter: function (val) {
                                return 'Rp ' + (val / 1000000).toFixed(0) + ' Jt';
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                            }
                        }
                    },
                    grid: { borderColor: '#f1f5f9' },
                    legend: { position: 'top', horizontalAlign: 'right', fontWeight: 600 }
                };

                const chart = new ApexCharts(chartEl, options);
                chart.render();
            }
        </script>
    @endif
</div>
