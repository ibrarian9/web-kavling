<div class="space-y-6">

    <!-- Header Section & Action -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span>Cicilan & Piutang</span>
                <span class="px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 text-[11px] font-extrabold border border-purple-200">Keuangan Proyek</span>
            </h2>
            <p class="text-slate-500 text-xs mt-0.5">
                @if($activeTab === 'unit_installments')
                    Pantau persentase pelunasan piutang pembeli, skema kredit berkala, dan histori setoran konsumen.
                @else
                    Kelola skema termin pembayaran pembelian tanah ke pemilik lahan dan riwayat pengeluaran kas lahan.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if($activeTab === 'unit_installments')
                @if($unpaidThisMonthCount > 0)
                    <x-button variant="outline" size="sm" 
                              wire:click="openViewerModal('pdf', '{{ route('installments.unpaid-pdf', ['project_id' => $projectIdFilter, 'search' => $search]) }}', 'Pratinjau PDF Laporan Pembeli Belum Bayar Bulan Ini')"
                              title="Pratinjau & Cetak Laporan PDF Pembeli Menunggak Bulan Ini">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak PDF Tunggakan ({{ $unpaidThisMonthCount }})</span>
                    </x-button>
                @endif

                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                    <x-button variant="emerald" size="sm" wire:click="openSetupModal" icon="plus">
                        Setup Cicilan Baru
                    </x-button>
                @endif
            @else
                @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                    <x-button variant="emerald" size="sm" wire:click="openLandPaymentModal" icon="plus">
                        Catat Bayar Lahan ke Penjual
                    </x-button>
                @endif
            @endif
        </div>
    </div>

    <!-- Navigation Tabs Bar (Pill Design) -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto custom-scrollbar">
        <!-- Tab 1: Cicilan Pembeli Unit -->
        <button type="button" 
                wire:click="setTab('unit_installments')" 
                class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-extrabold flex items-center gap-2 transition-all shrink-0 {{ $activeTab === 'unit_installments' ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20 ring-2 ring-slate-900/10' : 'bg-white text-slate-600 hover:bg-slate-100 hover:text-slate-900 border border-slate-200/80' }}">
            <svg class="w-4 h-4 {{ $activeTab === 'unit_installments' ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Cicilan & Piutang Pembeli</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $activeTab === 'unit_installments' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $installments->total() }}
            </span>
        </button>

        <!-- Tab 2: Pembayaran Lahan Proyek -->
        <button type="button" 
                wire:click="setTab('land_payments')" 
                class="px-4 py-2.5 rounded-2xl text-xs sm:text-sm font-extrabold flex items-center gap-2 transition-all shrink-0 {{ $activeTab === 'land_payments' ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20 ring-2 ring-slate-900/10' : 'bg-white text-slate-600 hover:bg-slate-100 hover:text-slate-900 border border-slate-200/80' }}">
            <svg class="w-4 h-4 {{ $activeTab === 'land_payments' ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Pembayaran Lahan Proyek</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $activeTab === 'land_payments' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                {{ $totalLandTransactions }} Transaksi
            </span>
        </button>
    </div>

    <!-- Active Tab Content -->
    @if($activeTab === 'unit_installments')
        @include('livewire.installments.partials.tab-unit-installments')
    @else
        @include('livewire.installments.partials.tab-land-payments')
    @endif

    <!-- Modal Setup Skema Cicilan Baru -->
    @include('livewire.installments.partials.modal-setup-installment')

    <!-- Modal Catat Pembayaran Setoran Cicilan Unit -->
    @include('livewire.installments.partials.modal-installment-payment')

    <!-- Modal Batalkan Skema Cicilan & Ganti ke Pelunasan Cash -->
    @include('livewire.installments.partials.modal-convert-to-cash')

    <!-- Modal Detail Rincian Skema Cicilan & Riwayat Setoran -->
    @include('livewire.installments.partials.modal-installment-detail')

    <!-- Modal Catat / Edit Pembayaran Lahan ke Penjual Tanah -->
    @include('livewire.installments.partials.modal-land-payment')

    <!-- Modal Detail Lengkap Pembayaran Lahan Proyek -->
    @include('livewire.installments.partials.modal-detail-land-payment')

    <!-- Modal Viewer PDF & Media Laporan -->
    <x-media-viewer-modal 
        :show="$showViewerModal ?? false" 
        :type="$viewerType ?? 'pdf'" 
        :url="$viewerUrl ?? ''" 
        :title="$viewerTitle ?? 'Pratinjau Dokumen'" 
        closeAction="closeViewerModal"
    />

</div>
