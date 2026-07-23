<div class="space-y-6">

    <!-- Role Welcome Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border border-slate-700/80 text-white rounded-2xl p-6 shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-64 h-64 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] uppercase tracking-widest font-bold px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        Selamat Datang
                    </span>
                    <span class="text-xs text-slate-400 font-mono">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-white">{{ $user->name }}</h2>
                <p class="text-slate-300 text-sm max-w-3xl">
                    @if($user->isFounder())
                        Akses Executive: Mengawasi kawasan proyek, kas perusahaan global, dan persetujuan penawaran harga final.
                    @elseif($user->role === 'pengawas_project')
                        Pengawasan Lapangan: Log material mingguan, penugasan tukang/mandor per-unit, dan pembebanan kas bon.
                    @elseif($user->isSupervisor())
                        Supervisi Lapangan: Penilaian fisik unit kavling & rumah, validasi HPP, dan persetujuan penawaran.
                    @elseif($user->isFinance())
                        Pengelolaan Keuangan: Arus kas proyek & global, pencatatan transaksi tukang, dan cicilan pembeli.
                    @else
                        Tim Penjualan: Pendaftaran booking fee/DP, pengajuan penawaran harga, dan pendaftaran pembeli.
                    @endif
                </p>
            </div>

            <!-- Role Badge Pill -->
            <div>
                @if($user->isFounder())
                    <span class="badge-role-founder text-xs px-4 py-2 shadow-lg shadow-purple-500/20">FOUNDER EXECUTIVE</span>
                @elseif($user->role === 'pengawas_project')
                    <span class="badge-role-pengawas text-xs px-4 py-2 shadow-lg shadow-amber-500/20">PENGAWAS LAPANGAN</span>
                @elseif($user->isSupervisor())
                    <span class="badge-role-supervisor text-xs px-4 py-2 shadow-lg shadow-cyan-500/20">FIELD SUPERVISOR</span>
                @elseif($user->isFinance())
                    <span class="badge-role-finance text-xs px-4 py-2 shadow-lg shadow-emerald-500/20">CHIEF FINANCE</span>
                @else
                    <span class="badge-role-marketing text-xs px-4 py-2 shadow-lg shadow-blue-500/20">SALES MARKETING</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert Pending Approval untuk Founder & Supervisor -->
    @if(($user->isFounder() || $user->isSupervisor()) && $pendingProposalsCount > 0)
        <div class="bg-amber-500/10 border border-amber-500/30 rounded-2xl p-4 flex items-center justify-between gap-4 text-amber-900 shadow-xs animate-pulse">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-500/20 rounded-xl text-amber-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-amber-900">Ada {{ $pendingProposalsCount }} Pengajuan Harga Menunggu Persetujuan Anda!</h4>
                    <p class="text-xs text-amber-700">Persyaratan persetujuan paralel membutuhkan keputusan Founder dan Supervisor.</p>
                </div>
            </div>
            <a href="{{ route('proposals.index') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-semibold shadow-xs transition whitespace-nowrap">
                Review Sekarang &rarr;
            </a>
        </div>
    @endif

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Stat Card 1 -->
        <div class="card-clean p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Proyek Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalProjects }}</p>
            <p class="text-xs text-slate-500 mt-1">Kawasan perumahan & kavling</p>
        </div>

        <!-- Stat Card 2 -->
        <div class="card-clean p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Unit Kavling & Rumah</span>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalUnits }}</span>
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                    {{ $availableUnits }} Tersedia
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Total stok unit proyek</p>
        </div>

        <!-- Stat Card 3 -->
        <div class="card-clean p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengajuan Harga</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-amber-600 tracking-tight">{{ $pendingProposalsCount }}</span>
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                    {{ $approvedProposalsCount }} Disetujui
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Proposal usulan penawaran</p>
        </div>

        <!-- Stat Card 4 -->
        <div class="card-clean p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Arus Kas Global</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-emerald-600 tracking-tight font-mono">
                Rp {{ number_format($netCashflow, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-500 mt-1">Masuk: Rp {{ number_format($totalCashIn, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Quick Action Shortcut per Role -->
    <div class="card-clean p-6">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Navigasi Pintar & Pintasan Modul</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('workers.index') }}" class="p-4 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 transition text-left group">
                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <p class="font-bold text-xs text-slate-800">Mandor & Tukang</p>
                <p class="text-[11px] text-slate-500">Pendaftaran & penugasan unit</p>
            </a>

            <a href="{{ route('materials.weekly-log') }}" class="p-4 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 transition text-left group">
                <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="font-bold text-xs text-slate-800">Log Barang Pengawas</p>
                <p class="text-[11px] text-slate-500">Log material & kas bon otomatis</p>
            </a>

            <a href="{{ route('bookings.index') }}" class="p-4 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 transition text-left group">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="font-bold text-xs text-slate-800">Booking Fee & DP</p>
                <p class="text-[11px] text-slate-500">Pemesanan kavling & rumah</p>
            </a>

            <a href="{{ route('cashflow.index') }}" class="p-4 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 transition text-left group">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-2.5 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <p class="font-bold text-xs text-slate-800">Kas Proyek & Global</p>
                <p class="text-[11px] text-slate-500">Rekapitulasi arus kas bersih</p>
            </a>
        </div>
    </div>

    <!-- Data Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Proposals -->
        <div class="card-clean p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pengajuan Harga Terbaru</h3>
                    <a href="{{ route('proposals.index') }}" class="text-xs text-emerald-600 font-semibold hover:underline">Lihat Semua &rarr;</a>
                </div>

                @if($recentProposals->isEmpty())
                    <p class="text-xs text-slate-400 py-6 text-center">Belum ada pengajuan harga yang dibuat.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                <tr>
                                    <th class="py-2.5 px-3">Unit</th>
                                    <th class="py-2.5 px-3">HPP</th>
                                    <th class="py-2.5 px-3">Usulan</th>
                                    <th class="py-2.5 px-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentProposals as $prop)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="py-3 px-3 font-bold text-slate-800">
                                            {{ $prop->unit->code }} <span class="text-slate-400 font-normal">({{ $prop->unit->project->name }})</span>
                                        </td>
                                        <td class="py-3 px-3 font-mono text-slate-600">Rp {{ number_format($prop->hpp_price, 0, ',', '.') }}</td>
                                        <td class="py-3 px-3 font-mono font-bold text-emerald-700">Rp {{ number_format($prop->proposed_price, 0, ',', '.') }}</td>
                                        <td class="py-3 px-3">
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

        <!-- Recent Unit Status -->
        <div class="card-clean p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Status Unit Kavling & Rumah</h3>
                    <a href="{{ route('units.index') }}" class="text-xs text-emerald-600 font-semibold hover:underline">Lihat Semua &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="py-2.5 px-3">Kode / Tipe</th>
                                <th class="py-2.5 px-3">Proyek</th>
                                <th class="py-2.5 px-3">Luas Tanah</th>
                                <th class="py-2.5 px-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentUnits as $unit)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-3 px-3 font-bold text-slate-800">
                                        {{ $unit->code }}
                                        <span class="text-[10px] font-semibold text-slate-500 uppercase block">({{ $unit->category ?? $unit->type }})</span>
                                    </td>
                                    <td class="py-3 px-3 text-slate-600 font-medium">{{ $unit->project->name }}</td>
                                    <td class="py-3 px-3 font-mono text-slate-700">{{ $unit->land_area }} m²</td>
                                    <td class="py-3 px-3">
                                        @if($unit->status === 'tersedia')
                                            <span class="status-tersedia">Tersedia</span>
                                        @elseif($unit->status === 'booked')
                                            <span class="status-booked">Booked</span>
                                        @elseif($unit->status === 'menunggu_persetujuan')
                                            <span class="status-menunggu">Pending</span>
                                        @elseif($unit->status === 'disetujui')
                                            <span class="status-disetujui">ACC</span>
                                        @elseif($unit->status === 'terjual')
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

</div>
