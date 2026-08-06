<!-- MicroModal Global Confirmation Dialog -->
<div class="modal modal-micromodal micromodal-slide relative z-50" id="micromodal-confirm" aria-hidden="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="modal__container w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-6 text-left align-middle shadow-2xl transition-all border border-slate-200" role="dialog" aria-modal="true" aria-labelledby="micromodal-confirm-title">
                <div class="flex items-start justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200/80">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 leading-tight" id="micromodal-confirm-title">
                                Konfirmasi Tindakan
                            </h3>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Sistem Manajemen PT. Atlantik Perkasa Abadi</p>
                        </div>
                    </div>
                    <button type="button" class="text-slate-400 hover:text-slate-600 text-lg font-bold p-1" data-micromodal-close aria-label="Close modal">✕</button>
                </div>

                <div class="py-4">
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed" id="micromodal-confirm-message">
                        Apakah Anda yakin ingin melanjutkan tindakan ini?
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition border border-slate-200/80" data-micromodal-close id="micromodal-cancel-btn">
                        Batal
                    </button>
                    <button type="button" id="micromodal-confirm-btn" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
