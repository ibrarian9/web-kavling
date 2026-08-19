<!-- TAB 6: BIAYA OPERASIONAL LAPANGAN, TUKANG & SALES REPORT -->
@if($activeTab === 'operasional')
    <div class="card-clean p-6 sm:p-8 space-y-8 bg-white border border-slate-200/80 rounded-3xl shadow-xs">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">6</div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Operasional & Lapangan</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Panduan Belanja Material, Upah Pekerja & Laporan Sales</h2>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('field-expenses.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center gap-2 shadow-xs transition">
                    <span>Biaya Lapangan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ route('workers.index') }}" class="px-4 py-2.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl font-bold flex items-center gap-2 transition">
                    <span>Pekerja</span>
                </a>
                <a href="{{ route('daily-activity-reports.index') }}" class="px-4 py-2.5 text-xs bg-blue-50 hover:bg-blue-100 text-blue-800 rounded-xl font-bold flex items-center gap-2 transition">
                    <span>Laporan Sales</span>
                </a>
            </div>
        </div>

        <!-- 3-Step Cards Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
            <!-- Step 1: Belanja Material Proyek -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Alur 01</span>
                        <span class="text-slate-400 font-mono text-[10px]">Pengawas / Supervisor</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Belanja Material Lapangan</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka menu <strong class="font-bold text-slate-900">Biaya Lapangan</strong> (`/field-expenses`) atau tombol belanja di halaman Detail Unit.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1.5 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Prosedur Input:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-1">
                        <li>Pilih Nama Toko Bangunan / Suplier dan Proyek Terkait.</li>
                        <li>Rincian Material: Nama Barang, Qty, Satuan (sak, m³, pcs), Harga Satuan.</li>
                        <li>Status Pembayaran: <strong class="font-bold">Lunas</strong> (mengurangi kas) atau <strong class="font-bold">Hutang Toko</strong> (masuk daftar hutang).</li>
                        <li>Wajib lampirkan foto struk/nota pembelian fisik.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 2: Manajemen Mandor & Upah Pekerja -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Alur 02</span>
                        <span class="text-slate-400 font-mono text-[10px]">Mandor & Tukang</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Pekerja & Upah Borongan</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka menu <strong class="font-bold text-slate-900">Pekerja Lapangan</strong> (`/workers`) untuk mendaftarkan Mandor, Tukang, atau Kontraktor.
                    </p>
                </div>
                <div class="p-3.5 bg-emerald-50/90 border border-emerald-200 rounded-xl space-y-1.5 text-emerald-950">
                    <div class="font-bold text-[11px]">Setup Borongan & Pembayaran Gaji:</div>
                    <ul class="list-disc pl-4 text-emerald-900 space-y-1">
                        <li>Buka Detail Unit terkait, klik <strong class="font-bold">+ Setup Borongan Tukang</strong>.</li>
                        <li>Masukkan total upah yang disepakati (misal Rp 15.000.000).</li>
                        <li>Setiap pencairan upah mingguan, klik <strong class="font-bold">+ Bayar Upah Tukang</strong>.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3: Laporan Aktivitas Harian Sales -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-blue-800 uppercase tracking-wider text-[10px] bg-blue-100 px-2.5 py-1 rounded-md">Alur 03</span>
                        <span class="text-slate-400 font-mono text-[10px]">Marketing & Supervisor</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Laporan Harian Sales Properti</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Setiap staf Marketing wajib mengisi <strong class="font-bold text-slate-900">Laporan Aktivitas Harian</strong> (`/daily-activity-reports`).
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Aktivitas Yang Dilaporkan:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-0.5">
                        <li>Jumlah prospek baru yang dihubungi via telepon / WhatsApp.</li>
                        <li>Survey lokasi kavling bersama konsumen (lampirkan foto di lokasi).</li>
                        <li>Follow-up calon pembeli dan catatan progres closing.</li>
                        <li>Supervisor & Founder dapat memberikan evaluasi langsung.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
