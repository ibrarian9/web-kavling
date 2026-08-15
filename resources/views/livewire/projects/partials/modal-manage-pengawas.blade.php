<!-- Modal Form Kelola & Penugasan Pengawas Project (Founder Only) -->
@if($showWorkerModal)
    <x-modal-dialog show="showWorkerModal" 
                    :title="'Kelola Pengawas Proyek ' . ($selectedProjectForModal->name ?? '')" 
                    subTitle="Tugaskan, pindahkan, atau copot pengawas lapangan" 
                    maxWidth="max-w-lg">
        <div class="space-y-4 text-xs">
            @if($selectedProjectForModal)
                <!-- Section 1: Daftar Pengawas Aktif Saat Ini di Proyek ini -->
                <div>
                    <h4 class="font-bold text-slate-800 mb-2 uppercase text-[11px] tracking-wider flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Pengawas Aktif Saat Ini di Proyek Ini</span>
                    </h4>
                    @php
                        $activeAssignments = $selectedProjectForModal->assignments->where('status', 'active')->filter(fn($a) => $a->user_id !== null);
                    @endphp
                    @forelse($activeAssignments as $pa)
                        <div class="p-3 bg-purple-50/70 border border-purple-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                            <div>
                                <span class="font-bold text-purple-900 text-xs flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $pa->user->name ?? 'Pengawas' }}
                                </span>
                                <p class="text-slate-500 text-[10px]">{{ $pa->user->email ?? '' }} ({{ $pa->assigned_role ?? 'Pengawas Proyek' }})</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <!-- Dropdown Pindahkan Proyek -->
                                <select onchange="if(this.value) @this.call('movePengawasAssignment', {{ $pa->id }}, this.value)" class="text-[11px] bg-white border border-purple-200 rounded-xl px-2.5 py-1 font-semibold text-purple-900 focus:ring-1 focus:ring-purple-500">
                                    <option value="">Pindahkan Proyek</option>
                                    @foreach($allProjects as $otherProj)
                                        @if($otherProj->id !== $selectedProjectForModal->id)
                                            <option value="{{ $otherProj->id }}">Ke {{ $otherProj->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="button" @click="confirmModalAction({
                                    title: 'Copot Pengawas Proyek',
                                    message: 'Yakin ingin mencopot {{ $pa->user->name ?? 'Pengawas ini' }} dari proyek ini?',
                                    confirmText: 'Copot Pengawas',
                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                    onConfirm: () => $wire.removePengawasAssignment({{ $pa->id }})
                                })" class="px-2.5 py-1 text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl text-[11px] font-semibold transition" title="Copot Pengawas">
                                    Copot
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs italic bg-slate-50 p-3 rounded-2xl border border-slate-100 mb-2">Belum ada pengawas yang ditugaskan pada proyek ini.</p>
                    @endforelse
                </div>
            @endif

            <!-- Section 2: Form Tambah Penugasan Pengawas Baru -->
            <div class="pt-3 border-t border-slate-100">
                <h4 class="font-bold text-slate-800 mb-2 uppercase text-[11px] tracking-wider">Penugasan Pengawas Baru</h4>
                <form wire:submit.prevent="saveWorkerAssignment" class="space-y-3">
                    <div>
                        <label class="block font-semibold text-purple-900 mb-1">Pilih Akun Pengawas Project</label>
                        <select wire:model="assign_user_id" class="w-full select-clean font-bold text-xs">
                            @forelse($pengawasUsers as $pu)
                                <option value="{{ $pu->id }}">{{ $pu->name }} ({{ $pu->email }})</option>
                            @empty
                                <option value="">Semua Pengawas Project sudah ditugaskan pada proyek ini</option>
                            @endforelse
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Peran / Posisi Penugasan</label>
                        <input type="text" wire:model="assigned_role" placeholder="Pengawas Utama Proyek A..." class="w-full input-clean font-bold text-xs">
                    </div>

                    <div class="flex justify-end pt-2">
                        <x-button type="submit" variant="primary" size="xs">Tugaskan Pengawas Baru</x-button>
                    </div>
                </form>
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <x-button type="button" variant="outline" size="sm" wire:click="$set('showWorkerModal', false)">Tutup</x-button>
            </div>
        </div>
    </x-modal-dialog>
@endif
