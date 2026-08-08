<!-- Header Section -->
<div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded-md bg-purple-100 text-purple-800 text-[10px] font-extrabold tracking-wider uppercase border border-purple-200">Khusus Founder</span>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Input Penjualan Masa Lalu (Terjual & Lunas)</h1>
        </div>
        <p class="text-xs text-slate-500 mt-1">Form khusus pendaftaran unit kavling/rumah yang sudah terjual & lunas 100% sebelum sistem SIM Properti dibuat.</p>
    </div>

    <button wire:click="openCreateModal" class="btn-header-setup bg-purple-700 hover:bg-purple-800 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Catat Unit Terjual Lunas</span>
    </button>
</div>

<!-- Highlight Information Banner -->
<div class="card-clean bg-amber-50/70 border border-amber-200/90 p-4 text-amber-900 text-xs flex items-start gap-3 shadow-2xs">
    <div class="p-2 bg-amber-100/80 rounded-xl text-amber-700 shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="space-y-1">
        <h4 class="font-bold text-amber-950 text-sm">Informasi Penting Pencatatan Unit Masa Lalu (Historis):</h4>
        <p class="text-slate-600 leading-relaxed">
            Form ini secara otomatis akan menandai unit sebagai <strong>STATUS TERJUAL</strong>, membuat data pemesanan (Booking ACC), menerbitkan dokumen <strong>SPP PDF Resmi Lunas</strong>, serta mencatat status skema cicilan/pembayaran menjadi <strong>LUNAS 100% (Sisa Rp 0)</strong>. Pengisian ini menjamin seluruh statistik dashboard & rekap data unit lengkap tanpa merusak siklus transaksi aktif.
        </p>
    </div>
</div>
