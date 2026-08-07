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
