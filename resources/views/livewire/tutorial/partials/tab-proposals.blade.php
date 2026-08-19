<!-- TAB 3: PROPOSAL HARGA & APPROVAL DEAL -->
@if($activeTab === 'proposals')
    <div class="card-clean p-6 sm:p-8 space-y-8 bg-white border border-slate-200/80 rounded-3xl shadow-xs">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black flex items-center justify-center text-xl shadow-md">3</div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Alur Deal & Legalitas</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-0.5">Panduan Pengajuan & Approval Harga Jual Unit</h2>
                </div>
            </div>
            <a href="{{ route('proposals.index') }}" class="btn-action-primary px-4 py-2.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold flex items-center gap-2 shadow-xs transition">
                <span>Buka Menu Usulan Harga (SPP)</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- 3-Step Cards Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
            <!-- Step 1: Ajukan Proposal Harga -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 01</span>
                        <span class="text-slate-400 font-mono text-[10px]">Marketing / Sales</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Pengajuan Usulan Harga Deal</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Buka menu <strong class="font-bold text-slate-900">Usulan Harga (SPP)</strong> (`/proposals`) atau dari tombol <strong class="font-bold text-emerald-700">Proposal SPP</strong> di tabel booking.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1.5 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Kalkulasi Margin Transparan:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-1">
                        <li>Sistem otomatis menampilkan modal dasar <strong class="font-bold">HPP Unit</strong>.</li>
                        <li>Marketing menginput <strong class="font-bold">Harga Usulan Jual</strong> hasil negosiasi konsumen.</li>
                        <li>Estimasi <strong class="font-bold">Margin Keuntungan Perusahaan</strong> terhitung otomatis.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 2: Approval Ganda Sejajar -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 02</span>
                        <span class="text-slate-400 font-mono text-[10px]">Founder & Supervisor</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Review & Approval Bertingkat</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Founder dan Supervisor meninjau kelayakan margin keuntungan usulan harga tersebut.
                    </p>
                </div>
                <div class="p-3.5 bg-emerald-50/90 border border-emerald-200 rounded-xl space-y-1.5 text-emerald-950">
                    <div class="font-bold text-[11px]">Mekanisme Persetujuan:</div>
                    <ul class="list-disc pl-4 text-emerald-900 space-y-1">
                        <li>Founder atau Supervisor mengklik tombol <strong class="font-bold text-emerald-700">Review & ACC</strong>.</li>
                        <li>Dapat memilih opsi: <strong class="font-bold text-emerald-800">Disetujui</strong> atau <strong class="font-bold text-rose-700">Ditolak</strong> disertai catatan revisi.</li>
                        <li>Founder memiliki kewenangan *Override* untuk langsung menyetujui final.</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3: Terbitkan SPP & SPJB Resmi -->
            <div class="p-5 bg-gradient-to-b from-slate-50 to-white rounded-2xl border border-slate-200 shadow-xs space-y-3 relative overflow-hidden flex flex-col justify-between">
                <div class="w-1 bg-emerald-600 absolute left-0 top-0 bottom-0"></div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-black text-emerald-800 uppercase tracking-wider text-[10px] bg-emerald-100 px-2.5 py-1 rounded-md">Langkah 03</span>
                        <span class="text-slate-400 font-mono text-[10px]">Dokumen Legalitas</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Terbitkan Surat Resmi PDF</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Setelah proposal berstatus <strong class="font-bold text-emerald-700">Disetujui (ACC)</strong>, tombol <strong class="font-bold text-emerald-700">Terbitkan SPP</strong> akan aktif.
                    </p>
                </div>
                <div class="p-3.5 bg-slate-100/90 rounded-xl space-y-1 text-slate-700">
                    <div class="font-bold text-slate-900 text-[11px]">Hasil Penerbitan Dokumen:</div>
                    <ul class="list-disc pl-4 text-slate-600 space-y-0.5">
                        <li>Surat Pesanan Properti (<strong class="font-bold">SPP PDF</strong>) diterbitkan resmi.</li>
                        <li>Surat Perjanjian Jual Beli (<strong class="font-bold">SPJB PDF</strong>) dibuat otomatis.</li>
                        <li>Nomor surat ter-registrasi unik di database dokumen perusahaan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
