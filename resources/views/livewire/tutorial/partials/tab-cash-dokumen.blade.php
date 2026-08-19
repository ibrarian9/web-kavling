<!-- TAB 4: PEMBELIAN CASH & DOKUMEN LEGALITAS (SPP & SPJB) -->
@if($activeTab === 'cash_dokumen')
    <div class="card-clean p-6 sm:p-8 space-y-8 bg-white border border-slate-200/80 rounded-3xl shadow-xs">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">4</div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Alur Pelunasan Tunai & Berkas Sah</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Panduan Pembelian Cash & Penerbitan SPP / SPJB PDF</h2>
                </div>
            </div>
            <a href="{{ route('documents.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center gap-2 shadow-xs transition">
                <span>Buka Menu Dokumen SPP/SPJB</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- 3-Step Cards Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
            <!-- Step 1: Eksekusi Pelunasan Cash -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 01</span>
                        <span class="text-slate-400 font-mono text-[10px]">Finance / Founder</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Pencatatan Pembayaran Cash</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka halaman <strong class="font-bold text-slate-900">Detail Unit</strong> (`/units/{id}`) atau dari proposal yang disetujui, lalu klik <strong class="font-bold text-emerald-700">Terima Pelunasan Cash</strong>.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1.5 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Parameter Pelunasan:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-1">
                        <li>Nominal Pelunasan Bersih (dikurangi Booking Fee jika ada).</li>
                        <li>Metode Pembayaran: Transfer Bank / Tunai Kasir.</li>
                        <li>Upload foto struk transfer / bukti mutasi bank.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 2: Auto-Update Status & Arus Kas -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 02</span>
                        <span class="text-slate-400 font-mono text-[10px]">Otomatis Real-Time</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Status Terjual & Kas Masuk</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Sistem secara otomatis memperbarui status unit menjadi <strong class="font-bold text-emerald-700">Terjual (Cash)</strong>.
                    </p>
                </div>
                <div class="p-3.5 bg-emerald-50/90 border border-emerald-200 rounded-xl space-y-1.5 text-emerald-950">
                    <div class="font-bold text-[11px]">Dampak Langsung ke Sistem:</div>
                    <ul class="list-disc pl-4 text-emerald-900 space-y-1">
                        <li>Uang masuk otomatis dibukukan pada <strong class="font-bold text-slate-900">Arus Kas Masuk</strong>.</li>
                        <li>Unit tertutup dari penjualan baru dan tercatat pada laporan closing.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3: Cetak Berkas Legalitas SPP & SPJB PDF -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 03</span>
                        <span class="text-slate-400 font-mono text-[10px]">Cetak Berkas Sah</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Cetak SPP, SPJB & Kwitansi PDF</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka menu <strong class="font-bold text-slate-900">Surat Resmi PDF</strong> (`/documents`), klik tombol <strong class="font-bold text-emerald-700">Lihat SPP / SPJB</strong>.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Spesifikasi Dokumen PDF:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-0.5">
                        <li>Format standar hukum notaris (Pasal 1 s.d. 5 lengkap).</li>
                        <li>Identitas NIK Penjual (Founder) & NIK Pembeli tercantum otomatis.</li>
                        <li>Dilengkapi barcode / stempel dan kolom tanda tangan bermaterai.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
