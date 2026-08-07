<!-- Visual Interactive Flow Map Banner (Unified Emerald Theme - Single Top Navigation) -->
<div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
        <h3 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00-2 2h2a2 2 0 00-2-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span>Peta Alur Transaksi Properti</span>
        </h3>
        <span class="text-[11px] text-slate-400 font-medium">Klik pilihan untuk membuka panduan</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
        <div wire:click="setTab('booking')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'booking' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'booking' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Tahap 1</span>
                <span class="text-[10px] opacity-80 font-mono">Tersedia ➔ Booked</span>
            </div>
            <h4 class="font-bold text-xs">1. Booking Fee / NUP</h4>
            <p class="text-[11px] opacity-90 mt-1">Kunci unit & Resi Booking PDF</p>
        </div>

        <div wire:click="setTab('cash')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'cash' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'cash' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Tahap 2</span>
                <span class="text-[10px] opacity-80 font-mono">Persetujuan Deal</span>
            </div>
            <h4 class="font-bold text-xs">2. Pembelian Cash</h4>
            <p class="text-[11px] opacity-90 mt-1">ACC Deal & Resi Pelunasan</p>
        </div>

        <div wire:click="setTab('cicilan')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'cicilan' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'cicilan' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Tahap 3</span>
                <span class="text-[10px] opacity-80 font-mono">Kredit Tenor</span>
            </div>
            <h4 class="font-bold text-xs">3. Skema Cicilan</h4>
            <p class="text-[11px] opacity-90 mt-1">Angsuran & Invoice PDF</p>
        </div>

        <div wire:click="setTab('dokumen')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'dokumen' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'dokumen' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Tahap 4</span>
                <span class="text-[10px] opacity-80 font-mono">Legalitas Sah</span>
            </div>
            <h4 class="font-bold text-xs">4. Dokumen SPP & SPJB</h4>
            <p class="text-[11px] opacity-90 mt-1">Surat Perjanjian Jual Beli PDF</p>
        </div>

        <div wire:click="setTab('faq')" class="cursor-pointer p-3.5 rounded-2xl border transition group {{ $activeTab === 'faq' ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-slate-50 border-slate-200 hover:bg-slate-100' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $activeTab === 'faq' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-800' }}">Q&A</span>
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h4 class="font-bold text-xs">5. Tanya Jawab (FAQ)</h4>
            <p class="text-[11px] opacity-90 mt-1">Jawaban seputar operasional</p>
        </div>
    </div>
</div>
