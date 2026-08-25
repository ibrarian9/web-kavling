<!-- Skema Pembayaran Lahan Proyek (ke Penjual Tanah) Card Header Summary -->
<div class="card-clean p-5 sm:p-6 bg-gradient-to-r from-purple-900 via-slate-900 to-indigo-900 text-white shadow-md rounded-2xl sm:rounded-3xl">
    <!-- Bagian Atas: Header Informasi & Tombol Aksi -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-purple-800/60">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md bg-purple-500/20 text-purple-300 font-mono text-[10px] uppercase font-bold border border-purple-400/30">Skema Beli Lahan dari Penjual</span>
                @if($project->total_project_price > 0 && $project->remaining_balance <= 0)
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/30 text-emerald-300 border border-emerald-400/40">LAHAN LUNAS</span>
                @endif
            </div>
            <h3 class="text-lg font-extrabold text-white tracking-tight">Pembelian & Pelunasan Lahan Proyek ke Penjual Tanah</h3>
            <p class="text-xs text-purple-200/80">Pencatatan termin & riwayat pembayaran tanah/lahan proyek {{ $project->name }} ke Penjual Lahan</p>
        </div>

        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
            <div class="shrink-0">
                <x-button variant="emerald" size="md" wire:click="openPaymentModal" class="w-full sm:w-auto shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Pembayaran Lahan</span>
                </x-button>
            </div>
        @endif
    </div>

    <!-- Bagian Bawah: 3 Card Metrik Finansial Lahan (Full Width & Terbaca Jelas) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-4 font-mono text-xs w-full pt-4">
        <!-- Card 1: Harga Beli Lahan -->
        <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15 min-w-0 shadow-2xs">
            <span class="text-purple-300 block text-[11px] uppercase font-bold tracking-wider">Harga Beli Lahan</span>
            <span class="font-black text-base sm:text-lg xl:text-xl text-white tracking-tight block whitespace-normal break-words mt-1.5" title="Rp {{ number_format($project->total_project_price, 0, ',', '.') }}">
                @if($project->total_project_price > 0)
                    Rp {{ number_format($project->total_project_price, 0, ',', '.') }}
                @else
                    <span class="text-slate-400 font-normal italic">Belum diset</span>
                @endif
            </span>
            <span class="text-[10px] text-purple-300/70 block mt-1 font-sans">Total komitmen beli lahan</span>
        </div>

        <!-- Card 2: Sudah Dibayar -->
        <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15 min-w-0 shadow-2xs">
            <span class="text-emerald-300 block text-[11px] uppercase font-bold tracking-wider">Sudah Dibayar</span>
            <span class="font-black text-base sm:text-lg xl:text-xl text-emerald-400 tracking-tight block whitespace-normal break-words mt-1.5" title="Rp {{ number_format($project->total_paid, 0, ',', '.') }}">
                Rp {{ number_format($project->total_paid, 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-emerald-300/70 block mt-1 font-sans">Akumulasi pelunasan</span>
        </div>

        <!-- Card 3: Sisa Hutang Lahan -->
        <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/15 min-w-0 shadow-2xs">
            <span class="text-amber-300 block text-[11px] uppercase font-bold tracking-wider">Sisa Hutang Lahan</span>
            <span class="font-black text-base sm:text-lg xl:text-xl text-amber-300 tracking-tight block whitespace-normal break-words mt-1.5" title="Rp {{ number_format($project->remaining_balance, 0, ',', '.') }}">
                Rp {{ number_format($project->remaining_balance, 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-amber-300/70 block mt-1 font-sans">Sisa kewajiban ke pemilik</span>
        </div>
    </div>

    <!-- Progress Bar -->
    @if($project->total_project_price > 0)
        <div class="mt-4 pt-3 border-t border-purple-800/60">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-[11px] font-bold text-purple-200 mb-1.5 gap-0.5">
                <span>Progress Pelunasan: {{ number_format($project->payment_progress_percentage, 1) }}%</span>
                <span class="font-mono text-[10px] sm:text-[11px]">Rp {{ number_format($project->total_paid, 0, ',', '.') }} / Rp {{ number_format($project->total_project_price, 0, ',', '.') }}</span>
            </div>
            <div class="w-full h-2.5 bg-slate-800/90 rounded-full overflow-hidden border border-purple-700/50">
                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500" style="width: {{ $project->payment_progress_percentage }}%"></div>
            </div>
        </div>
    @endif
</div>
