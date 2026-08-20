<!-- Modal Form Booking Unit Directly from Detail Page -->
@if($showBookingModal)
    <x-modal-dialog show="showBookingModal" 
                    title="Booking Unit {{ $unit->code }}" 
                    subTitle="Pencatatan booking & DP langsung di dalam sistem" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="saveBooking" class="space-y-4.5 sm:space-y-5 text-xs sm:text-sm">
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5 text-xs uppercase tracking-wider">Nama Pembeli <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="buyer_name" required placeholder="Contoh: Bpk. H. Hendra Wijaya" class="input-clean w-full font-bold text-xs sm:text-sm h-10.5">
                @error('buyer_name') <span class="text-rose-500 text-[10px] mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5 text-xs uppercase tracking-wider">Nomor HP / WhatsApp Pembeli <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="buyer_phone" required placeholder="081234567890" class="input-clean w-full font-mono text-xs sm:text-sm h-10.5">
                @error('buyer_phone') <span class="text-rose-500 text-[10px] mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5 text-xs uppercase tracking-wider">Tgl Pembayaran / Booking <span class="text-rose-500">*</span></label>
                <input type="date" wire:model="booking_date" required class="input-clean w-full font-mono text-xs sm:text-sm h-10.5">
                @error('booking_date') <span class="text-rose-500 text-[10px] mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-currency-input
                    label="Nominal Tanda Jadi / Booking Fee (Rp)"
                    model="booking_amount"
                    :value="$booking_amount"
                    placeholder="5.000.000"
                    badgeColor="blue"
                    required
                />
                @error('booking_amount') <span class="text-rose-500 text-[10px] mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1.5 text-xs uppercase tracking-wider">Catatan Pembayaran & Bukti DP</label>
                <textarea wire:model="booking_notes" rows="2" placeholder="Informasi bukti transfer DP, skema pelunasan, dll." class="input-clean w-full text-xs sm:text-sm"></textarea>
            </div>

            <x-receipt-upload 
                model="receipt_photo" 
                :photo="$receipt_photo" 
                label="Foto Struk / Bukti Transfer DP"
            />

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-4 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showBookingModal', false)">Batal</x-button>
                <x-button type="submit" variant="primary" loadingTarget="saveBooking">Proses Booking Unit</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
