<!-- TAB 1: MASTER DATA PROYEK & UNIT KAVLING -->
@if($activeTab === 'master_unit')
    <div class="card-clean p-6 sm:p-8 space-y-8 bg-white border border-slate-200/80 rounded-3xl shadow-xs">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">1</div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Fondasi Master Data</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Panduan Pengelolaan Proyek & Unit Kavling</h2>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('projects.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center gap-2 shadow-xs transition">
                    <span>Menu Proyek</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ route('units.index') }}" class="px-4 py-2.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl font-bold flex items-center gap-2 transition">
                    <span>Daftar Unit</span>
                </a>
            </div>
        </div>

        <!-- 3-Step Cards Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
            <!-- Step 1: Pendaftaran Proyek -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 01</span>
                        <span class="text-slate-400 font-mono text-[10px]">Founder / Super Admin</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Pendaftaran Master Proyek</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka menu <strong class="font-bold text-slate-900">Proyek Properti</strong> (`/projects`), lalu klik tombol <strong class="font-bold text-emerald-700">+ Tambah Proyek Baru</strong>.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1.5 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Parameter Wajib:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-1">
                        <li><strong>Nama & Lokasi Proyek:</strong> Contoh <em>Kavling Harmoni Asri - Rimbo Panjang</em>.</li>
                        <li><strong>Luas Standar (m²):</strong> Standar kavling acuan (misal 100 m²).</li>
                        <li><strong>Tarif Kelebihan Tanah /m²:</strong> Tarif otomatis per meter jika luas unit melebihi standar.</li>
                        <li><strong>Harga Beli Lahan:</strong> Total komitmen pembayaran lahan ke pemilik tanah.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 2: Input Unit & Rumus Dimensi -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 02</span>
                        <span class="text-slate-400 font-mono text-[10px]">Kalkulasi Otomatis</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Input Unit (Panjang × Lebar)</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka menu <strong class="font-bold text-slate-900">Unit Properti</strong> (`/units`), klik <strong class="font-bold text-emerald-700">+ Tambah Unit</strong>. Masukkan kode kavling (contoh: <em>BLOK-A01</em>).
                    </p>
                </div>
                <div class="p-3.5 bg-emerald-50/90 border border-emerald-200 rounded-xl space-y-1.5 text-emerald-950">
                    <div class="font-bold text-[11px]">Fitur Cerdas Sistem:</div>
                    <ul class="list-disc pl-4 text-emerald-900 space-y-1">
                        <li>Cukup isi <strong class="font-bold">Panjang (m)</strong> dan <strong class="font-bold">Lebar (m)</strong>, maka <strong class="font-bold">Luas Tanah (m²)</strong> terhitung instan.</li>
                        <li>Harga jual dasar dan kelebihan tanah dihitung otomatis dari standar proyek.</li>
                        <li>Tentukan <strong class="font-bold">Harga Pokok Penjualan (HPP)</strong> sebagai batas minimal negosiasi.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3: Halaman Detail Unit Sentral -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 03</span>
                        <span class="text-slate-400 font-mono text-[10px]">Kontrol Terpadu</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Halaman Detail Unit Sentral</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Klik nomor unit manapun untuk membuka halaman <strong class="font-bold text-slate-900">Detail Unit Terpadu</strong> (`/units/{id}`).
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Aksi Langsung Dari Detail Unit:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-0.5">
                        <li>Eksekusi Booking, Pelunasan Cash, atau Setup Cicilan.</li>
                        <li>Terbitkan SPP & SPJB PDF resmi dengan 1-klik.</li>
                        <li>Input belanja material & catatan upah borongan pekerja unit.</li>
                        <li>Kelola komisi marketing yang menjual unit ini.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pro Tips Callout -->
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3 text-xs text-amber-950">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            <div>
                <strong class="font-bold">Tips Master Data:</strong> Pastikan Anda telah menugaskan <em>Pengawas Lapangan</em> pada menu Proyek (`/projects`) melalui tombol <strong>Kelola Pengawas</strong> agar staf lapangan terkait dapat langsung menginput catatan belanja material dan memantau progres fisik pembangunan.
            </div>
        </div>
    </div>
@endif
