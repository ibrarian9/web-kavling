<!-- TAB 3: INSTALLMENT PURCHASE FLOW -->
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
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
                                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
                                <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
