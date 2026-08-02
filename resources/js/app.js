import MicroModal from 'micromodal';

window.MicroModal = MicroModal;

document.addEventListener('DOMContentLoaded', () => {
    try {
        MicroModal.init({
            awaitCloseAnimation: true,
            disableScroll: true
        });
    } catch (e) {
        console.error('MicroModal init error:', e);
    }
});

/**
 * Global Confirm Action Modal Bridge powered by MicroModal
 */
window.confirmModalAction = function(options = {}) {
    const title = options.title || 'Konfirmasi Tindakan';
    const message = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
    const confirmText = options.confirmText || 'Ya, Lanjutkan';
    const cancelText = options.cancelText || 'Batal';
    const btnClass = options.btnClass || 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5';

    const titleEl = document.getElementById('micromodal-confirm-title');
    const messageEl = document.getElementById('micromodal-confirm-message');
    const btnConfirm = document.getElementById('micromodal-confirm-btn');
    const btnCancel = document.getElementById('micromodal-cancel-btn');

    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;
    if (btnCancel) btnCancel.textContent = cancelText;

    if (btnConfirm) {
        btnConfirm.textContent = confirmText;
        btnConfirm.className = btnClass;

        const newBtnConfirm = btnConfirm.cloneNode(true);
        btnConfirm.parentNode.replaceChild(newBtnConfirm, btnConfirm);

        newBtnConfirm.addEventListener('click', () => {
            MicroModal.close('micromodal-confirm');
            if (typeof options.onConfirm === 'function') {
                options.onConfirm();
            }
        });
    }

    try {
        MicroModal.show('micromodal-confirm', {
            awaitCloseAnimation: true,
            disableScroll: true
        });
    } catch (e) {
        if (confirm(message)) {
            if (typeof options.onConfirm === 'function') {
                options.onConfirm();
            }
        }
    }
};
