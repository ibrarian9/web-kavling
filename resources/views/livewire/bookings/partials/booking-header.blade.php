<!-- Header & Single-row Toolbar -->
<div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Booking Fee & DP (Proyek & Unit)</h1>
        <p class="text-xs text-slate-500 mt-0.5">Modul pencatatan pemesanan, verifikasi DP langsung di sistem, dan konfirmasi penjualan unit</p>
    </div>
    @if(!auth()->user()->isPengawasProject())
        <x-button variant="primary" size="sm" wire:click="create" icon="plus">
            <span>Catat Booking Baru</span>
        </x-button>
    @endif
</div>
