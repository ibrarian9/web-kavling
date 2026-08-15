<!-- Modal Form Create & Edit Manual Invoice -->
@if($showModal)
    <x-modal-dialog show="showModal" 
                    :title="$editingInvoiceId ? 'Edit Invoice Manual' : 'Buat Invoice Manual Baru'" 
                    subTitle="Konfigurasi invoice manual yang terhubung ke sistem keuangan" 
                    maxWidth="max-w-xl">
        <form wire:submit.prevent="saveInvoice" class="space-y-4 text-xs sm:text-sm">
            @if(!$editingInvoiceId)
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Nomor Invoice (Opsional / Otomatis System)</label>
                    <input type="text" wire:model="invoice_number" placeholder="Contoh: INV-MANUAL-2026-001 (Kosongkan utk auto)" class="input-clean w-full font-mono">
                </div>
            @endif

            <div>
                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Nama Penerima / Klien / Pembeli *</label>
                <input type="text" wire:model="recipient_name" placeholder="Contoh: Bapak Ahmad Fauzi / PT Karya Mandiri" class="input-clean w-full font-bold">
                @error('recipient_name') <span class="text-rose-500 text-[10px] block mt-1 font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">No. HP / Telepon (Opsional)</label>
                    <input type="text" wire:model="recipient_phone" placeholder="08123456789" class="input-clean w-full">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Tipe Mutasi Keuangan *</label>
                    <select wire:model.live="type" class="select-clean w-full font-bold">
                        <option value="masuk">Kas Masuk (Pemasukan)</option>
                        <option value="keluar">Kas Keluar (Pengeluaran)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Kategori Transaksi *</label>
                    <select wire:model="category" class="select-clean w-full font-semibold">
                        @if($type === 'masuk')
                            <option value="penjualan_unit">Penjualan Unit / Addendum</option>
                            <option value="biaya_legalitas">Biaya Legalitas / Notaris</option>
                            <option value="pendapatan_sewa">Pendapatan Sewa / Jasa</option>
                            <option value="lain_lain">Lain-lain (Kas Masuk)</option>
                        @else
                            <option value="operasional">Biaya Operasional Khusus</option>
                            <option value="pembelian_lahan">Pembayaran Vendor / Penjual</option>
                            <option value="gaji">Pengeluaran Jasa / Gaji</option>
                            <option value="lain_lain">Lain-lain (Kas Keluar)</option>
                        @endif
                    </select>
                </div>

                <div>
                    <x-currency-input 
                        label="Nominal Invoice (Rp)" 
                        model="amount" 
                        :value="$amount"
                        placeholder="10.000.000" 
                        required 
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Cakupan Proyek (Opsional)</label>
                    <select wire:model.live="project_id" class="select-clean w-full">
                        <option value="">Konsolidasi Global (Tanpa Proyek)</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Unit Terkait (Opsional)</label>
                    <select wire:model="unit_id" class="select-clean w-full" {{ !$project_id ? 'disabled' : '' }}>
                        <option value="">Tanpa Unit Spesifik</option>
                        @foreach($availableUnits as $u)
                            <option value="{{ $u->id }}">Unit {{ $u->code }} ({{ $u->type }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Tanggal Invoice *</label>
                    <input type="date" wire:model="invoice_date" class="input-clean w-full font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Jatuh Tempo (Opsional)</label>
                    <input type="date" wire:model="due_date" class="input-clean w-full font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Status Payment *</label>
                    <select wire:model="status" class="select-clean w-full font-bold">
                        <option value="lunas">Lunas (Masuk Kas)</option>
                        <option value="pending">Pending (Menunggu)</option>
                        <option value="draf">Draf</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Metode Pembayaran</label>
                <select wire:model="payment_method" class="select-clean w-full">
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="Tunai / Cash">Tunai / Cash</option>
                    <option value="Cek / Giro">Cek / Giro</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Keterangan / Rincian Tagihan (Opsional)</label>
                <textarea wire:model="description" rows="2" placeholder="Pembayaran biaya sertifikat balik nama kavling A1..." class="input-clean w-full"></textarea>
            </div>

            <div class="bg-teal-50 border border-teal-200 p-3 rounded-2xl flex items-center justify-between">
                <div>
                    <span class="font-bold text-teal-900 text-xs block">Sinkronisasi Otomatis Arus Kas</span>
                    <span class="text-[11px] text-teal-700">Masuk secara otomatis ke laporan mutasi kas jika status Lunas</span>
                </div>
                <input type="checkbox" wire:model="record_cashflow" class="w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <x-button type="button" variant="outline" size="sm" wire:click="closeModal">Batal</x-button>
                <x-button type="submit" variant="emerald" size="sm" icon="check">Simpan Invoice & Arus Kas</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
