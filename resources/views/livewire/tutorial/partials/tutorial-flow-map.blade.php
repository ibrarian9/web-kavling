<!-- Visual Interactive Workflow Navigation Map -->
<div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-xs space-y-3">
    <div class="flex items-center justify-between">
        <h3 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider text-slate-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00-2 2h2a2 2 0 00-2-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span>Peta Modul & Alur Kerja Sistem</span>
        </h3>
        <span class="text-[11px] text-slate-400 font-medium hidden sm:inline">Pilih modul di bawah untuk membaca panduan praktis:</span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2.5 text-xs">
        <!-- 1. Master Proyek & Unit -->
        <div wire:click="setTab('master_unit')" class="cursor-pointer p-3 rounded-2xl border transition group {{ $activeTab === 'master_unit' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-500/20' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60 text-slate-800' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded {{ $activeTab === 'master_unit' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Modul 1</span>
                <svg class="w-4 h-4 {{ $activeTab === 'master_unit' ? 'text-white' : 'text-emerald-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h4 class="font-bold text-[11px] leading-tight">Master Unit</h4>
            <p class="text-[10px] opacity-80 truncate mt-0.5">Dimensi & HPP</p>
        </div>

        <!-- 2. Booking Fee -->
        <div wire:click="setTab('booking')" class="cursor-pointer p-3 rounded-2xl border transition group {{ $activeTab === 'booking' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-500/20' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60 text-slate-800' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded {{ $activeTab === 'booking' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Modul 2</span>
                <svg class="w-4 h-4 {{ $activeTab === 'booking' ? 'text-white' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <h4 class="font-bold text-[11px] leading-tight">Booking Fee</h4>
            <p class="text-[10px] opacity-80 truncate mt-0.5">Kunci & DP</p>
        </div>

        <!-- 3. Proposal Harga -->
        <div wire:click="setTab('proposals')" class="cursor-pointer p-3 rounded-2xl border transition group {{ $activeTab === 'proposals' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-500/20' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60 text-slate-800' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded {{ $activeTab === 'proposals' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Modul 3</span>
                <svg class="w-4 h-4 {{ $activeTab === 'proposals' ? 'text-white' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h4 class="font-bold text-[11px] leading-tight">Proposal SPP</h4>
            <p class="text-[10px] opacity-80 truncate mt-0.5">Margin & ACC</p>
        </div>

        <!-- 4. Pelunasan Cash & Dokumen Legalitas -->
        <div wire:click="setTab('cash_dokumen')" class="cursor-pointer p-3 rounded-2xl border transition group {{ $activeTab === 'cash_dokumen' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-500/20' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60 text-slate-800' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded {{ $activeTab === 'cash_dokumen' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Modul 4</span>
                <svg class="w-4 h-4 {{ $activeTab === 'cash_dokumen' ? 'text-white' : 'text-emerald-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h4 class="font-bold text-[11px] leading-tight">Cash & SPJB</h4>
            <p class="text-[10px] opacity-80 truncate mt-0.5">Surat PDF Sah</p>
        </div>

        <!-- 5. Cicilan Kredit -->
        <div wire:click="setTab('cicilan')" class="cursor-pointer p-3 rounded-2xl border transition group {{ $activeTab === 'cicilan' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-500/20' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60 text-slate-800' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded {{ $activeTab === 'cicilan' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Modul 5</span>
                <svg class="w-4 h-4 {{ $activeTab === 'cicilan' ? 'text-white' : 'text-teal-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h4 class="font-bold text-[11px] leading-tight">Kredit Cicilan</h4>
            <p class="text-[10px] opacity-80 truncate mt-0.5">Tenor & Tagihan</p>
        </div>

        <!-- 6. Operasional Lapangan -->
        <div wire:click="setTab('operasional')" class="cursor-pointer p-3 rounded-2xl border transition group {{ $activeTab === 'operasional' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-500/20' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60 text-slate-800' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded {{ $activeTab === 'operasional' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}"">Modul 6<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h4 class="font-bold text-[11px] leading-tight">Operasional</h4>
            <p class="text-[10px] opacity-80 truncate mt-0.5">Material & Upah</p>
        </div>

        <!-- 7. Arus Kas & Hutang Piutang -->
        <div wire:click="setTab('keuangan')" class="cursor-pointer p-3 rounded-2xl border transition group {{ $activeTab === 'keuangan' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-500/20' : 'bg-slate-50 border-slate-200 hover:bg-emerald-50/60 text-slate-800' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded {{ $activeTab === 'keuangan' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">Modul 7</span>
                <svg class="w-4 h-4 {{ $activeTab === 'keuangan' ? 'text-white' : 'text-emerald-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h4 class="font-bold text-[11px] leading-tight">Arus Kas & Gaji</h4>
            <p class="text-[10px] opacity-80 truncate mt-0.5">Hutang Piutang</p>
        </div>

        <!-- 8. Tanya Jawab (FAQ) -->
        <div wire:click="setTab('faq')" class="cursor-pointer p-3 rounded-2xl border transition group {{ $activeTab === 'faq' ? 'bg-slate-900 text-white border-slate-900 shadow-md ring-2 ring-slate-800' : 'bg-slate-50 border-slate-200 hover:bg-slate-100 text-slate-800' }}">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded {{ $activeTab === 'faq' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-800' }}">Q&A</span>
                <svg class="w-4 h-4 {{ $activeTab === 'faq' ? 'text-emerald-400' : 'text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h4 class="font-bold text-[11px] leading-tight">Tanya Jawab</h4>
            <p class="text-[10px] opacity-80 truncate mt-0.5">Solusi Kendala</p>
        </div>
    </div>
</div>
