<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Daftar Proyek Kavling & Properti</h2>
            <p class="text-slate-500 text-xs mt-0.5">Kelola standar luas kavling, penugasan mandor/tukang, dan lihat dashboard penjualan & arus kas proyek</p>
        </div>

        @if(auth()->user()->isFounder() || auth()->user()->isSupervisor())
            <button wire:click="openModal" class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-md transition inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Proyek Baru</span>
            </button>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Projects Table Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/80 text-slate-700 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Nama Proyek & Lokasi</th>
                        <th class="p-3.5">Pekerja Lapangan</th>
                        <th class="p-3.5">Luas Standar (m²)</th>
                        <th class="p-3.5">Harga Dasar Standar</th>
                        <th class="p-3.5">Tarif Kelebihan / m²</th>
                        <th class="p-3.5">Jumlah Unit</th>
                        <th class="p-3.5 text-right">Aksi Proyek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $p)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            <td class="p-3.5">
                                <a href="{{ route('projects.show', $p->id) }}" class="font-bold text-slate-900 text-sm hover:text-emerald-600 transition block">
                                    {{ $p->name }}
                                </a>
                                <p class="text-slate-500 text-[11px] flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $p->location }}
                                </p>
                            </td>
                            <td class="p-3.5">
                                @php
                                    $allProjAssignments = $p->assignments->where('status', 'active');
                                    $firstProjWorker = $allProjAssignments->first();
                                    $projWorkerCount = $allProjAssignments->count();
                                @endphp
                                <div class="flex items-center gap-1">
                                    @if($firstProjWorker && $firstProjWorker->worker)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-800 border border-blue-200/80 max-w-[180px] truncate" title="{{ $firstProjWorker->worker->name }} ({{ ucfirst($firstProjWorker->worker->type) }})">
                                            <span class="truncate">{{ $firstProjWorker->worker->name }} ({{ ucfirst($firstProjWorker->worker->type) }})</span>
                                            @if($projWorkerCount > 1)
                                                <span class="text-blue-600 font-bold shrink-0">...</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">Belum ada pekerja</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-3.5 font-mono font-medium text-slate-700">{{ number_format($p->standard_land_area, 0, ',', '.') }} m²</td>
                            <td class="p-3.5 font-mono text-emerald-700 font-bold">Rp {{ number_format($p->base_price, 0, ',', '.') }}</td>
                            <td class="p-3.5 font-mono text-slate-700">Rp {{ number_format($p->excess_price_per_sqm, 0, ',', '.') }} / m²</td>
                            <td class="p-3.5 font-bold text-slate-800">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-full border border-slate-200">
                                    {{ $p->units_count }} Unit
                                </span>
                            </td>
                            <td class="p-3.5 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('projects.show', $p->id) }}" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-sm transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>{{ auth()->user()->isPengawasProject() ? 'Detail Proyek' : 'Detail Dashboard & Arus Kas' }}</span>
                                </a>

                                @if(auth()->user()->isSupervisor() || auth()->user()->isPengawasProject() || auth()->user()->isFounder())
                                    <button wire:click="openWorkerModal({{ $p->id }})" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold border border-blue-200 inline-flex items-center gap-1">
                                        + Pekerja
                                    </button>
                                @endif

                                <a href="{{ route('units.index', ['project_id' => $p->id]) }}" class="px-2.5 py-1 bg-sky-50 hover:bg-sky-100 text-sky-700 rounded-lg text-xs font-semibold border border-sky-200 inline-flex items-center gap-1">
                                    Lihat Unit
                                </a>

                                @if(auth()->user()->isFounder() || auth()->user()->isSupervisor())
                                    <button wire:click="editProject({{ $p->id }})" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold border border-slate-300">
                                        Edit
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada proyek yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200">
            {{ $projects->links() }}
        </div>
    </div>

    <!-- Modal Form Proyek -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">
                        {{ $editingProjectId ? 'Edit Data Proyek' : 'Tambah Proyek Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveProject" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Proyek</label>
                        <input type="text" wire:model="name" placeholder="Grand Kavling..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 outline-none">
                        @error('name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Lokasi Proyek</label>
                        <input type="text" wire:model="location" placeholder="Panam, Pekanbaru, Riau" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 outline-none">
                        @error('location') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Luas Standar (m²)</label>
                            <input type="number" step="0.01" wire:model="standard_land_area" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 outline-none">
                            @error('standard_land_area') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Harga Dasar Kavling (Rp)</label>
                            <x-currency-input model="base_price" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-bold font-mono focus:ring-2 focus:ring-emerald-500 outline-none" />
                            @error('base_price') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Harga per m² Kelebihan Tanah (Rp)</label>
                        <x-currency-input model="excess_price_per_sqm" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-bold font-mono focus:ring-2 focus:ring-emerald-500 outline-none" />
                        @error('excess_price_per_sqm') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-500 shadow-md transition">Simpan Proyek</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Form Penugasan Worker ke Proyek -->
    @if($showWorkerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Tugaskan Mandor / Tukang ke Proyek</h3>
                    <button wire:click="$set('showWorkerModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveWorkerAssignment" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Mandor / Tukang</label>
                        <select wire:model="worker_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-semibold focus:ring-2 focus:ring-blue-500 outline-none">
                            @foreach($allWorkers as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Peran Penugasan di Proyek</label>
                        <input type="text" wire:model="assigned_role" placeholder="Mandor Utama Proyek / Penataan Drainase..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="$set('showWorkerModal', false)" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-500 shadow-md transition">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
