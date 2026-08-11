<div class="space-y-6">

    <!-- Role Welcome Banner Executive Header -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 border border-slate-800 text-white rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-80 h-80 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[10px] uppercase tracking-widest font-extrabold px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>SIM Kavling & Properti Active</span>
                    </span>
                    <span class="text-xs text-slate-400 font-mono flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center gap-2">
                    <span>Selamat Datang, {{ $user->name }}</span>
                    <span class="text-xl">👋</span>
                </h2>
                <p class="text-slate-300 text-xs sm:text-sm max-w-3xl leading-relaxed">
                    @if($user->isFounder())
                        Akses Executive Founder: Pemantauan arus kas global, rincian ketersediaan unit kavling, dan persetujuan pengajuan harga.
                    @elseif($user->role === 'pengawas_project')
                        Pengawasan Lapangan: Monitoring pekerja mandor/tukang, pencatatan belanja material, dan evaluasi progres unit.
                    @elseif($user->isSupervisor())
                        Supervisi Lapangan: Validasi HPP unit, penilaian fisik proyek perumahan, dan persetujuan proposal penawaran.
                    @elseif($user->isFinance())
                        Manajemen Keuangan: Monitor arus kas proyek & global, slip gaji karyawan, pencatatan transaksi kas, dan cicilan konsumen.
                    @else
                        Tim Penjualan: Pencatatan booking fee & DP, pengajuan penawaran harga, dan pendaftaran pembeli unit kavling.
                    @endif
                </p>
            </div>

            <!-- Quick Action Shortcut Buttons inside Header Banner -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                @if($user->isAdminOrFounder() || $user->isFinance())
                    <a href="{{ route('cashflow.index') }}" wire:navigate.hover class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-900/40 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Arus Kas</span>
                    </a>
                @endif

                @if($user->isAdminOrFounder() || $user->isMarketing() || $user->isFinance())
                    <a href="{{ route('bookings.index') }}" wire:navigate.hover class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-100 font-bold border border-slate-700 rounded-xl text-xs transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Booking Unit</span>
                    </a>
                @endif

                <a href="{{ route('units.index') }}" wire:navigate.hover class="px-4 py-2.5 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 font-bold border border-slate-700/80 rounded-xl text-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                    <span>Daftar Unit</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Pending Approval untuk Founder & Supervisor -->
    @if(($user->isAdminOrFounder() || $user->isSupervisor()) && $pendingProposalsCount > 0)
        <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-amber-900 shadow-sm animate-pulse">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-amber-500 text-white rounded-xl shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm text-amber-900">Perhatian: Ada {{ $pendingProposalsCount }} Pengajuan Harga Menunggu Persetujuan Anda!</h4>
                    <p class="text-xs text-amber-700">Persetujuan pengajuan harga membutuhkan keputusan keputusan bertingkat dari Founder & Supervisor.</p>
                </div>
            </div>
            <a href="{{ route('proposals.index') }}" wire:navigate.hover class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-sm transition whitespace-nowrap self-start sm:self-auto flex items-center gap-1.5">
                <span>Review Proposal Sekarang</span>
                <span>&rarr;</span>
            </a>
        </div>
    @endif

    <!-- KPI Metric Cards Grid (4 Top Metric Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Stat Card 1: Proyek Aktif -->
        <div class="kpi-card-blue flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Proyek Properti</span>
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-2xl font-extrabold text-slate-900 font-mono">{{ $totalProjects }} Proyek</p>
                <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    <span>Kawasan perumahan & kavling aktif</span>
                </p>
            </div>
        </div>

        <!-- Stat Card 2: Stok Unit & Accessibility Breakdown -->
        <div class="kpi-card-emerald flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Stok Unit & Availability</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-extrabold text-slate-900 font-mono">{{ $totalUnits }} Unit</span>
                    <span class="status-tersedia text-[10px]">
                        {{ $availableUnits }} Tersedia
                    </span>
                </div>
                <div class="flex items-center gap-2 text-[10px] text-slate-500 mt-1.5 font-mono">
                    <span class="text-amber-700 font-bold">{{ $bookedUnits }} Booked</span>
                    <span>•</span>
                    <span class="text-rose-700 font-bold">{{ $soldUnits }} Terjual</span>
                </div>
            </div>
        </div>

        <!-- Stat Card 3: Booking Fee & Total DP -->
        <div class="kpi-card-amber flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Booking Fee & DP</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-2xl font-extrabold text-amber-700 font-mono">Rp {{ number_format($totalBookingAmount, 0, ',', '.') }}</p>
                <p class="text-[11px] text-slate-500 mt-1">Terverifikasi dari {{ $totalBookingsCount }} pemesanan konsumen</p>
            </div>
        </div>

        <!-- Stat Card 4: Saldo Arus Kas Global -->
        <div class="kpi-card-dark flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Saldo Kas Bersih Global</span>
                <div class="p-2.5 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-2xl font-extrabold text-white font-mono">Rp {{ number_format($netCashflow, 0, ',', '.') }}</p>
                <p class="text-[11px] text-emerald-400 mt-1 font-mono">Pemasukan: Rp {{ number_format($totalCashIn, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Visual Analytics: Grafik Real-Time Tren Arus Kas -->
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

            <!-- Shortcut 3: Booking Fee -->
            <a href="{{ route('bookings.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-amber-300 hover:bg-amber-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-xs text-slate-800 group-hover:text-amber-700">Booking Fee & DP</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Pemesanan konsumen</p>
                </div>
            </a>

            <!-- Shortcut 4: Surat SPP -->
            <a href="{{ route('documents.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-teal-300 hover:bg-teal-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-xs text-slate-800 group-hover:text-teal-700">Surat SPP (PDF)</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Dokumen surat pesanan</p>
                </div>
            </a>

            <!-- Shortcut 5: Worker & Mandor -->
            <a href="{{ route('workers.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-blue-300 hover:bg-blue-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-xs text-slate-800 group-hover:text-blue-700">Mandor & Tukang</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Penugasan & upah kerja</p>
                </div>
            </a>

            <!-- Shortcut 6: Cicilan & Piutang -->
            <a href="{{ route('installments.index') }}" wire:navigate.hover class="p-3.5 rounded-2xl border border-slate-200/80 hover:border-sky-300 hover:bg-sky-50/40 transition-all duration-150 text-left group flex flex-col justify-between shadow-2xs">
                <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="font-extrabold text-xs text-slate-800 group-hover:text-sky-700">Cicilan Pembeli</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Skema & pelunasan</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Data Tables Grid (Recent Proposals & Recent Unit Status) -->
    <div class="grid grid-cols-1 {{ $user->isPengawasProject() ? '' : 'lg:grid-cols-2' }} gap-6">
        @if(!$user->isPengawasProject())
            <!-- Recent Proposals -->
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
                                        <th class="py-3 px-4">Unit / Proyek</th>
                                        @if(auth()->user()->canViewHpp())
                                            <th class="py-3 px-4">HPP</th>
                                        @endif
                                        <th class="py-3 px-4">Usulan Harga</th>
                                        <th class="py-3 px-4">Status</th>
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
                                                <td class="py-3.5 px-4 font-mono text-slate-600">Rp {{ number_format($prop->hpp_price, 0, ',', '.') }}</td>
                                            @endif
                                            <td class="py-3.5 px-4 font-mono font-extrabold text-emerald-700">Rp {{ number_format($prop->proposed_price, 0, ',', '.') }}</td>
                                            <td class="py-3.5 px-4">
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
        @endif

        <!-- Recent Unit Status -->
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
                                <th class="py-3 px-4">Kode Unit</th>
                                <th class="py-3 px-4">Proyek</th>
                                <th class="py-3 px-4">Harga Jual</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentUnits as $unit)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-3.5 px-4 font-extrabold text-slate-800">
                                        <span>Unit {{ $unit->code }}</span>
                                        <span class="text-[10px] font-semibold text-slate-500 uppercase block">({{ $unit->category ?? $unit->type }})</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 font-medium">{{ $unit->project->name }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-800 font-bold">
                                        Rp {{ number_format($unit->final_selling_price ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4">
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
    </div>

    <!-- Script Chart Real-time Trend ApexCharts -->
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
</div>
