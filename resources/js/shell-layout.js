const FULL_WIDTH_STORAGE_KEY = 'binom-tools-shell-full-width';

/** @returns {boolean} */
export function getShellFullWidth() {
    return localStorage.getItem(FULL_WIDTH_STORAGE_KEY) === 'true';
}

/** @param {boolean} enabled */
export function applyShellFullWidth(enabled) {
    document.documentElement.dataset.shellFullWidth = enabled ? 'true' : 'false';

    const shell = document.getElementById('tools-shell');
    shell?.classList.toggle('tools-shell--full-width', enabled);

    document.querySelectorAll('[data-shell-full-width-toggle]').forEach((input) => {
        if (input instanceof HTMLInputElement) {
            input.checked = enabled;
        }
    });
}

/** @param {boolean} enabled */
export function setShellFullWidth(enabled) {
    localStorage.setItem(FULL_WIDTH_STORAGE_KEY, enabled ? 'true' : 'false');
    applyShellFullWidth(enabled);
    window.dispatchEvent(new CustomEvent('binom-tools:shell-layout', { detail: { fullWidth: enabled } }));
}

function closeSettingsMenu() {
    const root = document.querySelector('[data-header-settings]');
    const toggle = root?.querySelector('[data-header-settings-toggle]');
    const menu = root?.querySelector('[data-header-settings-menu]');

    if (!(toggle instanceof HTMLButtonElement) || !(menu instanceof HTMLElement)) {
        return;
    }

    menu.hidden = true;
    toggle.setAttribute('aria-expanded', 'false');
}

function openSettingsMenu() {
    const root = document.querySelector('[data-header-settings]');
    const toggle = root?.querySelector('[data-header-settings-toggle]');
    const menu = root?.querySelector('[data-header-settings-menu]');

    if (!(toggle instanceof HTMLButtonElement) || !(menu instanceof HTMLElement)) {
        return;
    }

    menu.hidden = false;
    toggle.setAttribute('aria-expanded', 'true');
}

function toggleSettingsMenu() {
    const menu = document.querySelector('[data-header-settings-menu]');
    if (menu instanceof HTMLElement && menu.hidden) {
        openSettingsMenu();
    } else {
        closeSettingsMenu();
    }
}

export function initShellLayoutControls() {
    applyShellFullWidth(getShellFullWidth());

    document.querySelector('[data-header-settings-toggle]')?.addEventListener('click', (event) => {
        event.stopPropagation();
        toggleSettingsMenu();
    });

    document.querySelector('[data-shell-full-width-toggle]')?.addEventListener('change', (event) => {
        const input = event.currentTarget;
        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        setShellFullWidth(input.checked);
    });

    document.addEventListener('click', (event) => {
        const root = document.querySelector('[data-header-settings]');
        if (!(root instanceof HTMLElement)) {
            return;
        }

        if (!root.contains(/** @type {Node} */ (event.target))) {
            closeSettingsMenu();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSettingsMenu();
        }
    });
}
