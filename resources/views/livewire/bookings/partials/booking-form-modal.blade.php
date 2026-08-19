<!-- Modal Form Catat & Edit Booking / DP -->
@if ($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6">
        <div class="bg-white rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 border border-slate-200/80 max-h-[88vh] sm:max-h-[85vh] flex flex-col my-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <h3 class="text-base font-bold text-slate-900">
                    {{ $editingBookingId ? 'Edit Data Booking Pemesanan' : 'Catat Tanda Jadi / Booking Fee Pemesanan' }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form wire:submit.prevent="save" class="space-y-4 text-xs flex-1 overflow-y-auto pr-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Perumahan / Proyek *</label>
                        <select wire:model.live="project_id" required class="input-clean w-full">
                            <option value="">Pilih Proyek</option>
                            @foreach ($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Tingkat Booking *</label>
                        <select wire:model.live="booking_type" required class="input-clean w-full">
                            <option value="unit">Per Unit Spesifik</option>
                            <option value="project">Per Proyek Kolektif</option>
                        </select>
                    </div>
                </div>

                @if ($booking_type === 'unit')
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Unit Spesifik *</label>
                        <select wire:model="unit_id" class="input-clean w-full font-semibold" {{ !$project_id ? 'disabled' : '' }}>
                            <option value="">Pilih Unit</option>
                            @foreach ($availableUnits as $u)
                                <option value="{{ $u->id }}">Unit Kode: {{ $u->code }} ({{ ucfirst($u->category) }} - {{ $u->status }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Nama Calon Pembeli *</label>
                        <input type="text" wire:model="buyer_name" required class="input-clean w-full font-bold">
                        @error('buyer_name') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">No. Kontak / WhatsApp *</label>
                        <input type="text" wire:model="buyer_phone" required placeholder="08123456789" class="input-clean w-full font-mono">
                        @error('buyer_phone') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <x-currency-input 
                        label="Nominal Booking Fee (Rp)" 
                        model="booking_amount" 
                        :value="$booking_amount"
                        placeholder="5.000.000"
                        badgeColor="blue"
                        required 
                    />

                    <x-currency-input 
                        label="Total Uang Muka / DP (Rp) (Opsional)" 
                        model="dp_amount" 
                        :value="$dp_amount"
                        placeholder="25.000.000"
                        badgeColor="emerald"
                    />
                </div>

                <x-receipt-upload 
                    model="receipt_photo" 
                    :photo="$receipt_photo" 
                    label="Foto Struk / Bukti Transfer Booking Fee"
                />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1 text-[11px] sm:text-xs">Tgl Pembayaran *</label>
                        <input type="date" wire:model="booking_date" required class="input-clean w-full font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1 text-[11px] sm:text-xs">Tgl Jatuh Tempo Booking</label>
                        <input type="date" wire:model="expiry_date" class="input-clean w-full font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Catatan Tambahan</label>
                    <textarea wire:model="notes" rows="2" placeholder="Catatan pembayaran tanda jadi..." class="input-clean w-full"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 shrink-0">
                    <x-button variant="secondary" size="md" type="button" wire:click="$set('showModal', false)">Batal</x-button>
                    <x-button variant="primary" size="md" type="submit" loadingTarget="save">
                        {{ $editingBookingId ? 'Simpan Perubahan' : 'Simpan Pemesanan' }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endif
