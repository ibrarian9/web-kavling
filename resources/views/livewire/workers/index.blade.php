<div class="space-y-6">
    <!-- Header & Single-row Toolbar -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Master Data Mandor & Tukang</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pendaftaran dan direktori pekerja lapangan proyek perumahan</p>
        </div>
        <button wire:click="create" class="btn-primary whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Daftarkan Pekerja Baru</span>
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
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, telepon, atau spesialisasi..." class="input-clean w-full">
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="typeFilter" class="input-clean w-full">
                <option value="">Semua Tipe Pekerja</option>
                <option value="mandor">Mandor</option>
                <option value="tukang">Tukang</option>
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
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Nama Pekerja</th>
                        <th class="px-5 py-3.5">Tipe</th>
                        <th class="px-5 py-3.5">Spesialisasi</th>
                        <th class="px-5 py-3.5">Telepon</th>
                        <th class="px-5 py-3.5 text-right">Total Sisa Piutang</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($workers as $worker)
                        @php
                            $totalLoan = $worker->loans_sum_amount ?? 0;
                            $paidLoan = $worker->loans_sum_paid_amount ?? 0;
                            $remainingLoan = max(0, $totalLoan - $paidLoan);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-900">
                                {{ $worker->name }}
                                <div class="text-[11px] text-slate-400 font-normal mt-0.5">{{ $worker->address ?? 'Alamat belum terisi' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                @if ($worker->type === 'mandor')
                                    <span class="bg-amber-50 text-amber-800 border border-amber-200 font-semibold px-2.5 py-0.5 rounded-full text-[11px] inline-flex items-center gap-1">
                                        Mandor
                                    </span>
                                @else
                                    <span class="bg-sky-50 text-sky-800 border border-sky-200 font-semibold px-2.5 py-0.5 rounded-full text-[11px] inline-flex items-center gap-1">
                                        Tukang
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-700">
                                {{ $worker->specialty ?: '-' }}
                            </td>
                            <td class="px-5 py-4 font-mono">
                                {{ $worker->phone ?: '-' }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-bold">
                                @if ($remainingLoan > 0)
                                    <span class="text-rose-600">Rp {{ number_format($remainingLoan, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-400 font-normal">Rp 0</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($worker->status === 'active')
                                    <span class="status-tersedia">Aktif</span>
                                @else
                                    <span class="status-draft">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right space-x-2">
                                <button wire:click="edit({{ $worker->id }})" class="text-emerald-600 hover:text-emerald-800 font-semibold transition">Edit</button>
                                <button wire:click="toggleStatus({{ $worker->id }})" class="text-slate-400 hover:text-slate-600 transition">
                                    {{ $worker->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
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

    <!-- Modal Create/Edit -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200/80">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $editingId ? 'Edit Data Pekerja' : 'Pendaftaran Pekerja Baru' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Nama Lengkap Pekerja</label>
                        <input type="text" wire:model="name" class="input-clean w-full font-bold">
                        @error('name') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Tipe Pekerja</label>
                            <select wire:model="type" class="input-clean w-full">
                                <option value="tukang">Tukang</option>
                                <option value="mandor">Mandor</option>
                            </select>
                            @error('type') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Status</label>
                            <select wire:model="status" class="input-clean w-full">
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                            @error('status') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Spesialisasi</label>
                        <input type="text" wire:model="specialty" placeholder="Contoh: Batu & Keramik, Kayu & Atap, Dll" class="input-clean w-full">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">No. Telepon / WhatsApp</label>
                        <input type="text" wire:model="phone" placeholder="08123456789" class="input-clean w-full font-mono">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Alamat Tempat Tinggal</label>
                        <textarea wire:model="address" rows="2" class="input-clean w-full"></textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Catatan Tambahan</label>
                        <textarea wire:model="notes" rows="2" class="input-clean w-full"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button wire:click="$set('showModal', false)" type="button" class="btn-secondary">Batal</button>
                    <button wire:click="save" type="button" class="btn-primary">Simpan Data</button>
                </div>
            </div>
        </div>
    @endif
</div>
