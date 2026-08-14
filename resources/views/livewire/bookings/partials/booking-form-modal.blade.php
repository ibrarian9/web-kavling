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

                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Foto Struk / Bukti Transfer <span class="text-amber-600 font-bold lowercase text-[10px] bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">(Opsional, Maks. 10MB)</span></label>
                    <input type="file" wire:model="receipt_photo" accept="image/*,.heic,.heif,.pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer">
                    <div wire:loading wire:target="receipt_photo" class="text-[11px] text-amber-600 font-semibold mt-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Mengunggah foto resi...</span>
                    </div>
                    @error('receipt_photo') <span class="text-[10px] text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                    @if ($receipt_photo ?? false)
                        <div class="mt-2.5 p-2.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between text-[11px] font-semibold text-slate-700">
                                <span class="flex items-center gap-1 text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Berkas Terpilih ({{ method_exists($receipt_photo, 'getClientOriginalName') ? $receipt_photo->getClientOriginalName() : 'Foto Resi' }}):</span>
                                </span>
                                <button type="button" wire:click="$set('receipt_photo', null)" class="text-rose-500 hover:text-rose-700 text-[10px] underline font-bold">Hapus Foto</button>
                            </div>
                            @if (is_object($receipt_photo) && method_exists($receipt_photo, 'isPreviewable') && $receipt_photo->isPreviewable())
                                <div class="relative max-h-36 sm:max-h-40 overflow-y-auto rounded-xl border border-slate-200 bg-slate-900 flex items-center justify-center p-1.5">
                                    <img src="{{ $receipt_photo->temporaryUrl() }}" alt="Preview Resi" class="max-h-32 sm:max-h-36 w-auto max-w-full object-contain rounded-lg shadow-sm">
                                </div>
                            @else
                                <div class="p-2.5 bg-amber-50 border border-amber-200/80 rounded-xl text-amber-800 text-[11px] font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Format berkas siap diunggah. Pratinjau langsung didukung untuk file gambar (JPG/PNG).</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

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

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" wire:loading.attr="disabled" class="btn-primary flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin text-white shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="save">{{ $editingBookingId ? 'Simpan Perubahan' : 'Simpan Pemesanan' }}</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
