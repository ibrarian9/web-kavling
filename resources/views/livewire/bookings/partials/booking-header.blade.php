<!-- Header & Single-row Toolbar -->
<div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Booking Fee & DP (Proyek & Unit)</h1>
        <p class="text-xs text-slate-500 mt-0.5">Modul pencatatan pemesanan, verifikasi DP langsung di sistem, dan konfirmasi penjualan unit</p>
    </div>
    @if(!auth()->user()->isPengawasProject())
        <button wire:click="create" class="btn-primary whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Catat Booking Baru</span>
        </button>
    @endif
</div>
