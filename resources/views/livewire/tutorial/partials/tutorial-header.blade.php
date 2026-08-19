<!-- Premium Glassmorphic Hero Banner & Role Filter Navigation -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white p-6 sm:p-8 shadow-2xl border border-slate-800 space-y-5">
    <!-- Glow Background Accent -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 space-y-3">
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[11px] font-extrabold uppercase tracking-wider">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>Pusat Tutorial & Panduan Operasional Sistem</span>
            </span>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-800/80 text-slate-300 border border-slate-700/80 text-[11px] font-semibold">
                <span>SIM-Kavling v2.0 • Standar Operasional Properti</span>
            </span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white leading-snug">
            Panduan Lengkap Alur Penjualan, Keuangan & Proyek Lapangan
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-4xl">
            Pelajari langkah demi langkah pengoperasian sistem dari hulu ke hilir — mulai dari pengelolaan <strong class="text-emerald-400 font-bold">Master Proyek</strong>, alur penerimaan <strong class="text-emerald-400 font-bold">Booking Fee</strong>, pengajuan <strong class="text-emerald-400 font-bold">Proposal Harga</strong>, penerbitan dokumen resmi <strong class="text-emerald-300 font-bold">SPP & SPJB PDF</strong>, skema <strong class="text-emerald-300 font-bold">Cicilan Kredit</strong>, biaya <strong class="text-emerald-400 font-bold">Material & Upah Lapangan</strong>, hingga pembukuan <strong class="text-emerald-400 font-bold">Arus Kas & Hutang Piutang</strong>.
        </p>

        <!-- Role Filter Selector -->
        <div class="pt-2 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-t border-slate-800/80">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-bold text-slate-400">Filter Sesuai Peran Anda:</span>
                <div class="inline-flex p-1 bg-slate-900/90 rounded-2xl border border-slate-800 flex-wrap gap-1">
                    <button wire:click="setViewMode('all')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ ($viewMode ?? 'all') === 'all' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>Semua Peran</span>
                    </button>
                    <button wire:click="setViewMode('founder')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ ($viewMode ?? 'all') === 'founder' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}">
                        <svg class="w-3.5 h-3.5 text-amber-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <span>Founder (Executive)</span>
                    </button>
                    <button wire:click="setViewMode('marketing')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ ($viewMode ?? 'all') === 'marketing' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}">
                        <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span>Marketing / Sales</span>
                    </button>
                    <button wire:click="setViewMode('finance')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ ($viewMode ?? 'all') === 'finance' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}">
                        <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Finance & Kasir</span>
                    </button>
                    <button wire:click="setViewMode('supervisor_pengawas')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ ($viewMode ?? 'all') === 'supervisor_pengawas' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-400 hover:text-white' }}">
                        <svg class="w-3.5 h-3.5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Supervisor / Pengawas</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Role Focus Alert Box -->
@if(($viewMode ?? 'all') === 'founder')
    <div class="p-4 bg-emerald-50 border border-emerald-200/90 rounded-2xl text-emerald-950 text-xs flex items-start gap-3 shadow-xs">
        <div class="p-2 bg-emerald-600 text-white rounded-xl font-bold shrink-0">
            <svg class="w-5 h-5 text-amber-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <div class="space-y-1">
            <div class="font-extrabold text-sm text-emerald-900">Hak Istimewa Founder (Super Administrator)</div>
            <p class="text-emerald-800 leading-relaxed">
                Sebagai <strong class="font-bold">Founder / Direktur Utama</strong>, Anda memiliki hak penuh untuk mengeksekusi <strong class="font-bold">seluruh transaksi secara mandiri langsung tanpa perantara</strong> (termasuk pendaftaran proyek, input booking, approval proposal harga, penerbitan SPP & SPJB PDF, pencatatan kas, hingga penggajian karyawan dan penugasan pengawas).
            </p>
        </div>
    </div>
@elseif(($viewMode ?? 'all') === 'marketing')
    <div class="p-4 bg-blue-50 border border-blue-200/90 rounded-2xl text-blue-950 text-xs flex items-start gap-3 shadow-xs">
        <div class="p-2 bg-blue-600 text-white rounded-xl font-bold shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
        <div class="space-y-1">
            <div class="font-extrabold text-sm text-blue-900">Fokus Tugas Utama: Tim Marketing & Penjualan</div>
            <p class="text-blue-800 leading-relaxed">
                Fokus utama Anda adalah mengunci unit calon konsumen via <strong class="font-bold">Booking Fee</strong>, mengajukan <strong class="font-bold">Proposal Harga Jual</strong> ke Founder/Supervisor, mengisi <strong class="font-bold">Laporan Aktivitas Harian</strong>, serta memantau progres transaksi dan komisi penjualan.
            </p>
        </div>
    </div>
@elseif(($viewMode ?? 'all') === 'finance')
    <div class="p-4 bg-amber-50 border border-amber-200/90 rounded-2xl text-amber-950 text-xs flex items-start gap-3 shadow-xs">
        <div class="p-2 bg-amber-600 text-white rounded-xl font-bold shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
        <div class="space-y-1">
            <div class="font-extrabold text-sm text-amber-900">Fokus Tugas Utama: Tim Finance & Kasir</div>
            <p class="text-amber-800 leading-relaxed">
                Fokus utama Anda adalah verifikasi & approval tanda jadi booking, pencatatan setoran cicilan bulanan konsumen, penerbitan kwitansi pelunasan, pencatatan invoice manual, pembukuan arus kas, kontrol hutang toko material & upah pekerja, serta pembayaran gaji karyawan.
            </p>
        </div>
    </div>
@elseif(($viewMode ?? 'all') === 'supervisor_pengawas')
    <div class="p-4 bg-purple-50 border border-purple-200/90 rounded-2xl text-purple-950 text-xs flex items-start gap-3 shadow-xs">
        <div class="p-2 bg-purple-600 text-white rounded-xl font-bold shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div class="space-y-1">
            <div class="font-extrabold text-sm text-purple-900">Fokus Tugas Utama: Supervisor & Pengawas Proyek Lapangan</div>
            <p class="text-purple-800 leading-relaxed">
                Fokus utama Anda adalah review proposal harga jual unit, pencatatan belanja material proyek beserta lampiran struk nota, pendaftaran & penugasan pekerja (Mandor/Tukang), serta pencatatan upah borongan unit properti.
            </p>
        </div>
    </div>
@endif
