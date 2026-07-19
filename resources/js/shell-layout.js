import { getLocale } from './locale.js';

const FULL_WIDTH_STORAGE_KEY = 'binom-tools-shell-full-width';
const PLAYBOOK_FOCUS_STORAGE_KEY = 'binom-tools-playbook-focus';
const PLAYBOOK_TOC_OPEN_STORAGE_KEY = 'binom-tools-playbook-toc-open';

/** @returns {boolean} */
export function getShellFullWidth() {
    return localStorage.getItem(FULL_WIDTH_STORAGE_KEY) === 'true';
}

/** @returns {boolean} */
export function getPlaybookFocus() {
    return localStorage.getItem(PLAYBOOK_FOCUS_STORAGE_KEY) === 'true';
}

/** @returns {boolean} */
export function getPlaybookTocOpen() {
    return localStorage.getItem(PLAYBOOK_TOC_OPEN_STORAGE_KEY) === 'true';
}

/** @returns {boolean} */
function isPlaybookPage() {
    return Boolean(
        document.querySelector('.tools-shell__main--playbook')
        || document.querySelector('.tools-shell__main--sprint-planner')
        || document.querySelector('.sp-app[data-sp-page="show"]'),
    );
}

/** @returns {boolean} */
function isPlaybookStoryPage() {
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

    applyPlaybookTocOpen(getPlaybookTocOpen(), { syncControls });
}

/**
 * @param {boolean} enabled
 * @param {{ syncControls?: boolean }} [options]
 */
export function applyPlaybookTocOpen(enabled, options = {}) {
    const { syncControls = true } = options;
    const open = Boolean(enabled);
    document.documentElement.dataset.playbookTocOpen = open ? 'true' : 'false';

    document.querySelectorAll('[data-playbook-root]').forEach((root) => {
        if (root instanceof HTMLElement) {
            root.classList.toggle('playbook-detail--toc-open', open);
        }
    });

    if (!syncControls) {
        return;
    }

    document.querySelectorAll('[data-playbook-toc-rail-button]').forEach((button) => {
        if (!(button instanceof HTMLElement)) {
            return;
        }
        button.setAttribute('aria-pressed', open ? 'true' : 'false');
        button.classList.toggle('playbook-toc-rail-toggle--active', open);
        button.setAttribute(
            'data-i18n-aria',
            open ? 'playbooks.tocHide' : 'playbooks.tocShow',
        );
        button.setAttribute('title', open ? 'Hide table of contents' : 'Show table of contents');
        const label = button.querySelector('[data-playbook-toc-rail-label]');
        if (label) {
            label.setAttribute('data-i18n', open ? 'playbooks.tocHide' : 'playbooks.tocShow');
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
    // Entering focus: collapse TOC so reading starts clean; list button can reopen it.
    // Leaving focus: restore TOC visible by default.
    if (enabled) {
        localStorage.setItem(PLAYBOOK_TOC_OPEN_STORAGE_KEY, 'false');
    } else {
        localStorage.setItem(PLAYBOOK_TOC_OPEN_STORAGE_KEY, 'true');
    }
    applyPlaybookFocus(enabled);
    window.dispatchEvent(new CustomEvent('binom-tools:playbook-focus', { detail: { focus: enabled } }));
    const locale = getLocale();
    window.dispatchEvent(new CustomEvent('binom-tools:locale', { detail: { locale } }));
}

/** @param {boolean} enabled */
export function setPlaybookTocOpen(enabled) {
    localStorage.setItem(PLAYBOOK_TOC_OPEN_STORAGE_KEY, enabled ? 'true' : 'false');
    applyPlaybookTocOpen(enabled);
    window.dispatchEvent(new CustomEvent('binom-tools:playbook-toc-open', { detail: { open: enabled } }));
    const locale = getLocale();
    window.dispatchEvent(new CustomEvent('binom-tools:locale', { detail: { locale } }));
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
    // First visit: TOC open by default when preference was never stored.
    if (localStorage.getItem(PLAYBOOK_TOC_OPEN_STORAGE_KEY) === null) {
        localStorage.setItem(PLAYBOOK_TOC_OPEN_STORAGE_KEY, 'true');
    }
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

    // Event delegation: locale panels remount content; keep TOC toggle reliable.
    document.addEventListener('click', (event) => {
        const target = event.target;
        if (!(target instanceof Element)) {
            return;
        }
        const button = target.closest('[data-playbook-toc-rail-button]');
        if (!(button instanceof HTMLElement)) {
            return;
        }
        event.preventDefault();
        setPlaybookTocOpen(!getPlaybookTocOpen());
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
