<div class="space-y-6">
    <!-- Header & Single-row Toolbar -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Log Barang Mingguan (Pengawas Project)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pencatatan mingguan barang & material yang diambil atau dibeli oleh mandor/tukang</p>
        </div>
        <button wire:click="create" class="btn-primary whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Input Barang Mingguan</span>
        </button>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Banner Info Rule -->
    <div class="p-4 bg-amber-50 border border-amber-200/80 rounded-2xl text-amber-900 text-xs flex items-start gap-3 shadow-2xs">
        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <span class="font-bold">Aturan Pembebanan Log Barang:</span> Setiap penginputan transaksi barang/material mingguan oleh Pengawas akan <strong>otomatis diterbitkan sebagai piutang worker</strong> (Mandor/Tukang terkait) untuk pemotongan saat opname.
        </div>
    </div>

    <!-- Filters & KPI -->
    <div class="card-clean p-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <div class="w-full sm:w-56">
                <select wire:model.live="projectFilter" class="input-clean w-full">
                    <option value="">Semua Perumahan / Proyek</option>
                    @foreach ($projects as $proj)
                        <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-56">
                <select wire:model.live="workerFilter" class="input-clean w-full">
                    <option value="">Semua Mandor & Tukang</option>
                    @foreach ($workers as $w)
                        <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="text-right w-full md:w-auto border-t md:border-t-0 pt-2.5 md:pt-0 border-slate-100">
            <div class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">Total Log Pembelian Barang</div>
            <div class="text-xl font-bold font-mono text-emerald-600">Rp {{ number_format($totalWeeklyPurchases, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Tgl Transaksi</th>
                        <th class="px-5 py-3.5">Perumahan / Unit</th>
                        <th class="px-5 py-3.5">Mandor / Tukang</th>
                        <th class="px-5 py-3.5">Pengawas Pencatat</th>
                        <th class="px-5 py-3.5">Nama Barang / Material</th>
                        <th class="px-5 py-3.5 text-center">Jumlah</th>
                        <th class="px-5 py-3.5 text-right">Harga Satuan</th>
                        <th class="px-5 py-3.5 text-right">Total Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($purchases as $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-mono font-medium text-slate-700">
                                {{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-800">
                                {{ $item->project->name }}
                                @if ($item->unit)
                                    <span class="block text-emerald-600 font-bold font-mono">Unit: {{ $item->unit->code }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-900">
                                {{ $item->worker->name }}
                                <span class="block text-[11px] font-normal text-slate-400 capitalize">{{ $item->worker->type }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-500 font-medium">
                                {{ $item->pengawas->name ?? '-' }}
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-900">
                                {{ $item->item_name }}
                                @if ($item->notes)
                                    <span class="block text-[11px] font-normal text-slate-400">{{ $item->notes }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center font-bold font-mono">
                                {{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit_measure }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono">
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-rose-600">
                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Log Barang Mingguan</p>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Input Barang Mingguan" di atas untuk menambahkan catatan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $purchases->links() }}
        </div>
    </div>

    <!-- Modal Form Input Barang -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200/80">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">
                        Input Log Barang Mingguan (Pengawas)
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-3">
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
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Unit (Opsional)</label>
                            <select wire:model="unit_id" class="input-clean w-full" {{ !$project_id ? 'disabled' : '' }}>
                                <option value="">-- Pilih Unit --</option>
                                @foreach ($availableUnits as $u)
                                    <option value="{{ $u->id }}">Unit: {{ $u->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Mandor / Tukang yang Mengambil/Membeli</label>
                        <select wire:model="worker_id" class="input-clean w-full font-semibold">
                            <option value="">-- Pilih Pekerja --</option>
                            @foreach ($workers as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                            @endforeach
                        </select>
                        @error('worker_id') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Tanggal Pembelian/Pengambilan</label>
                        <input type="date" wire:model="purchase_date" class="input-clean w-full font-mono">
                        @error('purchase_date') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Nama Barang / Material</label>
                        <input type="text" wire:model="item_name" placeholder="Contoh: Semen Tiga Roda, Besi 10mm, Cat Dulux, Dll" class="input-clean w-full font-bold">
                        @error('item_name') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Jumlah</label>
                            <input type="number" step="0.01" wire:model.live="quantity" class="input-clean w-full font-mono font-bold">
                            @error('quantity') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Satuan</label>
                            <input type="text" wire:model="unit_measure" placeholder="sak, m3, pcs" class="input-clean w-full">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Harga Satuan (Rp)</label>
                            <x-currency-input model="unit_price" class="input-clean w-full font-mono font-bold" />
                            @error('unit_price') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>


                    <div class="p-3 bg-emerald-50 border border-emerald-200/80 rounded-xl flex justify-between items-center text-xs font-semibold text-emerald-900">
                        <span>Total Biaya Transaksi:</span>
                        <span class="font-mono text-emerald-700 text-sm font-extrabold">Rp {{ number_format($quantity * $unit_price, 0, ',', '.') }}</span>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Catatan Pengawas</label>
                        <textarea wire:model="notes" rows="2" placeholder="Catatan pembelian mingguan..." class="input-clean w-full"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button wire:click="$set('showModal', false)" type="button" class="btn-secondary">Batal</button>
                    <button wire:click="save" type="button" class="btn-primary">Simpan Log Barang</button>
                </div>
            </div>
        </div>
    @endif
</div>
