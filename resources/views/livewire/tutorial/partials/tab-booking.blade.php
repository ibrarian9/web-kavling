<!-- TAB 2: BOOKING FEE & TANDA JADI -->
@if($activeTab === 'booking')
    <div class="card-clean p-6 sm:p-8 space-y-8 bg-white border border-slate-200/80 rounded-3xl shadow-xs">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">2</div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Alur Tanda Jadi</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Panduan Booking Fee, Kunci Unit & Approval DP</h2>
                </div>
            </div>
            <a href="{{ route('bookings.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center gap-2 shadow-xs transition">
                <span>Buka Menu Booking Fee</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- 3-Step Cards Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
            <!-- Step 1: Input Booking Fee -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 01</span>
                        <span class="text-slate-400 font-mono text-[10px]">Marketing / Founder</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Input Uang Booking Fee</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Saat calon pembeli membayar tanda jadi (contoh Rp 2.000.000), buka menu <strong class="font-bold text-slate-900">Booking Fee & DP</strong> (`/bookings`) lalu klik <strong class="font-bold text-emerald-700">+ Tambah Booking Baru</strong>.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1.5 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Formulir Yang Diisi:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-1">
                        <li>Pilih Nama Proyek & Unit Kavling (atau Booking Tingkat Proyek).</li>
                        <li>Nama Lengkap & Nomor HP / WhatsApp Pembeli.</li>
                        <li>Nominal Booking Fee & Masa Berlaku (Default 14 hari).</li>
                        <li>Upload foto struk transfer / nota tanda terima kasir.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 2: Auto-Lock Status & Approval Finance -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 02</span>
                        <span class="text-slate-400 font-mono text-[10px]">Otomatis & Approval</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Penguncian Unit & Approval DP</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Sistem otomatis mengubah status unit menjadi <strong class="font-bold text-amber-700">Booked</strong> agar tidak bentrok dengan konsumen lain.
                    </p>
                </div>
                <div class="p-3.5 bg-emerald-50/90 border border-emerald-200 rounded-xl space-y-1.5 text-emerald-950">
                    <div class="font-bold text-[11px]">Approval Finance / Founder:</div>
                    <ul class="list-disc pl-4 text-emerald-900 space-y-1">
                        <li>Tim Finance atau Founder meninjau mutasi rekening bank.</li>
                        <li>Klik tombol <strong class="font-bold">Setujui Tanda Jadi (ACC DP)</strong>.</li>
                        <li>Dana otomatis tercatat ke pembukuan <strong class="font-bold">Arus Kas Masuk</strong>.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3: Cetak Kwitansi & Lanjut Transaksi -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 03</span>
                        <span class="text-slate-400 font-mono text-[10px]">Dokumen Sah</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Cetak Resi PDF & Ajukan SPP</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Klik tombol <strong class="font-bold text-emerald-700">PDF</strong> pada tabel untuk mencetak Kwitansi Tanda Jadi resmi PT. Atlantik Perkasa Abadi.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Kelanjutan Transaksi:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-0.5">
                        <li>Klik tombol <strong class="font-bold text-emerald-700">+ Proposal SPP</strong> untuk langsung mengajukan harga deal konsumen ke Founder/Supervisor.</li>
                        <li>Jika pembeli membatalkan, gunakan opsi <strong class="font-bold text-rose-600">Batalkan Booking</strong> (unit akan kembali tersedia).</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
