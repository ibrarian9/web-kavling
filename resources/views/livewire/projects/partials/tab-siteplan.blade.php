<div class="space-y-6">

    <!-- KPI Summary & Status Legend Bar -->
    <div class="card-clean p-4 sm:p-5 space-y-4">
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
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Tersedia ({{ $availableUnits }})</span>
                </span>
                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1.5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Booked ({{ $pendingUnits }})</span>
                </span>
                <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-800 border border-rose-200 flex items-center gap-1.5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>Terjual ({{ $soldUnits }})</span>
                </span>
                <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-800 border border-indigo-200 flex items-center gap-1.5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <span>Fasum/Infra ({{ $infraUnitsCount }})</span>
                </span>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
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
    </div>

    <!-- Responsive Interactive Visual Siteplan Grid -->
    <div class="card-clean p-4 sm:p-6 bg-slate-900/5 border border-slate-200">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3 sm:gap-4">
            @forelse($unitsList as $u)
                @php
                    $isInfra = ($u->category === 'infrastruktur' || $u->type === 'infrastruktur');
                    $isSold = in_array($u->status, ['terjual', 'disetujui']);
                    $isBooked = in_array($u->status, ['booked', 'menunggu_persetujuan']);
                    $isAvailable = in_array($u->status, ['tersedia', 'draft']);

                    if ($isInfra) {
                        $cardBg = 'bg-indigo-50/90 border-indigo-200 hover:border-indigo-400 text-indigo-950';
                        $badgeBg = 'bg-indigo-100 text-indigo-800 border-indigo-200';
                        $statusLabel = 'Fasum';
                    } elseif ($isSold) {
                        $cardBg = 'bg-rose-50/90 border-rose-200 hover:border-rose-400 text-rose-950';
                        $badgeBg = 'bg-rose-100 text-rose-800 border-rose-200';
                        $statusLabel = 'Terjual';
                    } elseif ($isBooked) {
                        $cardBg = 'bg-amber-50/90 border-amber-200 hover:border-amber-400 text-amber-950';
                        $badgeBg = 'bg-amber-100 text-amber-800 border-amber-200';
                        $statusLabel = 'Booked';
                    } else {
                        $cardBg = 'bg-emerald-50/90 border-emerald-200 hover:border-emerald-400 text-emerald-950';
                        $badgeBg = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                        $statusLabel = 'Tersedia';
                    }
                @endphp

                <div wire:click="openSiteplanUnitModal({{ $u->id }})" class="{{ $cardBg }} border rounded-2xl p-3 flex flex-col justify-between transition-all duration-200 transform hover:-translate-y-1 hover:shadow-md cursor-pointer group relative overflow-hidden min-h-[128px]">
                    <!-- Top Ribbon Header -->
                    <div class="flex items-center justify-between gap-1">
                        <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full border {{ $badgeBg }}">
                            {{ $statusLabel }}
                        </span>
                        <span class="text-[10px] font-mono text-slate-500 font-semibold">
                            {{ (float)$u->land_area }} m²
                        </span>
                    </div>

                    <!-- Unit Code & Category -->
                    <div class="my-2">
                        <p class="text-base sm:text-lg font-black font-mono tracking-tight group-hover:text-emerald-700 transition">
                            {{ $u->code }}
                        </p>
                        <p class="text-[10px] text-slate-500 font-medium capitalize truncate">
                            {{ $u->category === 'rumah' ? ($u->building_area ? 'Rumah Tipe ' . (int)$u->building_area : 'Rumah') : 'Kavling' }}
                        </p>
                    </div>

                    <!-- Bottom Price Tag -->
                    <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                        <span class="font-mono font-bold text-slate-800">
                            Rp {{ number_format($u->final_selling_price ?? $u->hpp ?? 0, 0, ',', '.') }}
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-emerald-600 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                    <p class="font-bold text-slate-600">Tidak ada unit yang sesuai dengan filter site plan</p>
                    <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau filter status unit.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Modal Detail Unit Siteplan -->
    @if($showSiteplanModal && $selectedSiteplanUnit)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6">
            <div class="bg-white rounded-2xl sm:rounded-3xl max-w-md w-full p-4 sm:p-6 shadow-2xl space-y-4 border border-slate-200 max-h-[90vh] flex flex-col my-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-black font-mono text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200">
                            {{ $selectedSiteplanUnit->code }}
                        </span>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm sm:text-base">Detail Unit Kavling / Rumah</h4>
                            <p class="text-[11px] text-slate-400">{{ $selectedSiteplanUnit->project->name ?? 'Proyek' }}</p>
                        </div>
                    </div>
                    <button wire:click="closeSiteplanUnitModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-3.5 text-xs flex-1 overflow-y-auto pr-1">
                    <!-- Status Badge Block -->
                    <div class="p-3 rounded-2xl border flex items-center justify-between {{ in_array($selectedSiteplanUnit->status, ['terjual', 'disetujui']) ? 'bg-rose-50 border-rose-200 text-rose-900' : (in_array($selectedSiteplanUnit->status, ['booked', 'menunggu_persetujuan']) ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-emerald-50 border-emerald-200 text-emerald-900') }}">
                        <span class="font-bold uppercase tracking-wider text-[10px]">Status Keterjualan Unit:</span>
                        <span class="font-bold text-xs uppercase">{{ $selectedSiteplanUnit->status }}</span>
                    </div>

                    <!-- Specification Grid -->
                    <div class="grid grid-cols-2 gap-2.5 bg-slate-50 p-3 rounded-2xl border border-slate-200/80">
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
                        <div>
                            <span class="text-[10px] text-slate-400 font-semibold uppercase block">Harga Jual / HPP</span>
                            <span class="font-extrabold text-emerald-700 font-mono text-sm">Rp {{ number_format($selectedSiteplanUnit->final_selling_price ?? $selectedSiteplanUnit->hpp ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Info Pembeli / Booking jika ada -->
                    @if($selectedSiteplanUnit->officialDocument)
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-2xl space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700">Pembeli Terdaftar (SPP):</span>
                            <p class="font-bold text-slate-900 text-xs">{{ $selectedSiteplanUnit->officialDocument->buyer_name }}</p>
                            <p class="text-[11px] font-mono text-slate-500">{{ $selectedSiteplanUnit->officialDocument->buyer_contact }}</p>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer Action Buttons -->
                <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row gap-2 justify-end shrink-0">
                    <a href="{{ route('units.show', $selectedSiteplanUnit->id) }}" class="btn-primary justify-center text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Ke Detail Lengkap Unit</span>
                    </a>
                    <button type="button" wire:click="closeSiteplanUnitModal" class="btn-secondary justify-center text-xs">Tutup</button>
                </div>
            </div>
        </div>
    @endif

</div>
