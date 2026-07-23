<div class="space-y-6">

    <!-- Header Section & Action -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pencatatan Biaya Tukang & Material</h2>
            <p class="text-slate-500 text-xs mt-0.5">Pengelolaan beban proyek, operasional, dan vendor per lokasi proyek</p>
        </div>

        @if(auth()->user()->isFinance() || auth()->user()->isFounder())
            <button wire:click="openModal" class="btn-primary whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Catat Biaya Baru</span>
            </button>
        @endif
    </div>

    <!-- Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Kategori & Deskripsi</th>
                        <th class="px-5 py-3.5">Proyek / Unit</th>
                        <th class="px-5 py-3.5">Vendor / Tukang</th>
                        <th class="px-5 py-3.5">Nominal Biaya</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Status Pembayaran</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($costs as $cost)
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            <td class="px-5 py-4">
                                <span class="uppercase font-bold text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200/60">
                                    {{ $cost->category }}
                                </span>
                                <p class="font-bold text-slate-900 text-sm mt-1">{{ $cost->description }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-800">{{ $cost->project->name }}</p>
                                @if($cost->unit)
                                    <p class="text-slate-500 text-[11px] font-mono">Unit: {{ $cost->unit->code }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-700 font-medium">
                                {{ $cost->vendor_name ?: '-' }}
                            </td>
                            <td class="px-5 py-4 font-mono font-extrabold text-rose-600 text-sm">
                                Rp {{ number_format($cost->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-slate-600 font-mono font-medium">
                                {{ $cost->cost_date->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4">
                                @if($cost->status === 'dibayar')
                                    <span class="status-disetujui">Dibayar</span>
                                @else
                                    <span class="status-ditolak">Belum Dibayar</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($cost->status === 'belum_dibayar' && (auth()->user()->isFinance() || auth()->user()->isFounder()))
                                    <button wire:click="markAsPaid({{ $cost->id }})" class="btn-primary text-[11px] px-2.5 py-1">
                                        Lunasi
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Rincian Biaya Dicatat</p>
                                <p class="text-xs text-slate-400 mt-1">Klik "Catat Biaya Baru" untuk menambahkan pengeluaran operasional.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $costs->links() }}
        </div>
    </div>

    <!-- Modal Form Biaya -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Catat Biaya Proyek Baru</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveCost" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Proyek</label>
                        <select wire:model.live="project_id" class="input-clean w-full font-semibold">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Kategori</label>
                            <select wire:model="category" class="input-clean w-full font-semibold">
                                <option value="tukang">Upah Tukang</option>
                                <option value="material">Bahan Material</option>
                                <option value="perizinan">Biaya Perizinan</option>
                                <option value="lainnya">Lain-lain</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Tanggal Transaksi</label>
                            <input type="date" wire:model="cost_date" class="input-clean w-full font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Deskripsi Pekerjaan / Biaya</label>
                        <input type="text" wire:model="description" required placeholder="Pengecoran jalan, pasir 5 dam..." class="input-clean w-full font-medium">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nominal Biaya (Rp)</label>
                            <x-currency-input model="amount" class="input-clean w-full font-bold text-sm font-mono text-rose-700" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Vendor / Mandor</label>
                            <input type="text" wire:model="vendor_name" placeholder="Pak Slamet" class="input-clean w-full">
                        </div>
                    </div>


                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Status Pembayaran</label>
                        <select wire:model="status" class="input-clean w-full font-semibold">
                            <option value="belum_dibayar">Belum Dibayar (Hutang)</option>
                            <option value="dibayar">Langsung Dibayar (Kas Keluar)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Biaya</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
