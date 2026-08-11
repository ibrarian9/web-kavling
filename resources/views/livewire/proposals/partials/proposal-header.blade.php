<!-- Header Section -->
<div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pengajuan & Persetujuan Harga Jual</h2>
        <p class="text-slate-500 text-xs mt-0.5">Alur approval berjenjang & paralel (Founder & Supervisor) sebelum penerbitan Surat Resmi</p>
    </div>

    @if(auth()->user()->isMarketing() || auth()->user()->isAdminOrFounder())
        <button wire:click="openCreateModal" class="btn-primary whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Buat Pengajuan Harga</span>
        </button>
    @endif
</div>
