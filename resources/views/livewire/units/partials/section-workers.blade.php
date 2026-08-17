<!-- Assigned Workers (Mandor & Tukang) Card -->
<x-card padding="p-5">
    <!-- Header Section -->
    <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-2">
        <div class="flex items-center gap-2.5">
            <div class="p-2 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="font-extrabold text-slate-900 text-sm">Mandor & Tukang Bertugas</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                        {{ count($unitAssignments) }}
                    </span>
                </div>
            </div>
        </div>
        
        @if(auth()->user()->isSupervisor() || auth()->user()->isPengawasProject() || auth()->user()->isFounder())
            <x-button variant="outline" size="xs" wire:click="openWorkerModal" icon="plus" title="Tugaskan Pekerja">
                <span>Tugaskan</span>
            </x-button>
        @endif
    </div>

    <!-- Workers List -->
    <div class="space-y-2.5 text-xs">
        @forelse($unitAssignments as $assign)
            @if($assign->worker)
                <div class="p-3 bg-slate-50/90 rounded-2xl border border-slate-200/70 flex items-center justify-between gap-3 transition-all duration-200 hover:bg-white hover:shadow-xs hover:border-slate-300">
                    <!-- Left Side: 70% Width (Nama Mandor & Spesialisasi) -->
                    <div class="w-[70%] min-w-0 space-y-0.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm truncate leading-tight">{{ $assign->worker->name }}</h4>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-200/80 text-slate-700 uppercase tracking-wider">
                                {{ ucfirst($assign->worker->type) }}
                            </span>
                        </div>
                        <p class="text-[11px] text-blue-700 font-semibold truncate">
                            {{ $assign->assigned_role }}
                        </p>
                        <p class="text-[10px] text-slate-500 truncate">
                            Spesialisasi: <span class="font-medium text-slate-700">{{ $assign->worker->specialty ?: '-' }}</span>
                        </p>
                    </div>

                    <!-- Right Side: Action + Status -->
                    <div class="shrink-0 flex items-center justify-end gap-1.5 whitespace-nowrap flex-nowrap">
                        <x-status-badge status="tersedia" label="Active" />

                        @if(auth()->user()->isFounder())
                            <x-action-dropdown title="Menu Opsi Penugasan" size="xs">
                                <div class="py-1">
                                    <button type="button" wire:click="editWorkerAssignment({{ $assign->id }})" class="w-full text-left px-3.5 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition">
                                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit Penugasan</span>
                                    </button>
                                </div>
                                <div class="py-1">
                                    <button type="button" class="w-full text-left px-3.5 py-2 text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition"
                                    @click="confirmModalAction({
                                        title: 'Hapus Penugasan Pekerja',
                                        message: 'Yakin ingin menghapus penugasan {{ $assign->worker->name }} dari unit ini?',
                                        confirmText: 'Hapus Penugasan',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteWorkerAssignment({{ $assign->id }})
                                    })">
                                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus Penugasan</span>
                                    </button>
                                </div>
                            </x-action-dropdown>
                        @endif
                    </div>
                </div>
            @elseif($assign->user)
                <div class="p-3 bg-purple-50/80 rounded-2xl border border-purple-200/80 flex items-center justify-between gap-3 transition-all duration-200 hover:bg-purple-50">
                    <!-- Left Side: Nama Pengawas & Tugas -->
                    <div class="min-w-0 space-y-0.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="font-extrabold text-purple-950 text-xs sm:text-sm truncate leading-tight">{{ $assign->user->name }}</h4>
                            <span class="badge-role-pengawas text-[9px] px-1.5 py-0.5">
                                Pengawas
                            </span>
                        </div>
                        <p class="text-[11px] text-purple-800 font-semibold truncate">
                            {{ $assign->assigned_role }}
                        </p>
                        <p class="text-[10px] text-purple-600 truncate">
                            Spesialisasi: <span class="font-medium text-purple-900">Pengawasan Sistem</span>
                        </p>
                    </div>

                    <!-- Right Side: Action + Badge -->
                    <div class="shrink-0 flex items-center justify-end gap-1.5 whitespace-nowrap flex-nowrap">
                        <span class="badge-role-pengawas text-[9px] px-2 py-1 font-bold rounded-lg shrink-0">
                            Pengawas
                        </span>

                        @if(auth()->user()->isFounder())
                            <x-action-dropdown title="Menu Opsi Pengawas" size="xs">
                                <div class="py-1">
                                    <button type="button" wire:click="editWorkerAssignment({{ $assign->id }})" class="w-full text-left px-3.5 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition">
                                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit Penugasan</span>
                                    </button>
                                </div>
                                <div class="py-1">
                                    <button type="button" class="w-full text-left px-3.5 py-2 text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition"
                                    @click="confirmModalAction({
                                        title: 'Hapus Penugasan Pengawas',
                                        message: 'Yakin ingin menghapus penugasan pengawas {{ $assign->user->name }} dari unit ini?',
                                        confirmText: 'Hapus Penugasan',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteWorkerAssignment({{ $assign->id }})
                                    })">
                                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus Penugasan</span>
                                    </button>
                                </div>
                            </x-action-dropdown>
                        @endif
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center py-6 text-slate-400 text-xs italic bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                Belum ada penugasan mandor/tukang spesifik pada unit ini.
            </div>
        @endforelse
    </div>
</x-card>
