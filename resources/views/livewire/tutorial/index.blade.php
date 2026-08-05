<div class="space-y-6">
    <!-- Premium Glassmorphic Hero Banner (Unified Emerald & Dark Slate Theme) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white p-6 sm:p-10 shadow-2xl border border-slate-800">
        <!-- Subtle Glow Background -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-4xl space-y-4">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-extrabold uppercase tracking-wider">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Pusat Panduan & Tutorial Sistem</span>
                </span>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 text-xs font-bold">
                    <span>PT. Atlantik Perkasa Abadi</span>
                </span>
            </div>

            <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-white leading-tight">
                Panduan Lengkap Alur Penjualan & Operasional Properti
            </h1>
            <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-3xl">
                Petunjuk operasional penggunaan sistem penjualan kavling dan rumah — penerimaan <strong class="text-emerald-400 font-bold">Booking Fee</strong>, <strong class="text-emerald-400 font-bold">Pembelian Cash</strong>, <strong class="text-emerald-400 font-bold">Skema Kredit Cicilan</strong>, hingga penerbitan otomatis dokumen <strong class="text-emerald-300 font-bold">SPP & SPJB PDF</strong>.
            </p>

            <!-- Mode Switcher Header Control (Founder vs Umum) -->
            <div class="pt-2 flex flex-wrap items-center gap-3">
                <span class="text-xs font-bold text-slate-300">Pilih Mode Panduan:</span>
                <div class="inline-flex p-1 bg-slate-800/90 rounded-2xl border border-slate-700/80">
                    <button wire:click="setViewMode('founder')" class="px-4 py-2 rounded-xl text-xs font-extrabold transition flex items-center gap-2 {{ ($viewMode ?? 'founder') === 'founder' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        <span>Mode Founder</span>
                    </button>
                    <button wire:click="setViewMode('umum')" class="px-4 py-2 rounded-xl text-xs font-extrabold transition flex items-center gap-2 {{ ($viewMode ?? 'founder') === 'umum' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Mode Umum</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mode Highlight Info Box -->
    @if(($viewMode ?? 'founder') === 'founder')
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 text-xs flex items-start gap-3 shadow-xs">
            <div class="p-2.5 bg-emerald-600 text-white rounded-xl font-bold shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <div class="space-y-1">
                <div class="font-extrabold text-sm text-emerald-900">Mode Panduan Founder (Super Admin)</div>
                <p class="text-emerald-800 leading-relaxed">
                    Sebagai <strong class="font-bold">Founder / Direktur Utama</strong>, Anda dapat mengeksekusi <strong class="font-bold">seluruh alur transaksi secara mandiri langsung tanpa bantuan tim Marketing</strong> (termasuk meng-input Booking Fee, menerbitkan SPP & SPJB PDF, menyetujui proposal deal, hingga pencatatan pelunasan cash / cicilan).
                </p>
            </div>
        </div>
    @else
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-xs flex items-start gap-3 shadow-xs">
            <div class="p-2.5 bg-slate-800 text-white rounded-xl font-bold shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div class="space-y-1">
                <div class="font-extrabold text-sm text-slate-900">Mode Panduan Umum & Staf Operasional</div>
                <p class="text-slate-600 leading-relaxed">
                    Panduan alur kerja standar operasional harian untuk tim <strong class="font-bold">Marketing, Finance, Supervisor, dan Pengawas Lapangan</strong> dalam mengelola pemesanan, verifikasi dokumen, dan pencatatan transaksi.
                </p>
            </div>
        </div>
    @endif

    <!-- Visual Interactive Flow Map Banner (Unified Emerald Theme - Single Top Navigation) -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00-2 2h2a2 2 0 00-2-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Peta Alur Transaksi Properti</span>
            </h3>
            <span class="text-[11px] text-slate-400 font-medium">Klik pilihan untuk membuka panduan</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <div wire:click="setTab('booking')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'booking' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60' }}">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'booking' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Tahap 1</span>
                    <span class="text-[10px] opacity-80 font-mono">Tersedia ➔ Booked</span>
                </div>
                <h4 class="font-bold text-xs">1. Booking Fee / NUP</h4>
                <p class="text-[11px] opacity-90 mt-1">Kunci unit & Resi Booking PDF</p>
            </div>

            <div wire:click="setTab('cash')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'cash' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60' }}">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'cash' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Tahap 2</span>
                    <span class="text-[10px] opacity-80 font-mono">Persetujuan Deal</span>
                </div>
                <h4 class="font-bold text-xs">2. Pembelian Cash</h4>
                <p class="text-[11px] opacity-90 mt-1">ACC Deal & Resi Pelunasan</p>
            </div>

            <div wire:click="setTab('cicilan')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'cicilan' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60' }}">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'cicilan' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Tahap 3</span>
                    <span class="text-[10px] opacity-80 font-mono">Kredit Tenor</span>
                </div>
                <h4 class="font-bold text-xs">3. Skema Cicilan</h4>
                <p class="text-[11px] opacity-90 mt-1">Angsuran & Invoice PDF</p>
            </div>

            <div wire:click="setTab('dokumen')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'dokumen' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60' }}">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'dokumen' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Tahap 4</span>
                    <span class="text-[10px] opacity-80 font-mono">Legalitas Sah</span>
                </div>
                <h4 class="font-bold text-xs">4. Dokumen SPP & SPJB</h4>
                <p class="text-[11px] opacity-90 mt-1">Surat Perjanjian Jual Beli PDF</p>
            </div>

            <div wire:click="setTab('faq')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'faq' ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-slate-100' }}">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'faq' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-800' }}">Q&A</span>
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="font-bold text-xs">5. Tanya Jawab (FAQ)</h4>
                <p class="text-[11px] opacity-90 mt-1">Jawaban seputar operasional</p>
            </div>
        </div>
    </div>

    <!-- TAB 1: BOOKING UNIT FLOW -->
    @if($activeTab === 'booking')
        <div class="card-clean p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">1</div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Alur Tanda Jadi</span>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Alur Transaksi: Hanya Booking Fee / NUP Unit</h2>
                    </div>
                </div>
                <a href="{{ route('bookings.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    <span>Buka Menu Booking Fee & DP</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
                <!-- Step 1 Card -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-700 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 01</span>
                        <span class="text-slate-400 font-mono text-[10px]">
                            {{ ($viewMode ?? 'founder') === 'founder' ? 'Founder / Marketing / Admin' : 'Staf Marketing / Admin' }}
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Input Uang Booking Fee</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka menu <strong class="font-bold text-slate-900">Booking Fee & DP</strong> atau klik <strong class="font-bold text-emerald-700">+ Booking Unit</strong> di halaman <strong class="font-bold text-slate-900">Detail Unit</strong>.
                    </p>
                    <div class="p-3 bg-slate-100/80 rounded-xl space-y-1.5 text-slate-600">
                        <div class="font-bold text-slate-800 text-[11px]">Formulir Yang Diisi:</div>
                        <ul class="list-disc pl-4 text-slate-500 space-y-1">
                            <li>Pilih Nama Pembeli / Calon Konsumen</li>
                            <li>Masukkan Nominal Uang Booking (misal: Rp 2.000.000)</li>
                            <li>Upload foto struk transfer / bukti bayar</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 2 Card -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-700 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 02</span>
                        <span class="text-slate-400 font-mono text-[10px]">Otomatis Real-Time</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Update Status & Arus Kas</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Sistem secara otomatis mengunci unit agar tidak bisa dijual ke konsumen lain.
                    </p>
                    <div class="p-3 bg-emerald-50/80 border border-emerald-100 rounded-xl space-y-1.5 text-emerald-900">
                        <div class="font-bold text-[11px]">Hasil Otomatis Sistem:</div>
                        <ul class="list-disc pl-4 text-emerald-700 space-y-1">
                            <li>Status Unit berubah dari <strong class="font-bold text-emerald-800">Tersedia</strong> ke <strong class="font-bold text-emerald-700">Booked</strong></li>
                            <li>Uang masuk otomatis tercatat di <strong class="font-bold text-slate-900">Arus Kas Masuk</strong></li>
                        </ul>
                    </div>
                </div>

                <!-- Step 3 Card -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-700 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 03</span>
                        <span class="text-slate-400 font-mono text-[10px]">Dokumen Cetak</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Cetak Kwitansi Resi Booking</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Di menu <strong class="font-bold text-slate-900">Booking Fee & DP</strong>, klik tombol <strong class="font-bold text-emerald-700">Resi PDF</strong>.
                    </p>
                    <div class="p-3 bg-emerald-50/80 border border-emerald-100 rounded-xl text-emerald-900 space-y-1">
                        <div class="font-bold text-[11px]">Fungsi Resi PDF:</div>
                        <p class="text-slate-600">Diserahkan kepada calon pembeli sebagai bukti sah penerimaan tanda jadi booking fee dari developer.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 2: CASH PURCHASE FLOW -->
    @if($activeTab === 'cash')
        <div class="card-clean p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">2</div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Alur Pelunasan Tunai</span>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Alur Transaksi: Pembelian Cash</h2>
                    </div>
                </div>
                <a href="{{ route('units.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Pilih Unit Kavling / Rumah</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
                <!-- Tahap 1 -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-700 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Tahap 01</span>
                        <span class="text-slate-400 font-mono text-[10px]">
                            {{ ($viewMode ?? 'founder') === 'founder' ? 'Founder Direct Deal' : 'Marketing & Approval Founder' }}
                        </span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Proposal Harga Kesepakatan</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Jika ada kesepakatan harga diskon khusus, buat <strong class="font-bold text-emerald-700">+ Proposal Harga</strong> di Detail Unit.
                    </p>
                    <div class="p-3 bg-emerald-50/80 border border-emerald-100 rounded-xl text-emerald-900">
                        <strong class="font-bold">Approval Founder:</strong> Founder menyetujui proposal di menu <strong class="font-bold">Pengajuan & Approval</strong> (atau ter-ACC langsung jika dibuat Founder).
                    </div>
                </div>

                <!-- Tahap 2 -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-700 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Tahap 02</span>
                        <span class="text-slate-400 font-mono text-[10px]">Legalitas Otomatis</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Penerbitan Dokumen SPP & SPJB</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka menu <strong class="font-bold text-slate-900">Surat Resmi PDF</strong> atau <strong class="font-bold text-slate-900">Detail Unit</strong>, klik <strong class="font-bold text-emerald-700">+ Terbitkan Dokumen SPP</strong>.
                    </p>
                    <div class="p-3 bg-emerald-50/80 border border-emerald-100 rounded-xl space-y-1 text-emerald-900">
                        <div class="font-bold text-[11px]">2 Dokumen Sah Dihasilkan:</div>
                        <ul class="list-disc pl-4 text-emerald-800 space-y-1">
                            <li><strong class="font-bold">SPP PDF</strong> (Surat Pesanan Penjualan)</li>
                            <li><strong class="font-bold">SPJB PDF</strong> (Surat Perjanjian Jual Beli Pasal 1-5 lengkap Tanda Tangan Pembeli & Founder Penjual)</li>
                        </ul>
                    </div>
                </div>

                <!-- Tahap 3 -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-700 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Tahap 03</span>
                        <span class="text-slate-400 font-mono text-[10px]">Keuangan Lunas</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Input Pelunasan Cash & Resi</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Di halaman <strong class="font-bold text-slate-900">Detail Unit</strong>, klik tombol <strong class="font-bold text-emerald-700">+ Penjualan Cash / Pelunasan</strong>.
                    </p>
                    <div class="p-3 bg-emerald-50/80 border border-emerald-100 rounded-xl space-y-1 text-emerald-900">
                        <div class="font-bold text-[11px]">Finalisasi Transaksi:</div>
                        <ul class="list-disc pl-4 text-emerald-800 space-y-1">
                            <li>Status Unit menjadi <strong class="font-bold text-emerald-900">Terjual</strong></li>
                            <li>Sistem mencetak <strong class="font-bold text-slate-800">Resi Pelunasan Cash PDF</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Callout Box Khusus Pembelian Cash Langsung Tanpa Booking -->
            <div class="p-5 bg-slate-900 text-white rounded-2xl space-y-3 shadow-md border border-slate-800 text-xs">
                <div class="flex items-center gap-2 text-emerald-400 font-extrabold text-sm uppercase tracking-wider">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Petunjuk Founder: Pembelian Cash Langsung (Tanpa Melalui Booking Fee)</span>
                </div>
                <p class="text-slate-300 leading-relaxed">
                    Founder / Admin dapat melakukan penjualan cash lunas secara langsung <strong class="text-emerald-300 font-bold">tanpa harus meng-input Booking Fee terlebih dahulu</strong> melalui tombol aksi langsung:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div class="p-3 bg-slate-800/90 rounded-xl border border-slate-700/80 space-y-1">
                        <div class="font-extrabold text-emerald-400 text-xs">Cara 1: Tombol Direct "+ Pembelian Cash" Per Unit</div>
                        <p class="text-slate-300 text-[11px]">
                            Buka <strong class="text-white">Detail Unit</strong>, daftar unit <strong class="text-white">/units</strong>, atau tab unit di <strong class="text-white">Proyek</strong> &rarr; klik tombol <strong class="text-emerald-300">+ Pembelian Cash</strong> &rarr; isi Nama Pembeli, NIK KTP (terpisah), Alamat, dan Harga Cash (dengan titik ribuan otomatis). SPP & SPJB PDF langsung terbit dan unit otomatis berstatus <strong class="text-emerald-300">Terjual</strong>.
                        </p>
                    </div>
                    <div class="p-3 bg-slate-800/90 rounded-xl border border-slate-700/80 space-y-1">
                        <div class="font-extrabold text-emerald-400 text-xs">Cara 2: Terbitkan Dokumen SPP via Menu Surat Resmi PDF</div>
                        <p class="text-slate-300 text-[11px]">
                            Buka menu <strong class="text-white">Surat Resmi PDF</strong> &rarr; klik <strong class="text-emerald-300">+ Terbitkan Dokumen SPP</strong> &rarr; pilih unit & isi data transaksi. Dokumen SPP & SPJB PDF langsung terbit resmi dan unit otomatis berstatus Terjual.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 3: INSTALLMENT PURCHASE FLOW (REVISED EXECUTIVE DESIGN) -->
    @if($activeTab === 'cicilan')
        <div class="card-clean p-6 sm:p-8 space-y-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">3</div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Alur Kredit & Angsuran</span>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Panduan Skema Pembelian Cicilan (Kredit Kavling / Rumah)</h2>
                    </div>
                </div>
                <a href="{{ route('installments.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Buka Menu Cicilan Pembeli</span>
                </a>
            </div>

            <!-- Executive 4-Step Cards Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 text-xs">
                <!-- Step 1: Legalitas Surat -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 01</span>
                            <span class="text-slate-400 font-mono text-[10px]">Legalitas Awal</span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm">Terbitkan SPP & SPJB PDF</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Terbitkan berkas resmi <strong class="font-bold text-slate-900">SPP & SPJB PDF</strong> terlebih dahulu di menu Surat Resmi PDF atau Detail Unit.
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50/80 border border-emerald-100 rounded-xl text-emerald-900 text-[11px]">
                        <strong class="font-bold">Fungsi Legalitas:</strong> Mengunci kesepakatan harga deal, besaran DP, dan identitas para pihak (Pembeli & Penjual) secara sah.
                    </div>
                </div>

                <!-- Step 2: Setup Skema Kredit -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 02</span>
                            <span class="text-slate-400 font-mono text-[10px]">Setup Tenor</span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm">Setup Skema Cicilan</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Di menu <strong class="font-bold text-slate-900">Cicilan Pembeli</strong>, klik <strong class="font-bold text-emerald-700">+ Setup Skema Cicilan</strong>.
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50/80 border border-emerald-100 rounded-xl space-y-1 text-emerald-900 text-[11px]">
                        <div class="font-bold">Kalkulasi Otomatis Sistem:</div>
                        <ul class="list-disc pl-4 text-emerald-800 space-y-0.5">
                            <li>Total Harga Deal & Uang Muka (DP)</li>
                            <li>Tenor (12, 24, 36 Bulan)</li>
                            <li>Estimasi Angsuran per Bulan</li>
                        </ul>
                    </div>
                </div>

                <!-- Step 3: Input Angsuran Bulanan -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 03</span>
                            <span class="text-slate-400 font-mono text-[10px]">Setoran Bulanan</span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm">Input Setoran & Kwitansi</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Setiap pembeli menyetor angsuran bulanan, klik <strong class="font-bold text-emerald-700">+ Input Setoran Cicilan</strong>.
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50/80 border border-emerald-100 rounded-xl space-y-1 text-emerald-900 text-[11px]">
                        <div class="font-bold">Output Transaksi:</div>
                        <ul class="list-disc pl-4 text-emerald-800 space-y-0.5">
                            <li>Sisa Piutang Berkurang</li>
                            <li>Cetak <strong class="font-bold">Kwitansi Invoice Setoran PDF</strong></li>
                        </ul>
                    </div>
                </div>

                <!-- Step 4: Pelunasan / Pembatalan -->
                <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                    <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 04</span>
                            <span class="text-slate-400 font-mono text-[10px]">Finalisasi</span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm">Pelunasan / Konversi Cash</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Jika sisa piutang Rp 0, akun cicilan otomatis berstatus <strong class="font-bold text-emerald-800">Lunas</strong>.
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50/80 border border-emerald-100 rounded-xl text-emerald-900 text-[11px]">
                        <strong class="font-bold">Pelunasan Cepat:</strong> Gunakan fitur <strong class="font-bold">Batalkan Cicilan & Pelunasan Cash</strong> untuk melunasi sisa kredit sekaligus.
                    </div>
                </div>
            </div>

            <!-- Detailed Visual Box: Penjelasan Fitur Khusus Pelunasan & Konversi -->
            <div class="p-5 bg-slate-900 text-white rounded-2xl space-y-3 shadow-md border border-slate-800 text-xs">
                <div class="flex items-center gap-2 text-emerald-400 font-extrabold text-sm uppercase tracking-wider">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Informasi Khusus: Fitur Pelunasan Cepat & Konversi Kredit ke Cash</span>
                </div>
                <p class="text-slate-300 leading-relaxed">
                    Apabila pembeli yang sedang dalam skema kredit cicilan memutuskan untuk melunasi seluruh sisa angsurannya secara sekaligus sebelum masa tenor berakhir, Anda tidak perlu meng-input setoran bulanan satu per satu. Cukup tekan tombol <strong class="text-emerald-300 font-bold">Batalkan Cicilan & Pelunasan Cash</strong> di Detail Unit / Menu Cicilan. Sistem akan menutup catatan piutang dan mengonversi transaksi menjadi <strong class="text-white font-bold">Lunas Cash</strong> secara instan.
                </p>
            </div>
        </div>
    @endif

    <!-- TAB 4: OFFICIAL DOCUMENT SUMMARY -->
    @if($activeTab === 'dokumen')
        <div class="card-clean p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">4</div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Daftar Dokumen PDF</span>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Daftar Surat & Dokumen Resmi PDF Yang Dihasilkan Sistem</h2>
                    </div>
                </div>
                <a href="{{ route('documents.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Buka Menu Surat Resmi PDF</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                    <thead class="bg-slate-900 text-white font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Dokumen Surat PDF</th>
                            <th class="p-3.5">Kapan Diterbitkan?</th>
                            <th class="p-3.5">Komponen Utama Isi Surat</th>
                            <th class="p-3.5">Lokasi Download / Pratinjau</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 font-medium text-slate-700 bg-white">
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-bold text-slate-900">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-extrabold">
                                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>1. Resi Booking Fee (PDF)</span>
                                </span>
                            </td>
                            <td class="p-3.5 text-emerald-700 font-semibold">Saat pembayaran Uang Tanda Jadi Booking Fee</td>
                            <td class="p-3.5">Data Pembeli, Nominal Booking Fee, Kode Unit, Foto Struk Bayar</td>
                            <td class="p-3.5">Menu <strong class="font-bold text-slate-900">Booking Fee & DP</strong></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-bold text-slate-900">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-extrabold">
                                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>2. Surat Pesanan Penjualan / SPP (PDF)</span>
                                </span>
                            </td>
                            <td class="p-3.5 text-emerald-700 font-semibold">Saat pendaftaran pesanan resmi unit</td>
                            <td class="p-3.5">No SPP, Identitas Pembeli, Rincian Unit, Kesepakatan Harga Deal</td>
                            <td class="p-3.5">Menu <strong class="font-bold text-slate-900">Surat Resmi PDF</strong> & Detail Unit</td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-bold text-slate-900">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-extrabold">
                                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>3. Surat Perjanjian Jual Beli / SPJB (PDF)</span>
                                </span>
                            </td>
                            <td class="p-3.5 text-emerald-700 font-semibold">Otomatis bersamaan dengan SPP</td>
                            <td class="p-3.5">Pasal 1-5, NIK Pembeli, NIK Penjual (Founder), Tanda Tangan Kedua Pihak</td>
                            <td class="p-3.5">Menu <strong class="font-bold text-slate-900">Detail Unit</strong> & Surat Resmi PDF</td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-bold text-slate-900">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-extrabold">
                                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>4. Kwitansi Invoice Setoran Cicilan (PDF)</span>
                                </span>
                            </td>
                            <td class="p-3.5 text-emerald-700 font-semibold">Saat angsuran kredit bulanan dibayar</td>
                            <td class="p-3.5">Nominal Setoran, Sisa Piutang, Tenor Bulan, Struk Transfer</td>
                            <td class="p-3.5">Menu <strong class="font-bold text-slate-900">Cicilan Pembeli</strong></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-bold text-slate-900">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-extrabold">
                                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>5. Resi Pelunasan Cash (PDF)</span>
                                </span>
                            </td>
                            <td class="p-3.5 text-emerald-700 font-semibold">Saat pelunasan lunas tunai</td>
                            <td class="p-3.5">No Resi, Total Pelunasan, Rincian Unit Terjual Lunas</td>
                            <td class="p-3.5">Menu <strong class="font-bold text-slate-900">Detail Unit / Arus Kas</strong></td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 font-bold text-slate-900">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 border border-slate-300 font-extrabold">
                                    <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>6. Surat Perintah Kerja / SPK Worker (PDF)</span>
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-700 font-semibold">Saat borongan tukang/mandor ditetapkan</td>
                            <td class="p-3.5">No SPK, Nilai Kontrak Borongan, Identitas Worker, Format Hitam-Putih</td>
                            <td class="p-3.5">Menu <strong class="font-bold text-slate-900">Detail Unit (Gaji Borongan)</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- TAB 5: FAQ & INTERACTIVE Q&A ACCORDION -->
    @if($activeTab === 'faq')
        <div class="card-clean p-6 space-y-6" x-data="{ activeFaq: null }">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 text-emerald-400 font-black flex items-center justify-center text-xl shadow-md">?</div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">Pertanyaan Populer</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Tanya Jawab Operasional Sistem (FAQ)</h2>
                </div>
            </div>

            <div class="space-y-3">
                <!-- Question 1 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                    <button @click="activeFaq = activeFaq === 'faq1' ? null : 'faq1'" class="w-full p-4 text-left font-extrabold text-slate-900 text-xs sm:text-sm flex items-center justify-between hover:bg-slate-50 transition">
                        <span class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center">Q1</span>
                            <span>Apakah dokumen SPP & SPJB PDF otomatis dibuat bersamaan?</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq1' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === 'faq1'" x-cloak class="px-4 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50">
                        <strong>Ya, 100% otomatis!</strong> Saat Anda menerbitkan dokumen resmi di menu <strong class="font-bold text-slate-900">Surat Resmi PDF</strong> atau di <strong class="font-bold text-slate-900">Detail Unit</strong>, sistem langsung menerbitkan 2 berkas resmi sekaligus: <strong class="font-bold text-emerald-700">Surat Pesanan Penjualan (SPP)</strong> dan <strong class="font-bold text-emerald-700">Surat Perjanjian Jual Beli (SPJB)</strong> yang menyantumkan NIK Pembeli, NIK Penjual (Founder), Pasal 1-5, dan tempat tanda tangan kedua pihak.
                    </div>
                </div>

                <!-- Question 2 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                    <button @click="activeFaq = activeFaq === 'faq2' ? null : 'faq2'" class="w-full p-4 text-left font-extrabold text-slate-900 text-xs sm:text-sm flex items-center justify-between hover:bg-slate-50 transition">
                        <span class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center">Q2</span>
                            <span>Bagaimana jika pembeli awalnya mengambil cicilan, lalu ingin melunasi lebih cepat secara cash?</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq2' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === 'faq2'" x-cloak class="px-4 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50">
                        Anda cukup membuka menu <strong class="font-bold text-slate-900">Cicilan Pembeli</strong> atau halaman <strong class="font-bold text-slate-900">Detail Unit</strong> terkait, kemudian klik tombol <strong class="font-bold text-emerald-700">Batalkan Cicilan & Pelunasan Cash</strong>. Masukkan nominal sisa pelunasan cash, lalu sistem akan menutup akun cicilan dan mengonversinya menjadi status <strong class="font-bold text-emerald-800">Terjual Lunas Cash</strong>.
                    </div>
                </div>

                <!-- Question 3 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                    <button @click="activeFaq = activeFaq === 'faq3' ? null : 'faq3'" class="w-full p-4 text-left font-extrabold text-slate-900 text-xs sm:text-sm flex items-center justify-between hover:bg-slate-50 transition">
                        <span class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center">Q3</span>
                            <span>Di mana saya bisa melihat seluruh riwayat uang masuk dari Booking, Cash, dan Cicilan?</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq3' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === 'faq3'" x-cloak class="px-4 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50">
                        Seluruh riwayat penerimaan uang (Booking Fee, Pelunasan Cash, Uang Muka DP, dan Angsuran Cicilan Bulanan) secara otomatis tercatat di menu <strong class="font-bold text-slate-900">Arus Kas & Global</strong>. Anda juga dapat memfilter kas masuk berdasarkan proyek perumahan atau periode tanggal tertentu.
                    </div>
                </div>

                <!-- Question 4 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                    <button @click="activeFaq = activeFaq === 'faq4' ? null : 'faq4'" class="w-full p-4 text-left font-extrabold text-slate-900 text-xs sm:text-sm flex items-center justify-between hover:bg-slate-50 transition">
                        <span class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center">Q4</span>
                            <span>Apakah Founder bisa melakukan pembelian & pendaftaran unit mandiri tanpa Marketing?</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq4' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === 'faq4'" x-cloak class="px-4 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50">
                        <strong class="text-emerald-700 font-bold">BISA 100%!</strong> Founder memiliki hak akses penuh (*Super Admin*) untuk mengeksekusi seluruh alur transaksi secara mandiri langsung dari awal sampai akhir (meng-input Booking Fee, membuat Proposal Deal, menerbitkan dokumen SPP & SPJB PDF, hingga meng-input pelunasan Cash / Setup Cicilan) tanpa perlu bantuan atau persetujuan Marketing terlebih dahulu.
                    </div>
                </div>

                <!-- Question 5 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                    <button @click="activeFaq = activeFaq === 'faq5' ? null : 'faq5'" class="w-full p-4 text-left font-extrabold text-slate-900 text-xs sm:text-sm flex items-center justify-between hover:bg-slate-50 transition">
                        <span class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center">Q5</span>
                            <span>Bagaimana cara mengeset NIK KTP Founder agar otomatis tercetak di setiap SPJB PDF?</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq5' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === 'faq5'" x-cloak class="px-4 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50">
                        Buka menu <strong class="font-bold text-slate-900">Profil Akun & Legalitas</strong> (`/profile`) di navigasi utama, lalu masukkan NIK KTP, Jabatan, dan Alamat Anda. Data tersebut akan tersimpan permanen dan otomatis menjadi acuan dasar pembuatan dokumen SPJB PDF & SPP PDF tanpa perlu Anda ketik ulang.
                    </div>
                </div>

                <!-- Question 6 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                    <button @click="activeFaq = activeFaq === 'faq6' ? null : 'faq6'" class="w-full p-4 text-left font-extrabold text-slate-900 text-xs sm:text-sm flex items-center justify-between hover:bg-slate-50 transition">
                        <span class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center">Q6</span>
                            <span>Mengapa tombol "+ Pembelian Cash" atau "Booking Unit" hilang pada unit tertentu?</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq6' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === 'faq6'" x-cloak class="px-4 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50">
                        Tombol transaksi hanya aktif jika status unit adalah <strong class="font-bold text-emerald-700">Tersedia</strong>. Apabila unit telah diproses menjadi <strong class="font-bold text-amber-700">Booked</strong> atau <strong class="font-bold text-rose-700">Terjual</strong> (karena diterbitkan SPP/SPJB), sistem secara otomatis mengunci dan menyembunyikan tombol transaksi untuk mencegah penjualan ganda.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
