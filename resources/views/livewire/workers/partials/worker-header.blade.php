<!-- Header & Single-row Toolbar -->
<div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Master Data Mandor, Tukang & Kontraktor</h1>
        <p class="text-xs text-slate-500 mt-0.5">Pendaftaran dan direktori pekerja & kontraktor lapangan proyek perumahan</p>
    </div>
    <x-button variant="primary" size="sm" wire:click="create" icon="plus">
        <span>Daftarkan Pekerja Baru</span>
    </x-button>
</div>
