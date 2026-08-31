<div class="space-y-6">

    <!-- KPI Summary & Status Legend Bar -->
    <x-card padding="p-4 sm:p-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                    <span>Site Plan Interaktif & Denah Kavling Visual</span>
                </h3>
                <p class="text-slate-500 text-xs mt-0.5">Peta visual tata letak unit kavling & perumahan secara real-time berdasarkan status keterjualan</p>
            </div>

            <!-- Status Color Legend -->
            <div class="flex items-center gap-2 flex-wrap text-[11px] font-semibold">
                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-900 border border-emerald-300 flex items-center gap-1.5 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span>Tersedia ({{ $availableUnits }})</span>
                </span>
                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300 flex items-center gap-1.5 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                    <span>Booked ({{ $pendingUnits }})</span>
                </span>
                <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-900 border border-rose-300 flex items-center gap-1.5 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                    <span>Terjual ({{ $soldUnits }})</span>
                </span>
                <span class="px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-900 border border-indigo-300 flex items-center gap-1.5 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    <span>Fasum/Infra ({{ $infraUnitsCount }})</span>
                </span>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4">
            <x-search-input model="unitSearch" placeholder="Cari kode unit (cth: A-01)..." containerClass="w-full sm:w-72" />

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select wire:model.live="statusFilter" class="input-clean text-xs w-full sm:w-48 font-medium">
                    <option value="">Semua Status Unit</option>
                    <option value="tersedia">Tersedia (Ready)</option>
                    <option value="booked">Booked (Tanda Jadi)</option>
                    <option value="terjual">Terjual (Sold)</option>
                    <option value="disetujui">Disetujui (ACC)</option>
                    <option value="menunggu_persetujuan">Menunggu Approval</option>
                </select>

                <select wire:model.live="typeFilter" class="input-clean text-xs w-full sm:w-44 font-medium">
                    <option value="">Semua Kategori</option>
                    <option value="kavling">Kavling Tanah</option>
                    <option value="rumah">Unit Rumah</option>
                    <option value="infrastruktur">Fasum / Infrastruktur</option>
                </select>
            </div>
        </div>
    </x-card>

    <!-- Responsive Interactive Visual Siteplan Grid -->
    <div class="p-4 sm:p-6 bg-slate-100/90 rounded-3xl border border-slate-200 shadow-inner">
        <x-siteplan-visual-grid :units="$unitsList" :interactiveModal="true" modalAction="openSiteplanUnitModal" />
    </div>

    <!-- Quick Modal Detail Unit Siteplan -->
    @if($showSiteplanModal && $selectedSiteplanUnit)
        <x-modal-dialog show="showSiteplanModal" 
                        closeAction="closeSiteplanUnitModal" 
                        title="Detail Unit {{ $selectedSiteplanUnit->code }}" 
                        subTitle="{{ $selectedSiteplanUnit->project->name ?? 'Proyek' }}" 
                        maxWidth="max-w-md">
            <div class="space-y-3.5 text-xs">
                <!-- Status Badge Block -->
                <div class="p-3.5 rounded-2xl border flex items-center justify-between {{ in_array($selectedSiteplanUnit->status, ['terjual', 'disetujui']) ? 'bg-rose-50 border-rose-200 text-rose-900' : (in_array($selectedSiteplanUnit->status, ['booked', 'menunggu_persetujuan']) ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900') }}">
                    <span class="font-bold uppercase tracking-wider text-[10px]">Status Keterjualan Unit:</span>
                    <x-status-badge :status="$selectedSiteplanUnit->status" />
                </div>

                <!-- Specification Grid -->
                <div class="grid grid-cols-2 gap-2.5 bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80">
                    <div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase block">Kategori</span>
                        <span class="font-bold text-slate-800 capitalize">{{ $selectedSiteplanUnit->category }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase block">Dimensi Lahan</span>
                        <span class="font-bold text-slate-800 font-mono">{{ (float)$selectedSiteplanUnit->land_width }}m × {{ (float)$selectedSiteplanUnit->land_length }}m</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase block">Luas Tanah</span>
                        <span class="font-bold text-slate-800 font-mono">{{ (float)$selectedSiteplanUnit->land_area }} m²</span>
                    </div>
                    @if(auth()->user()->canViewSalesPrices())
                        <div>
                            <span class="text-[10px] text-slate-400 font-semibold uppercase block">Harga Jual Standar</span>
                            <span class="font-extrabold text-emerald-700 font-mono text-sm">Rp {{ number_format($selectedSiteplanUnit->final_selling_price ?? $selectedSiteplanUnit->hpp ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div>
                            <span class="text-[10px] text-slate-400 font-semibold uppercase block">Status Unit</span>
                            <span class="font-bold text-slate-800 capitalize">{{ ucfirst($selectedSiteplanUnit->status) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Info Pembeli / Booking jika ada -->
                @if($selectedSiteplanUnit->officialDocument)
                    <div class="p-3.5 bg-blue-50/80 border border-blue-200/80 rounded-2xl space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700">Pembeli Terdaftar (SPP):</span>
                        <p class="font-bold text-slate-900 text-xs">{{ $selectedSiteplanUnit->officialDocument->buyer_name }}</p>
                        <p class="text-[11px] font-mono text-slate-500">{{ $selectedSiteplanUnit->officialDocument->buyer_contact }}</p>
                    </div>
                @endif
            </div>

            <!-- Modal Footer Action Buttons -->
            <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row gap-2 justify-end shrink-0 mt-4">
                <x-button variant="secondary" size="sm" wire:click="closeSiteplanUnitModal">Tutup</x-button>
                <x-button variant="primary" size="sm" href="{{ route('units.show', $selectedSiteplanUnit->id) }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Ke Detail Lengkap Unit</span>
                </x-button>
            </div>
        </x-modal-dialog>
    @endif

</div>
