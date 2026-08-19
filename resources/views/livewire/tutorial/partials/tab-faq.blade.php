<!-- TAB 8: TANYA JAWAB OPERASIONAL POPULER (FAQ) -->
@if($activeTab === 'faq')
    <div class="card-clean p-6 sm:p-8 space-y-6 bg-white border border-slate-200/80 rounded-3xl shadow-xs" x-data="{ activeFaq: 'faq1' }">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
            <div class="w-10 h-10 rounded-2xl bg-slate-900 text-emerald-400 flex items-center justify-center shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">Pertanyaan Populer</span>
                <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Tanya Jawab Seputar Operasional Harian (FAQ)</h2>
            </div>
        </div>

        <div class="space-y-3 text-xs">
            <!-- FAQ 1: Hak Akses Founder Mandiri -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                <button @click="activeFaq = activeFaq === 'faq1' ? null : 'faq1'" class="w-full p-4 text-left font-extrabold text-slate-900 flex items-center justify-between hover:bg-slate-50 transition">
                    <span class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q1</span>
                        <span>Apakah Founder bisa mengeksekusi seluruh transaksi secara mandiri tanpa bantuan Marketing?</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq1' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === 'faq1'" x-cloak class="px-4 pb-4 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50 text-slate-700">
                    <strong class="text-emerald-700 font-bold">BISA 100%!</strong> Founder memiliki hak istimewa (*Super Admin*) untuk melakukan seluruh tahapan alur operasional sendiri dari awal hingga selesai (meng-input Booking Fee, membuat Proposal Harga, menerbitkan berkas resmi SPP & SPJB PDF, meng-input pelunasan Cash / Setup Cicilan, mencatat belanja lapangan, hingga penggajian) tanpa harus menunggu input dari tim Marketing atau staf lainnya.
                </div>
            </div>

            <!-- FAQ 2: Otomasi SPP & SPJB PDF -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                <button @click="activeFaq = activeFaq === 'faq2' ? null : 'faq2'" class="w-full p-4 text-left font-extrabold text-slate-900 flex items-center justify-between hover:bg-slate-50 transition">
                    <span class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q2</span>
                        <span>Apakah dokumen SPP (Surat Pemesanan) dan SPJB (Surat Perjanjian) dibuat otomatis?</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq2' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === 'faq2'" x-cloak class="px-4 pb-4 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50 text-slate-700">
                    <strong>Ya, 100% otomatis dan terstandarisasi!</strong> Saat Anda menerbitkan dokumen di menu <strong class="font-bold text-slate-900">Surat Resmi PDF</strong> (`/documents`) atau langsung dari <strong class="font-bold text-slate-900">Detail Unit</strong>, sistem otomatis menyusun 2 berkas resmi notarial: <strong class="font-bold text-emerald-700">Surat Pesanan Penjualan (SPP)</strong> dan <strong class="font-bold text-emerald-700">Surat Perjanjian Jual Beli (SPJB)</strong> lengkap dengan identitas pihak pertama (Founder), pihak kedua (Pembeli), pasal-pasal perjanjian, spesifikasi unit, dan kolom tanda tangan bermaterai.
                </div>
            </div>

            <!-- FAQ 3: Pelunasan Cepat / Batalkan Cicilan ke Cash -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                <button @click="activeFaq = activeFaq === 'faq3' ? null : 'faq3'" class="w-full p-4 text-left font-extrabold text-slate-900 flex items-center justify-between hover:bg-slate-50 transition">
                    <span class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q3</span>
                        <span>Bagaimana jika pembeli kredit ingin melunasi seluruh sisa cicilan sekaligus secara cash?</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq3' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === 'faq3'" x-cloak class="px-4 pb-4 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50 text-slate-700">
                    Buka menu <strong class="font-bold text-slate-900">Cicilan Pembeli</strong> (`/installments`) atau halaman <strong class="font-bold text-slate-900">Detail Unit</strong> terkait, kemudian klik tombol <strong class="font-bold text-emerald-700">Batalkan Cicilan & Pelunasan Cash</strong>. Masukkan nominal sisa pelunasan cash, lalu sistem akan menutup akun cicilan tersebut dan mengonversi status unit menjadi <strong class="font-bold text-emerald-800">Terjual Lunas Cash</strong> serta membukukan penerimaan kas secara otomatis.
                </div>
            </div>

            <!-- FAQ 4: NIK KTP Founder di SPJB -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                <button @click="activeFaq = activeFaq === 'faq4' ? null : 'faq4'" class="w-full p-4 text-left font-extrabold text-slate-900 flex items-center justify-between hover:bg-slate-50 transition">
                    <span class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q4</span>
                        <span>Di mana mengatur NIK KTP, Alamat, dan Jabatan Founder agar otomatis tercetak di SPJB?</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq4' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === 'faq4'" x-cloak class="px-4 pb-4 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50 text-slate-700">
                    Buka menu <strong class="font-bold text-slate-900">Profil Akun & Legalitas</strong> (`/profile`) di pojok atas atau navigasi, lalu lengkapi <strong class="font-bold">NIK KTP, Jabatan Perusahaan, dan Alamat Domisili</strong> Anda. Data ini otomatis menjadi dasar pencetakan seluruh dokumen legalitas SPP & SPJB PDF tanpa perlu diketik ulang.
                </div>
            </div>

            <!-- FAQ 5: Arus Kas & Multi-Filter Waktu -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                <button @click="activeFaq = activeFaq === 'faq5' ? null : 'faq5'" class="w-full p-4 text-left font-extrabold text-slate-900 flex items-center justify-between hover:bg-slate-50 transition">
                    <span class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q5</span>
                        <span>Bagaimana cara memfilter laporan kas dan transaksi berdasarkan rentang tanggal tertentu?</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq5' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === 'faq5'" x-cloak class="px-4 pb-4 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50 text-slate-700">
                    Seluruh tabel di sistem (Arus Kas, Booking, Usulan SPP, Dokumen, Hutang Piutang, Biaya Lapangan, dan Gaji) telah dilengkapi toolbar <strong class="font-bold text-emerald-700">Filter Waktu / Periode Tanggal</strong>. Anda dapat memilih preset cepat (*Hari Ini, Pekan Ini, Bulan Ini, Tahun Ini*) atau memilih *Kustom Rentang Tanggal* dengan menentukan Tanggal Mulai dan Tanggal Selesai secara presisi.
                </div>
            </div>
        </div>
    </div>
@endif
