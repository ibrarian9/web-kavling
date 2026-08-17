<!-- Header Section -->
<div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pengajuan & Persetujuan Harga Jual</h2>
        <p class="text-slate-500 text-xs mt-0.5">Alur approval berjenjang & paralel (Founder & Supervisor) sebelum penerbitan Surat Resmi</p>
    </div>

    @if(auth()->user()->isMarketing() || auth()->user()->isAdminOrFounder())
        <x-button variant="primary" size="sm" wire:click="openCreateModal" icon="plus">
            <span>Buat Pengajuan Harga</span>
        </x-button>
    @endif
</div>
