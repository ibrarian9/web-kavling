<!-- Modal Catat Kas Manual -->
@if($showManualModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-md w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <h3 class="font-bold text-slate-900 text-sm sm:text-base">Catat Mutasi Kas Manual</h3>
                <button wire:click="$set('showManualModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
            </div>

            <form wire:submit.prevent="saveTransaction" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pilih Proyek <span class="text-rose-500">*</span></label>
                    <select wire:model="project_id" class="select-clean w-full font-semibold">
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">🏗️ {{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tipe Arus Kas <span class="text-rose-500">*</span></label>
                        <select wire:model="type" class="select-clean w-full font-bold">
                            <option value="masuk">📈 Pemasukan (Kas Masuk)</option>
                            <option value="keluar">📉 Pengeluaran (Kas Keluar)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Mutasi <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="transaction_date" required class="input-clean w-full font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nominal Mutasi Kas <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-xl shadow-xs">
                        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                            Rp
                        </span>
                        <x-currency-input model="amount" class="input-clean rounded-r-xl rounded-l-none font-bold text-xs sm:text-sm font-mono w-full" placeholder="0" />
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Keterangan Transaksi <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="description" required placeholder="Pendapatan lain / Konsumsi tukang..." class="input-clean w-full text-xs sm:text-sm">
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showManualModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Mutasi</button>
                </div>
            </form>
        </div>
    </div>
@endif
