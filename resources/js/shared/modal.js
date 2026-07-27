/**
 * Shared modal helpers — native <dialog>, top-layer safe.
 * Domains keep their own markup inside the dialog; use these for open/close.
 */

/**
 * @param {HTMLDialogElement} dialog
 */
export function ensureDialogOnBody(dialog) {
    if (dialog.parentElement !== document.body) {
        document.body.appendChild(dialog);
    }
}

/**
 * @param {HTMLDialogElement} dialog
 */
export function openSharedModal(dialog) {
    ensureDialogOnBody(dialog);
    if (typeof dialog.showModal === 'function') {
        if (!dialog.open) {
            dialog.showModal();
        }
        return;
    }
    dialog.setAttribute('open', '');
}

/**
 * @param {HTMLDialogElement} dialog
 */
export function closeSharedModal(dialog) {
    if (typeof dialog.close === 'function' && dialog.open) {
        dialog.close();
        return;
    }
    dialog.removeAttribute('open');
}

/**
 * @param {HTMLDialogElement} dialog
 * @param {{ closeSelector?: string, backdropClose?: boolean }} [options]
 */
export function bindSharedModal(dialog, options = {}) {
    const closeSelector = options.closeSelector || '[data-shared-modal-close]';
    const backdropClose = options.backdropClose !== false;

    dialog.querySelectorAll(closeSelector).forEach((btn) => {
        btn.addEventListener('click', (event) => {
            event.preventDefault();
            closeSharedModal(dialog);
        });
    });

    if (backdropClose) {
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) {
                closeSharedModal(dialog);
            }
        });
    }

    dialog.addEventListener('cancel', (event) => {
        event.preventDefault();
        closeSharedModal(dialog);
    });
}
