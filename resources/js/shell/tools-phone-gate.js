/** Show a tablet+ notice when tool pages are opened on phone viewports. */

const PHONE_QUERY = '(max-width: 768px)';

/**
 * @returns {boolean}
 */
function isPhoneViewport() {
    return window.matchMedia(PHONE_QUERY).matches;
}

export function initToolsPhoneGate() {
    const gate = document.querySelector('[data-tools-phone-gate]');
    const shell = document.getElementById('tools-shell');

    if (!(gate instanceof HTMLElement) || !(shell instanceof HTMLElement)) {
        return;
    }

    const sync = () => {
        const active = isPhoneViewport();
        gate.hidden = !active;
        shell.classList.toggle('tools-shell--phone-gate', active);
    };

    sync();

    const media = window.matchMedia(PHONE_QUERY);
    if (typeof media.addEventListener === 'function') {
        media.addEventListener('change', sync);
    } else if (typeof media.addListener === 'function') {
        media.addListener(sync);
    }
}
