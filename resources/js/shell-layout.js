const FULL_WIDTH_STORAGE_KEY = 'binom-tools-shell-full-width';
const PLAYBOOK_FOCUS_STORAGE_KEY = 'binom-tools-playbook-focus';

/** @returns {boolean} */
export function getShellFullWidth() {
    return localStorage.getItem(FULL_WIDTH_STORAGE_KEY) === 'true';
}

/** @returns {boolean} */
export function getPlaybookFocus() {
    return localStorage.getItem(PLAYBOOK_FOCUS_STORAGE_KEY) === 'true';
}

/** @returns {boolean} */
function isPlaybookPage() {
    return Boolean(document.querySelector('.tools-shell__main--playbook'));
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

/**
 * @param {boolean} enabled
 * @param {{ syncControls?: boolean }} [options]
 */
export function applyPlaybookFocus(enabled, options = {}) {
    const { syncControls = true } = options;
    const active = enabled && isPlaybookPage();

    document.documentElement.dataset.playbookFocus = active ? 'true' : 'false';

    const shell = document.getElementById('tools-shell');
    shell?.classList.toggle('tools-shell--playbook-focus', active);

    if (syncControls) {
        document.querySelectorAll('[data-playbook-focus-toggle]').forEach((input) => {
            if (input instanceof HTMLInputElement) {
                input.checked = enabled;
            }
        });

        document.querySelectorAll('[data-playbook-focus-button]').forEach((button) => {
            if (!(button instanceof HTMLElement)) {
                return;
            }

            button.setAttribute('aria-pressed', active ? 'true' : 'false');
            button.classList.toggle('playbook-focus-toggle--active', active);
            button.setAttribute(
                'data-i18n-aria',
                active ? 'playbooks.focusCollapse' : 'playbooks.focusExpand',
            );
            button.setAttribute('title', active ? 'Show sidebars' : 'Hide sidebars');
            const label = button.querySelector('[data-playbook-focus-label]');
            if (label) {
                label.setAttribute(
                    'data-i18n',
                    active ? 'playbooks.focusCollapse' : 'playbooks.focusExpand',
                );
            }
        });
    }

    document.querySelectorAll('[data-playbook-focus-setting]').forEach((el) => {
        if (el instanceof HTMLElement) {
            el.hidden = !isPlaybookPage();
        }
    });
}

/** @param {boolean} enabled */
export function setShellFullWidth(enabled) {
    localStorage.setItem(FULL_WIDTH_STORAGE_KEY, enabled ? 'true' : 'false');
    applyShellFullWidth(enabled);
    window.dispatchEvent(new CustomEvent('binom-tools:shell-layout', { detail: { fullWidth: enabled } }));
}

/** @param {boolean} enabled */
export function setPlaybookFocus(enabled) {
    localStorage.setItem(PLAYBOOK_FOCUS_STORAGE_KEY, enabled ? 'true' : 'false');
    applyPlaybookFocus(enabled);
    window.dispatchEvent(new CustomEvent('binom-tools:playbook-focus', { detail: { focus: enabled } }));
    window.dispatchEvent(new CustomEvent('binom-tools:locale'));
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
    applyPlaybookFocus(getPlaybookFocus());

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

    document.querySelectorAll('[data-playbook-focus-toggle]').forEach((input) => {
        input.addEventListener('change', (event) => {
            const target = event.currentTarget;
            if (!(target instanceof HTMLInputElement)) {
                return;
            }

            setPlaybookFocus(target.checked);
        });
    });

    document.querySelectorAll('[data-playbook-focus-button]').forEach((button) => {
        button.addEventListener('click', () => {
            setPlaybookFocus(!getPlaybookFocus());
        });
    });

    window.addEventListener('binom-tools:locale', () => {
        applyPlaybookFocus(getPlaybookFocus());
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
