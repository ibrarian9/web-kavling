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
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q1</span>
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
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q2</span>
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
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q3</span>
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
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q4</span>
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
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q5</span>
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
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q6</span>
                        <span>Mengapa tombol "+ Pembelian Cash" atau "Booking Unit" hilang pada unit tertentu?</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq6' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === 'faq6'" x-cloak class="px-4 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50">
                    Tombol transaksi hanya aktif jika status unit adalah <strong class="font-bold text-emerald-700">Tersedia</strong>. Apabila unit telah diproses menjadi <strong class="font-bold text-amber-700">Booked</strong> atau <strong class="font-bold text-rose-700">Terjual</strong> (karena diterbitkan SPP/SPJB), sistem secara otomatis mengunci dan menyembunyikan tombol transaksi untuk mencegah penjualan ganda.
                </div>
            </div>

            <!-- Question 7 -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                <button @click="activeFaq = activeFaq === 'faq7' ? null : 'faq7'" class="w-full p-4 text-left font-extrabold text-slate-900 text-xs sm:text-sm flex items-center justify-between hover:bg-slate-50 transition">
                    <span class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q7</span>
                        <span>Bagaimana cara kerja Log Aktivitas & Audit Sistem di menu Audit Trail?</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq7' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === 'faq7'" x-cloak class="px-4 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50">
                    Halaman Audit Log (<strong class="font-bold text-slate-900">`/activity-logs`</strong>) dilengkapi <strong class="font-bold text-emerald-700">3 Tab Utama</strong>: Audit Log Database, File Log (`laravel.log`), dan Log Dev Warnings. Tab Audit Log Database memperbarui data secara <strong class="font-bold text-emerald-700">otomatis setiap 5 detik di latar belakang</strong> tanpa perlu F5. Seluruh aksi pembuatan, pembaruan, penghapusan, ubah kata sandi, cetak PDF, dan mutasi kas tercatat lengkap secara otomatis.
                </div>
            </div>

            <!-- Question 8 -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
                <button @click="activeFaq = activeFaq === 'faq8' ? null : 'faq8'" class="w-full p-4 text-left font-extrabold text-slate-900 text-xs sm:text-sm flex items-center justify-between hover:bg-slate-50 transition">
                    <span class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">Q8</span>
                        <span>Apakah Invoice Manual yang diterbitkan akan otomatis terhubung ke Proyek & Unit?</span>
                    </span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeFaq === 'faq8' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="activeFaq === 'faq8'" x-cloak class="px-4 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3 bg-slate-50/50">
                    <strong class="text-emerald-700 font-bold">Ya, 100% tersinkronisasi!</strong> Saat membuat invoice manual di menu <strong class="font-bold text-slate-900">Invoice Manual</strong> (`/manual-invoices`), Anda dapat memilih Proyek dan Unit terkait. Jika status diset ke <strong class="font-bold text-emerald-700">Lunas</strong>, nominal uang masuk akan otomatis dihitung di Detail Proyek & Detail Unit, serta memperbarui status unit menjadi <strong class="font-bold text-emerald-800">Terjual</strong> jika kategori transaksi adalah Penjualan Unit.
                </div>
            </div>
        </div>
    </div>
@endif
