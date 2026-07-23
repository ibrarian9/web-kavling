<div class="space-y-6">
    <!-- Header & Single-row Toolbar -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Penugasan Worker Per-Proyek & Unit</h1>
            <p class="text-xs text-slate-500 mt-0.5">Menu alokasi penugasan mandor dan tukang pada perumahan atau unit spesifik</p>
        </div>
        <button wire:click="create" class="btn-primary whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tugaskan Worker Baru</span>
        </button>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filters Toolbar -->
    <div class="card-clean p-4 flex flex-col md:flex-row gap-3">
        <div class="w-full md:w-64">
            <select wire:model.live="projectFilter" class="input-clean w-full">
                <option value="">Semua Perumahan / Proyek</option>
                @foreach ($projects as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="statusFilter" class="input-clean w-full">
                <option value="active">Penugasan Aktif</option>
                <option value="completed">Selesai</option>
                <option value="">Semua Status</option>
            </select>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Nama Pekerja</th>
                        <th class="px-5 py-3.5">Tipe</th>
                        <th class="px-5 py-3.5">Perumahan / Proyek</th>
                        <th class="px-5 py-3.5">Unit Spesifik</th>
                        <th class="px-5 py-3.5">Peran / Tugas</th>
                        <th class="px-5 py-3.5">Tgl Mulai</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($assignments as $assignment)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-900">
                                {{ $assignment->worker->name }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="capitalize text-[10px] font-bold px-2.5 py-0.5 rounded-md border {{ $assignment->worker->type === 'mandor' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-sky-50 text-sky-800 border-sky-200' }}">
                                    {{ $assignment->worker->type }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $assignment->project->name }}
                            </td>
                            <td class="px-5 py-4">
                                @if ($assignment->unit)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 font-mono">
                                        Kode: {{ $assignment->unit->code }} ({{ ucfirst($assignment->unit->category) }})
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Seluruh Proyek</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-700 font-medium">
                                {{ $assignment->assigned_role ?: '-' }}
                            </td>
                            <td class="px-5 py-4 font-mono font-medium text-slate-600">
                                {{ $assignment->start_date ? $assignment->start_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($assignment->status === 'active')
                                    <span class="status-disetujui">Aktif</span>
                                @else
                                    <span class="status-batal">Selesai</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if ($assignment->status === 'active')
                                    <button wire:click="completeAssignment({{ $assignment->id }})" class="btn-outline text-[11px] px-2.5 py-1 text-rose-600 border-rose-200 hover:bg-rose-50">Selesaikan</button>
                                @else
                                    <span class="text-[11px] font-semibold text-slate-400">Tuntas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Data Penugasan Worker</p>
                                <p class="text-xs text-slate-400 mt-1">Klik tombol "Tugaskan Worker Baru" untuk mengalokasikan mandor / tukang.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $assignments->links() }}
        </div>
    </div>

    <!-- Modal Create Penugasan -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200/80">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">
                        Form Penugasan Pekerja
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Pilih Worker (Mandor/Tukang)</label>
                        <select wire:model="worker_id" class="input-clean w-full font-bold">
                            <option value="">-- Pilih Pekerja --</option>
                            @foreach ($workers as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                            @endforeach
                        </select>
                        @error('worker_id') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Perumahan / Proyek</label>
                        <select wire:model.live="project_id" class="input-clean w-full">
                            <option value="">-- Pilih Proyek --</option>
                            @foreach ($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Unit Spesifik (Opsional)</label>
                        <select wire:model="unit_id" class="input-clean w-full font-semibold" {{ !$project_id ? 'disabled' : '' }}>
                            <option value="">-- Semua Unit / General Proyek --</option>
                            @foreach ($availableUnits as $u)
                                <option value="{{ $u->id }}">Unit Kode: {{ $u->code }} ({{ ucfirst($u->category) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Peran / Penugasan Khusus</label>
                        <input type="text" wire:model="assigned_role" placeholder="Contoh: Mandor Utama, Tukang Pasang Keramik, Dll" class="input-clean w-full">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Tanggal Mulai Penugasan</label>
                        <input type="date" wire:model="start_date" class="input-clean w-full font-mono">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button wire:click="$set('showModal', false)" type="button" class="btn-secondary">Batal</button>
                    <button wire:click="save" type="button" class="btn-primary">Simpan Penugasan</button>
                </div>
            </div>
        </div>
    @endif
</div>
