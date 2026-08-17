<!-- Modal Form Catat / Edit Daily Activity Report -->
@if($showReportModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-xl w-full p-5 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>{{ $editingReportId ? 'Edit Laporan Aktivitas Prospek' : 'Catat Laporan Aktivitas Harian Baru' }}</span>
                </h3>
                <button wire:click="$set('showReportModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-lg p-1">&times;</button>
            </div>

            <!-- Modal Form Body -->
            <form wire:submit.prevent="saveReport" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <!-- Founder/Supervisor Select Sales -->
                @if(auth()->user()->isFounder() || auth()->user()->isSupervisor())
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Sales / Marketing Penanggung Jawab <span class="text-rose-500">*</span></label>
                        <select wire:model="user_id" class="input-clean w-full text-xs font-medium text-slate-800">
                            @foreach($allMarketingUsers as $mUser)
                                <option value="{{ $mUser->id }}">{{ $mUser->name }} ({{ ucfirst($mUser->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Tanggal Laporan <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="report_date" class="input-clean w-full text-xs font-medium text-slate-800">
                        @error('report_date') <span class="text-rose-600 text-[11px] mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Nama Klien / Prospek <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="client_name" placeholder="misal: Bpk. Hendra Wijaya" class="input-clean w-full text-xs font-medium text-slate-800">
                        @error('client_name') <span class="text-rose-600 text-[11px] mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">No Telp / WhatsApp Klien <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="client_phone" placeholder="misal: 081234567890" class="input-clean w-full text-xs font-medium text-slate-800">
                        @error('client_phone') <span class="text-rose-600 text-[11px] mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Sumber Lead / Darimana Klien Tau <span class="text-rose-500">*</span></label>
                        <select wire:model="lead_source" class="input-clean w-full text-xs font-medium text-slate-800">
                            @foreach(\App\Models\DailyActivityReport::leadSources() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Proyek Minat (Opsional)</label>
                        <select wire:model.live="project_id" class="input-clean w-full text-xs font-medium text-slate-800">
                            <option value="">Pilih Proyek</option>
                            @foreach($allProjects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Unit Spesifik (Opsional)</label>
                        <select wire:model="unit_id" class="input-clean w-full text-xs font-medium text-slate-800">
                            <option value="">Pilih Unit</option>
                            @foreach($availableUnits as $un)
                                <option value="{{ $un->id }}">Unit {{ $un->code }} (Status: {{ strtoupper($un->status) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Jenis Interaksi Harian <span class="text-rose-500">*</span></label>
                        <select wire:model="interaction_type" class="input-clean w-full text-xs font-medium text-slate-800">
                            @foreach(\App\Models\DailyActivityReport::interactionTypes() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Tahap Status Prospek <span class="text-rose-500">*</span></label>
                        <select wire:model="lead_stage" class="input-clean w-full text-xs font-medium text-slate-800">
                            @foreach(\App\Models\DailyActivityReport::leadStages() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Tipe Pembelian / Skema</label>
                        <select wire:model="payment_type" class="input-clean w-full text-xs font-medium text-slate-800">
                            <option value="tanpa_dp">Tanpa Transaksi / Masih Prospek</option>
                            <option value="dp_booking">Booking Fee / DP Unit</option>
                            <option value="cash_bertahap">Cash Bertahap</option>
                            <option value="cash_lunas">Pembelian Cash Lunas</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1 text-xs">Nominal Closing / Deal (Rp)</label>
                        <x-currency-input model="deal_amount" placeholder="0" class="input-clean text-xs font-medium text-emerald-700 w-full" />
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">Catatan Pembicaraan / Follow Up</label>
                    <textarea wire:model="notes" rows="2" placeholder="Catat hasil pembicaraan, respon klien, atau hal penting lainnya..." class="input-clean w-full text-xs font-medium text-slate-800"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">Tanggal Janji Follow Up Berikutnya (Opsional)</label>
                    <input type="date" wire:model="follow_up_date" class="input-clean w-full text-xs font-medium text-slate-800">
                </div>

                <!-- Action Buttons Footer -->
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <x-button variant="secondary" size="md" type="button" wire:click="$set('showReportModal', false)">Batal</x-button>
                    <x-button variant="primary" size="md" type="submit">Simpan Laporan Activity</x-button>
                </div>
            </form>
        </div>
    </div>
@endif
