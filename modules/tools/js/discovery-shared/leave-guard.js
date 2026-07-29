/**
 * Warn on in-app navigation when a form has unsaved changes.
 * Uses a shared modal — never the browser beforeunload dialog.
 */

import { bindSharedModal, closeSharedModal, openSharedModal } from '../../../../resources/js/shared/modal.js';

/** @type {HTMLDialogElement|null} */
let sharedDialog = null;

/**
 * @param {{
 *   title: string,
 *   message: string,
 *   stayLabel: string,
 *   leaveLabel: string,
 * }} copy
 * @param {() => void} onLeave
 */
function confirmLeave(copy, onLeave) {
    if (!sharedDialog) {
        sharedDialog = document.createElement('dialog');
        sharedDialog.className = 'bn-shared-modal tools-leave-modal';
        sharedDialog.innerHTML = `
            <div class="tools-leave-modal__sheet" role="document">
                <h2 class="tools-leave-modal__title" data-leave-title></h2>
                <p class="tools-leave-modal__body" data-leave-body></p>
                <div class="tools-leave-modal__actions">
                    <button type="button" class="tools-btn" data-leave-stay></button>
                    <button type="button" class="tools-btn tools-btn--primary" data-leave-go></button>
                </div>
            </div>
        `;
        bindSharedModal(sharedDialog, { closeSelector: '[data-leave-stay]', backdropClose: true });
        sharedDialog.querySelector('[data-leave-stay]')?.addEventListener('click', () => {
            closeSharedModal(sharedDialog);
        });
    }

    const titleEl = sharedDialog.querySelector('[data-leave-title]');
    const bodyEl = sharedDialog.querySelector('[data-leave-body]');
    const stayEl = sharedDialog.querySelector('[data-leave-stay]');
    const goEl = sharedDialog.querySelector('[data-leave-go]');
    if (titleEl) titleEl.textContent = copy.title;
    if (bodyEl) bodyEl.textContent = copy.message;
    if (stayEl) stayEl.textContent = copy.stayLabel;
    if (goEl) {
        goEl.textContent = copy.leaveLabel;
        const next = () => {
            closeSharedModal(sharedDialog);
            goEl.removeEventListener('click', next);
            onLeave();
        };
        // Replace previous leave handler
        const fresh = goEl.cloneNode(true);
        goEl.replaceWith(fresh);
        fresh.addEventListener('click', next);
    }

    openSharedModal(sharedDialog);
}

/**
 * @param {() => boolean} isDirty
 * @param {() => string} getMessage
 * @param {{
 *   getTitle?: () => string,
 *   getStayLabel?: () => string,
 *   getLeaveLabel?: () => string,
 * }} [labels]
 * @returns {() => void} dispose
 */
export function bindLeaveGuard(isDirty, getMessage, labels = {}) {
    /**
     * @param {MouseEvent} event
     */
    function onDocumentClick(event) {
        if (!(event.target instanceof Element)) {
            return;
        }
        if (event.defaultPrevented || event.button !== 0) {
            return;
        }
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }
        if (!isDirty()) {
            return;
        }

        const anchor = event.target.closest('a[href]');
        if (!(anchor instanceof HTMLAnchorElement)) {
            return;
        }
        if (anchor.target === '_blank' || anchor.hasAttribute('download')) {
            return;
        }
        const href = anchor.getAttribute('href');
        if (!href || href.startsWith('#') || href.toLowerCase().startsWith('javascript:')) {
            return;
        }

        let nextUrl;
        try {
            nextUrl = new URL(href, window.location.href);
        } catch {
            return;
        }
        if (nextUrl.origin !== window.location.origin) {
            return;
        }
        if (
            nextUrl.pathname === window.location.pathname
            && nextUrl.search === window.location.search
            && nextUrl.hash !== window.location.hash
        ) {
            return;
        }
        if (nextUrl.href === window.location.href) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const lang = document.documentElement.lang === 'de' ? 'de' : 'en';
        confirmLeave(
            {
                title:
                    labels.getTitle?.()
                    || (lang === 'de' ? 'Ungespeicherte Änderungen' : 'Unsaved changes'),
                message: getMessage(),
                stayLabel:
                    labels.getStayLabel?.()
                    || (lang === 'de' ? 'Bleiben' : 'Stay'),
                leaveLabel:
                    labels.getLeaveLabel?.()
                    || (lang === 'de' ? 'Seite verlassen' : 'Leave page'),
            },
            () => {
                window.location.assign(nextUrl.href);
            },
        );
    }

    document.addEventListener('click', onDocumentClick, true);

    return () => {
        document.removeEventListener('click', onDocumentClick, true);
    };
}
