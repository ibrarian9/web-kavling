<!-- TAB 7: ARUS KAS, HUTANG PIUTANG, INVOICE & GAJI KARYAWAN -->
@if($activeTab === 'keuangan')
    <div class="card-clean p-6 sm:p-8 space-y-8 bg-white border border-slate-200/80 rounded-3xl shadow-xs">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">7</div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Keuangan & Pembukuan</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Panduan Arus Kas, Hutang Piutang & Penggajian</h2>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('cashflow.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center gap-2 shadow-xs transition">
                    <span>Arus Kas</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ route('payables.index') }}" class="px-4 py-2.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl font-bold flex items-center gap-2 transition">
                    <span>Hutang Piutang</span>
                </a>
                <a href="{{ route('employee-salaries.index') }}" class="px-4 py-2.5 text-xs bg-purple-50 hover:bg-purple-100 text-purple-800 rounded-xl font-bold flex items-center gap-2 transition">
                    <span>Gaji Karyawan</span>
                </a>
            </div>
        </div>

        <!-- 3-Step Cards Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
            <!-- Step 1: Arus Kas Real-Time -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Alur 01</span>
                        <span class="text-slate-400 font-mono text-[10px]">Otomatis Terintegrasi</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Pembukuan Arus Kas (Cashflow)</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Menu <strong class="font-bold text-slate-900">Arus Kas & Global</strong> (`/cashflow`) membukukan seluruh mutasi uang masuk dan keluar secara otomatis.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1.5 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Komponen Arus Kas:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-1">
                        <li><strong>Kas Masuk (+):</strong> Tanda Jadi Booking, Pelunasan Cash, DP, dan Angsuran Cicilan.</li>
                        <li><strong>Kas Keluar (-):</strong> Belanja Material, Upah Tukang, Komisi Marketing, dan Gaji Karyawan.</li>
                        <li>Dapat di-filter berdasarkan periode tanggal atau proyek spesifik.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 2: Manajemen 5 Tab Hutang Piutang -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Alur 02</span>
                        <span class="text-slate-400 font-mono text-[10px]">Kontrol Kewajiban</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Hutang Piutang Perusahaan</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka menu <strong class="font-bold text-slate-900">Hutang & Piutang</strong> (`/payables`) untuk mengontrol dan melunasi kewajiban keuangan.
                    </p>
                </div>
                <div class="p-3.5 bg-emerald-50/90 border border-emerald-200 rounded-xl space-y-1.5 text-emerald-950">
                    <div class="font-bold text-[11px]">5 Kategori Tab Terintegrasi:</div>
                    <ul class="list-disc pl-4 text-emerald-900 space-y-1">
                        <li><strong>Tab 1:</strong> Hutang Belanja Toko Material.</li>
                        <li><strong>Tab 2:</strong> Hutang Upah Tukang & Borongan.</li>
                        <li><strong>Tab 3:</strong> Hutang Komisi Penjualan Marketing.</li>
                        <li><strong>Tab 4:</strong> Piutang Pinjaman Kasbon Karyawan.</li>
                        <li><strong>Tab 5:</strong> Riwayat Global Transaksi Lunas.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3: Invoice Manual & Gaji Karyawan -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-purple-800 uppercase tracking-wider text-[10px] bg-purple-100 px-2.5 py-1 rounded-md">Alur 03</span>
                        <span class="text-slate-400 font-mono text-[10px]">Invoice & Payroll</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Invoice Manual & Gaji Karyawan</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Penerbitan invoice tagihan non-unit dan penggajian bulanan staf kantor.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Fitur Pendukung:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-0.5">
                        <li><strong>Invoice Manual (`/manual-invoices`):</strong> Buat invoice custom dengan logo dan kop resmi perusahaan.</li>
                        <li><strong>Gaji Karyawan (`/employee-salaries`):</strong> Rekap gaji pokok, tunjangan, potongan kasbon, dan cetak slip gaji.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
