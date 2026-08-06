<!-- Filters Toolbar -->
<div class="card-clean p-4 flex flex-col md:flex-row gap-3">
    <div class="flex-1">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, telepon, atau spesialisasi..." class="input-clean w-full">
    </div>
    <div class="w-full md:w-48">
        <select wire:model.live="typeFilter" class="input-clean w-full">
            <option value="">Semua Tipe Pekerja</option>
            <option value="mandor">Mandor</option>
            <option value="tukang">Tukang</option>
            <option value="kontraktor">Kontraktor</option>
        </select>
    </div>
    <div class="w-full md:w-48">
        <select wire:model.live="statusFilter" class="input-clean w-full">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
        </select>
    </div>
</div>

<!-- Table Card -->
<div class="card-clean overflow-hidden">
    <div class="overflow-x-auto relative min-h-[260px]">
        <!-- Reusable System Centered Table Loading Component -->
        <x-table-loading target="search, typeFilter, statusFilter, gotoPage, nextPage, previousPage" text="Memuat & Menyaring Data Pekerja..." subtext="Mohon tunggu sebentar, sistem sedang memproses data pekerja." />

        <table class="w-full text-left text-xs text-slate-600" wire:loading.class="opacity-30 pointer-events-none transition-opacity duration-300" wire:target="search, typeFilter, statusFilter, gotoPage, nextPage, previousPage">
            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3.5">Nama Pekerja</th>
                    <th class="px-5 py-3.5">Tipe & Spesialisasi</th>
                    <th class="px-5 py-3.5">Penugasan Bertugas</th>
                    <th class="px-5 py-3.5">No. Telepon</th>
                    <th class="px-5 py-3.5 text-center">Status</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($workers as $worker)
                    @php
                        $activeAssign = $worker->activeAssignments->first();
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-4 font-bold text-slate-900">
                            {{ $worker->name }}
                            <div class="text-[11px] text-slate-400 font-normal mt-0.5">{{ $worker->address ?? 'Alamat belum terisi' }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 mb-1">
                                @if ($worker->type === 'mandor')
                                    <span class="bg-amber-50 text-amber-800 border border-amber-200 font-semibold px-2 py-0.5 rounded-full text-[10px]">
                                        Mandor
                                    </span>
                                @elseif ($worker->type === 'kontraktor')
                                    <span class="bg-purple-50 text-purple-800 border border-purple-200 font-semibold px-2 py-0.5 rounded-full text-[10px]">
                                        Kontraktor
                                    </span>
                                @else
                                    <span class="bg-sky-50 text-sky-800 border border-sky-200 font-semibold px-2 py-0.5 rounded-full text-[10px]">
                                        Tukang
                                    </span>
                                @endif
                            </div>
                            <span class="text-slate-600 text-[11px] font-medium">{{ $worker->specialty ?: '-' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($activeAssign)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>{{ $activeAssign->project->name }} {{ $activeAssign->unit ? '(Unit ' . $activeAssign->unit->code . ')' : '(Seluruh Proyek)' }}</span>
                                </span>
                            @else
                                <span class="text-slate-400 italic text-[11px]">Belum Ditugaskan</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 font-mono">
                            {{ $worker->phone ?: '-' }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if ($worker->status === 'active')
                                <span class="status-tersedia">Aktif</span>
                            @else
                                <span class="status-draft">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                <button wire:click="openAssignModal({{ $worker->id }})" class="btn-action-assign" title="Tugaskan Pekerja ke Proyek / Unit">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>Tugaskan</span>
                                </button>
                                <button wire:click="edit({{ $worker->id }})" class="btn-action-edit" title="Edit Data Pekerja">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Edit</span>
                                </button>
                                <button type="button" @click="confirmModalAction({
                                    title: '{{ $worker->status === "active" ? "Nonaktifkan Status Pekerja" : "Aktifkan Status Pekerja" }}',
                                    message: '{{ $worker->status === "active" ? "Yakin ingin menonaktifkan status pekerja " . $worker->name . "?" : "Yakin ingin mengaktifkan kembali status pekerja " . $worker->name . "?" }}',
                                    confirmText: '{{ $worker->status === "active" ? "Nonaktifkan Pekerja" : "Aktifkan Pekerja" }}',
                                    btnClass: '{{ $worker->status === "active" ? "px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5" : "px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5" }}',
                                    onConfirm: () => $wire.toggleStatus({{ $worker->id }})
                                })" class="{{ $worker->status === 'active' ? 'btn-action-delete' : 'btn-action-payment' }}" title="Ubah Status Aktif/Nonaktif Pekerja">
                                    @if($worker->status === 'active')
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        <span>Nonaktifkan</span>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Aktifkan</span>
                                    @endif
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="font-semibold text-slate-600">Belum Ada Data Pekerja</p>
                            <p class="text-xs text-slate-400 mt-1">Daftarkan mandor atau tukang baru menggunakan tombol di atas.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3.5 border-t border-slate-100">
        {{ $workers->links() }}
    </div>
</div>
