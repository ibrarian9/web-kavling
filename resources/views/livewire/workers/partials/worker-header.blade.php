<!-- Header & Single-row Toolbar -->
<div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Master Data Mandor, Tukang & Kontraktor</h1>
        <p class="text-xs text-slate-500 mt-0.5">Pendaftaran dan direktori pekerja & kontraktor lapangan proyek perumahan</p>
    </div>
    <button wire:click="create" class="btn-primary whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Daftarkan Pekerja Baru</span>
    </button>
</div>

@if (session()->has('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif
