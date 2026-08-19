<!-- TAB 5: SKEMA CICILAN KREDIT BERTAHAP -->
@if($activeTab === 'cicilan')
    <div class="card-clean p-6 sm:p-8 space-y-8 bg-white border border-slate-200/80 rounded-3xl shadow-xs">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">5</div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Alur Kredit In-House</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Panduan Skema Pembelian Cicilan Bertahap</h2>
                </div>
            </div>
            <a href="{{ route('installments.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center gap-2 shadow-xs transition">
                <span>Buka Menu Cicilan Pembeli</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- 4-Step Cards Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 text-xs">
            <!-- Step 1: Legalitas Awal -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 01</span>
                        <span class="text-slate-400 font-mono text-[10px]">Legalitas SPP</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Terbitkan Dokumen SPP</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Terbitkan surat kesepakatan <strong class="font-bold text-slate-900">SPP & SPJB PDF</strong> terlebih dahulu di menu Surat Resmi atau Proposal Harga.
                    </p>
                </div>
                <div class="p-3 bg-slate-100/90 rounded-xl text-slate-700 text-[11px]">
                    <strong class="font-bold">Fungsi:</strong> Mengunci harga deal total, besaran uang muka (DP), dan identitas konsumen secara sah sebelum skema cicilan dibuka.
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
                        Di menu <strong class="font-bold text-slate-900">Cicilan Pembeli</strong> (`/installments`), klik <strong class="font-bold text-emerald-700">+ Setup Skema Cicilan</strong>.
                    </p>
                </div>
                <div class="p-3 bg-emerald-50/90 border border-emerald-200 rounded-xl space-y-1 text-emerald-950 text-[11px]">
                    <div class="font-bold">Kalkulasi Otomatis Sistem:</div>
                    <ul class="list-disc pl-4 text-emerald-900 space-y-0.5">
                        <li>Harga Deal dikurangi Uang Muka (DP).</li>
                        <li>Pilihan Tenor (12, 24, 36 Bulan).</li>
                        <li>Nominal Angsuran Bulanan terhitung otomatis tanpa bunga (0%).</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3: Input Setoran Angsuran -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 03</span>
                        <span class="text-slate-400 font-mono text-[10px]">Setoran Bulanan</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Input Setoran & Kwitansi</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Setiap konsumen menyetor angsuran bulanan, klik tombol <strong class="font-bold text-emerald-700">+ Input Setoran Cicilan</strong>.
                    </p>
                </div>
                <div class="p-3 bg-slate-100/90 rounded-xl space-y-1 text-slate-700 text-[11px]">
                    <div class="font-bold text-slate-900">Output Transaksi:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-0.5">
                        <li>Sisa piutang konsumen otomatis berkurang.</li>
                        <li>Cetak <strong class="font-bold">Kwitansi Invoice Setoran PDF</strong> instan untuk konsumen.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 4: Pelunasan / Konversi Cash -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 04</span>
                        <span class="text-slate-400 font-mono text-[10px]">Pelunasan / Konversi</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Pelunasan / Pelunasan Cepat</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Saat sisa piutang mencapai Rp 0, akun cicilan otomatis berstatus <strong class="font-bold text-emerald-700">Lunas</strong>.
                    </p>
                </div>
                <div class="p-3 bg-emerald-50/90 border border-emerald-200 rounded-xl text-emerald-950 text-[11px]">
                    <strong class="font-bold">Pelunasan Cepat:</strong> Jika pembeli ingin melunasi seluruh sisa cicilan lebih awal, gunakan tombol <strong class="font-bold">Batalkan Cicilan & Pelunasan Cash</strong>.
                </div>
            </div>
        </div>
    </div>
@endif
