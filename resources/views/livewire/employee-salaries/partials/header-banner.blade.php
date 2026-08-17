<!-- Header Banner -->
<div class="card-clean p-6 bg-gradient-to-r from-emerald-900 via-teal-800 to-slate-900 text-white rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center font-extrabold text-xl text-emerald-300 shadow-inner">
            <svg class="w-7 h-7 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <h1 class="text-xl font-black tracking-tight flex items-center gap-2">
                <span>Penetapan & Penggajian Karyawan</span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-500/30 border border-purple-400/40 text-purple-200">
                    Akses Khusus Founder
                </span>
            </h1>
            <p class="text-emerald-100/80 text-xs mt-1">Kelola gaji pokok, tunjangan jabatan, potongan, pembayaran gaji bulanan, dan cetak Slip Gaji PDF resmi.</p>
        </div>
    </div>

    <x-button variant="emerald" size="md" wire:click="openSalaryModal" icon="plus">
        <span>Tetapkan Gaji Karyawan</span>
    </x-button>
</div>
