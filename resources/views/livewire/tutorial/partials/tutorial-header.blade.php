<!-- Premium Glassmorphic Hero Banner (Unified Emerald & Dark Slate Theme) -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white p-6 sm:p-10 shadow-2xl border border-slate-800">
    <!-- Subtle Glow Background -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl space-y-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-extrabold uppercase tracking-wider">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>Pusat Panduan & Tutorial Sistem</span>
            </span>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 text-xs font-bold">
                <span>PT. Atlantik Perkasa Abadi</span>
            </span>
        </div>

        <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-white leading-tight">
            Panduan Lengkap Alur Penjualan & Operasional Properti
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-3xl">
            Petunjuk operasional penggunaan sistem penjualan kavling dan rumah — penerimaan <strong class="text-emerald-400 font-bold">Booking Fee</strong>, <strong class="text-emerald-400 font-bold">Pembelian Cash</strong>, <strong class="text-emerald-400 font-bold">Skema Kredit Cicilan</strong>, hingga penerbitan otomatis dokumen <strong class="text-emerald-300 font-bold">SPP & SPJB PDF</strong>.
        </p>

        <!-- Mode Switcher Header Control (Founder vs Umum) -->
        <div class="pt-2 flex flex-wrap items-center gap-3">
            <span class="text-xs font-bold text-slate-300">Pilih Mode Panduan:</span>
            <div class="inline-flex p-1 bg-slate-800/90 rounded-2xl border border-slate-700/80">
                <button wire:click="setViewMode('founder')" class="px-4 py-2 rounded-xl text-xs font-extrabold transition flex items-center gap-2 {{ ($viewMode ?? 'founder') === 'founder' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    <span>Mode Founder</span>
                </button>
                <button wire:click="setViewMode('umum')" class="px-4 py-2 rounded-xl text-xs font-extrabold transition flex items-center gap-2 {{ ($viewMode ?? 'founder') === 'umum' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Mode Umum</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mode Highlight Info Box -->
@if(($viewMode ?? 'founder') === 'founder')
    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 text-xs flex items-start gap-3 shadow-xs">
        <div class="p-2.5 bg-emerald-600 text-white rounded-xl font-bold shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <div class="space-y-1">
            <div class="font-extrabold text-sm text-emerald-900">Mode Panduan Founder (Super Admin)</div>
            <p class="text-emerald-800 leading-relaxed">
                Sebagai <strong class="font-bold">Founder / Direktur Utama</strong>, Anda dapat mengeksekusi <strong class="font-bold">seluruh alur transaksi secara mandiri langsung tanpa bantuan tim Marketing</strong> (termasuk meng-input Booking Fee, menerbitkan SPP & SPJB PDF, menyetujui proposal deal, hingga pencatatan pelunasan cash / cicilan).
            </p>
        </div>
    </div>
@else
    <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-xs flex items-start gap-3 shadow-xs">
        <div class="p-2.5 bg-slate-800 text-white rounded-xl font-bold shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div class="space-y-1">
            <div class="font-extrabold text-sm text-slate-900">Mode Panduan Umum & Staf Operasional</div>
            <p class="text-slate-600 leading-relaxed">
                Panduan alur kerja standar operasional harian untuk tim <strong class="font-bold">Marketing, Finance, Supervisor, dan Pengawas Lapangan</strong> dalam mengelola pemesanan, verifikasi dokumen, dan pencatatan transaksi.
            </p>
        </div>
    </div>
@endif
